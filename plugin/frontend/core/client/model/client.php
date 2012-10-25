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
 * $Id: client.php 655 2012-04-24 08:51:44Z gekosale $
 */

class clientModel extends Model
{

	public function countriesList ()
	{
		$sql = 'SELECT C.idcountry as countryid, C.name
				FROM country C';
		$rs = $this->registry->db->executeQuery($sql);
		while ($rs->next()){
			$Data[$rs->getInt('countryid')] = $rs->getString('name');
		}
		return $Data;
	}

	public function getClient ()
	{
		if ($this->registry->session->getActiveClientid() != NULL){
			$sql = "SELECT 	 
						AES_DECRYPT(CD.surname, :encryptionkey) AS surname,
						AES_DECRYPT(CD.firstname, :encryptionkey) AS firstname,
						AES_DECRYPT(CD.phone, :encryptionkey) AS phone,
						CA.idclientaddress,
						AES_DECRYPT(CA.street, :encryptionkey) AS street,
						AES_DECRYPT(CA.streetno, :encryptionkey) AS streetno,
						AES_DECRYPT(CA.postcode, :encryptionkey) AS postcode,
						AES_DECRYPT(CA.placename, :encryptionkey) AS placename,
						AES_DECRYPT(CA.placeno, :encryptionkey) AS placeno,
						AES_DECRYPT(CA.nip, :encryptionkey) AS nip,
						AES_DECRYPT(CA.companyname, :encryptionkey) AS companyname,
						CA.countryid,
						AES_DECRYPT(CD.email, :encryptionkey) AS email
					FROM clientdata CD
					LEFT JOIN client C ON C.idclient=CD.clientid
					LEFT JOIN clientaddress CA ON CA.clientid=CD.clientid
					WHERE C.idclient= :clientid AND C.viewid= :viewid";
			$Data = Array();
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
			$rs = $stmt->executeQuery();
			try{
				if ($rs->first()){
					$Data = Array(
						'firstname' => $rs->getString('firstname'),
						'surname' => $rs->getString('surname'),
						'idclientaddress' => $rs->getString('idclientaddress'),
						'phone' => $rs->getString('phone'),
						'street' => $rs->getString('street'),
						'streetno' => $rs->getString('streetno'),
						'postcode' => $rs->getString('postcode'),
						'placename' => $rs->getString('placename'),
						'placeno' => $rs->getString('placeno'),
						'nip' => $rs->getString('nip'),
						'companyname' => $rs->getString('companyname'),
						'email' => $rs->getString('email'),
						'countryid' => $rs->getInt('countryid')
					);
					return $Data;
				}
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_NO_EXIST'));
			}
		}
	}

	public function getClientAddress ($main)
	{
		$sql = "SELECT 	 
					idclientaddress,
					AES_DECRYPT(firstname, :encryptionkey) AS firstname,
					AES_DECRYPT(surname, :encryptionkey) AS surname,
					AES_DECRYPT(companyname, :encryptionkey) AS companyname,
					AES_DECRYPT(nip, :encryptionkey) AS nip,
					AES_DECRYPT(street, :encryptionkey) AS street,
					AES_DECRYPT(streetno, :encryptionkey) AS streetno,
					AES_DECRYPT(postcode, :encryptionkey) AS postcode,
					AES_DECRYPT(placename, :encryptionkey) AS placename,
					AES_DECRYPT(placeno, :encryptionkey) AS placeno,
					countryid
				FROM clientaddress
				WHERE clientid=:clientid AND main = :main";
		$Data = Array(
			'idclientaddress' => 0,
			'firstname' => '',
			'surname' => '',
			'companyname' => '',
			'nip' => '',
			'street' => '',
			'streetno' => '',
			'placeno' => '',
			'placename' => '',
			'postcode' => '',
			'countryid' => $this->layer['countryid']
		);
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('main', $main);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		try{
			if ($rs->first()){
				$Data = Array(
					'idclientaddress' => $rs->getInt('idclientaddress'),
					'firstname' => $rs->getString('firstname'),
					'surname' => $rs->getString('surname'),
					'companyname' => $rs->getString('companyname'),
					'nip' => $rs->getString('nip'),
					'street' => $rs->getString('street'),
					'streetno' => $rs->getString('streetno'),
					'placeno' => $rs->getString('placeno'),
					'placename' => $rs->getString('placename'),
					'postcode' => $rs->getString('postcode'),
					'countryid' => $rs->getInt('countryid')
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_NO_EXIST'));
		}
		return $Data;
	}

	public function updateClientAddress ($Data, $main)
	{
		$sql = 'INSERT INTO clientaddress SET 
					clientid	= :clientid,
					main		= :main,
					firstname 	= AES_ENCRYPT(:firstname, :encryptionKey), 
					surname   	= AES_ENCRYPT(:surname, :encryptionKey), 
					companyname	= AES_ENCRYPT(:companyname, :encryptionKey), 
					street		= AES_ENCRYPT(:street, :encryptionKey), 
					streetno	= AES_ENCRYPT(:streetno, :encryptionKey),
					placeno		= AES_ENCRYPT(:placeno, :encryptionKey),
					postcode	= AES_ENCRYPT(:postcode, :encryptionKey),
					nip			= AES_ENCRYPT(:nip, :encryptionKey),
					placename	= AES_ENCRYPT(:placename, :encryptionKey),
					countryid	= :countryid
				ON DUPLICATE KEY UPDATE 
					firstname 	= AES_ENCRYPT(:firstname, :encryptionKey), 
					surname   	= AES_ENCRYPT(:surname, :encryptionKey), 
					companyname	= AES_ENCRYPT(:companyname, :encryptionKey), 
					street		= AES_ENCRYPT(:street, :encryptionKey), 
					streetno	= AES_ENCRYPT(:streetno, :encryptionKey),
					placeno		= AES_ENCRYPT(:placeno, :encryptionKey),
					postcode	= AES_ENCRYPT(:postcode, :encryptionKey),
					nip			= AES_ENCRYPT(:nip, :encryptionKey),
					placename	= AES_ENCRYPT(:placename, :encryptionKey),
					countryid	= :countryid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('main', $main);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('placename', $Data['placename']);
		$stmt->setString('countryid', isset($Data['country']) ? $Data['country'] : $Data['countryid']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return true;
	}

	protected function addClientNewAddress ($Data)
	{
		$sql = 'INSERT INTO clientaddress(
						street, 
						streetno, 
						placeno, 
						postcode, 
						companyname, 
						firstname, 
						surname, 
						placename, 
						clientid)
					VALUES (
						:street, 
						:streetno, 
						:placeno, 
						:postcode, 
						:companyname, 
						:firstname, 
						:surname,
						:placename, 
						:clientid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('placename', $Data['placename']);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENTADRESS_ADD'));
		}
	}

	public function getClientPass ()
	{
		$sql = "SELECT password 
					FROM client
					WHERE idclient= :idclient 
						AND viewid= :viewid";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idclient', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'password' => $rs->getString('password')
			);
		}
		else{
			throw new FrontendException($this->registry->core->getMessage('ERR_PASSWORD_NOT_EXIST'));
		}
		return $Data;
	}

	public function addNewClient ($Data, $password = null)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newClientId = $this->addClient($Data);
			$this->addClientData($Data, $newClientId);
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_NEWCLIENT_ADD'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $newClientId;
	}

	public function checkClientLink ($link)
	{
		$sql = 'SELECT
					login,
					password,
					idclient
				FROM client
				WHERE 
					activelink = :activelink
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('activelink', $link);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'email' => $rs->getString('login'),
				'password' => $rs->getString('password')
			);
			$this->updateClientDisable($rs->getInt('idclient'), 0);
		}
		return $Data;
	}

	public function updateClientDisable ($id, $disable, $activelink = NULL)
	{
		$sql = 'UPDATE client SET
					disable = :disable,
					activelink = :activelink
				WHERE idclient = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		if ($disable == 1){
			$stmt->setInt('disable', $disable);
			$stmt->setString('activelink', $activelink);
		}
		else{
			$stmt->setInt('disable', 0);
			$stmt->setNull('activelink');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_ADD'), 4, $e->getMessage());
		}
		return $activelink;
	}

	protected function addClient ($Data, $disable = 0)
	{
		$sql = 'INSERT INTO client SET
					login = :login, 
					password = :password, 
					disable = :disable, 
					viewid = :viewid,
					facebookid = :facebookid,
					activelink = :activelink';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', sha1($Data['email']));
		$stmt->setString('password', sha1($Data['password']));
		$stmt->setInt('disable', $disable);
		$stmt->setNull('activelink');
		$stmt->setInt('viewid', Helper::getViewId());
		if (isset($Data['facebookid']) && $Data['facebookid'] != ''){
			$stmt->setString('facebookid', $Data['facebookid']);
		}
		else{
			$stmt->setNull('facebookid');
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getDefaultClientGroupId ()
	{
		$sql = 'SELECT clientgroupid 
					FROM assigntogroup 
					WHERE viewid = :viewid 
					ORDER BY `from` ASC LIMIT 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			return $rs->getInt('clientgroupid');
		}
	}

	protected function addClientData ($Data, $ClientId)
	{
		$sql = 'INSERT INTO clientdata(
						firstname, 
						surname, 
						email, 
						phone,
						newsletter,
						clientid,
						clientgroupid
					)
					VALUES (
						AES_ENCRYPT(:firstname, :encryptionKey), 
						AES_ENCRYPT(:surname, :encryptionKey),
						AES_ENCRYPT(:email, :encryptionKey),  
						AES_ENCRYPT(:phone, :encryptionKey),
						:newsletter,
						:clientid,
						:clientgroupid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('email', $Data['email']);
		$stmt->setString('phone', $Data['phone']);
		$stmt->setInt('clientid', $ClientId);
		if (isset($Data['newsletter']) && $Data['newsletter'] == 1){
			$stmt->setInt('newsletter', 1);
		}
		else{
			$stmt->setInt('newsletter', 0);
		}
		$groupid = $this->getDefaultClientGroupId();
		if ($groupid > 0){
			$stmt->setInt('clientgroupid', $groupid);
		}
		else{
			$stmt->setNull('clientgroupid');
		}
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENTDATA_ADD'), 4, $e->getMessage());
		}
		return true;
	}

	public function getOrderByClient ($idorder)
	{
		$sql = 'SELECT 
					OST.name as orderstatusname, 
					O.idorder, 
					O.adddate as orderdate, 
					O.dispatchmethodname, 
					O.paymentmethodname,
					O.dispatchmethodprice, 
					O.globalprice, 
					O.price, 
					O.globalpricenetto,
					O.currencysymbol
				FROM `order` O 
				LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OST.orderstatusid=OS.idorderstatus
				WHERE O.clientid= :clientid AND idorder= :idorder';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('idorder', $idorder);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			$invoicedata = explode('-', $rs->getString('orderdate'));
			$invoicedata[2] = substr($invoicedata[2], 0, 2);
			$dateinvoice = $invoicedata[0] . $invoicedata[1] . $invoicedata[2];
			
			$Data = Array(
				'idorder' => $rs->getInt('idorder'),
				'globalprice' => $rs->getFloat('globalprice'),
				'price' => $rs->getFloat('price'),
				'globalpricenetto' => $rs->getFloat('globalpricenetto'),
				'orderstatusname' => $rs->getString('orderstatusname'),
				'orderdate' => $rs->getString('orderdate'),
				'currencysymbol' => $rs->getString('currencysymbol'),
				'dispatchmethodname' => $rs->getString('dispatchmethodname'),
				'paymentmethodname' => $rs->getString('paymentmethodname'),
				'dispatchmethodprice' => $rs->getFloat('dispatchmethodprice'),
				'dateinvoice' => $dateinvoice,
				'billingaddress' => App::getModel('order')->getOrderBillingData($rs->getInt('idorder')),
				'shippingaddress' => App::getModel('order')->getOrderShippingData($rs->getInt('idorder')),
				'invoices' => $this->getOrderInvoices($rs->getInt('idorder')),
				'files' => $this->getOrderFiles($rs->getInt('idorder'))
			);
		}
		return $Data;
	}

	public function getOrderFiles ($id)
	{
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

	public function getOrderInvoices ($id)
	{
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

	public function getOrderForPrevPrintedInvoice ($idorder)
	{
		$sql = "SELECT 
						OST.name as orderstatusname,
						O.idorder, O.adddate as orderdate, O.dispatchmethodname, O.paymentmethodname, O.dispatchmethodprice,
						O.globalprice, O.price, O.globalpricenetto,
						AES_DECRYPT(firstname, :encryptionKey) as firstname, 
						AES_DECRYPT(surname, :encryptionKey) as surname, 
						AES_DECRYPT(email, :encryptionKey) as email,  
						AES_DECRYPT(street, :encryptionKey) as street, 
						AES_DECRYPT(streetno, :encryptionKey) as streetno,
						AES_DECRYPT(placeno, :encryptionKey) as placeno,  
						AES_DECRYPT(phone, :encryptionKey) as phone,
						AES_DECRYPT(place, :encryptionKey) as placename,
						AES_DECRYPT(postcode, :encryptionKey) as postcode,
						I.idinvoice, I.noinvoice, I.adddate as printeddate, I.saledate
					FROM `order` O 
					LEFT JOIN orderclientdata OCD ON OCD.orderid=O.idorder
					LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
					LEFT JOIN orderstatustranslation OST ON OST.orderstatusid=OS.idorderstatus
					LEFT JOIN invoice I ON I.orderid=O.idorder
					WHERE O.clientid= :clientid and idorder=:idorder";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('idorder', $idorder);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'idorder' => $rs->getInt('idorder'),
				'globalprice' => $rs->getFloat('globalprice'),
				'globalpricenetto' => $rs->getFloat('globalpricenetto'),
				'price' => $rs->getFloat('price'),
				'email' => $rs->getString('email'),
				'orderstatusname' => $rs->getString('orderstatusname'),
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
				'dispatchmethodprice' => $rs->getFloat('dispatchmethodprice'),
				'idinvoice' => $rs->getInt('idinvoice'),
				'noinvoice' => $rs->getString('noinvoice'),
				'printeddate' => $rs->getString('printeddate'),
				'saledate' => $rs->getString('saledate')
			);
		}
		return $Data;
	}

	public function getOrderListByClient ()
	{
		$sql = 'SELECT O.idorder, O.adddate as orderdate
					FROM `order` O 
						LEFT JOIN orderclientdata OCD ON OCD.orderid= O.idorder
					WHERE O.clientid= :clientid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'idorder' => $rs->getInt('idorder'),
				'orderdate' => $rs->getString('orderdate')
			);
		}
		return $Data;
	}

	public function getOrderProductListByClient ($idorder)
	{
		$sql = 'SELECT 
					O.idorder,
					OP.name as productname, 
					OP.qty, 
					OP.productid, 
					OP.qtyprice, 
					OP.price, 
					OP.pricenetto,
					OP.vat, 
					OP.productid, 
					OP.idorderproduct,
					PT.seo
				FROM `order` O 
				LEFT JOIN orderclientdata OCD ON OCD.orderid=O.idorder
				LEFT JOIN orderproduct OP ON OP.orderid=O.idorder
				LEFT JOIN producttranslation PT ON OP.productid = PT.productid AND PT.languageid = :languageid
				WHERE O.clientid= :clientid and idorder= :idorder 
				ORDER BY productname';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('idorder', $idorder);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'idproduct' => $this->isProductAvailable($rs->getInt('productid')),
				'seo' => $rs->getString('seo'),
				'qty' => $rs->getInt('qty'),
				'productid' => $rs->getInt('productid'),
				'qtyprice' => $rs->getFloat('qtyprice'),
				'price' => $rs->getFloat('price'),
				'pricenetto' => $rs->getFloat('pricenetto'),
				'vat' => $rs->getFloat('vat'),
				'productname' => $rs->getString('productname'),
				'idorderproduct' => $rs->getString('idorderproduct'),
				'attributes' => $this->getProductAttributes($rs->getInt('idorderproduct'))
			);
		}
		return $Data;
	}

	public function isProductAvailable ($productid)
	{
		$sql = 'SELECT P.idproduct
					FROM product P 
					WHERE P.idproduct= :productid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$available = $rs->getInt('idproduct');
		}
		else{
			$available = 0;
		}
		return $available;
	}

	public function getProductAttributes ($productid)
	{
		$sql = 'SELECT OP.idorderproduct as attrId, OPA.name as attributename
					FROM orderproduct OP 
					LEFT JOIN orderproductattribute OPA ON OPA.orderproductid=OP.idorderproduct
					WHERE orderproductid= :productid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('productid', $productid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'attributename' => $rs->getString('attributename')
			);
		}
		return $Data;
	}

	public function updateClientPass ($password)
	{
		if (isset($password) && ! empty($password)){
			$sql = 'UPDATE client SET password = :password WHERE idclient = :idclient';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('password', sha1($password));
			$stmt->setInt('idclient', $this->registry->session->getActiveClientid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_PASSWORD_CLIENT_UPDATE'), 18, $e->getMessage());
			}
		}
	}

	public function updateClientLogin ($login)
	{
		if (isset($login) && ! empty($login)){
			$sql = 'UPDATE client SET login = :login WHERE idclient = :idclient';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('login', sha1($login));
			$stmt->setInt('idclient', $this->registry->session->getActiveClientid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_LOGIN_CLIENT_UPDATE'), 18, $e->getMessage());
			}
		}
	}

	public function updateClientEmail ($Data)
	{
		$sql = 'UPDATE clientdata SET 
					email=AES_ENCRYPT(:email, :encryptionKey)
					WHERE clientid=:clientid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setString('email', $Data['email']);
		try{
			$stmt->executeUpdate();
			//update session's variable- clientEmail
			$this->registry->session->setActiveClientEmail($Data['email']);
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return true;
	}

	public function sendAJAXAlertAfterChangingMail ()
	{
		$objResponseAlert = new xajaxResponse();
		try{
			$objResponseAlert->script('alert("' . $this->registry->core->getMessage('TXT_LOGGOUT_CHANGED_EMAIL') . '")');
			$this->registry->session->killSession();
			$objResponseAlert->redirect('mainside');
		}
		catch (FrontendException $fe){
			new FrontendException('Error while asking client if he want delete address- clientEncrypted model');
		}
		return $objResponseAlert;
	}

	public function checkClientNewMail ($Data)
	{
		$sql = "SELECT 
					idclientdata 
				FROM clientdata 
				LEFT JOIN client C ON C.idclient = clientid
				WHERE AES_DECRYPT(email, :encryptionKey) = :newmail AND C.viewid=:viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('newmail', $Data['email']);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$ismail = 0;
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$result = $rs->getInt('idclientdata');
			if ($result > 0)
				$ismail = 1;
		}
		return $ismail;
	}

	public function updateClient ($Data)
	{
		$sql = 'UPDATE clientaddress 
					SET 
						firstname=AES_ENCRYPT(:firstname, :encryptionKey), 
						surname=AES_ENCRYPT(:surname, :encryptionKey), 
						companyname=AES_ENCRYPT(:companyname, :encryptionKey), 
						street=AES_ENCRYPT(:street, :encryptionKey), 
						streetno=AES_ENCRYPT(:streetno, :encryptionKey),
						placeno=AES_ENCRYPT(:placeno, :encryptionKey),
						postcode=AES_ENCRYPT(:postcode, :encryptionKey),
						nip=AES_ENCRYPT(:nip, :encryptionKey),
						placename=AES_ENCRYPT(:placename, :encryptionKey)
					WHERE clientid= :clientid AND idclientaddress= :idclientaddress';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('idclientaddress', $Data['idclientaddress']);
		$stmt->setString('firstname', $Data['firstname']);
		$stmt->setString('surname', $Data['surname']);
		$stmt->setString('companyname', $Data['companyname']);
		$stmt->setString('street', $Data['street']);
		$stmt->setString('streetno', $Data['streetno']);
		$stmt->setString('postcode', $Data['postcode']);
		$stmt->setString('placeno', $Data['placeno']);
		$stmt->setString('nip', $Data['nip']);
		$stmt->setString('placename', $Data['placename']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return true;
	}

	public function bindClientAccount ($facebookData)
	{
		
		$sql = 'SELECT idclient,disable FROM client WHERE login = :login AND viewid=:viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', sha1($facebookData['registration']['email']));
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$id = $rs->getInt('idclient');
			$sql = 'UPDATE client SET
						facebookid = :facebookid
					WHERE idclient = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$stmt->setString('facebookid', $facebookData['user_id']);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_ADD'), 4, $e->getMessage());
			}
			return $id;
		}
		return 0;
	}

	public function saveClientData ()
	{
		if ($this->registry->session->getActiveClientid() == 0){
			return false;
		}
		$sql = 'SELECT 
						AES_DECRYPT(email, :encryptionkey) AS email, 
						AES_DECRYPT(firstname, :encryptionkey) AS firstname,  
						AES_DECRYPT(surname, :encryptionkey) AS surname,
						clientgroupid
					FROM clientdata
						LEFT JOIN client C ON C.idclient= :clientid
					WHERE clientid= :clientid AND C.viewid= :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->registry->session->setActiveClientFirstname($rs->getString('firstname'));
			$this->registry->session->setActiveClientSurname($rs->getString('surname'));
			$this->registry->session->setActiveClientEmail($rs->getString('email'));
			$this->registry->session->setActiveClientGroupid($rs->getInt('clientgroupid'));
		}
		return true;
	}

	public function getClientTags ()
	{
		$sql = "SELECT T.name, T.textcount, T.idtags, clientid, T.viewid 
					FROM tags T
					LEFT JOIN producttags PT ON PT.tagsid = T.idtags
					WHERE clientid=:clientid AND T.viewid = :viewid
					GROUP BY name";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idtags' => $rs->getInt('idtags'),
				'name' => $rs->getString('name'),
				'textcount' => $rs->getInt('textcount'),
				'viewid' => $rs->getInt('viewid')
			);
		}
		return $Data;
	}
}
?>