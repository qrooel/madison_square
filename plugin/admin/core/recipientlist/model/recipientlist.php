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
 * $Id: recipientlist.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class recipientlistModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('recipientlist', Array(
			'idrecipientlist' => Array(
				'source' => 'idrecipientlist'
			),
			'name' => Array(
				'source' => 'name'
			),
			'adddate' => Array(
				'source' => 'adddate'
			)
		));
		$datagrid->setFrom('
				recipientlist 
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getRecipientListForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteRecipientList ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteRecipientList'
		), $this->getName());
	}

	public function deleteRecipientList ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idrecipientlist' => $id
			), $this->getName(), 'deleteRecipientList');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewRecipient ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$reciepientlistid = $this->addNewRecipientName($Data);
			$this->addNewRecipientList($Data, $reciepientlistid);
			$this->addNewNewsletterList($Data, $reciepientlistid);
			$this->addNewClientgroupList($Data, $reciepientlistid);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NEWSLETTER_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $reciepientlistid;
	}

	public function addNewClientgroupList ($Data, $id)
	{
		foreach ($Data['clientgroup'] as $value){
			$sql = 'INSERT INTO recipientclientgrouplist (clientgroupid, recipientlistid, addid)
						VALUES (:clientgroupid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', $value);
			$stmt->setInt('recipientlistid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_GROUP_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addNewNewsletterList ($Data, $id)
	{
		foreach ($Data['clientnewsletter'] as $value){
			$sql = 'INSERT INTO recipientnewsletterlist (clientnewsletterid, recipientlistid, addid)
						VALUES (:clientnewsletterid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientnewsletterid', $value);
			$stmt->setInt('recipientlistid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NEWSLETTER_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addNewRecipientList ($Data, $id)
	{
		foreach ($Data['clients'] as $value){
			$sql = 'INSERT INTO recipientclientlist (clientid, recipientlistid, addid)
						VALUES (:clientid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientid', $value);
			$stmt->setInt('recipientlistid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NEWSLETTER_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addNewRecipientName ($Data)
	{
		$sql = 'INSERT INTO recipientlist (name, addid) 
					VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_RECIPIENT_LIST_ADD'), 11, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getRecipientListView ($idrecipientlist)
	{
		$sql = "SELECT idrecipientlist as id, name
					FROM recipientlist
					WHERE idrecipientlist = :idrecipientlist";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idrecipientlist', $idrecipientlist);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'clientgrouplist' => $this->getRecipientClientGroupList($rs->getInt('id')),
				'clientlist' => $this->getRecipientClientList($rs->getInt('id')),
				'clientnewsletterlist' => $this->getClientNewsletterList($rs->getInt('id'))
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_RECIPIENT_LIST_NO_EXIST'));
		}
		return $Data;
	}

	public function getRecipientClientGroupList ($recipientlistid)
	{
		$sql = "SELECT clientgroupid
					FROM recipientclientgrouplist
					WHERE recipientlistid = :recipientlistid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$clientgroupid = $rs->getInt('clientgroupid');
			$Data[$clientgroupid] = Array(
				'clientgroupid' => $rs->getInt('clientgroupid')
			);
		}
		return $Data;
	}

	public function getRecipientClientList ($recipientlistid)
	{
		$sql = "SELECT clientid
					FROM recipientclientlist
					WHERE recipientlistid = :recipientlistid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$clientid = $rs->getInt('clientid');
			$Data[$clientid] = Array(
				'clientid' => $rs->getInt('clientid')
			);
		}
		return $Data;
	}

	public function getClientNewsletterList ($recipientlistid)
	{
		$sql = "SELECT clientnewsletterid
					FROM recipientnewsletterlist
					WHERE recipientlistid = :recipientlistid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$clientnewsletterid = $rs->getInt('clientnewsletterid');
			$Data[$clientnewsletterid] = Array(
				'clientnewsletterid' => $rs->getInt('clientnewsletterid')
			);
		}
		return $Data;
	}

	public function editRecipientList ($Data, $recipientListId)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateRecipientList($Data, $recipientListId);
			$this->updateClientGroupList($Data, $recipientListId);
			$this->updateClientsList($Data, $recipientListId);
			$this->updateRecipientNewsletterList($Data, $recipientListId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_USER_UPDATE'), 118, $e - getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function updateRecipientList ($Data, $recipientlistid)
	{
		$sql = 'UPDATE recipientlist SET name=:name, editid=:editid WHERE idrecipientlist = :recipientlistid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('recipientlistid', $recipientlistid);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_RECEPIENTLIST_EDIT'), 13, $e->getMessage());
			return false;
		}
	}

	public function updateClientGroupList ($Data, $recipientlistid)
	{
		$sql = 'DELETE FROM recipientclientgrouplist WHERE recipientlistid = :recipientlistid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$stmt->executeUpdate();
		
		foreach ($Data['clientgroup'] as $key => $val){
			$sql = 'INSERT INTO recipientclientgrouplist (clientgroupid, recipientlistid, addid)
						VALUES (:clientgroupid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientgroupid', $val);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_RECIPIENT_CLIENT_GROUP_LIST_EDIT'), 13, $e->getMessage());
			}
		}
		return true;
	}

	public function updateClientsList ($Data, $recipientlistid)
	{
		$sql = 'DELETE FROM recipientclientlist WHERE recipientlistid = :recipientlistid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$stmt->executeUpdate();
		
		foreach ($Data['clients'] as $key => $val){
			$sql = 'INSERT INTO recipientclientlist (clientid, recipientlistid, addid)
						VALUES (:clientid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientid', $val);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_RECIPIENT_CLIENT_LIST_EDIT'), 13, $e->getMessage());
			}
		}
		return true;
	}

	public function updateRecipientNewsletterList ($Data, $recipientlistid)
	{
		$sql = 'DELETE FROM recipientnewsletterlist WHERE recipientlistid = :recipientlistid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('recipientlistid', $recipientlistid);
		$stmt->executeUpdate();
		
		foreach ($Data['clientnewsletter'] as $key => $val){
			$sql = 'INSERT INTO recipientnewsletterlist (clientnewsletterid, recipientlistid, addid)
						VALUES (:clientnewsletterid, :recipientlistid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientnewsletterid', $val);
			$stmt->setInt('recipientlistid', $recipientlistid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_RECIPIENT_NEWSLETTER_LIST_EDIT'), 13, $e->getMessage());
			}
		}
		return true;
	}
}