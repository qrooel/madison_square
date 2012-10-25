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
 * $Id: login.php 655 2012-04-24 08:51:44Z gekosale $
 */

class LoginModel extends Model
{

	public function authProccess ($login, $password)
	{
		$sql = 'SELECT DISTINCT iduser FROM user U
					WHERE login = :login AND password = :password AND active = 1
					';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', $login);
		$stmt->setString('password', $password);
		$rs = $stmt->executeQuery();
		$id = 0;
		if ($rs->first()){
			$id = $rs->getInt('iduser');
		}
		return $id;
	}

	public function checkUsers ($login)
	{
		$sql = 'SELECT iduser FROM user U
					WHERE login = :login AND active = 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', $login);
		$rs = $stmt->executeQuery();
		$id = 0;
		if ($rs->first()){
			$id = $rs->getInt('iduser');
		}
		return $id;
	}

	public function getUserStoresDataByGroupId ($groupid)
	{
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
				$Data[$rs->getInt('storeid')] = $this->getUserViewDataByStoreId($rs->getInt('storeid'));
			}
		}
		return $Data;
	}

	public function getUserViewDataByStoreId ($storeid)
	{
		$sql = 'SELECT idview FROM `view` WHERE storeid = :storeid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('storeid', $storeid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('idview');
		}
		return $Data;
	}

	public function changeUsersPassword ($id, $password)
	{
		$sql = 'UPDATE user SET password=:password
					WHERE iduser=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('password', sha1($password));
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PASSWORD_USER_FORGOT'), 13, $e->getMessage());
			return false;
		}
		return true;
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

	public function setDefaultView ($result)
	{
		$sql = 'SELECT globaluser FROM user WHERE iduser = :userid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('userid', $result);
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			$globaluser = $rs->getInt('globaluser');
		}
		
		if ($globaluser == 0){
			$sql = 'SELECT
						UGV.viewid,
						V.storeid,
						V.name as viewname,
						UGV.groupid,
						S.idstore as storeid,
						S.shortcompanyname as storename
						FROM usergroupview UGV 
						lEFT JOIN view V ON UGV.viewid = V.idview
						lEFT JOIN store S ON V.storeid = S.idstore
						WHERE UGV.userid = :userid
						LIMIT 1';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('userid', $this->registry->session->getActiveUserid());
			$stmt->setInt('languageid', $this->registry->session->getActiveLanguageId());
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				App::getRegistry()->session->setActiveStoreId($rs->getInt('storeid'));
				App::getRegistry()->session->setActiveViewId($rs->getInt('viewid'));
				return true;
			}
		}
		else{
			App::getRegistry()->session->setActiveStoreId(0);
			App::getRegistry()->session->setActiveViewId(0);
		}
	}
}
?>