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
 * $Id: orderstatusgroups.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class orderstatusgroupsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('orderstatusgroups', Array(
			'idorderstatusgroups' => Array(
				'source' => 'OSG.idorderstatusgroups'
			),
			'name' => Array(
				'source' => 'OSGT.name',
				'prepareForAutosuggest' => true
			),
			'adddate' => Array(
				'source' => 'adddate'
			)
		));
		$datagrid->setFrom('
				orderstatusgroups OSG
				LEFT JOIN orderstatusgroupstranslation OSGT ON OSG.idorderstatusgroups = OSGT.orderstatusgroupsid AND OSGT.languageid = :languageid
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

	public function getOrderStatusGroupsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteOrderStatusGroups ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteOrderStatusGroups'
		), $this->getName());
	}

	public function deleteOrderStatusGroups ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idorderstatusgroups' => $id
			), $this->getName(), 'deleteOrderStatusGroups');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getOrderStatusGroupsView ($id)
	{
		$sql = "SELECT 
					idorderstatusgroups AS id
				FROM orderstatusgroups 
				WHERE idorderstatusgroups = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getOrderStatusGroupsTranslation($id),
				'id' => $rs->getInt('id'),
				'orderstatus' => $this->orderStatusOrderStatusGroupsIds($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_GROUPS_NO_EXIST'));
		}
		return $Data;
	}

	public function getOrderStatusGroupsTranslation ($id)
	{
		$sql = "SELECT name, languageid
					FROM orderstatusgroupstranslation
					WHERE orderstatusgroupsid =:id";
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

	public function orderStatusOrderStatusGroups ($id)
	{
		$sql = 'SELECT OS.idorderstatus AS id
					FROM orderstatusorderstatusgroups OSG
					LEFT JOIN orderstatus OS ON OSG.orderstatusid = OS.idorderstatus
					WHERE OSG.orderstatusgroupsid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id')
			);
		}
		return $Data;
	}

	public function orderStatusOrderStatusGroupsIds ($id)
	{
		$Data = $this->orderStatusOrderStatusGroups($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function addNewOrderStatusGroups ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newOrderStatusGroupId = $this->addOrderStatusGroups($Data);
			$this->addOrderStatusOrderStatusGroups($Data['orderstatus'], $newOrderStatusGroupId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_GROUPS_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addOrderStatusGroups ($Data)
	{
		$sql = 'INSERT INTO orderstatusgroups (addid) VALUES (:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException('ERR_ORDER_STATUS_GROUP_ADD', 15, $e->getMessage());
		}
		
		$orderstatusgroupsid = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO orderstatusgroupstranslation (orderstatusgroupsid,name, languageid)
						VALUES (:orderstatusgroupsid,:name, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusgroupsid', $orderstatusgroupsid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_GROUP_ADD'), 15, $e->getMessage());
			}
		}
		
		return $orderstatusgroupsid;
	}

	protected function addOrderStatusOrderStatusGroups ($orderstatusarray, $newOrderStatusGroupId)
	{
		foreach ($orderstatusarray as $value){
			$sql = 'INSERT INTO orderstatusorderstatusgroups (orderstatusid, orderstatusgroupsid, addid)
						VALUES (:orderstatusid, :orderstatusgroupsid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusgroupsid', $newOrderStatusGroupId);
			$stmt->setInt('orderstatusid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function editOrderStatusGroups ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateOrderStatusGroups($Data, $id);
			$this->updateOrderStatusOrderStatusGroups($Data['orderstatus'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_GROUPS_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateOrderStatusGroups ($Data, $id)
	{
		$sql = 'DELETE FROM orderstatusgroupstranslation WHERE orderstatusgroupsid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO orderstatusgroupstranslation (orderstatusgroupsid,name, languageid)
						VALUES (:orderstatusgroupsid,:name, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusgroupsid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_GROUP_ADD'), 15, $e->getMessage());
			}
		}
		
		return true;
	}

	public function updateOrderStatusOrderStatusGroups ($array, $id)
	{
		$sql = 'DELETE FROM orderstatusorderstatusgroups WHERE orderstatusgroupsid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($array as $value){
			$sql = 'INSERT INTO orderstatusorderstatusgroups (orderstatusid, orderstatusgroupsid, addid)
						VALUES (:orderstatusid, :orderstatusgroupsid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusgroupsid', $id);
			$stmt->setInt('orderstatusid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function getOrderStatusGroupsAll ()
	{
		
		$sql = 'SELECT 
					OSG.idorderstatusgroups AS id,
					OSGT.name as name
				FROM orderstatusgroups OSG
				LEFT JOIN orderstatusgroupstranslation OSGT ON OSGT.orderstatusgroupsid = OSG.idorderstatusgroups AND OSGT.languageid = :languageid';
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

	public function getOrderStatusGroupsAllToSelect ()
	{
		$Data = $this->getOrderStatusGroupsAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}
}