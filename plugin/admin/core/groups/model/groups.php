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
 * $Id: groups.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class GroupsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('clientgroup', Array(
			'idgroup' => Array(
				'source' => 'G.idgroup'
			),
			'name' => Array(
				'source' => 'G.name',
				'prepareForAutosuggest' => true
			),
			'usercount' => Array(
				'source' => 'COUNT(DISTINCT UG.userid)+COUNT(DISTINCT UGV.userid)'
			),
			'adddate' => Array(
				'source' => 'G.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'G.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`group` G
				LEFT JOIN `usergroup` UG ON UG.groupid = G.idgroup
				LEFT JOIN  usergroupview UGV ON UGV.groupid = G.idgroup
				LEFT JOIN `user` UA ON G.addid = UA.iduser
				LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
				LEFT JOIN `user` UE ON G.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				G.idgroup
			');
		/*$datagrid->setAdditionalWhere('
				IF(:storeid IS NULL,G.storeid IS NULL,G.storeid = :storeid)
			');*/
	//IF(:viewid IS NULL,G.viewid IS NULL,G.viewid = :viewid)
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getGroupsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteGroups ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteGroup'
		), $this->getName());
	}

	public function deleteGroup ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idgroup' => $id
			), $this->getName(), 'deleteGroup');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getGroupsView ($id)
	{
		$sql = "SELECT idgroup AS id, name FROM `group` 
				WHERE idgroup = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_GROUP_NO_EXIST'));
		}
		return $Data;
	}

	public function getGroupsAll ()
	{
		$rs = $this->registry->db->executeQuery('SELECT idgroup AS id, name FROM `group`');
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getGroupsAllToSelect ()
	{
		$Data = $this->getGroupsAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function getUsersAll ($id)
	{
		$sql = 'SELECT UD.firstname, UD.surname 
					FROM userdata UD 
					LEFT JOIN usergroup UG ON UG.userid = UD.userid 
					WHERE UG.groupid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname')
			);
		}
		return $Data;
	}

	protected function setPermission ($Data)
	{
		$Perm = Array();
		foreach ($Data as $controller => $value){
			if (count($value) > 0 && is_array($value)){
				foreach ($value as $action => $permission){
					if (! isset($Perm[$controller])){
						$Perm[$controller] = 0;
					}
					$Perm[$controller] += $permission * $action;
				}
			}
		}
		
		return $Perm;
	}

	protected function updatePermission ($Data, $id)
	{
		if (! is_array($Data) || count($Data) == 0){
			return;
		}
		$current = $this->getGroupRightsById($id);
		$sqlInsert = '	INSERT INTO `right`(controllerid, groupid, permission,storeid, addid)
							VALUES (:controllerid, :groupid, :permission, :storeid, :addid)';
		$sqlUpdate = '	UPDATE `right` SET permission = :permission, editid = :editid
							WHERE controllerid = :controllerid
							AND groupid = :groupid AND storeid IS NULL';
		$sqlDelete = '	DELETE FROM `right` WHERE controllerid NOT IN (:controllerIds)
							AND groupid = :groupid AND storeid IS NULL';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setINInt('controllerIds', array_keys($Data));
		$stmt->setInt('groupid', $id);
		$stmt->setNull('storeid');
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($Data as $key => $value){
			if (! array_key_exists($key, $current)){
				$stmt = $this->registry->db->prepareStatement($sqlInsert);
				$stmt->setInt('controllerid', $key);
				$stmt->setInt('groupid', $id);
				$stmt->setInt('permission', $value);
				$stmt->setNull('storeid');
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
			else{
				$stmt = $this->registry->db->prepareStatement($sqlUpdate);
				$stmt->setInt('controllerid', $key);
				$stmt->setInt('groupid', $id);
				$stmt->setInt('permission', $value);
				$stmt->setInt('editid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
	}

	protected function updateStorePermission ($Data, $id, $storeid)
	{
		if (! is_array($Data) || count($Data) == 0){
			return;
		}
		$current = $this->getStoreGroupRightsById($id, $storeid);
		
		$sqlInsert = '	INSERT INTO `right`(controllerid, groupid, permission,storeid, addid)
							VALUES (:controllerid, :groupid, :permission, :storeid, :addid)';
		$sqlUpdate = '	UPDATE `right` SET permission = :permission, editid = :editid
							WHERE controllerid = :controllerid
							AND groupid = :groupid AND storeid = :storeid';
		$sqlDelete = '	DELETE FROM `right` WHERE controllerid NOT IN (:controllerIds)
							AND groupid = :groupid AND storeid = :storeid';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setINInt('controllerIds', array_keys($Data));
		$stmt->setInt('groupid', $id);
		$stmt->setInt('storeid', $storeid);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($Data as $key => $value){
			if (! array_key_exists($key, $current)){
				$stmt = $this->registry->db->prepareStatement($sqlInsert);
				$stmt->setInt('controllerid', $key);
				$stmt->setInt('groupid', $id);
				$stmt->setInt('permission', $value);
				$stmt->setInt('storeid', $storeid);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
			else{
				$stmt = $this->registry->db->prepareStatement($sqlUpdate);
				$stmt->setInt('controllerid', $key);
				$stmt->setInt('groupid', $id);
				$stmt->setInt('permission', $value);
				$stmt->setInt('storeid', $storeid);
				$stmt->setInt('editid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		}
	}

	public function editPermission ($Data, $id)
	{
		$this->editGroup($Data, $id);
		$this->updatePermission($this->setPermission($Data['rights_data']['rights']), $id);
		
		$stores = App::getModel('store')->getStoreAll();
		foreach ($stores as $key => $store){
			$this->updateStorePermission($this->setPermission($Data['rights_data_' . $store['id']]['rights']), $id, $store['id']);
		}
		$this->registry->right->flushPermission();
	}

	protected function editGroup ($Data, $id)
	{
		$sql = 'UPDATE `group` SET name=:name, editid=:editid WHERE idgroup = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['basic_data']['name']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GROUP_EDIT'), 13, $e->getMessage());
			return false;
		}
		return true;
	}

	public function getGroupRightsById ($id)
	{
		$sql = 'SELECT controllerid, permission FROM `right` WHERE groupid=:id AND storeid IS NULL';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('controllerid')] = $rs->getInt('permission');
		}
		return $Data;
	}

	public function getStoreGroupRightsById ($id, $storeid)
	{
		$sql = 'SELECT controllerid, permission,storeid FROM `right` WHERE groupid=:id AND storeid = :storeid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('storeid', $storeid);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('controllerid')] = $rs->getInt('permission');
		}
		return $Data;
	}

	protected function addGroup ($Data)
	{
		$sql = 'INSERT INTO `group` (name, addid) VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['basic_data']['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GROUP_ADD'), 14, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function add ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$id = $this->addGroup($Data);
			$this->updatePermission($this->setPermission($Data['rights_data']['rights']), $id);
			$stores = App::getModel('store')->getStoreAll();
			foreach ($stores as $key => $store){
				$this->updateStorePermission($this->setPermission($Data['rights_data_' . $store['id']]['rights']), $id, $store['id']);
			}
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_GROUP_ADD_ERROR'), 3001, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function getFullPermission ()
	{
		$permissions = $this->getGroupRightsById($this->registry->core->getParam());
		$controllers = App::getModel('controller')->getControllerSimpleList();
		$Data = Array();
		foreach ($controllers as $con){
			$permission = 0;
			if (array_key_exists($con['id'], $permissions)){
				$permission = $permissions[$con['id']];
			}
			$Data[] = Array(
				'id' => $con['id'],
				'name' => $con['name'],
				'permission' => $permission
			);
		}
		return $Data;
	}

	public function getStorePermission ($storeid)
	{
		$permissions = $this->getStoreGroupRightsById($this->registry->core->getParam(), $storeid);
		$controllers = App::getModel('controller')->getControllerSimpleList();
		$Data = Array();
		foreach ($controllers as $con){
			$permission = 0;
			if (array_key_exists($con['id'], $permissions)){
				$permission = $permissions[$con['id']];
			}
			$Data[] = Array(
				'id' => $con['id'],
				'name' => $con['name'],
				'permission' => $permission
			);
		}
		return $Data;
	}

}