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
 * $Id: users.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class usersModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('userdata', Array(
			'iduser' => Array(
				'source' => 'U.iduser'
			),
			'active' => Array(
				'source' => 'U.active'
			),
			'firstname' => Array(
				'source' => 'UD.firstname',
				'prepareForAutosuggest' => true
			),
			'surname' => Array(
				'source' => 'UD.surname',
				'prepareForAutosuggest' => true
			),
			'email' => Array(
				'source' => 'UD.email',
				'prepareForAutosuggest' => false
			),
			'groupnames' => Array(
				'source' => 'IF(U.globaluser = 1,G.name,G2.name)',
				'prepareForSelect' => true
			),
			'groupname' => Array(
				'source' => 'GROUP_CONCAT(SUBSTRING(CONCAT(\' \', IF(U.globaluser = 1,G.name,G2.name)), 1))',
				'filter' => 'having'
			),
			'adddate' => Array(
				'source' => 'U.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'U.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		
		$globaluser = $this->registry->session->getActiveUserIsGlobal();
		
		if ($globaluser == 0){
			$datagrid->setFrom('
					`user` U
					LEFT JOIN `userdata` UD ON UD.userid = U.iduser
					LEFT JOIN `usergroup` UG ON UG.userid = U.iduser
					LEFT JOIN `group` G ON G.idgroup = UG.groupid
					LEFT JOIN `user` UA ON U.addid = UA.iduser
					LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
					LEFT JOIN `user` UE ON U.editid = UE.iduser
					LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
					INNER JOIN usergroupview UGV ON U.iduser = UGV.userid AND UGV.viewid IN (:viewids)
					LEFT JOIN `group` G2 ON G2.idgroup = UGV.groupid
				');
		}
		else{
			$datagrid->setFrom('
					`user` U
					LEFT JOIN `userdata` UD ON UD.userid = U.iduser
					LEFT JOIN `usergroup` UG ON UG.userid = U.iduser
					LEFT JOIN usergroupview UGV ON U.iduser = UGV.userid
					LEFT JOIN `group` G ON G.idgroup = UG.groupid
					LEFT JOIN `group` G2 ON G2.idgroup = UGV.groupid
					LEFT JOIN `user` UA ON U.addid = UA.iduser
					LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
					LEFT JOIN `user` UE ON U.editid = UE.iduser
					LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
				');
		}
		$datagrid->setGroupBy('
				U.iduser
			');
	}

	public function getUsers ()
	{
		$rs = $this->registry->db->executeQuery('SELECT userid AS id, firstname, surname, email FROM userdata
													ORDER BY surname, firstname');
		return $rs->getAllRows();
	}

	public function getUsersCount ()
	{
	
	}

	public function getFirstnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('firstname', $request, $processFunction);
	}

	public function getSurnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('surname', $request, $processFunction);
	}

	public function getEmailForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('email', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getUsersForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteUser ($id = NULL, $datagridName = 'list-users')
	{
		return $this->getDatagrid()->deleteRow($datagridName, $id, Array(
			$this,
			'deleteUser'
		), $this->getName());
	}

	public function deleteUser ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'iduser' => $id
			), $this->getName(), 'deleteUser');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnableUser ($datagridId, $id)
	{
		try{
			$this->enableUser($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableUser ($datagridId, $id)
	{
		try{
			$this->disableUser($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableUser ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception($this->registry->core->getMessage('ERR_CAN_NOT_DISABLE_YOURSELF'));
		}
		$sql = 'UPDATE user SET
					active = 0
					WHERE iduser = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableUser ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception($this->registry->core->getMessage('ERR_CAN_NOT_ENABLE_YOURSELF'));
		}
		$sql = 'UPDATE user SET
					active = 1
					WHERE iduser = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getUsersToSelect ()
	{
		$rs = $this->registry->db->executeQuery('SELECT userid AS id, firstname, surname FROM userdata
													ORDER BY surname, firstname');
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getString('firstname') . ' ' . $rs->getString('surname');
		}
		return $Data;
	}

	public function getUserById ($id)
	{
		$sql = 'SELECT
					UD.userid as id,
					UD.firstname,
					UD.surname,
					UD.email,
					UD.description,
					UD.lastlogged,
					G.name as groupname,
					G.idgroup,
					U.active,
					U.globaluser
					FROM userdata UD
					LEFT JOIN usergroup UG ON UD.userid = UG.userid
					LEFT JOIN user U ON U.iduser = UD.userid
					LEFT JOIN `group` G ON G.idgroup = UG.groupid
					WHERE UD.userid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'email' => $rs->getString('email'),
				'description' => $rs->getString('description'),
				'lastlogged' => $rs->getString('lastlogged'),
				'groupname' => $rs->getString('groupname'),
				'idgroup' => $rs->getInt('idgroup'),
				'active' => $rs->getInt('active'),
				'globaluser' => $rs->getInt('globaluser'),
				'photo' => $this->getPhotoUserById($id),
				'layer' => $this->getLayersById($id)
			);
			return $Data;
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getLayersById ($id)
	{
		
		$sql = 'SELECT
					UGV.viewid,
					V.storeid,
					UGV.groupid
					FROM usergroupview UGV
					lEFT JOIN view V ON UGV.viewid = V.idview
					WHERE UGV.userid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'store' => $rs->getInt('storeid'),
				'view' => $rs->getInt('viewid'),
				'group' => $rs->getInt('groupid')
			);
		}
		
		return $Data;
	
	}

	public function getPhotoUserById ($id)
	{
		$sql = 'SELECT
					photoid
					FROM userdata UD
					LEFT JOIN file F ON F.idfile = UD.photoid
					WHERE UD.userid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = $rs->getInt('photoid');
		}
		return $Data;
	}

	public function getPhotos (&$users)
	{
		if (! is_array($users)){
			throw new Exception('Wrong array given');
		}
		foreach ($users['photo'] as $photo){
			$users['photo']['small'][] = App::getModel('gallery')->getSmallImageById($photo['photoid']);
		}
	}

	public function getUserHistorylogView ($id)
	{
		$sql = 'SELECT
					UD.userid AS id,
					UHL.`URL` AS adress,
					UHL.sessionid,
					UHL.adddate
					FROM userdata UD
					LEFT JOIN userhistorylog UHL ON UHL.userid = UD.userid
					WHERE UD.userid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'sessionid' => $rs->getString('sessionid'),
				'adress' => $rs->getString('adress'),
				'adddate' => $rs->getString('adddate')
			);
		}
		return $Data;
	}

	protected function updateUserLogin ()
	{
		$sql = 'UPDATE user SET
					login = (SELECT sha1(email) FROM userdata WHERE userid = :iduser),
					editid = :editid
					WHERE iduser = :iduser';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('iduser', $this->registry->core->getParam());
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LOGINUSER_UPDATE'), 17, $e->getMessage());
		}
	}

	public function updateUserPassword ($password)
	{
		if (isset($password) && ! empty($password)){
			$sql = 'UPDATE user SET
						password = :password,
						editid = :editid
						WHERE iduser = :iduser';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('password', sha1($password));
			$stmt->setInt('iduser', $this->registry->core->getParam());
			$stmt->setInt('editid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
				return true;
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PASSWORDUSER_UPDATE'), 18, $e->getMessage());
			}
		}
	}

	public function updateUser ($Data, $userId)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateUserData($Data, $userId);
			$this->updateUserGroup($Data);
			$this->updateUserActive($Data['additional_data']['active']);
			$this->updateUserLogin();
		
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_UPDATE'), 118, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		if ($userId == $this->registry->session->getActiveUserid()){
			//				$this->registry->session->flush();
		//				App::redirect('login');
		}
	}

	public function checkActiveUserIsGlobal ()
	{
		$sql = 'SELECT
					globaluser
					FROM user WHERE iduser = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->session->getActiveUserid());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			return $rs->getInt('globaluser');
		}
	}

	protected function updateUserGroup ($Data)
	{
		
		$global = (int) $Data['rights_data']['global'];
		
		$sql = 'UPDATE user SET
					globaluser = :globaluser,
					editid = :editid
					WHERE iduser = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->core->getParam());
		$stmt->setInt('globaluser', $global);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		$sql = 'DELETE FROM usergroup
					WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->core->getParam());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		$sql = 'DELETE FROM usergroupview
					WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->core->getParam());
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		if ($global == 1){
			
			$sql = 'INSERT INTO usergroup SET
						groupid = :groupid,
						addid = :addid,
						userid = :userid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('userid', $this->registry->core->getParam());
			$stmt->setInt('groupid', $Data['rights_data']['group']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
			}
		
		}
		else{
			
			$layers = $this->getLayersAll();
			
			foreach ($layers as $key => $store){
				if (is_array($Data['rights_data']['store_' . $store['id']]) && ! empty($Data['rights_data']['store_' . $store['id']])){
					foreach ($store['views'] as $v => $view){
						
						$groupid = $Data['rights_data']['store_' . $store['id']]['view_' . $view['id']];
						
						if ($groupid > 0){
							
							$sql = 'INSERT INTO usergroupview SET
		           						userid = :userid,
		           						groupid = :groupid,
		           						viewid = :viewid,
		           						addid = :addid
										';
							$stmt = $this->registry->db->prepareStatement($sql);
							$stmt->setInt('userid', $this->registry->core->getParam());
							$stmt->setInt('groupid', $groupid);
							$stmt->setInt('viewid', $view['id']);
							$stmt->setInt('addid', $this->registry->session->getActiveUserid());
							$stmt->executeQuery();
						
						}
					
					}
				
				}
			
			}
		
		}
	
	}

	protected function updateUserActive ($active)
	{
		$sql = 'UPDATE user SET
					active=:active,
					editid=:editid
					WHERE iduser = :iduser';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('active', $active);
		$stmt->setInt('iduser', $this->registry->core->getParam());
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_ACTIVE_UPDATE'), 19, $e->getMessage());
		}
	}

	protected function updateUserData ($Data, $userid)
	{
		$sql = 'UPDATE userdata SET
					firstname = :firstname,
					surname = :surname,
					email = :email,
					editid = :loggeduserid,
					description = :description,
					photoid =  :photo
					WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['personal_data']['firstname']);
		$stmt->setString('surname', $Data['personal_data']['surname']);
		$stmt->setString('email', $Data['personal_data']['email']);
		$stmt->setString('description', $Data['additional_data']['description']);
		$stmt->setInt('loggeduserid', $this->registry->session->getActiveUserid());
		$stmt->setInt('userid', $userid);
		if (($Data['photos_pane']['photo'][0]) > 0){
			$stmt->setInt('photo', $Data['photos_pane']['photo']);
		}
		else{
			$stmt->setInt('photo', $this->registry->core->setDefaultPhoto());
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USERDATA_UPDATE'), 19, $e->getMessage());
		}
	}

	protected function addUser ($email, $password, $active = 1)
	{
		if ($email == ''){
			throw new CoreException($this->registry->core->getMessage('TXT_WRONG_EMAIL'), 1001, 'Email is blank -> mysql fix');
		}
		if ($password == NULL){
			$password = 'topsecret';
		}
		$sql = 'INSERT INTO user SET
						login = :login,
						password = :password,
						active = :active,
						addid = :addid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', sha1($email));
		$stmt->setString('password', sha1($password));
		$stmt->setInt('active', $active);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('newid', 0);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_ADD'), 20, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	protected function addUserData ($Data, $userId)
	{
		$sql = 'INSERT INTO userdata SET
					firstname = :firstname,
					surname = :surname,
					email = :email,
					description = :description,
					userid = :userid,
					photoid = :photoid,
					addid = :addid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('firstname', $Data['personal_data']['firstname']);
		$stmt->setString('surname', $Data['personal_data']['surname']);
		$stmt->setString('email', $Data['personal_data']['email']);
		$stmt->setString('description', $Data['additional_data']['description']);
		$stmt->setInt('userid', $userId);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		if (($Data['photos_pane']['photo'][0]) > 0){
			$stmt->setInt('photoid', $Data['photos_pane']['photo']);
		}
		else{
			$stmt->setInt('photoid', $this->registry->core->setDefaultPhoto());
		}
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_ADD'), 20, $e->getMessage());
		}
		return true;
	}

	protected function addUserToGroup ($Data, $userId)
	{
		$global = (int) $Data['rights_data']['global'];
		
		$sql = 'UPDATE user SET
					globaluser = :globaluser,
					editid = :editid
					WHERE iduser = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $userId);
		$stmt->setInt('globaluser', $global);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		$sql = 'DELETE FROM usergroupview
					WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $userId);
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		$sql = 'DELETE FROM usergroup
					WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $userId);
		
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
		}
		
		if ($global == 1){
			
			$sql = 'INSERT INTO usergroup SET
						groupid = :groupid,
						addid = :addid,
						userid = :userid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('userid', $userId);
			$stmt->setInt('groupid', $Data['rights_data']['group']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_USER_TO_GROUP'), 21, $e->getMessage());
			}
		
		}
		else{
			
			$layers = $this->getLayersAll();
			
			foreach ($layers as $key => $store){
				if (is_array($Data['rights_data']['store_' . $store['id']]) && ! empty($Data['rights_data']['store_' . $store['id']])){
					foreach ($store['views'] as $v => $view){
						
						$groupid = $Data['rights_data']['store_' . $store['id']]['view_' . $view['id']];
						
						if ($groupid > 0){
							
							$sql = 'INSERT INTO usergroupview SET
		           						userid = :userid,
		           						groupid = :groupid,
		           						viewid = :viewid,
		           						addid = :addid
										';
							$stmt = $this->registry->db->prepareStatement($sql);
							$stmt->setInt('userid', $userId);
							$stmt->setInt('groupid', $groupid);
							$stmt->setInt('viewid', $view['id']);
							$stmt->setInt('addid', $this->registry->session->getActiveUserid());
							$stmt->executeQuery();
						
						}
					
					}
				
				}
			
			}
		
		}
	
	}

	public function addNewUser ($Data, $password)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newUserId = $this->addUser($Data['personal_data']['email'], $password);
			$this->addUserData($Data, $newUserId);
			$this->addUserToGroup($Data, $newUserId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_ADD'), 21, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function getUserFullName ()
	{
		if ($this->registry->session->getActiveUserid() !== NULL){
			$fullName = $this->registry->session->getActiveUserFirstname() . ' ' . $this->registry->session->getActiveUserSurname();
			return $fullName;
		}
	}

	public function getActiveUserid ()
	{
		if ($this->registry->session->getActiveUserid() !== NULL){
			return $this->registry->session->getActiveUserid();
		}
	}

	public function getLastLoggedUsers ()
	{
		$sql = 'SELECT
					userid,
					firstname,
					surname,
					lastlogged,
					photoid
					FROM userdata
					WHERE lastlogged > 0
					ORDER BY lastlogged DESC
					LIMIT 5';
		$rs = $this->registry->db->executeQuery($sql);
		return $rs->getAllRows();
	}

	public function setLoginTime ()
	{
		$sql = 'UPDATE userdata SET lastlogged = NOW() WHERE userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LOGINTIME'), 22, $e->getMessage());
		}
	}

	public function getUserData ()
	{
		if ($this->registry->session->getActiveUserid() == 0){
			return false;
		}
		$sql = 'SELECT
					UD.firstname,
					UD.surname,
					UD.email,
					UG.groupid,
					U.globaluser
					FROM userdata UD
					LEFT JOIN user U ON UD.userid = U.iduser
					LEFT JOIN usergroup UG ON UG.userid = UD.userid
					WHERE UD.userid = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $this->registry->session->getActiveUserid());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->registry->session->setActiveUserFirstname($rs->getString('firstname'));
			$this->registry->session->setActiveUserSurname($rs->getString('surname'));
			$this->registry->session->setActiveUserEmail($rs->getString('email'));
			$this->registry->session->setActiveUserGroupid($rs->getInt('groupid'));
			$this->registry->session->setActiveStoreData($this->getUserStoresDataByGroupId($rs->getInt('groupid')));
			$this->registry->session->setActiveUserIsGlobal($rs->getInt('globaluser'));
		}
		return true;
	}

	public function getUserStoresDataByGroupId ($groupid)
	{
		$globalUser = $this->registry->session->getActiveUserIsGlobal();
		if ($globalUser == 0){
			$sql = 'SELECT DISTINCT storeid
						FROM `view` V
						LEFT JOIN usergroupview UGV ON UGV.viewid = V.idview
						WHERE UGV.userid = :userid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('groupid', $groupid);
			$stmt->setInt('userid', $this->registry->session->getActiveUserid());
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				if ($rs->getInt('storeid') == NULL){
					$Data['global'] = 1;
				}
				else{
					$Data['stores'] = Array(
						$rs->getInt('storeid') => $this->getUserViewDataByStoreId($rs->getInt('storeid'))
					);
				}
			}
		}
		else{
		
		}
		return $Data;
	}

	public function getUserViewDataByStoreId ($storeid)
	{
		$sql = 'SELECT DISTINCT idview FROM `view` WHERE storeid = :storeid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('storeid', $storeid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('idview');
		}
		return $Data;
	}

	public function getLayerIdByViewId ($id)
	{
		$globaluser = $this->registry->session->getActiveUserIsGlobal();
		
		$Stores = $this->registry->session->getActiveStoreData();
		
		if ($globaluser == 1){
			return 0;
		}
		
		if ($id == 0){
			if (isset($Stores['global'])){
				return 0;
			}
			else{
				//					throw new Exception('No privileges '.$id);
			}
		}
		
		foreach ($Stores as $key => $store){
			if ($key != 'global'){
				foreach ($store as $storeKey => $view){
					if ($id == $view){
						return $key;
					}
				}
			}
		}
		
	//			throw new Exception('No privileges '.$id);
	}

	public function getLayersAll ()
	{
		$sql = "SELECT S.idstore AS id, S.shortcompanyname AS name,COUNT(V.idview) as views
					FROM store S
					LEFT JOIN view V ON V.storeid = S.idstore
					GROUP BY S.idstore
					HAVING views > 0";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $this->registry->session->getActiveLanguageId());
		$rs = $stmt->executeQuery();
		
		while ($rs->next()){
			
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'views' => App::getModel('view')->getViewsByStoreId($rs->getInt('id'))
			);
		}
		
		return $Data;
	
	}
}