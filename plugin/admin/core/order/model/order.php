<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: order.php 687 2012-09-01 12:02:47Z gekosale $
 */
class orderModel extends ModelWithDatagrid {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid) {
		$datagrid->setTableData('order', Array(
			'idorder' => Array(
				'source' => 'O.idorder'
			),
			'client' => Array(
				'source' => 'CONCAT(\'<strong>\',AES_DECRYPT(OC.surname,:encryptionkey),\' \',AES_DECRYPT(OC.firstname,:encryptionkey),\'</strong><br />\',AES_DECRYPT(OC.email,:encryptionkey))',
				'prepareForAutosuggest' => true
			),
			'price' => Array(
				'source' => 'O.price'
			),
			'globalprice' => Array(
				'source' => 'CONCAT(O.globalprice,\' \',O.currencysymbol)'
			),
			'dispatchmethodprice' => Array(
				'source' => 'O.dispatchmethodprice'
			),
			'orderstatusname' => Array(
				'source' => 'OST.name'
			),
			'products' => Array(
				'source' => 'O.idorder',
				'processFunction' => Array(
					$this,
					'getOrderProductListByClientForDatagrid'
				)
			),
			'orderstatusid' => Array(
				'source' => 'O.orderstatusid',
				'prepareForTree' => true,
				'first_level' => $this->getStatuses()
			),
			'dispatchmethodname' => Array(
				'source' => 'O.dispatchmethodname',
				'prepareForSelect' => true
			),
			'paymentmethodname' => Array(
				'source' => 'O.paymentmethodname',
				'prepareForSelect' => true
			),
			'adddate' => Array(
				'source' => 'O.adddate'
			),
			'clientid' => Array(
				'source' => 'O.clientid'
			),
			'view' => Array(
				'source' => 'V.name',
				'prepareForSelect' => true
			)
		));
		
		$datagrid->setFrom('
			`order` O
			LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
			LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
			LEFT JOIN orderclientdata OC ON OC.orderid=O.idorder
			LEFT JOIN view V ON V.idview = O.viewid
		');
		
		$datagrid->setAdditionalWhere('
			O.viewid IN (:viewids)
		');
	}

	public function getClientForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getFilterSuggestions('client', $request, $processFunction);
	}

	public function getFirstnameForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getFilterSuggestions('firstname', $request, $processFunction);
	}

	public function getSurnameForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getFilterSuggestions('surname', $request, $processFunction);
	}

	public function getStatusesAll () {
		$sql = 'SELECT 
					OST.orderstatusid, 
					OST.name 
				FROM `orderstatustranslation` OST 
				LEFT JOIN orderstatus OS ON OST.orderstatusid = OS.idorderstatus
				WHERE OST.languageid = :id
				ORDER BY OST.name ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $this->registry->session->getActiveLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		$i = 0;
		while ($rs->next()){
			$i ++;
			$Data[$rs->getInt('orderstatusid')] = Array(
				'id' => $rs->getInt('orderstatusid'),
				'name' => $rs->getString('name'),
				'hasChildren' => false,
				'parent' => null,
				'weight' => $i
			);
		}
		return $Data;
	}

	public function getStatuses () {
		$statuses = $this->getStatusesAll();
		usort($statuses, Array(
			$this,
			'sortStatuses'
		));
		return $statuses;
	}

	protected function sortStatuses ($a, $b) {
		return $a['weight'] - $b['weight'];
	}

	public function calculateDeliveryCostEdit ($request) {
		$rulesCart = Array();
		$cost = 0.00;
		$rate = 0.00;
		if (isset($request['price_for_deliverers']) && isset($request['delivery_method'])){
			$sql = 'SELECT type FROM dispatchmethod WHERE iddispatchmethod = :dipatchmethodid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dipatchmethodid', $request['delivery_method']);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$type = $rs->getInt('type');
			}
			
			if ($type == 1){
				$sql = "SELECT
							IF(DP.vat IS NOT NULL, ROUND(DP.dispatchmethodcost+(DP.dispatchmethodcost*(V.`value`/100)),4), DP.dispatchmethodcost) as dispatchmethodcost, 
							CASE
			  					WHEN (`from`<>0 AND `from`< :price AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
			 				 	WHEN ( :price BETWEEN `from` AND `to`) THEN D.name
			  					WHEN (`to` = 0 AND `from`< :price AND DP.dispatchmethodcost <> 0) THEN D.name
			  					WHEN (`from`=0 AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
							END as name
						FROM dispatchmethodprice DP
						LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
						LEFT JOIN vat V ON V.idvat = DP.vat
		       			WHERE dispatchmethodid = :dipatchmethodid
						HAVING name IS NOT NULL";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setFloat('price', $request['price_for_deliverers']);
				$stmt->setInt('dipatchmethodid', $request['delivery_method']);
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$cost = $rs->getFloat('dispatchmethodcost');
				}
			}
			else{
				$sql = "SELECT
							IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)),4), DW.cost) as cost, 
							CASE
			  					WHEN (`from`<>0 AND `from`< :weight AND `to`=0 AND DW.cost =0) THEN D.name
			 				 	WHEN ( :weight BETWEEN `from` AND `to`) THEN D.name
			  					WHEN (`to` = 0 AND `from`< :weight AND DW.cost <> 0) THEN D.name
			  					WHEN (`from`=0 AND `to`=0 AND DW.cost =0) THEN D.name
							END as name
						FROM dispatchmethodweight DW
						LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
						LEFT JOIN vat V ON V.idvat = DW.vat
		       			WHERE dispatchmethodid = :dipatchmethodid
						HAVING name IS NOT NULL";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setFloat('weight', $request['weight']);
				$stmt->setInt('dipatchmethodid', $request['delivery_method']);
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$cost = $rs->getFloat('cost');
				}
			}
		}
		$order = $this->getOrderById($request['idorder']);
		
		if (isset($request['rules_cart']) && $request['rules_cart'] > 0){
			$rulesCart = $this->calculateRulesCatalog($request['rules_cart']);
		}
		if (isset($request['rules_cart']) && ($request['rules_cart'] == $order['rulescartid'])){
			if ($order['total'] > $order['pricebeforepromotion']){
				$rulesCart = Array(
					'discount' => abs($order['total'] - $order['pricebeforepromotion']),
					'suffixtypeid' => 2,
					'symbol' => '+'
				);
			}
			else{
				$rulesCart = Array(
					'discount' => abs($order['pricebeforepromotion'] - $order['total']),
					'suffixtypeid' => 3,
					'symbol' => '-'
				);
			}
		}
		if ($order['totalnetto'] == $request['net_total'] && $request['delivery_method'] == $order['delivery_method']['dispatchmethodid']){
			$cost = $order['delivery_method']['delivererprice'];
		}
		$coupon = 0;
		if (isset($order['coupon']['couponfreedelivery']) && $order['coupon']['couponfreedelivery'] == 1){
			$cost = 0;
		}
		if (isset($order['coupon']['coupondiscount']) && $order['coupon']['coupondiscount'] > 0){
			$coupon = $order['coupon']['coupondiscount'];
		}
		return Array(
			'cost' => $cost,
			'rulesCart' => $rulesCart,
			'rate' => $rate,
			'coupon' => $coupon
		);
	}

	public function calculateDeliveryCostAdd ($request) {
		$rulesCart = Array();
		$cost = 0.00;
		$rate = 0.00;
		if (isset($request['price_for_deliverers']) && isset($request['delivery_method'])){
			$sql = 'SELECT type FROM dispatchmethod WHERE iddispatchmethod = :dipatchmethodid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('dipatchmethodid', $request['delivery_method']);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$type = $rs->getInt('type');
			}
			
			if ($type == 1){
				$sql = "SELECT
							IF(DP.vat IS NOT NULL, ROUND(DP.dispatchmethodcost+(DP.dispatchmethodcost*(V.`value`/100)),4), DP.dispatchmethodcost) as dispatchmethodcost, 
							CASE
			  					WHEN (`from`<>0 AND `from`< :price AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
			 				 	WHEN ( :price BETWEEN `from` AND `to`) THEN D.name
			  					WHEN (`to` = 0 AND `from`< :price AND DP.dispatchmethodcost <> 0) THEN D.name
			  					WHEN (`from`=0 AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
							END as name
						FROM dispatchmethodprice DP
						LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
						LEFT JOIN vat V ON V.idvat = DP.vat
		       			WHERE dispatchmethodid = :dipatchmethodid
						HAVING name IS NOT NULL";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setFloat('price', $request['price_for_deliverers']);
				$stmt->setInt('dipatchmethodid', $request['delivery_method']);
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$cost = $rs->getFloat('dispatchmethodcost');
				}
			}
			else{
				$sql = "SELECT
							IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)),4), DW.cost) as cost, 
							CASE
			  					WHEN (`from`<>0 AND `from`< :weight AND `to`=0 AND DW.cost =0) THEN D.name
			 				 	WHEN ( :weight BETWEEN `from` AND `to`) THEN D.name
			  					WHEN (`to` = 0 AND `from`< :weight AND DW.cost <> 0) THEN D.name
			  					WHEN (`from`=0 AND `to`=0 AND DW.cost =0) THEN D.name
							END as name
						FROM dispatchmethodweight DW
						LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
						LEFT JOIN vat V ON V.idvat = DW.vat
		       			WHERE dispatchmethodid = :dipatchmethodid
						HAVING name IS NOT NULL";
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setFloat('weight', $request['weight']);
				$stmt->setInt('dipatchmethodid', $request['delivery_method']);
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$cost = $rs->getFloat('cost');
				}
			}
		}
		
		return Array(
			'cost' => $cost,
			'rulesCart' => $rulesCart
		);
	}

	public function calculateRulesCatalog ($rulesCartId) {
		$rulesCart = Array();
		if (isset($rulesCartId) && ! empty($rulesCartId)){
			$sql = "SELECT 
						RC.discount, 
						RC.suffixtypeid, 
						ST.symbol
					FROM rulescart RC
					LEFT JOIN suffixtype ST ON ST.idsuffixtype = RC.suffixtypeid
					WHERE RC.idrulescart = :rulescartid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('rulescartid', $rulesCartId);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$rulesCart = Array(
					'discount' => $rs->getFloat('discount'),
					'suffixtypeid' => $rs->getInt('suffixtypeid'),
					'symbol' => $rs->getString('symbol')
				);
			}
		}
		return $rulesCart;
	}

	public function getDatagridFilterData () {
		return $this->getDatagrid()->getFilterData();
	}

	public function getOrderForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteOrder ($id, $datagrid) {
		if (is_array($id)){
			foreach ($id as $key => $orderid){
				$this->deleteOrder($orderid);
			}
		}
		else{
			$this->deleteOrder($id);
		}
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteOrder ($id) {
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idorder' => $id
			), $this->getName(), 'deleteOrder');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getProducts ($id) {
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
					ROUND(((OP.pricenetto*OP.qty)*OP.vat/100 )+(OP.pricenetto*OP.qty), 2) as subtotal,
					P.ean
				FROM orderproduct OP
				LEFT JOIN product P ON P.idproduct = OP.productid
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
				'ean' => $rs->getString('ean'),
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

	public function getOrderById ($id) {
		$sql = "SELECT
					O.clientid, 
					CD.clientgroupid,
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
					O.pricebeforepromotion ,
					(SELECT idorder FROM `order` WHERE idorder < :id ORDER BY idorder DESC LIMIT 1) AS previous,
					(SELECT idorder FROM `order` WHERE idorder > :id LIMIT 1) AS next,
					O.couponcode,
					O.coupondiscount,
					O.couponfreedelivery,
					O.couponid
				FROM `order` O
				LEFT JOIN clientdata CD ON CD.clientid = O.clientid
				LEFT JOIN view V ON O.viewid = V.idview
				LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
				LEFT JOIN paymentmethod PM ON PM.idpaymentmethod = O.paymentmethodid
				WHERE O.idorder=:id AND O.viewid IN (:viewids)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'clientid' => $rs->getInt('clientid'),
				'clientgroupid' => $rs->getInt('clientgroupid'),
				'customeropinion' => $rs->getString('customeropinion'),
				'order_id' => $rs->getInt('order_id'),
				'previous' => $rs->getInt('previous'),
				'next' => $rs->getInt('next'),
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
				'billing_address' => $this->getBillingAddress($id),
				'delivery_address' => $this->getDeliveryAddress($id),
				'products' => $this->getProducts($id),
				'order_history' => $this->getOrderHistory($id),
				'order_files' => $this->getOrderFiles($id),
				'invoices' => $this->getOrderInvoices($id)
			);
			$dispatchmethodVat = App::getModel('dispatchmethod')->getDispatchmethodForOrder($rs->getInt('dispatchmethodid'));
			
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
		else{
			App::redirect(__ADMINPANE__ . '/order');
		}
		return $Data;
	}

	public function getOrderInvoices ($id) {
		$sql = "SELECT 
					idinvoice,
					symbol, 
					invoicedate,
					comment,
					salesperson,
					paymentduedate,
					totalpayed
				FROM invoice
				WHERE orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getOrderFiles ($id) {
		$sql = "SELECT 
					path
				FROM orderfiles
				WHERE orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'path' => $rs->getString('path')
			);
		}
		return $Data;
	}

	public function getDeliveryAddress ($id) {
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
					AES_DECRYPT(OCDD.companyname, :encryptionKey) companyname,
					OCDD.countryid
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
				'countryid' => $rs->getInt('countryid'),
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

	public function getBillingAddress ($id) {
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
					AES_DECRYPT(OCD.companyname, :encryptionKey) AS companyname,
					OCD.countryid
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
				'countryid' => $rs->getInt('countryid'),
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

	public function checkProductWithAttributes ($id) {
		$sql = "SELECT 
					COUNT(orderid) AS total
				FROM orderproduct
				WHERE productid = :id AND productattributesetid IS NOT NULL";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if ($rs->getInt('total') > 0)
				return 0;
		}
		else{
			return 1;
		}
		return 1;
	}

	public function getDispatchMethodForPriceForAjaxEdit ($request) {
		$order = $this->getOrderById($request['idorder']);
		$methodsRaw = $this->getDispatchmethodForPrice($request['gross_total'], $request['idorder'], $order['currencyid'], $request['weight_total']);
		$methods = Array();
		
		foreach ($methodsRaw as $method){
			$methods[] = Array(
				'sValue' => $method['id'],
				'sLabel' => $method['namewithprice']
			);
		}
		foreach ($methods as $key => $m){
			if ($order['totalnetto'] == $request['net_total'] && $m['sValue'] == $order['delivery_method']['dispatchmethodid']){
				$name = $order['delivery_method']['deliverername'] . ' (' . $order['delivery_method']['delivererprice'] . ')';
				$methods[$key] = Array(
					'sValue' => $m['sValue'],
					'sLabel' => $name
				);
			}
		}
		return Array(
			'options' => $methods
		);
	}

	public function getDispatchMethodForPriceForAjaxAdd ($request) {
		$methodsRaw = $this->getDispatchmethodForPriceAdd($request['gross_total'], $request['weight_total']);
		$methods = Array();
		
		foreach ($methodsRaw as $method){
			$methods[] = Array(
				'sValue' => $method['id'],
				'sLabel' => $method['namewithprice']
			);
		}
		return Array(
			'options' => $methods
		);
	}

	public function getDispatchmethodForPrice ($price = 0, $idorder = 0, $currencyid = 0, $globalweight = 0) {
		$Data = Array();
		$sql = "SELECT 
					DP.dispatchmethodid as id, 
					DP.`from`, 
					DP.`to`, 
					IF(DP.vat IS NOT NULL, ROUND(DP.dispatchmethodcost+(DP.dispatchmethodcost*(V.`value`/100)),4), DP.dispatchmethodcost) as dispatchmethodcost,
					CASE
  						WHEN (`from`<>0 AND `from`< :price AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
 					 	WHEN ( :price BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`< :price AND DP.dispatchmethodcost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
					END as name
				FROM dispatchmethodprice DP
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN vat V ON V.idvat = DP.vat
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE 
					(DV.viewid = (SELECT O.viewid FROM `order` O WHERE O.idorder= :idorder) OR DP.dispatchmethodid = (SELECT O.dispatchmethodid FROM `order` O WHERE O.idorder= :idorder)) AND
					D.type = 1 AND 
					IF(D.maximumweight IS NOT NULL, D.maximumweight >= :globalweight, 1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setFloat('price', $price);
		$stmt->setInt('idorder', $idorder);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$cost = $rs->getFloat('dispatchmethodcost');
				$name = $rs->getString('name');
				if (! empty($name)){
					$Data[] = Array(
						'id' => $rs->getInt('id'),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'dispatchmethodcost' => $cost,
						'name' => $name,
						'namewithprice' => ($cost >= .01) ? sprintf('%s (%.2f)', $rs->getString('name'), $cost) : $rs->getString('name')
					);
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$sql = "SELECT 
					DW.dispatchmethodid as id, 
					DW.`from`, 
					DW.`to`, 
					IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)),4), DW.cost) as cost, 
					D.freedelivery,
					CASE
  						WHEN (`from`<>0 AND `from`< :globalweight AND `to`=0 AND DW.cost =0) THEN D.name
 					 	WHEN ( :globalweight BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`< :globalweight AND DW.cost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DW.cost =0) THEN D.name
					END as name
				FROM dispatchmethodweight DW
				LEFT JOIN vat V ON V.idvat = DW.vat
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE 
					(DV.viewid = (SELECT O.viewid FROM `order` O WHERE O.idorder= :idorder) OR DW.dispatchmethodid = (SELECT O.dispatchmethodid FROM `order` O WHERE O.idorder= :idorder)) AND 
					D.type = 2";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setFloat('price', $price);
		$stmt->setInt('idorder', $idorder);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$cost = $rs->getFloat('cost');
				if (($rs->getFloat('freedelivery') > 0) && ($rs->getFloat('freedelivery') <= $price)){
					$cost = 0.00;
				}
				else{
					$cost = $rs->getFloat('cost');
				}
				$name = $rs->getString('name');
				if (! empty($name)){
					$Data[] = Array(
						'id' => $rs->getInt('id'),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'dispatchmethodcost' => $cost,
						'name' => $name,
						'namewithprice' => ($cost >= .01) ? sprintf('%s (%.2f)', $rs->getString('name'), $cost) : $rs->getString('name')
					);
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getDispatchmethodForPriceAdd ($price = 0, $globalweight = 0) {
		$Data = Array();
		$sql = "SELECT 
					DP.dispatchmethodid as id, 
					DP.`from`, 
					DP.`to`, 
					IF(DP.vat IS NOT NULL, ROUND(DP.dispatchmethodcost+(DP.dispatchmethodcost*(V.`value`/100)),4), DP.dispatchmethodcost) as dispatchmethodcost,
					CASE
  						WHEN (`from`<>0 AND `from`< :price AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
 					 	WHEN ( :price BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`< :price AND DP.dispatchmethodcost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
					END as name
				FROM dispatchmethodprice DP
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN vat V ON V.idvat = DP.vat
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE 
					DV.viewid = :viewid AND
					D.type = 1 AND 
					IF(D.maximumweight IS NOT NULL, D.maximumweight >= :globalweight, 1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setFloat('price', $price);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$cost = $rs->getFloat('dispatchmethodcost');
				$name = $rs->getString('name');
				if (! empty($name)){
					$Data[] = Array(
						'id' => $rs->getInt('id'),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'dispatchmethodcost' => $cost,
						'name' => $name,
						'namewithprice' => ($cost >= .01) ? sprintf('%s (%.2f)', $rs->getString('name'), $cost) : $rs->getString('name')
					);
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$sql = "SELECT 
					DW.dispatchmethodid as id, 
					DW.`from`, 
					DW.`to`, 
					IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)),4), DW.cost) as cost, 
					D.freedelivery,
					CASE
  						WHEN (`from`<>0 AND `from`< :globalweight AND `to`=0 AND DW.cost =0) THEN D.name
 					 	WHEN ( :globalweight BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`< :globalweight AND DW.cost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DW.cost =0) THEN D.name
					END as name
				FROM dispatchmethodweight DW
				LEFT JOIN vat V ON V.idvat = DW.vat
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE 
					DV.viewid = :viewid AND 
					D.type = 2";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setFloat('price', $price);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$cost = $rs->getFloat('cost');
				if (($rs->getFloat('freedelivery') > 0) && ($rs->getFloat('freedelivery') <= $price)){
					$cost = 0.00;
				}
				else{
					$cost = $rs->getFloat('cost');
				}
				$name = $rs->getString('name');
				if (! empty($name)){
					$Data[] = Array(
						'id' => $rs->getInt('id'),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'dispatchmethodcost' => $cost,
						'name' => $name,
						'namewithprice' => ($cost >= .01) ? sprintf('%s (%.2f)', $rs->getString('name'), $cost) : $rs->getString('name')
					);
				}
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $Data;
	}

	public function getDispatchmethodAllToSelect ($price = 0, $idorder = 0) {
		$Data = $this->getDispatchmethodForPrice($price, $idorder);
		$tmp = Array();
		foreach ($Data as $key){
			if (! empty($key['name']) && $key['name'] !== NULL){
				$tmp[$key['id']] = $key['name'];
			}
		}
		return $tmp;
	}

	public function getPaymentmethodAll ($idorder = 0) {
		$Data = Array();
		
		$sql = "SELECT PM.idpaymentmethod AS id, PM.name
					FROM paymentmethod PM
					LEFT JOIN paymentmethodview PMV ON PMV.paymentmethodid =PM.idpaymentmethod
					WHERE 
						IF (:idorder>0, PMV.viewid= (SELECT O.viewid FROM `order` O WHERE O.idorder= :idorder),
							IF(:viewid>0, PMV.viewid= :viewid, 0))";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idorder', $idorder);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getPaymentmethodAllToSelect ($idorder = 0) {
		$Data = $this->getPaymentmethodAll($idorder);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function getAllRules ($orderid) {
		$Data = Array();
		$Data[0] = $this->registry->core->getMessage('TXT_CHOOSE_SELECT');
		$sql = "SELECT R.idrulescart, R.name
					FROM rulescart R
					LEFT JOIN rulescartview RV ON RV.rulescartid = R.idrulescart
					WHERE IF(:viewid >0, RV.viewid = :viewid, 0)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('idrulescart')] = $rs->getString('name');
		}
		return $Data;
	}

	public function getAllRulesForOrder ($orderid) {
		$Data = Array();
		$Data[0] = $this->registry->core->getMessage('TXT_CHOOSE_SELECT');
		$sql = "SELECT R.idrulescart, R.name
					FROM rulescart R
					LEFT JOIN rulescartview RV ON RV.rulescartid = R.idrulescart
					WHERE RV.viewid = (SELECT O.viewid FROM `order` O WHERE O.idorder = :orderid)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $orderid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('idrulescart')] = $rs->getString('name');
		}
		return $Data;
	}

	public function getProductForOrder ($id) {
		$sql = "SELECT O.idorder as id, OP.name as productname, OP.price, OP.qty, OP.idorderproduct
					FROM `order` O
					LEFT JOIN orderproduct OP ON OP.orderid = O.idorder
					WHERE idorder=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'productname' => $rs->getString('productname'),
				'price' => $rs->getFloat('price'),
				'qty' => $rs->getInt('qty'),
				'attributes' => $this->getProductAttributes($rs->getInt('idorderproduct'))
			);
		}
		return $Data;
	}

	public function getProductAttributes ($attrId) {
		$sql = 'SELECT
					OP.idorderproduct as attrId,
					OPA.name as attributename
				FROM orderproduct OP 
				LEFT JOIN orderproductattribute OPA ON OPA.orderproductid=OP.idorderproduct
				WHERE orderproductid = :attrId';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('attrId', $attrId);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'attributename' => $rs->getString('attributename')
			);
		}
		return $Data;
	}

	public function addOrderHistory ($Data, $orderid) {
		$sql = 'INSERT INTO orderhistory(content, orderstatusid, orderid, inform, addid)
					VALUES (:content, :orderstatusid, :orderid, :inform, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('content', $Data['comment']);
		$stmt->setInt('orderstatusid', $Data['status']);
		$stmt->setInt('orderid', $orderid);
		if (($Data['inform']) == 1){
			$stmt->setInt('inform', $Data['inform']);
		}
		else{
			$stmt->setInt('inform', 0);
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
		}
	}

	public function getOrderHistory ($id) {
		$sql = "SELECT 
					OH.content, 
					OST.name as orderstatusname, 
					OH.inform, 
					OH.adddate as date, 
					UD.firstname, 
					UD.surname
				FROM orderhistory OH
				LEFT JOIN orderstatus OS ON OS.idorderstatus = OH.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
				LEFT JOIN userdata UD ON UD.userid = OH.addid
				WHERE OH.orderid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'content' => $rs->getString('content'),
				'date' => $rs->getString('date'),
				'inform' => $rs->getInt('inform'),
				'orderstatusname' => $rs->getString('orderstatusname'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname')
			);
		}
		return $Data;
	}

	public function getLastOrderHistory ($id, $status) {
		$sql = "SELECT
					AES_DECRYPT(OCD.firstname, :encryptionKey) firstname, 
					AES_DECRYPT(OCD.email, :encryptionKey) email, 
					AES_DECRYPT(OCD.surname, :encryptionKey) surname, 
					OH.orderid as ids, OH.content, OST.name as orderstatusname
				FROM orderhistory OH
				LEFT JOIN orderstatus OS ON OS.idorderstatus = OH.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
				LEFT JOIN orderclientdata OCD ON OCD.orderid=OH.orderid
				WHERE OH.orderid=:id and OH.orderstatusid=:status ORDER BY OH.adddate DESC LIMIT 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('status', $status);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'ids' => $rs->getInt('ids'),
				'email' => $rs->getString('email'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'content' => $rs->getString('content'),
				'orderstatusname' => $rs->getString('orderstatusname')
			);
		}
		return $Data;
	}

	public function updateOrderById ($Data, $id) {
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateOrderDeliveryAddress($Data['address_data']['shipping_data'], $id);
			$this->updateOrderBillingAddress($Data['address_data']['billing_data'], $id);
			if (isset($Data['products_data'])){
				$this->updateOrderProduct($Data['products_data'], $id);
			}
			$this->updateOrder($Data, $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
		die();
	}

	public function updateOrderDeliveryAddress ($Data, $id) {
		$sql = 'UPDATE orderclientdeliverydata SET
					firstname = AES_ENCRYPT(:firstname, :encryptionKey),
					surname = AES_ENCRYPT(:surname, :encryptionKey),
					street = AES_ENCRYPT(:street, :encryptionKey),
					streetno = AES_ENCRYPT(:streetno, :encryptionKey),
					placeno = AES_ENCRYPT(:placeno, :encryptionKey),
					postcode = AES_ENCRYPT(:postcode, :encryptionKey),
					place = AES_ENCRYPT(:place, :encryptionKey),
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					email = AES_ENCRYPT(:email, :encryptionKey),
					nip = AES_ENCRYPT(:nip, :encryptionKey),
					companyname = AES_ENCRYPT(:companyname, :encryptionKey)
				WHERE orderid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('place', $Data['place']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_DELIVERY_ADDRESS_EDIT'), 13, $e->getMessage());
		}
		return true;
	}

	public function updateOrderBillingAddress ($Data, $id) {
		$sql = 'UPDATE orderclientdata SET
					firstname = AES_ENCRYPT(:firstname, :encryptionKey),
					surname = AES_ENCRYPT(:surname, :encryptionKey),
					street = AES_ENCRYPT(:street, :encryptionKey),
					streetno = AES_ENCRYPT(:streetno, :encryptionKey),
					placeno = AES_ENCRYPT(:placeno, :encryptionKey),
					postcode = AES_ENCRYPT(:postcode, :encryptionKey),
					place = AES_ENCRYPT(:place, :encryptionKey),
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					email = AES_ENCRYPT(:email, :encryptionKey),
					nip = AES_ENCRYPT(:nip, :encryptionKey),
					companyname = AES_ENCRYPT(:companyname, :encryptionKey)
				WHERE orderid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('place', $Data['place']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_BILLING_ADDRESS_EDIT'), 13, $e->getMessage());
		}
		return true;
	}

	public function getOrderProductAttributes ($productId, $variantId) {
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
				GROUP BY A.idproductattributeset
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

	public function updateOrderProduct ($Data, $id) {
		$sql = 'DELETE FROM orderproductattribute WHERE orderproductid IN (SELECT idorderproduct FROM orderproduct WHERE orderid = :id)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$sql = 'DELETE FROM orderproduct WHERE orderid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$stringData = Array();
		
		foreach ($Data['products'] as $value){
			$ids = $value['idproduct'];
			$sql = "SELECT
						P.sellprice, 
						V.`value` as vat, 
						PT.name as productname, 
						P.idproduct, 
						ROUND((P.sellprice + P.sellprice*(V.`value`/100)),2) as pricebrutto
					FROM product P
					LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
					LEFT JOIN vat V ON V.idvat = vatid
					WHERE P.idproduct=:ids";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('ids', $ids);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$productid = $rs->getInt('idproduct');
				$stringData[$productid] = Array(
					'idproduct' => $productid,
					'pricebrutto' => $rs->getString('pricebrutto'),
					'vat' => $rs->getString('vat'),
					'productname' => $rs->getString('productname')
				);
			}
		}
		foreach ($Data['products'] as $value){
			$sql = 'INSERT INTO orderproduct SET
						name = :name,
						price = :price,
						qty = :qty,
						qtyprice = :qtyprice,
						orderid = :orderid,
						productid = :productid,
						productattributesetid = :productattributesetid,
						vat = :vat,
						pricenetto = :pricenetto';
			$stmt = $this->registry->db->prepareStatement($sql);
			if (substr('' . $value['idproduct'], 0, 3) != 'new'){
				$stmt->setString('name', $stringData[$value['idproduct']]['productname']);
				$stmt->setString('price', floatval($value['sellprice']) * (1 + floatval($stringData[$value['idproduct']]['vat']) / 100));
				$stmt->setInt('orderid', $id);
				$stmt->setInt('productid', $value['idproduct']);
				$stmt->setString('vat', $stringData[$value['idproduct']]['vat']);
			}
			else{
				$stmt->setString('name', $value['name']);
				$stmt->setString('price', floatval($value['sellprice']) * (1 + floatval($value['vat']) / 100));
				$stmt->setInt('orderid', $id);
				$stmt->setNull('productid');
				$stmt->setString('vat', $value['vat']);
			}
			$stmt->setString('qty', $value['quantity']);
			$stmt->setString('qtyprice', ($value['quantity'] * $value['sellprice']));
			if ($value['variant'] > 0){
				$stmt->setInt('productattributesetid', $value['variant']);
			}
			else{
				$stmt->setInt('productattributesetid', NULL);
			}
			$stmt->setString('pricenetto', $value['sellprice']);
			
			if ($value['trackstock'] == 1){
				$decrease = $value['quantity'] - $value['previousquantity'];
				if ($decrease > $value['stock']){
					$decrease = $value['stock'];
				}
				if ($value['variant'] > 0){
					$this->decreaseProductAttributeStock($value['idproduct'], $value['variant'], $decrease);
				}
				else{
					$this->decreaseProductStock($value['idproduct'], $decrease);
				}
			}
			
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_TO_ORDER_ADD'), 112, $e->getMessage());
			}
		}
	}

	protected function decreaseProductAttributeStock ($productid, $idproductattribute, $qty) {
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

	protected function decreaseProductStock ($productid, $qty) {
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

	public function updateOrder ($Data, $id) {
		$dispatchmethodId = $Data['additional_data']['payment_data']['delivery_method'];
		
		$sql = "SELECT 
					D.name as dispatchmethodname, 
					D.iddispatchmethod
				FROM dispatchmethod D
				WHERE iddispatchmethod = :dispatchmethodId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('dispatchmethodId', $dispatchmethodId);
		$rs = $stmt->executeQuery();
		$dispatchData = Array();
		if ($rs->first()){
			$dispatchmethodname = $rs->getString('dispatchmethodname');
		}
		
		$paymentmethodId = $Data['additional_data']['payment_data']['payment_method'];
		
		$sql = "SELECT 
					name as paymentmethodname, 
					idpaymentmethod
				FROM paymentmethod 
				WHERE idpaymentmethod=:paymentmethodId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('paymentmethodId', $paymentmethodId);
		$rs = $stmt->executeQuery();
		$paymentData = Array();
		if ($rs->first()){
			$paymenId = $rs->getInt('idpaymentmethod');
			$paymentData[$paymenId] = Array(
				'paymentmethodname' => $rs->getString('paymentmethodname')
			);
		}
		$Data['pricebrutto'] = $Data['pricebrutto'] - $Data['coupon'];
		if (isset($Data['additional_data']['payment_data']['rules_cart']) && $Data['additional_data']['payment_data']['rules_cart'] > 0){
			$ruleCart = App::getModel('order')->calculateRulesCatalog($Data['additional_data']['payment_data']['rules_cart']);
			if (! empty($ruleCart) && $ruleCart['discount'] > 0){
				$symbol = $ruleCart['symbol'];
				switch ($symbol) {
					case '%':
						$pricePromo = abs($Data['pricebrutto'] * ($ruleCart['discount'] / 100));
						$globalpricePromo = abs(($Data['pricebrutto'] + $Data['dispatchmethodprice']) * ($ruleCart['discount'] / 100));
						$globalpricenettoPromo = abs($Data['pricenetto'] * ($ruleCart['discount'] / 100));
						break;
					case '+':
						$pricePromo = $Data['pricebrutto'] + $ruleCart['discount'];
						$globalpricePromo = ($Data['pricebrutto'] + $Data['dispatchmethodprice']) + $ruleCart['discount'];
						$globalpricenettoPromo = $Data['pricenetto'] + $ruleCart['discount'];
						break;
					case '-':
						$pricePromo = $Data['pricebrutto'] - $ruleCart['discount'];
						$globalpricePromo = ($Data['pricebrutto'] + $Data['dispatchmethodprice']) - $ruleCart['discount'];
						$globalpricenettoPromo = $Data['pricenetto'] - $ruleCart['discount'];
						break;
				}
			}
		}
		$sql = 'UPDATE `order` SET 
					price=:price,
					dispatchmethodprice=:dispatchmethodprice,
					globalprice=:globalprice,
					dispatchmethodname=:dispatchmethodname,
					dispatchmethodid=:dispatchmethodid, 
					paymentmethodname=:paymentmethodname,
					paymentmethodid=:paymentmethodid,
					globalpricenetto=:globalpricenetto,
					pricebeforepromotion= :pricebeforepromotion,
					rulescartid= :rulescartid
				WHERE idorder= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (isset($pricePromo) && $pricePromo > 0){
			$stmt->setFloat('price', $pricePromo);
			$stmt->setFloat('globalprice', $globalpricePromo);
			$stmt->setFloat('dispatchmethodprice', $Data['dispatchmethodprice']);
			$stmt->setFloat('globalpricenetto', $globalpricenettoPromo);
			$stmt->setFloat('pricebeforepromotion', ($Data['pricebrutto'] + $Data['dispatchmethodprice']));
			$stmt->setInt('rulescartid', $Data['additional_data']['payment_data']['rules_cart']);
		}
		else{
			$stmt->setFloat('price', $Data['pricebrutto']);
			$stmt->setFloat('globalprice', ($Data['pricebrutto'] + $Data['dispatchmethodprice']));
			$stmt->setFloat('dispatchmethodprice', $Data['dispatchmethodprice']);
			$stmt->setFloat('globalpricenetto', $Data['pricenetto']);
			$stmt->setNull('pricebeforepromotion');
			$stmt->setNull('rulescartid');
		}
		$stmt->setInt('id', $id);
		$stmt->setString('dispatchmethodname', $dispatchmethodname);
		$stmt->setInt('dispatchmethodid', $dispatchmethodId);
		$stmt->setString('paymentmethodname', $paymentData[$Data['additional_data']['payment_data']['payment_method']]['paymentmethodname']);
		$stmt->setInt('paymentmethodid', $Data['additional_data']['payment_data']['payment_method']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_EDIT'), 13, $e->getMessage());
		}
		return true;
	}

	public function getProductsDataGrid ($id) {
		$sql = "SELECT 
					OP.productid as idproduct, 
					OP.pricenetto as sellprice, 
					OP.productattributesetid as variant, 
					OP.qty as quantity,
					OP.vat AS vat,
					P.trackstock
 				FROM orderproduct OP
 				LEFT JOIN product P ON P.idproduct = OP.productid
				WHERE orderid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idproduct' => $rs->getInt('idproduct'),
				'quantity' => $rs->getInt('quantity'),
				'previousquantity' => $rs->getInt('quantity'),
				'trackstock' => (int) $rs->getInt('trackstock'),
				'sellprice' => $rs->getString('sellprice'),
				'vat' => $rs->getString('vat'),
				'variant' => $rs->getInt('variant'),
				'stock' => $this->getCurrentStock($rs->getInt('idproduct'), $rs->getInt('variant'))
			);
		}
		return $Data;
	}

	public function getCurrentStock ($idproduct, $variantid) {
		if ($variantid != NULL && $variantid > 0){
			$sql = "SELECT 
						stock
	 				FROM productattributeset
					WHERE productid = :productid AND idproductattributeset = :variant";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $idproduct);
			$stmt->setInt('variant', $variantid);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				return $rs->getInt('stock');
			}
		}
		else{
			$sql = "SELECT 
						stock
	 				FROM product
					WHERE idproduct = :productid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $idproduct);
			$stmt->setInt('variant', $variantid);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				return $rs->getInt('stock');
			}
		}
		return 0;
	}

	public function addNewOrder ($Data) {
		$this->registry->db->setAutoCommit(false);
		try{
			$newOrderId = $parentOrderId = $this->addOrder($Data);
			$this->addOrderClientData($Data['address_data']['billing_data'], $newOrderId);
			$this->addOrderClientDeliveryData($Data['address_data']['shipping_data'], $newOrderId);
			$this->addOrderProduct($Data['products_data']['products'], $newOrderId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $newOrderId;
	}

	public function addOrderProduct ($array, $newOrderId) {
		$Data = Array();
		foreach ($array as $value){
			$id = $value['idproduct'];
			$sql = "SELECT
						P.sellprice, 
						V.`value` as vat, 
						PT.name as productname, 
						P.idproduct, 
						ROUND((P.sellprice + P.sellprice*(V.`value`/100)),2) as pricebrutto,
						P.trackstock
					FROM product P
					LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
					LEFT JOIN vat V ON V.idvat = vatid
					WHERE P.idproduct=:id";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$productid = $rs->getInt('idproduct');
				$Data[$productid] = Array(
					'idproduct' => $rs->getInt('idproduct'),
					'pricebrutto' => $rs->getString('pricebrutto'),
					'vat' => $rs->getString('vat'),
					'productname' => $rs->getString('productname'),
					'trackstock' => $rs->getInt('trackstock')
				);
			}
		}
		foreach ($array as $value){
			$sql = 'INSERT INTO orderproduct SET
						name = :name, 
						price = :price, 
						qty = :qty, 
						qtyprice = :qtyprice,
						orderid = :orderid, 
						productid = :productid, 
						productattributesetid = :productattributesetid,
						variant = :variant,
						vat = :vat, 
						pricenetto = :pricenetto';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $Data[$value['idproduct']]['productname']);
			$stmt->setString('price', $Data[$value['idproduct']]['pricebrutto']);
			$stmt->setString('qty', $value['quantity']);
			$stmt->setString('qtyprice', ($value['quantity'] * $value['sellprice']));
			$stmt->setInt('orderid', $newOrderId);
			$stmt->setInt('productid', $value['idproduct']);
			if ($value['variant'] > 0){
				$stmt->setInt('productattributesetid', $value['variant']);
				$stmt->setInt('variant', $value['variantcaption']);
				if ($Data[$value['idproduct']]['trackstock'] == 1){
					$this->decreaseProductAttributeStock($value['idproduct'], $value['variant'], $value['quantity']);
				}
			}
			else{
				$stmt->setNull('productattributesetid');
				$stmt->setNull('variant');
				if ($Data[$value['idproduct']]['trackstock'] == 1){
					$this->decreaseProductStock($value['idproduct'], $value['quantity']);
				}
			}
			$stmt->setFloat('vat', $Data[$value['idproduct']]['vat']);
			$stmt->setString('pricenetto', $value['sellprice']);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_TO_ORDER_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addOrderClientDeliveryData ($Data, $newOrderId) {
		$sql = 'INSERT INTO orderclientdeliverydata SET
					orderid = :orderid, 
					firstname = AES_ENCRYPT(:firstname, :encryptionKey), 
					surname = AES_ENCRYPT(:surname, :encryptionKey),
					place = AES_ENCRYPT(:place, :encryptionKey),  
					postcode = AES_ENCRYPT(:postcode, :encryptionKey),  
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					email = AES_ENCRYPT(:email, :encryptionKey),
					street = AES_ENCRYPT(:street, :encryptionKey), 
					streetno = AES_ENCRYPT(:streetno, :encryptionKey),
					placeno = AES_ENCRYPT(:placeno, :encryptionKey),
					nip = AES_ENCRYPT(:nip, :encryptionKey),
					companyname = AES_ENCRYPT(:companyname, :encryptionKey)
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $newOrderId);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('place', $Data['place']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_CLIENT_DELIVERY_DATA_ADD'), 112, $e->getMessage());
		}
	}

	public function addOrderClientData ($Data, $newOrderId) {
		$sql = 'INSERT INTO orderclientdata SET
					orderid = :orderid,
					firstname = AES_ENCRYPT(:firstname, :encryptionKey), 
					surname = AES_ENCRYPT(:surname, :encryptionKey),
					place = AES_ENCRYPT(:place, :encryptionKey),  
					postcode = AES_ENCRYPT(:postcode, :encryptionKey),  
					phone = AES_ENCRYPT(:phone, :encryptionKey),
					email = AES_ENCRYPT(:email, :encryptionKey),
					street = AES_ENCRYPT(:street, :encryptionKey), 
					streetno = AES_ENCRYPT(:streetno, :encryptionKey),
					placeno = AES_ENCRYPT(:placeno, :encryptionKey),
					nip = AES_ENCRYPT(:nip, :encryptionKey),
					companyname = AES_ENCRYPT(:companyname, :encryptionKey)
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $newOrderId);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('place', $Data['place']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_CLIENT_DATA_ADD'), 112, $e->getMessage());
		}
	}

	public function addOrder ($Data) {
		$dispatchmethodId = $Data['additional_data']['payment_data']['delivery_method'];
		
		$sql = "SELECT 
					name as dispatchmethodname, 
					iddispatchmethod
				FROM dispatchmethod D
				WHERE iddispatchmethod=:dispatchmethodId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('dispatchmethodId', $dispatchmethodId);
		$rs = $stmt->executeQuery();
		$dispatchData = Array();
		if ($rs->first()){
			$dispatchmethodname = $rs->getString('dispatchmethodname');
		}
		
		$paymentmethodId = $Data['additional_data']['payment_data']['payment_method'];
		
		$sql = "SELECT 
					name as paymentmethodname, 
					idpaymentmethod
				FROM paymentmethod 
				WHERE idpaymentmethod=:paymentmethodId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('paymentmethodId', $paymentmethodId);
		$rs = $stmt->executeQuery();
		$paymentData = Array();
		if ($rs->first()){
			$paymentmethodname = $rs->getString('paymentmethodname');
		}
		
		$sql = 'INSERT INTO `order` SET
					clientid = :clientid, 
					orderstatusid = (SELECT idorderstatus FROM orderstatus WHERE `default` = 1),
					price = :price,
					dispatchmethodprice = :dispatchmethodprice,
					globalprice = :globalprice,
					dispatchmethodid = :dispatchmethodid,
					dispatchmethodname = :dispatchmethodname,
					paymentmethodid = :paymentmethodid,
					paymentmethodname = :paymentmethodname,
					globalpricenetto = :globalpricenetto,
					addid = :addid,
					viewid = :viewid,
					pricebeforepromotion = :pricebeforepromotion,
					rulescartid = :rulescartid,
					currencyid = :currencyid, 
					currencysymbol = :currencysymbol, 
					currencyrate = :currencyrate,
					sessionid = :sessionid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('sessionid', session_id());
		$stmt->setNull('clientid');
		$stmt->setInt('currencyid', $this->registry->session->getActiveCurrencyId());
		$stmt->setString('currencysymbol', $this->registry->session->getActiveCurrencySymbol());
		$stmt->setFloat('currencyrate', $this->registry->session->getActiveCurrencyRate());
		$stmt->setFloat('price', $Data['pricebrutto']);
		$stmt->setFloat('globalprice', ($Data['pricebrutto'] + $Data['dispatchmethodprice']));
		$stmt->setFloat('dispatchmethodprice', $Data['dispatchmethodprice']);
		$stmt->setFloat('globalpricenetto', $Data['pricenetto']);
		$stmt->setNull('pricebeforepromotion');
		$stmt->setNull('rulescartid');
		
		$stmt->setString('dispatchmethodname', $dispatchmethodname);
		$stmt->setInt('dispatchmethodid', $dispatchmethodId);
		$stmt->setString('paymentmethodname', $paymentmethodname);
		$stmt->setInt('paymentmethodid', $paymentmethodId);
		
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_ADD'), 112, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function updateOrderStatus ($Data, $id) {
		$sql = 'UPDATE `order` SET orderstatusid=:orderstatusid	WHERE idorder = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('orderstatusid', $Data['status']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_EDIT'), 13, $e->getMessage());
		}
		return true;
	}

	public function getOrderDeliveryData ($idorder) {
		$sql = "SELECT
					AES_DECRYPT(firstname, :encryptionKey) AS firstname, 
					AES_DECRYPT(surname, :encryptionKey) AS surname, 
					AES_DECRYPT(street, :encryptionKey) AS street, 
					AES_DECRYPT(streetno, :encryptionKey) AS streetno,
					AES_DECRYPT(placeno, :encryptionKey) AS placeno,   
					AES_DECRYPT(postcode, :encryptionKey) AS postcode,
					AES_DECRYPT(place, :encryptionKey) AS place,
        			O.dispatchmethodname
 				FROM orderclientdeliverydata ODC
				LEFT JOIN `order`O ON ODC.orderid = O.idorder
				WHERE ODC.orderid = :idorder";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'placeno' => $rs->getString('placeno'),
				'postcode' => $rs->getString('postcode'),
				'place' => $rs->getString('place'),
				'placename' => $rs->getString('place'),
				'dispatchmethodname' => $rs->getString('dispatchmethodname')
			);
		}
		return $Data;
	}

	public function getOrderProductListByClientForDatagrid ($id) {
		$Data = $this->getOrderProductListByClient($id);
		$Html = '';
		foreach ($Data as $key => $product){
			if (count($product['attributes']) > 0){
				$Html .= $product['qty'] . ' x <strong>' . $product['productname'] . '</strong><br />';
				$Html .= $product['attributes']['name'] . '<br />';
			}
			else{
				$Html .= $product['qty'] . ' x ' . $product['productname'] . '<br />';
			}
		}
		return $Html;
	}

	public function getOrderProductListByClient ($idorder) {
		$sql = 'SELECT 
					O.idorder,
					OP.name as productname,
					OP.qty,
					OP.qtyprice,
					OP.price,
					OP.pricenetto,
					OP.vat,
					OP.productid,
					OP.idorderproduct,
					OP.productattributesetid AS variant
				FROM `order` O 
				LEFT JOIN orderclientdata OCD ON OCD.orderid=O.idorder
				LEFT JOIN orderproduct OP ON OP.orderid=O.idorder
				WHERE idorder=:idorder
				ORDER BY productname';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('idorder', $idorder);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'idproduct' => $rs->getInt('productid'),
				'qty' => $rs->getInt('qty'),
				'productid' => $rs->getInt('productid'),
				'qtyprice' => $rs->getFloat('qtyprice'),
				'price' => $rs->getFloat('price'),
				'pricenetto' => $rs->getFloat('pricenetto'),
				'vat' => $rs->getFloat('vat'),
				'productname' => $rs->getString('productname'),
				'attributes' => $this->getOrderProductAttributes($rs->getInt('productid'), $rs->getInt('variant'))
			);
		}
		return $Data;
	}

	public function getOrderNotesType () {
		$Data = Array(
			0 => 'Notatka do zamówienia',
			2 => 'Notatka o kliencie'
		);
		return $Data;
	}

	public function addClientNotes ($Data, $clientid) {
		$sql = 'INSERT INTO clientnotes (content, clientid, addid)
					VALUES (:content, :clientid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('content', $Data['contents']);
		$stmt->setInt('clientid', $clientid);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NOTES_ADD'), 112, $e->getMessage());
		}
	}

	public function addProductNotes ($Data, $id) {
		$sql = 'INSERT INTO orderproductnotes (content, productid, addid, orderid)
					VALUES (:content, :productid, :addid, :orderid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('content', $Data['contents']);
		$stmt->setInt('productid', $Data['product']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('orderid', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_NOTES_ADD'), 112, $e->getMessage());
		}
	}

	public function getOrderProductNotes ($orderid) {
		$sql = "SELECT OPN.content, UD.firstname, UD.surname, OPN.productid,  OPN.orderid 
					FROM orderproductnotes OPN
					LEFT JOIN userdata UD ON UD.userid = OPN.addid
					WHERE OPN.orderid =:orderid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $orderid);
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'content' => $rs->getString('content'),
					'firstname' => $rs->getString('firstname'),
					'surname' => $rs->getString('surname'),
					'productid' => $rs->getInt('productid'),
					'orderid' => $rs->getInt('orderid')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ORDER_PRODUCT_NOTES_NO_EXIST'), 11, $e->getMessage());
		}
		return $Data;
	}

	public function addOrderNotes ($Data, $orderid) {
		$sql = 'INSERT INTO ordernotes (content, orderid, addid)
					VALUES (:content, :orderid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('content', $Data['contents']);
		$stmt->setInt('orderid', $orderid);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_NOTES_ADD'), 112, $e->getMessage());
		}
	}

	public function getOrderNotes ($orderid) {
		$sql = "SELECT N.content, N.adddate, UD.firstname, UD.surname, N.orderid 
					FROM ordernotes N
					LEFT JOIN userdata UD ON UD.userid = N.addid
					WHERE N.orderid =:orderid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderid', $orderid);
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'content' => $rs->getString('content'),
					'firstname' => $rs->getString('firstname'),
					'surname' => $rs->getString('surname'),
					'adddate' => $rs->getString('adddate'),
					'orderid' => $rs->getInt('orderid')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ORDER_NOTES_NO_EXIST'), 11, $e->getMessage());
		}
		return $Data;
	}

	public function getclientOrderHistory ($clientid) {
		$sql = "SELECT idorder, `adddate`, globalprice FROM `order` WHERE clientid=:clientid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientid);
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'idorder' => $rs->getInt('idorder'),
					'adddate' => $rs->getString('adddate'),
					'globalprice' => $rs->getString('globalprice')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_NO_EXIST'), 11, $e->getMessage());
		}
		return $Data;
	}

	public function getClientNotes ($clientid) {
		$sql = "SELECT 
					CN.content, 
					CN.addid, 
					CN.adddate, 
					UD.firstname, 
					UD.surname,
					AES_DECRYPT(CD.firstname, :encryptionKey) clientname, 
					AES_DECRYPT(CD.surname, :encryptionKey) clientsurname
				FROM clientnotes CN
				LEFT JOIN userdata UD ON UD.userid = CN.addid
				LEFT JOIN clientdata CD ON CD.clientid = CN.clientid
				WHERE CN.clientid =:clientid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $clientid);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'content' => $rs->getString('content'),
					'firstname' => $rs->getString('firstname'),
					'surname' => $rs->getString('surname'),
					'adddate' => $rs->getString('adddate'),
					'clientname' => $rs->getString('clientname'),
					'clientsurname' => $rs->getString('clientsurname')
				);
			}
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_CLIENT_NOTES_NO_EXIST'), 11, $e->getMessage());
		}
		return $Data;
	}

	public function doAJAXChangeOrderStatus ($id, $datagrid, $status) {
		if (! is_array($id)){
			$id = Array(
				$id
			);
		}
		
		$sql = "UPDATE `order` SET orderstatusid = :status 
				WHERE idorder IN (:ids)";
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($status > 0){
			$stmt->setInt('status', $status);
		}
		else{
			$stmt->setNull('status');
		}
		$stmt->setINInt('ids', $id);
		
		$rs = $stmt->executeQuery();
		$Data['inform'] = 0;
		$Data['status'] = $status;
		$Data['comment'] = App::getModel('orderstatus')->getDefaultComment($status);
		foreach ($id as $order){
			$this->addOrderHistory($Data, $order);
		}
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function getPrintableOrderById ($id, $tpl) {
		$pdf = new pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Gekosale');
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setHeaderFont(Array(
			PDF_FONT_NAME_MAIN,
			'',
			PDF_FONT_SIZE_MAIN
		));
		$pdf->setFooterFont(Array(
			PDF_FONT_NAME_DATA,
			'',
			PDF_FONT_SIZE_DATA
		));
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(1);
		$pdf->SetFont('dejavusans', '', 10);
		
		$order = $this->getOrderById($id);
		$lp = 1;
		$couponDiscountBrutto = (isset($order['coupon']['coupondiscount']) && $order['coupon']['coupondiscount'] > 0) ? $order['coupon']['coupondiscount'] : 0;
		foreach ($order['products'] as $key => $val){
			if ($couponDiscountBrutto > 0){
				if (($order['products'][$key]['subtotal'] - $couponDiscountBrutto) > 0){
					$order['products'][$key]['subtotal'] = sprintf('%01.2f', $order['products'][$key]['subtotal'] - $couponDiscountBrutto);
					$order['products'][$key]['net_price'] = sprintf('%01.2f', $order['products'][$key]['net_price'] - (($couponDiscountBrutto / (1 + ($order['products'][$key]['vat'] / 100))) / $order['products'][$key]['quantity']));
					$order['products'][$key]['net_subtotal'] = sprintf('%01.2f', $order['products'][$key]['net_price'] * $order['products'][$key]['quantity']);
					$order['products'][$key]['vat_value'] = sprintf('%01.2f', $order['products'][$key]['subtotal'] - $order['products'][$key]['net_subtotal']);
				}
				else{
					$order['products'][$key]['net_price'] = sprintf('%01.2f', $order['products'][$key]['net_price']);
					$order['products'][$key]['subtotal'] = sprintf('%01.2f', $order['products'][$key]['subtotal']);
					$order['products'][$key]['net_subtotal'] = sprintf('%01.2f', $order['products'][$key]['net_subtotal']);
				}
			}
			$order['products'][$key]['net_subtotal'] = sprintf('%01.2f', $order['products'][$key]['net_subtotal']);
			$order['products'][$key]['lp'] = $lp;
			
			$lp ++;
		}
		
		if ($order['pricebeforepromotion'] > 0 && ($order['pricebeforepromotion'] < $order['total'])){
			$rulesCostGross = $order['total'] - $order['pricebeforepromotion'];
			$rulesCostNet = ($order['total'] - $order['pricebeforepromotion']) / (1 + ($order['delivery_method']['deliverervat'] / 100));
			$rulesVat = $rulesCostGross - $rulesCostNet;
			$order['products'][] = Array(
				'name' => $order['delivery_method']['deliverername'],
				'net_price' => sprintf('%01.2f', $order['delivery_method']['delivererpricenetto'] + $rulesCostNet),
				'quantity' => 1,
				'net_subtotal' => sprintf('%01.2f', $order['delivery_method']['delivererpricenetto'] + $rulesCostNet),
				'vat' => sprintf('%01.2f', $order['delivery_method']['deliverervat']),
				'vat_value' => sprintf('%01.2f', $order['delivery_method']['deliverervatvalue'] + $rulesVat),
				'subtotal' => sprintf('%01.2f', $order['delivery_method']['delivererprice'] + $rulesCostGross),
				'lp' => $lp
			);
		}
		else{
			$order['products'][] = Array(
				'name' => $order['delivery_method']['deliverername'],
				'net_price' => sprintf('%01.2f', $order['delivery_method']['delivererpricenetto']),
				'quantity' => 1,
				'net_subtotal' => sprintf('%01.2f', $order['delivery_method']['delivererpricenetto']),
				'vat' => sprintf('%01.2f', $order['delivery_method']['deliverervat']),
				'vat_value' => sprintf('%01.2f', $order['delivery_method']['deliverervatvalue']),
				'subtotal' => sprintf('%01.2f', $order['delivery_method']['delivererprice']),
				'lp' => $lp
			);
		}
		$rulesCostGross = 0;
		$rulesCostNet = 0;
		$rulesVat = 0;
		if ($order['pricebeforepromotion'] > 0 && ($order['pricebeforepromotion'] < $order['total'])){
			$rulesCostGross = $order['total'] - $order['pricebeforepromotion'];
			$rulesCostNet = ($order['total'] - $order['pricebeforepromotion']) / (1 + ($order['delivery_method']['deliverervat'] / 100));
			$rulesVat = $rulesCostGross - $rulesCostNet;
		}
		
		if (isset($order['coupon']['couponfreedelivery']) && $order['coupon']['couponfreedelivery'] == 1){
			$order['delivery_method']['delivererpricenetto'] = 0;
			$order['delivery_method']['delivererprice'] = 0;
			$order['delivery_method']['deliverervatvalue'] = 0;
		}
		$summary = Array();
		foreach ($order['products'] as $key => $val){
			$summary[$val['vat']]['vat'] = $val['vat'];
			$summary[$val['vat']]['netto'] += $val['net_subtotal'];
			$summary[$val['vat']]['brutto'] += $val['subtotal'];
			$summary[$val['vat']]['vatvalue'] += $val['vat_value'];
		}
		
		$Total = Array(
			'netto' => 0,
			'vatvalue' => 0
		);
		foreach ($summary as $key => $group){
			$Total['netto'] += $group['netto'];
			$Total['vatvalue'] += $group['vatvalue'];
		}
		$Total['brutto'] = sprintf('%01.2f', $Total['netto'] + $Total['vatvalue']);
		$companyaddress = App::getModel('invoice')->getMainCompanyAddress($order['viewid']);
		$this->registry->template->assign('order', $order);
		$this->registry->template->assign('companyaddress', $companyaddress);
		$this->registry->template->assign('summary', $summary);
		$this->registry->template->assign('total', $Total);
		$html = $this->registry->template->fetch($tpl);
		$pdf->AddPage();
		$pdf->writeHTML($html, true, 0, true, 0);
		ob_clean();
		$pdf->Output(Core::clearUTF($this->registry->core->getMessage('TXT_ORDER') . '_' . $order['order_id']), 'D');
	}
}