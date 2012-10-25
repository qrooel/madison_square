<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version. 
 * 
 * 
 * $Revision: 655 $
 * $Author: gekosale $
 * $Date: 2012-04-24 10:51:44 +0200 (Wt, 24 kwi 2012) $
 * $Id: order.php 655 2012-04-24 08:51:44Z gekosale $
 */

class orderModel extends Model
{

	public function saveOrder ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$clientId = $Data['clientid'];
			if ($clientId == NULL || $clientId == 0){
				$clientId = $this->registry->session->getActiveClientid();
			}
			$orderId = $this->addOrder($Data, $clientId);
			$this->addOrderClientData($Data['clientaddress'], $clientId, $orderId);
			$this->addOrderClientDeliveryData($Data['deliveryAddress'], $orderId);
			$this->addOrderProduct($Data['cart'], $orderId);
			App::getModel('order')->updateSessionString($orderId);
			$event = new sfEvent($this, 'frontend.order.saveOrder', Array(
				'orderid' => $orderId,
				'data' => $Data,
			));
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $orderId;
	}

	public function updateSessionString ($id)
	{
		$sql = 'UPDATE `order` SET sessionid = :crc WHERE idorder = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('crc', session_id() . '-' . $id);
		$stmt->setString('id', $id);
		$stmt->executeUpdate();
	}

	protected function addOrder ($Data, $clientId = 0, $orginalOrderId = NULL)
	{
		$globalPrice = 0;
		$globalNetto = 0;
		$price = 0;
		$sql = 'INSERT INTO `order` (
					price, 
					dispatchmethodprice, 
					globalprice, 
					dispatchmethodname, 
					paymentmethodname, 
					orderstatusid,
					dispatchmethodid, 
					paymentmethodid, 
					clientid, 
					globalpricenetto, 
					viewid, 
					orderid,
					pricebeforepromotion, 
					currencyid, 
					currencysymbol, 
					currencyrate,
					rulescartid,
					sessionid,
					customeropinion
				)
				VALUES (
					:price, 
					:dispatchmethodprice, 
					:globalprice, 
					:dispatchmethodname, 
					:paymentmethodname,
					(SELECT idorderstatus FROM orderstatus WHERE `default` = 1), 
					:dispatchmethodid, 
					:paymentmethodid, 
					:clientid, 
					:globalpricenetto, 
					:viewid, 
					:orderid,
					:pricebeforepromotion, 
					:currencyid, 
					:currencysymbol, 
					:currencyrate,
					:rulescartid,
					:sessionid,
					:customeropinion
				)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setFloat('dispatchmethodprice', $Data['dispatchmethod']['dispatchmethodcost']);
		$stmt->setString('dispatchmethodname', $Data['dispatchmethod']['dispatchmethodname']);
		$stmt->setInt('dispatchmethodid', $Data['dispatchmethod']['dispatchmethodid']);
		$stmt->setString('paymentmethodname', $Data['payment']['paymentmethodname']);
		$stmt->setInt('paymentmethodid', $Data['payment']['idpaymentmethod']);
		$stmt->setInt('clientid', $clientId);
		$stmt->setString('sessionid', session_id());
		$stmt->setString('customeropinion', $Data['customeropinion']);
		$shopCurrency = $this->registry->session->getActiveShopCurrencyId();
		$clientCurrency = $this->registry->session->getActiveCurrencyId();
		if ($shopCurrency !== $clientCurrency){
			$stmt->setInt('currencyid', $clientCurrency);
			$stmt->setString('currencysymbol', $this->registry->session->getActiveCurrencySymbol());
			$stmt->setFloat('currencyrate', $this->registry->session->getActiveCurrencyRate());
		}
		else{
			$stmt->setInt('currencyid', $shopCurrency);
			$stmt->setString('currencysymbol', $this->layer['currencysymbol']);
			$stmt->setFloat('currencyrate', $this->registry->session->getActiveCurrencyRate());
		}
		
		if (isset($Data['priceWithDispatchMethodPromo']) && $Data['priceWithDispatchMethodPromo'] > 0){
			$stmt->setFloat('pricebeforepromotion', $Data['priceWithDispatchMethod']);
			if ($globalPrice == 0){
				$globalPrice = $Data['priceWithDispatchMethodPromo'];
				$globalNetto = $Data['priceWithDispatchMethodNettoPromo'];
				$price = $Data['globalPricePromo'];
			}
		}
		else{
			$stmt->setFloat('pricebeforepromotion', 0);
		}
		if ($globalPrice == 0 || $globalNetto == 0){
			$globalPrice = $Data['priceWithDispatchMethod'];
			$globalNetto = $Data['globalPriceWithoutVat'];
			$price = $Data['globalPrice'];
		}
		if (isset($Data['rulescartid']) && ! empty($Data['rulescartid'])){
			$stmt->setInt('rulescartid', $Data['rulescartid']);
		}
		else{
			$stmt->setNull('rulescartid');
		}
		$stmt->setFloat('globalprice', $globalPrice);
		$stmt->setFloat('globalpricenetto', $globalNetto);
		$stmt->setFloat('price', $price);
		$stmt->setInt('viewid', Helper::getViewId());
		if ($orginalOrderId == NULL){
			$stmt->setNull('orderid');
		}
		else{
			$stmt->setInt('orderid', $orginalOrderId);
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	protected function addOrderClientData ($Data, $clientId = 0, $orderId)
	{
		
		$sql = 'INSERT INTO orderclientdata SET
					firstname = AES_ENCRYPT(:firstname, :encryptionKey), 
					surname = AES_ENCRYPT(:surname, :encryptionKey), 
					street = AES_ENCRYPT(:street, :encryptionKey), 
					streetno = AES_ENCRYPT(:streetno, :encryptionKey), 
					placeno = AES_ENCRYPT(:placeno, :encryptionKey), 
					postcode = AES_ENCRYPT(:postcode, :encryptionKey), 
					place = AES_ENCRYPT(:place, :encryptionKey), 
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					email = AES_ENCRYPT(:email, :encryptionKey), 
					companyname = AES_ENCRYPT(:companyname, :encryptionKey), 
					nip = AES_ENCRYPT(:nip, :encryptionKey), 
					orderid = :orderid,
					clientid = :clientid,
					countryid = :country
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('place', $Data['placename']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setInt('country', $Data['country']);
		$stmt->setInt('orderid', $orderId);
		$stmt->setInt('clientid', $clientId);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	protected function addOrderClientDeliveryData ($Data, $orderId)
	{
		
		$sql = 'INSERT INTO orderclientdeliverydata SET
					firstname = AES_ENCRYPT(:firstname, :encryptionKey), 
					surname = AES_ENCRYPT(:surname, :encryptionKey), 
					street = AES_ENCRYPT(:street, :encryptionKey), 
					streetno = AES_ENCRYPT(:streetno, :encryptionKey), 
					placeno = AES_ENCRYPT(:placeno, :encryptionKey), 
					postcode = AES_ENCRYPT(:postcode, :encryptionKey), 
					place = AES_ENCRYPT(:place, :encryptionKey), 
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					companyname = AES_ENCRYPT(:companyname, :encryptionKey), 
					nip = AES_ENCRYPT(:nip, :encryptionKey),  
					email = AES_ENCRYPT(:email, :encryptionKey),
					orderid = :orderid,
					countryid = :country';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('place', $Data['placename']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setInt('orderid', $orderId);
		$stmt->setInt('country', $Data['country']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getOrderBillingData ($idorder)
	{
		$sql = 'SELECT
					AES_DECRYPT(OCD.firstname, :encryptionkey) AS firstname,
					AES_DECRYPT(OCD.surname, :encryptionkey) AS surname,
					AES_DECRYPT(OCD.companyname, :encryptionkey) AS companyname,
					AES_DECRYPT(OCD.nip, :encryptionkey) AS nip,
					AES_DECRYPT(OCD.street, :encryptionkey) AS street,
					AES_DECRYPT(OCD.streetno, :encryptionkey) AS streetno,
					AES_DECRYPT(OCD.placeno, :encryptionkey) AS placeno,
					AES_DECRYPT(OCD.place, :encryptionkey) AS placename,
					AES_DECRYPT(OCD.postcode, :encryptionkey) AS postcode,
					AES_DECRYPT(OCD.email, :encryptionkey) AS email,
					AES_DECRYPT(OCD.phone, :encryptionkey) AS phone
					FROM orderclientdata OCD
				WHERE OCD.orderid = :idorder';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'companyname' => $rs->getString('companyname'),
				'nip' => $rs->getString('nip'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'phone' => $rs->getString('phone'),
				'email' => $rs->getString('email'),
				'placename' => $rs->getString('placename'),
				'postcode' => $rs->getString('postcode')
			);
		}
		return $Data;
	}

	public function getOrderShippingData ($idorder)
	{
		$sql = 'SELECT
					AES_DECRYPT(OCD.firstname, :encryptionkey) AS firstname,
					AES_DECRYPT(OCD.surname, :encryptionkey) AS surname,
					AES_DECRYPT(OCD.companyname, :encryptionkey) AS companyname,
					AES_DECRYPT(OCD.nip, :encryptionkey) AS nip,
					AES_DECRYPT(OCD.street, :encryptionkey) AS street,
					AES_DECRYPT(OCD.streetno, :encryptionkey) AS streetno,
					AES_DECRYPT(OCD.placeno, :encryptionkey) AS placeno,
					AES_DECRYPT(OCD.place, :encryptionkey) AS placename,
					AES_DECRYPT(OCD.postcode, :encryptionkey) AS postcode,
					AES_DECRYPT(OCD.email, :encryptionkey) AS email,
					AES_DECRYPT(OCD.phone, :encryptionkey) AS phone
					FROM orderclientdeliverydata OCD
				WHERE OCD.orderid = :idorder';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'companyname' => $rs->getString('companyname'),
				'nip' => $rs->getString('nip'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'phone' => $rs->getString('phone'),
				'email' => $rs->getString('email'),
				'placename' => $rs->getString('placename'),
				'postcode' => $rs->getString('postcode')
			);
		}
		return $Data;
	}

	protected function addOrderProduct ($Data, $orderId)
	{
		foreach ($Data as $idproduct => $product){
			if (isset($product['standard'])){
				$sql = 'INSERT INTO orderproduct(name, price, qty, qtyprice, orderid, productid, vat, pricenetto)
						VALUES (:name, :price, :qty, :qtyprice, :orderid, :productid, :vat, :pricenetto)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', $product['name']);
				$stmt->setFloat('price', $product['newprice']);
				$stmt->setFloat('qty', $product['qty']);
				$stmt->setFloat('qtyprice', $product['qtyprice']);
				$stmt->setInt('orderid', $orderId);
				$stmt->setInt('productid', $product['idproduct']);
				$stmt->setFloat('vat', $product['vat']);
				$stmt->setFloat('pricenetto', $product['pricewithoutvat']);
				try{
					$stmt->executeQuery();
					if ($product['trackstock'] == 1){
						$this->decreaseProductStock($product['idproduct'], $product['qty']);
					}
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			
			}
			
			if (isset($product['attributes'])){
				foreach ($product['attributes'] as $idattribute => $attribute){
					$sql = 'INSERT INTO orderproduct(name, price, qty, qtyprice, orderid, productid, productattributesetid, vat, pricenetto)
							VALUES (:name, :price, :qty, :qtyprice, :orderid, :productid, :productattributesetid, :vat, :pricenetto)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('name', $attribute['name']);
					$stmt->setFloat('price', $attribute['newprice']);
					$stmt->setFloat('qty', $attribute['qty']);
					$stmt->setFloat('qtyprice', $attribute['qtyprice']);
					$stmt->setInt('orderid', $orderId);
					$stmt->setInt('productid', $attribute['idproduct']);
					$stmt->setInt('productattributesetid', $attribute['attr']);
					$stmt->setFloat('vat', $attribute['vat']);
					$stmt->setFloat('pricenetto', $attribute['pricewithoutvat']);
					try{
						$stmt->executeQuery();
						$this->addOrderProductAttribute($attribute['features'], $stmt->getConnection()->getIdGenerator()->getId());
						if ($attribute['trackstock'] == 1){
							$this->decreaseProductAttributeStock($attribute['idproduct'], $attribute['attr'], $attribute['qty']);
						}
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
			}
		
		}
	}

	protected function addOrderProductAttribute ($Data, $orderProductId)
	{
		foreach ($Data as $featureid => $feature){
			$sql = 'INSERT INTO orderproductattribute (name, `group`, orderproductid)
					VALUES (:name,:group, :orderproductid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $feature['attributename']);
			$stmt->setString('group', $feature['group']);
			$stmt->setInt('orderproductid', $orderProductId);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	protected function decreaseProductAttributeStock ($productid, $idproductattribute, $qty)
	{
		$sql = 'UPDATE productattributeset SET stock = stock-:qty 
				WHERE productid = :productid 
				AND idproductattributeset = :idproductattribute';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('qty', $qty);
		$stmt->setInt('productid', $productid);
		$stmt->setInt('idproductattribute', $idproductattribute);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	protected function decreaseProductStock ($productid, $qty)
	{
		$sql = 'UPDATE product SET stock = stock-:qty
				WHERE idproduct = :idproduct';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('qty', $qty);
		$stmt->setInt('idproduct', $productid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getDate ()
	{
		$orderid = $this->registry->session->getActiveorderid();
		$signs = Array(
			':',
			'-',
			' '
		);
		$sql = "SELECT adddate FROM `order`
				WHERE idorder = :orderid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $orderid);
		$rs = $stmt->executeQuery();
		$Data = '';
		if ($rs->first()){
			$Data = $rs->getString('adddate');
			$Data = str_replace($signs, '', $Data);
		}
		return $Data;
	}

	public function generateOrderLink ($idorder)
	{
		$date = $this->getDate();
		$activelink = sha1($date . $idorder);
		return $activelink;
	}

	public function changeOrderLink ($orderid, $orderlink)
	{
		$sql = "UPDATE `order` SET activelink = :activelink 
					WHERE idorder = :orderid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('activelink', $orderlink);
		$stmt->setInt('orderid', $orderid);
		$rs = $stmt->executeQuery();
	}

	public function getOrderInfoForEraty ($idorder)
	{
		$sql = 'SELECT
					O.idorder, 
					O.adddate as orderdate, 
					O.dispatchmethodname,
					O.paymentmethodname, 
					O.dispatchmethodprice, 
					O.globalprice, 
					O.globalpricenetto, 
					O.price,
					AES_DECRYPT(OCD.firstname, :encryptionkey) AS firstname,
					AES_DECRYPT(OCD.surname, :encryptionkey) AS surname,
					AES_DECRYPT(OCD.email, :encryptionkey) AS email,
					AES_DECRYPT(OCD.street, :encryptionkey) AS street,
					AES_DECRYPT(OCD.streetno, :encryptionkey) AS streetno,
					AES_DECRYPT(OCD.placeno, :encryptionkey) AS placeno,
					AES_DECRYPT(OCD.phone, :encryptionkey) AS phone,
					AES_DECRYPT(OCD.place, :encryptionkey) AS placename,
					AES_DECRYPT(OCD.postcode, :encryptionkey) AS postcode
				FROM `order` O 
				LEFT JOIN orderclientdata OCD ON OCD.orderid= O.idorder
				WHERE O.idorder= :idorder';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'idorder' => $rs->getInt('idorder'),
				'globalprice' => $rs->getFloat('globalprice'),
				'orderdate' => $rs->getString('orderdate'),
				'price' => $rs->getFloat('price'),
				'email' => $rs->getString('email'),
				'orderdate' => $rs->getString('orderdate'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'phone' => $rs->getString('phone'),
				'placename' => $rs->getString('placename'),
				'postcode' => $rs->getString('postcode'),
				'dispatchmethodname' => $rs->getString('dispatchmethodname'),
				'paymentmethodname' => $rs->getString('paymentmethodname'),
				'dispatchmethodprice' => $rs->getFloat('dispatchmethodprice')
			);
		}
		return $Data;
	}

	public function getOrderById ($id)
	{
		$sql = "SELECT
					O.clientid, 
					O.customeropinion,
					O.adddate as order_date, 
					O.idorder as order_id, 
					OS.idorderstatus as current_status_id, 
					OST.name as current_status, 
					O.dispatchmethodprice as delivererprice, 
					O.dispatchmethodname as deliverername, 
					O.dispatchmethodid, 
					O.paymentmethodid,
					O.paymentmethodname as paymentname, 
					PM.controller AS paymentmethodcontroller,
					O.price as vat_value, 
					O.globalpricenetto as totalnetto, 
					O.globalprice as total, 
					O.orderstatusid,
					V.name as view,
					O.viewid, 
					O.currencyid, 
					O.currencysymbol, 
					O.currencyrate, 
					O.rulescartid,
					O.pricebeforepromotion,
					O.couponcode,
					O.coupondiscount,
					O.couponfreedelivery,
					O.couponid
				FROM `order` O
				LEFT JOIN view V ON O.viewid = V.idview
				LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
				LEFT JOIN paymentmethod PM ON PM.idpaymentmethod = O.paymentmethodid
				WHERE O.idorder = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'clientid' => $rs->getInt('clientid'),
				'customeropinion' => $rs->getString('customeropinion'),
				'order_id' => $rs->getInt('order_id'),
				'viewid' => $rs->getInt('viewid'),
				'view' => $rs->getString('view'),
				'orderstatusid' => $rs->getInt('orderstatusid'),
				'order_date' => $rs->getString('order_date'),
				'current_status' => $rs->getString('current_status'),
				'current_status_id' => $rs->getInt('current_status_id'),
				'clients_ip_address' => '123.456.123.456',
				'vat_value' => $rs->getString('vat_value'),
				'totalnetto' => $rs->getString('totalnetto'),
				'total' => $rs->getString('total'),
				'currencyid' => $rs->getInt('currencyid'),
				'currencysymbol' => $rs->getString('currencysymbol'),
				'currencyrate' => $rs->getFloat('currencyrate'),
				'pricebeforepromotion' => $rs->getFloat('pricebeforepromotion'),
				'rulescartid' => $rs->getInt('rulescartid'),
				'client' => $this->getClientData($id),
				'billing_address' => $this->getBillingAddress($id),
				'delivery_address' => $this->getDeliveryAddress($id),
				'products' => $this->getProducts($id)
			);
			
			$dispatchmethodVat = $this->getDispatchmethodForOrder($rs->getInt('dispatchmethodid'));
			
			$delivererpricenetto = $rs->getString('delivererprice') / (1 + ($dispatchmethodVat / 100));
			
			$Data['delivery_method'] = Array(
				'delivererprice' => $rs->getString('delivererprice'),
				'deliverername' => $rs->getString('deliverername'),
				'dispatchmethodid' => $rs->getInt('dispatchmethodid'),
				'delivererpricenetto' => $delivererpricenetto,
				'deliverervat' => sprintf('%01.2f', $dispatchmethodVat),
				'deliverervatvalue' => $rs->getString('delivererprice') - $delivererpricenetto
			);
			$Data['payment_method'] = Array(
				'paymentname' => $rs->getString('paymentname'),
				'paymentmethodcontroller' => $rs->getString('paymentmethodcontroller'),
				'paymentmethodid' => $rs->getInt('paymentmethodid')
			);
			
			$Data['coupon'] = Array(
				'couponcode' => $rs->getString('couponcode'),
				'coupondiscount' => $rs->getString('coupondiscount'),
				'couponid' => $rs->getInt('couponid'),
				'couponfreedelivery' => $rs->getInt('couponfreedelivery')
			);
		
		}
		return $Data;
	}

	public function getProducts ($id)
	{
		$sql = "SELECT 
					OP.idorderproduct,
					OP.productid as id, 
					OP.productattributesetid AS variant,
					OP.name, 
					OP.pricenetto as net_price, 
					OP.qty as quantity, 
					(OP.pricenetto*OP.qty) as net_subtotal, 
					OP.vat, 
					ROUND((OP.pricenetto * OP.qty) * OP.vat/100 , 2) as vat_value,
					ROUND(((OP.pricenetto*OP.qty)*OP.vat/100 )+(OP.pricenetto*OP.qty), 2) as subtotal
				FROM orderproduct OP
				WHERE OP.orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'net_price' => $rs->getString('net_price'),
				'quantity' => $rs->getInt('quantity'),
				'net_subtotal' => $rs->getString('net_subtotal'),
				'vat' => $rs->getString('vat'),
				'vat_value' => $rs->getString('vat_value'),
				'subtotal' => $rs->getString('subtotal'),
				'attributes' => $this->getOrderProductAttributes($rs->getInt('id'), $rs->getInt('variant'))
			);
		}
		return $Data;
	}

	public function getOrderProductAttributes ($productId, $variantId)
	{
		if ($variantId != NULL){
			$sql = '
				SELECT
					A.idproductattributeset AS id,
					A.`value`,
					A.stock AS qty,
					A.symbol,
					A.weight,
					A.suffixtypeid AS prefix_id,
					GROUP_CONCAT(SUBSTRING(CONCAT(\' \', CONCAT(AP.name,\': \',C.name)), 1) SEPARATOR \'<br />\') AS name
				FROM
					productattributeset A
					LEFT JOIN productattributevalueset B ON A.idproductattributeset = B.productattributesetid
					LEFT JOIN attributeproductvalue C ON B.attributeproductvalueid = C.idattributeproductvalue
					LEFT JOIN attributeproduct AP ON C.attributeproductid = AP.idattributeproduct
					LEFT JOIN product D ON A.productid = D.idproduct
					LEFT JOIN suffixtype E ON A.suffixtypeid = E.idsuffixtype
					LEFT JOIN vat V ON V.idvat = D.vatid
				WHERE
					productid = :productid AND
					A.idproductattributeset = :variantid
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $productId);
			$stmt->setInt('variantid', $variantId);
			$rs = $stmt->executeQuery();
			$Data = $rs->getAllRows();
			return (isset($Data[0]) ? $Data[0] : Array());
		}
		else{
			return Array();
		}
	
	}

	public function getClientData ($id)
	{
		$sql = "SELECT
					CGT.name as clientgroup,
					O.clientid as ids,
					AES_DECRYPT(OCD.firstname, :encryptionKey) as firstname, 
					AES_DECRYPT(OCD.surname, :encryptionKey) as surname, 
					AES_DECRYPT(CD.email, :encryptionKey) as email
				FROM orderclientdata OCD
				LEFT JOIN `order` O ON O.idorder = OCD.orderid
				LEFT JOIN clientdata CD ON CD.clientid=O.clientid
				LEFT JOIN clientgrouptranslation CGT ON CGT.clientgroupid = CD.clientgroupid AND languageid=:languageid
				WHERE OCD.orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'ids' => $rs->getInt('ids'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'email' => $rs->getString('email'),
				'clientgroup' => $rs->getString('clientgroup')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_DATA_NO_EXIST'));
		}
		return $Data;
	}

	public function getDeliveryAddress ($id)
	{
		$sql = "SELECT
					AES_DECRYPT(OCDD.firstname, :encryptionKey) firstname, 
					AES_DECRYPT(OCDD.surname, :encryptionKey) surname, 
					AES_DECRYPT(OCDD.place, :encryptionKey) city,
					AES_DECRYPT(OCDD.postcode, :encryptionKey) postcode,
					AES_DECRYPT(OCDD.phone, :encryptionKey) phone,
					AES_DECRYPT(OCDD.street, :encryptionKey) street,
					AES_DECRYPT(OCDD.streetno, :encryptionKey) streetno,
					AES_DECRYPT(OCDD.placeno, :encryptionKey) placeno,
					AES_DECRYPT(OCDD.email, :encryptionKey) email,
					AES_DECRYPT(OCDD.nip, :encryptionKey) nip,
					AES_DECRYPT(OCDD.companyname, :encryptionKey) companyname
				FROM orderclientdeliverydata OCDD
				WHERE orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'city' => $rs->getString('city'),
				'postcode' => $rs->getString('postcode'),
				'phone' => $rs->getString('phone'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'country' => 'Poland',
				'companyname' => $rs->getString('companyname'),
				'email' => $rs->getString('email'),
				'nip' => $rs->getString('nip')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_DELIVERY_ADDRESS_NO_EXIST'));
		}
		return $Data;
	}

	public function getBillingAddress ($id)
	{
		$sql = "SELECT
					AES_DECRYPT(OCD.firstname, :encryptionKey) AS firstname, 
					AES_DECRYPT(OCD.surname, :encryptionKey) AS surname, 
					AES_DECRYPT(OCD.place, :encryptionKey) AS city,
					AES_DECRYPT(OCD.postcode, :encryptionKey) AS postcode,
					AES_DECRYPT(OCD.phone, :encryptionKey) AS phone,
					AES_DECRYPT(OCD.street, :encryptionKey) AS street,
					AES_DECRYPT(OCD.streetno, :encryptionKey) AS streetno,
					AES_DECRYPT(OCD.placeno, :encryptionKey) AS placeno,
					AES_DECRYPT(OCD.email, :encryptionKey) AS email,
					AES_DECRYPT(OCD.nip, :encryptionKey) AS nip,
					AES_DECRYPT(OCD.companyname, :encryptionKey) AS companyname
				FROM orderclientdata OCD
				WHERE orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'city' => $rs->getString('city'),
				'postcode' => $rs->getString('postcode'),
				'phone' => $rs->getString('phone'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'country' => 'Poland',
				'companyname' => $rs->getString('companyname'),
				'email' => $rs->getString('email'),
				'nip' => $rs->getString('nip')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_BILLING_ADDRESS_NO_EXIST'));
		}
		return $Data;
	}

	public function getVATAllForRangeEditor ()
	{
		$sql = 'SELECT V.idvat AS id, V.value,	VT.name 
					FROM vat V
					LEFT JOIN vattranslation VT ON VT.vatid = V.idvat AND VT.languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getFloat('value');
		}
		return $Data;
	}

	public function getDispatchmethodForOrder ($id)
	{
		$sql = 'SELECT type FROM dispatchmethod WHERE iddispatchmethod = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$type = $rs->getInt('type');
		}
		if ($type == 1){
			$method = $this->getDispatchmethodPrice($id);
		}
		else{
			$method = $this->getDispatchmethodWeight($id);
		}
		if (isset($method['use_vat']) && $method['use_vat'] == 1 && $method['vat'] > 0){
			$vatData = $this->getVATAllForRangeEditor();
			$vatValue = $vatData[$method['vat']];
		}
		else{
			$vatValue = 0;
		}
		return $vatValue;
	}

	public function getDispatchmethodPrice ($id)
	{
		$sql = 'SELECT iddispatchmethodprice as id, dispatchmethodcost, `from`, `to`, vat 
					FROM dispatchmethodprice
					WHERE dispatchmethodid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['ranges'][] = Array(
				'min' => $rs->getString('from'),
				'max' => $rs->getString('to'),
				'price' => $rs->getString('dispatchmethodcost')
			);
			if ($rs->getString('vat') > 0){
				$Data['vat'] = $rs->getInt('vat');
				$Data['use_vat'] = 1;
			}
		}
		return $Data;
	}

	public function getDispatchmethodWeight ($id)
	{
		$sql = 'SELECT cost, `from`, `to`,vat
					FROM dispatchmethodweight
					WHERE dispatchmethodid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['ranges'][] = Array(
				'min' => $rs->getString('from'),
				'max' => $rs->getString('to'),
				'price' => $rs->getString('cost')
			);
			if ($rs->getString('vat') > 0){
				$Data['vat'] = $rs->getInt('vat');
				$Data['use_vat'] = 1;
			}
		}
		return $Data;
	}
}