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
 * $Revision: 656 $
 * $Author: gekosale $
 * $Date: 2012-04-24 11:00:20 +0200 (Wt, 24 kwi 2012) $
 * $Id: orderstatus.php 656 2012-04-24 09:00:20Z gekosale $ 
 */

class OrderstatusModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('orderstatus', Array(
			'idorderstatus' => Array(
				'source' => 'O.idorderstatus'
			),
			'name' => Array(
				'source' => 'OST.name',
				'prepareForAutosuggest' => true
			),
			'groupname' => Array(
				'source' => 'OSGT.name',
				'prepareForSelect' => true
			),
			'adddate' => Array(
				'source' => 'O.adddate'
			),
			'def' => Array(
				'source' => 'O.default'
			)
		));
		$datagrid->setFrom('
				`orderstatus` O
				LEFT JOIN orderstatustranslation OST ON O.idorderstatus = OST.orderstatusid AND OST.languageid = :languageid
				LEFT JOIN orderstatusorderstatusgroups OS ON OS.orderstatusid = O.idorderstatus
				LEFT JOIN orderstatusgroups OSG ON OSG.idorderstatusgroups = OS.orderstatusgroupsid
				LEFT JOIN orderstatusgroupstranslation OSGT ON OSG.idorderstatusgroups = OSGT.orderstatusgroupsid AND OSGT.languageid = :languageid
			');
		$datagrid->setGroupBy('O.idorderstatus');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getOrderstatusForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteOrderstatus ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteOrderstatus'
		), $this->getName());
	}

	public function deleteOrderstatus ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idorderstatus' => $id
			), $this->getName(), 'deleteOrderstatus');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewOrderstatus ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newOrderStatusId = $this->addOrderStatus($Data);
			$this->addOrderStatusOrderStatusGroups($Data['orderstatusgroupsid'], $newOrderStatusId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addOrderStatus ($Data)
	{
		$sql = 'INSERT INTO orderstatus (addid) VALUES (:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_ADD'), 11, $e->getMessage());
		}
		
		$orderstatusid = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO orderstatustranslation (orderstatusid, name,comment, languageid)
						VALUES (:orderstatusid, :name,:comment, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusid', $orderstatusid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('comment', $Data['comment'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_ADD'), 15, $e->getMessage());
			}
		}
		return $orderstatusid;
	}

	public function addOrderStatusOrderStatusGroups ($orderstatusgroupsid, $id)
	{
		$sql = 'INSERT INTO orderstatusorderstatusgroups (orderstatusgroupsid, orderstatusid, addid) VALUES (:orderstatusgroupsid, :orderstatusid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderstatusgroupsid', $orderstatusgroupsid);
		$stmt->setInt('orderstatusid', $id);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_ADD'), 11, $e->getMessage());
		}
		return true;
	}

	public function editOrderstatus ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateOrderstatus($Data, $id);
			$this->updateOrderStatusOrderStatusGroups($Data, $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDER_STATUS_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateOrderstatus ($Data, $id)
	{
		$sql = 'UPDATE orderstatus SET editid=:editid WHERE idorderstatus = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_EDIT'), 129, $e->getMessage());
		}
		
		$sql = 'DELETE FROM orderstatustranslation WHERE orderstatusid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO orderstatustranslation (orderstatusid,name,comment, languageid)
						VALUES (:orderstatusid,:name,:comment, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderstatusid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('comment', $Data['comment'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_EDIT'), 129, $e->getMessage());
			}
		}
		return true;
	}

	public function updateOrderStatusOrderStatusGroups ($Data, $id)
	{
		$sql = 'DELETE FROM orderstatusorderstatusgroups WHERE orderstatusid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		$sql = 'INSERT INTO orderstatusorderstatusgroups (orderstatusgroupsid, orderstatusid, addid) VALUES (:orderstatusgroupsid, :id, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('orderstatusgroupsid', $Data['orderstatusgroupsid']);
		$stmt->setInt('id', $id);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException('ERR_ORDER_STATUS_UPDATE', 15, $e->getMessage());
		}
	}

	public function getOrderstatusView ($id)
	{
		$sql = "SELECT idorderstatus AS id, orderstatusgroupsid FROM orderstatus
					LEFT JOIN orderstatusorderstatusgroups OSG ON OSG.orderstatusid = idorderstatus
					WHERE idorderstatus = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getOrderStatusTranslation($id),
				'id' => $rs->getInt('id'),
				'orderstatusgroupsid' => $rs->getInt('orderstatusgroupsid')
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_NO_EXIST'));
		}
		return $Data;
	}

	public function getOrderStatusTranslation ($id)
	{
		$sql = "SELECT name,comment,languageid
					FROM orderstatustranslation
					WHERE orderstatusid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'comment' => $rs->getString('comment')
			);
		}
		return $Data;
	}

	public function getOrderStatusAll ()
	{
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
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('orderstatusid'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getOrderStatusToSelect ()
	{
		$Data = $this->getOrderStatusAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function doAJAXDefault ($datagridId, $id)
	{
		try{
			$this->setDefault($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_SET_DEFAULT_ORDER_STATUS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function getDefaultComment ($id)
	{
		$Data = $this->getOrderStatusTranslation($id);
		return isset($Data[Helper::getLanguageId()]['comment']) ? $Data[Helper::getLanguageId()]['comment'] : '';
	}

	public function setDefault ($id)
	{
		$sql = 'UPDATE orderstatus SET `default`= 1 
					WHERE idorderstatus = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
			
			$sql2 = 'UPDATE orderstatus SET `default`=0 
						WHERE idorderstatus <> :id';
			$stmt2 = $this->registry->db->prepareStatement($sql2);
			$stmt2->setInt('id', $id);
			$stmt2->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}		