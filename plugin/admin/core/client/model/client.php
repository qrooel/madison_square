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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: client.php 687 2012-09-01 12:02:47Z gekosale $ 
 */

class ClientModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('clientdata', Array(
			'idclient' => Array(
				'source' => 'C.idclient'
			),
			'disable' => Array(
				'source' => 'disable'
			),
			'clientorder' => Array(
				'source' => 'SUM(O.globalprice)'
			),
			'firstname' => Array(
				'source' => 'CD.firstname',
				'prepareForAutosuggest' => true,
				'encrypted' => true
			),
			'surname' => Array(
				'source' => 'CD.surname',
				'prepareForAutosuggest' => true,
				'encrypted' => true
			),
			'email' => Array(
				'source' => 'CD.email',
				'encrypted' => true
			),
			'groupname' => Array(
				'source' => 'CGT.name',
				'prepareForSelect' => true
			),
			'phone' => Array(
				'source' => 'CD.phone',
				'encrypted' => true
			),
			'adddate' => Array(
				'source' => 'CD.adddate'
			),
			'editdate' => Array(
				'source' => 'CD.editdate'
			),
			'view' => Array(
				'source' => 'V.name',
				'prepareForSelect' => true
			)
		));
		$datagrid->setFrom('
				client C
				LEFT JOIN clientdata CD ON CD.clientid = C.idclient
				LEFT JOIN clientgrouptranslation CGT ON CGT.clientgroupid = CD.clientgroupid AND CGT.languageid=:languageid
				LEFT JOIN orderclientdata OCD ON OCD.clientid = CD.clientid
				LEFT JOIN `order` O ON O.idorder = OCD.orderid
				LEFT JOIN view V ON C.viewid = V.idview
			');
		$datagrid->setGroupBy('C.idclient');
		
		$datagrid->setAdditionalWhere('
			C.viewid IN (:viewids)
		');
	}

	public function getFirstnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('firstname', $request, $processFunction);
	}

	public function getSurnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('surname', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getClientForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXEnableClient ($datagridId, $id)
	{
		try{
			$this->enableClient($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableClient ($datagridId, $id)
	{
		try{
			$this->disableClient($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableClient ($id)
	{
		$sql = 'UPDATE client SET disable = 1 WHERE idclient = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableClient ($id)
	{
		$sql = 'UPDATE client SET disable = 0 WHERE idclient = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXDeleteClient ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteClient'
		), App::getRegistry()->router->getCurrentController());
	}

	public function deleteClient ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idclient' => $id
			), $this->getName(), 'deleteClient');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function clientGroup ($id)
	{
		$sql = 'SELECT clientgroupid
					FROM clientdata
					WHERE clientid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function clientGroupIds ($id)
	{
		$Data = $this->clientGroup($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['clientgroupid'];
		}
		return $tmp;
	}

	public function getClientView ($id)
	{
		$sql = "SELECT 	
					AES_DECRYPT(CD.phone, :encryptionkey) AS phone, 
					AES_DECRYPT(CD.description, :encryptionkey) AS description, 
					AES_DECRYPT(CD.email, :encryptionkey) AS email, 
					AES_DECRYPT(CD.firstname, :encryptionkey) AS firstname, 
					CD.clientid AS id, 
					AES_DECRYPT(CD.surname, :encryptionkey) AS surname,			
					CD.clientgroupid,
					C.disable,
					C.viewid,
					CD.newsletter
				FROM clientdata CD
				LEFT JOIN clientgrouptranslation CGT ON CGT.clientgroupid = CD.clientgroupid AND CGT.languageid=:languageid
				LEFT JOIN client C ON C.idclient = CD.clientid 
				WHERE CD.clientid=:id";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'email' => $rs->getString('email'),
				'phone' => $rs->getString('phone'),
				'description' => $rs->getString('description'),
				'clientgroupid' => $rs->getInt('clientgroupid'),
				'disable' => $rs->getInt('disable'),
				'viewid' => $rs->getInt('viewid'),
				'newsletter' => $rs->getInt('newsletter'),
				'billing_address' => $this->getClientAddress($id, 1),
				'delivery_address' => $this->getClientAddress($id, 0)
			);
			return $Data;
		}
		throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NO_EXIST'));
	}

	public function addNewClient ($Data, $password)
	{
		$this->registry->db->setAutoCommit(false);
		$newClientId = NULL;
		try{
			if (Helper::getViewId() == 0){
				$viewid = $Data['personal_data']['viewid'];
			}
			else{
				$viewid = Helper::getViewId();
			}
			$newClientId = $this->addClient($Data['personal_data']['email'], $password, $viewid);
			$this->addClientData($Data, $newClientId);
			$this->updateClientAddress($Data['billing_data'], $newClientId, 1);
			$this->updateClientAddress($Data['shipping_data'], $newClientId, 0);
			$viewid = $Data['personal_data']['viewid'];
			$this->editClientActive($Data['additional_data']['disable'], $newClientId, $viewid, $Data['personal_data']['email']);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWCLIENT_ADD'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $newClientId;
	}

	protected function addClientAddress ($Data, $ClientId)
	{
		foreach ($Data['street'] as $key => $street){
			$sql = 'INSERT INTO clientaddress(
							street, 
							streetno, 
							placeno, 
							postcode, 
							companyname, 
							firstname, 
							surname,
							placename, 
							countryid, 
							nip, 
							clientid, 
							addid)
						VALUES (
							AES_ENCRYPT(:street, :encryptionKey),
							AES_ENCRYPT(:nr, :encryptionKey),
							AES_ENCRYPT(:placeno, :encryptionKey),
							AES_ENCRYPT(:postcode, :encryptionKey),
							AES_ENCRYPT(:companyname, :encryptionKey),
							AES_ENCRYPT(:firstname, :encryptionKey),
							AES_ENCRYPT(:surname, :encryptionKey),		
							AES_ENCRYPT(:placename, :encryptionKey),
							:countryid,
							AES_ENCRYPT(:nip, :encryptionKey),
							:clientid, 
							:addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('street', $street);
			$stmt->setString('nr', $Data['streetno'][$key]);
			$stmt->setString('placeno', $Data['placeno'][$key]);
			$stmt->setString('postcode', $Data['postcode'][$key]);
			$stmt->setString('companyname', $Data['companyname'][$key]);
			$stmt->setString('firstname', $Data['firstname'][$key]);
			$stmt->setString('surname', $Data['surname'][$key]);
			$stmt->setString('placename', $Data['placename'][$key]);
			$stmt->setInt('countryid', $Data['country'][$key]);
			$stmt->setString('nip', $Data['nip'][$key]);
			$stmt->setInt('clientid', $ClientId);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENTADRESS_ADD'), 125, $e->getMessage());
			}
		}
		return $Data;
	}

	protected function addClient ($email, $password = 'topsecret', $viewid, $active = 0)
	{
		if ($email == ''){
			throw new CoreException($this->registry->core->getMessage('ERR_INVALID_EMAIL'));
		}
		$sql = 'INSERT INTO client (login, password, disable, addid, viewid) 
					VALUES (:login, :password, :disable, :addid, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', sha1($email));
		$stmt->setString('password', sha1($password));
		$stmt->setInt('disable', $active);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (Helper::getViewId() == 0){
			$stmt->setInt('viewid', $viewid);
		}
		else{
			$stmt->setInt('viewid', Helper::getViewId());
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	protected function addClientData ($Data, $ClientId)
	{
		$sql = 'INSERT INTO clientdata(
					firstname, 
					surname, 
					email,  
					newsletter,
					phone,
					description,
					clientgroupid,
					clientid,
					addid
				)VALUES (
					AES_ENCRYPT(:firstname, :encryptionKey), 
					AES_ENCRYPT(:surname, :encryptionKey),
					AES_ENCRYPT(:email, :encryptionKey),  
					:newsletter,
					AES_ENCRYPT(:phone, :encryptionKey),  
					AES_ENCRYPT(:description, :encryptionKey),
					:clientgroupid,
					:clientid, 
					:addid
				)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $ClientId);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('clientgroupid', $Data['personal_data']['clientgroupid']);
		$stmt->setString('firstname', $Data['personal_data']['firstname']);
		$stmt->setString('surname', $Data['personal_data']['surname']);
		$stmt->setString('email', $Data['personal_data']['email']);
		$stmt->setInt('newsletter', $Data['personal_data']['newsletter']);
		$stmt->setString('phone', $Data['personal_data']['phone']);
		$stmt->setString('description', $Data['additional_data']['description']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENTDATA_ADD'), 4, $e->getMessage());
		}
		return true;
	}

	public function editClient ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->editClientData($Data, $id);
			$this->updateClientAddress($Data['billing_data'], $id, 1);
			$this->updateClientAddress($Data['shipping_data'], $id, 0);
			$viewid = $Data['personal_data']['viewid'];
			$this->editClientActive($Data['additional_data']['disable'], $id, $viewid, $Data['personal_data']['email']);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function editClientActive ($active, $id, $viewid, $login)
	{
		$sql = 'UPDATE client SET 
					disable=:disable, 
					editid=:editid,
					viewid=:viewid,
					login=:login
				WHERE idclient=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('disable', $active);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('viewid', $viewid);
		$stmt->setInt('id', $id);
		$stmt->setString('login', sha1($login));
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_ACTIVE_UPDATE'), 1, $e->getMessage());
		}
		return true;
	}

	public function editClientData ($Data, $id)
	{
		$sql = 'UPDATE clientdata SET 
					clientgroupid=:clientgroupid, 
					firstname=AES_ENCRYPT(:firstname, :encryptionKey), 
					surname=AES_ENCRYPT(:surname, :encryptionKey), 
					email=AES_ENCRYPT(:email, :encryptionKey),
					phone=AES_ENCRYPT(:phone, :encryptionKey),
					description=AES_ENCRYPT(:description, :encryptionKey),
					newsletter = :newsletter
				WHERE clientid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('clientgroupid', $Data['personal_data']['clientgroupid']);
		$stmt->setString('firstname', $Data['personal_data']['firstname']);
		$stmt->setString('surname', $Data['personal_data']['surname']);
		$stmt->setString('email', $Data['personal_data']['email']);
		$stmt->setInt('newsletter', $Data['personal_data']['newsletter']);
		$stmt->setString('phone', $Data['personal_data']['phone']);
		$stmt->setString('description', $Data['additional_data']['description']);
		$stmt->setString('encryptionKey', $this->registry->session->getActiveEncryptionKeyValue());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENTDATA_UPDATE'), 1, $e->getMessage());
		}
		return true;
	}

	public function updateClientAddress ($Data, $id, $main)
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
		$stmt->setInt('clientid', $id);
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
		$stmt->setString('countryid', $Data['countryid']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return true;
	}

	public function selectClientsFromCategory ($groups)
	{
		$Data = Array();
		foreach ($groups as $idgroup){
			$sql = "SELECT AES_DECRYPT(email, :encryptionkey) AS email
						FROM clientgroup CG
						LEFT JOIN clientdata CD ON CD.clientgroupid = CG.idclientgroup
						WHERE idclientgroup=:id";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $idgroup);
			$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getString('email');
			}
		}
		return $Data;
	}

	public function selectClientGroup ($clients)
	{
		$Data = Array();
		foreach ($clients as $recipientlistid){
			$sql = "SELECT clientgroupid
						FROM recipientclientgrouplist
						WHERE recipientlistid=:recipientlistid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getInt('clientgroupid');
			}
		}
		return $Data;
	}

	public function selectClient ($clients)
	{
		$Data = Array();
		foreach ($clients as $recipientlistid){
			$sql = "SELECT clientid
						FROM recipientclientlist
						WHERE recipientlistid=:recipientlistid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getInt('clientid');
			}
		}
		return $Data;
	}

	public function selectClientNewsletter ($clients)
	{
		$Data = Array();
		foreach ($clients as $recipientlistid){
			$sql = "SELECT clientnewsletterid
						FROM recipientnewsletterlist
						WHERE recipientlistid=:recipientlistid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getInt('clientnewsletterid');
			}
		}
		return $Data;
	}

	public function selectClientsGroupFromNewsletter ($clients)
	{
		$Data = Array();
		foreach ($clients as $clientgroupid){
			$sql = "SELECT AES_DECRYPT(email, :encryptionkey) AS email
						FROM clientdata
						WHERE clientgroupid=:clientgroupid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', $clientgroupid);
			$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getString('email');
			}
		}
		return $Data;
	}

	public function selectClientsNewsletterFromNewsletter ($clients)
	{
		$Data = Array();
		foreach ($clients as $idclientnewsletter){
			$sql = "SELECT 
						email
					FROM clientnewsletter
					WHERE idclientnewsletter=:idclientnewsletter
					AND active=1";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('idclientnewsletter', $idclientnewsletter);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getString('email');
			}
		}
		return $Data;
	}

	public function selectClientsFromNewsletter ($clients)
	{
		$Data = Array();
		foreach ($clients as $clientid){
			$sql = "SELECT AES_DECRYPT(email, :encryptionkey) AS email
						FROM clientdata
						WHERE clientid=:clientid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientid', $clientid);
			$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = $rs->getString('email');
			}
		}
		return $Data;
	}

	public function getClientMailAddress ($clientId)
	{
		$mail = '';
		$sql = "SELECT AES_DECRYPT(CD.email, :encryptionkey) AS email
					FROM clientdata CD
					LEFT JOIN client C ON CD.clientid=C.idclient
					WHERE C.idclient= :idclient";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$stmt->setInt('idclient', $clientId);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$mail = $rs->getString('email');
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		return $mail;
	}

	public function getClientAddress ($id, $main)
	{
		$Data = Array(
			'idclientaddress' => '',
			'firstname' => '',
			'surname' => '',
			'companyname' => '',
			'nip' => '',
			'street' => '',
			'streetno' => '',
			'placeno' => '',
			'placename' => '',
			'postcode' => '',
			'countryid' => ''
		);
		
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
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $id);
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
}