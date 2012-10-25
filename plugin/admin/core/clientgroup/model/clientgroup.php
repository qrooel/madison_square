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
 * $Id: clientgroup.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class ClientGroupModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('clientgroup', Array(
			'idclientgroup' => Array(
				'source' => 'CG.idclientgroup'
			),
			'name' => Array(
				'source' => 'CGT.name',
				'prepareForAutosuggest' => true
			),
			'clientcount' => Array(
				'source' => 'COUNT(CD.clientid)'
			),
			'adddate' => Array(
				'source' => 'CG.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'CG.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				`clientgroup` CG
				LEFT JOIN clientgrouptranslation CGT ON CG.idclientgroup = CGT.clientgroupid AND CGT.languageid = :languageid
				LEFT JOIN `clientdata` CD ON CD.clientgroupid = CG.idclientgroup
				LEFT JOIN `user` UA ON CG.addid = UA.iduser
				LEFT JOIN `userdata` UDA ON UA.iduser = UDA.userid
				LEFT JOIN `user` UE ON CG.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON UA.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				CG.idclientgroup
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getClientGroupForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteClientGroup ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteClientGroup'
		), $this->getName());
	}

	public function deleteClientGroup ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idclientgroup' => $id
			), $this->getName(), 'deleteClientGroup');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function ClientGroup ($Data)
	{
		$sql = 'INSERT INTO clientgroup (addid) VALUES (:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENTGROUP_ADD'), 9, $e->getMessage());
		}
		
		$clientgroupid = $stmt->getConnection()->getIdGenerator()->getId();
		
		$sql = 'DELETE FROM clientgrouptranslation WHERE clientgroupid= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $clientgroupid);
		$stmt->executeUpdate();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO clientgrouptranslation (clientgroupid, name, languageid)
						VALUES (:clientgroupid, :name, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', $clientgroupid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENTGROUP_EDIT'), 10, $e->getMessage());
			}
		}
		
		return $clientgroupid;
	}

	public function addClientGroup ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newClientGroupId = $this->ClientGroup($Data);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_GROUP_ADD'));
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function getClientGroupById ($id)
	{
		$sql = 'SELECT CG.idclientgroup AS id
					FROM clientgroup CG
					WHERE idclientgroup= :id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getClientGroupTranslation($id),
				'id' => $rs->getInt('id')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_GROUP_NO_EXIST'));
		}
		return $Data;
	}

	public function getClientGroupTranslation ($id)
	{
		$sql = "SELECT name,languageid
					FROM clientgrouptranslation
					WHERE clientgroupid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function editClientGroup ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$sql = 'UPDATE clientgroup SET editid= :editid WHERE idclientgroup= :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('editid', $this->registry->session->getActiveUserid());
			$stmt->setInt('id', $id);
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CLIENTGROUP_EDIT'), 10, $e->getMessage());
		}
		
		$sql = 'DELETE FROM clientgrouptranslation WHERE clientgroupid= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO clientgrouptranslation (clientgroupid, name, languageid)
						VALUES (:clientgroupid, :name, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENTGROUP_EDIT'), 10, $e->getMessage());
			}
		}
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function getClientGroupAll ()
	{
		$sql = 'SELECT clientgroupid AS id, name
				FROM clientgrouptranslation 
				WHERE languageid= :languageid
				ORDER BY name ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getClientGroupAllToSelect ()
	{
		$Data = $this->getClientGroupAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function clientGroupClients ($id)
	{
		$sql = 'SELECT 
						AES_DECRYPT(firstname, :encryptionkey) AS firstname,
						AES_DECRYPT(surname, :encryptionkey) AS surname, 
						idclientgroup as id
					FROM clientdata
					LEFT JOIN clientgroup ON idclientgroup = clientgroupid
					WHERE clientgroupid= :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getClientGroupToRangeEditor ()
	{
		$sql = 'SELECT clientgroupid AS id, name
					FROM clientgrouptranslation 
					WHERE languageid= :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getString('name');
		}
		return $Data;
	}

	public function getAssignToGroupPerView ($viewid)
	{
		$sql = 'SELECT `from`, `to`, clientgroupid
					FROM assigntogroup 
					WHERE viewid= :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', $viewid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['ranges'][] = Array(
				'min' => $rs->getString('from'),
				'max' => $rs->getString('to'),
				'price' => $rs->getInt('clientgroupid')
			);
		}
		return $Data;
	}
}