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
 * $Id: transmailheader.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class TransmailheaderModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('transmailheader', Array(
			'idtransmailheader' => Array(
				'source' => 'TMH.idtransmailheader'
			),
			'name' => Array(
				'source' => 'TMH.name'
			),
			'adddate' => Array(
				'source' => 'TMH.adddate'
			)
		));
		
		$datagrid->setFrom('
				transmailheader TMH
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getTransmailheaderForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteTransmailheader ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteTransmailheader'
		), $this->getName());
	}

	public function deleteTransmailheader ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idtransmailheader' => $id
			), $this->getName(), 'deleteTransmailheader');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewTransMailHeader ($submittedData)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->insertNewTransMailHeader($submittedData);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_HEADER_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function insertNewTransMailHeader ($submittedData)
	{
		$sql = 'INSERT INTO transmailheader 
					 	(name, contenthtml, contenttxt, addid, viewid)
					VALUES 
						(:name, :contenthtml, :contenttxt, :addid, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('contenthtml', $submittedData['contenthtml']);
		$stmt->setString('contenttxt', $submittedData['contenttxt']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
		}
		try{
			$stmt->executeUpdate();
			return $stmt->getConnection()->getIdGenerator()->getId();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_HEADER_INSERT'), 112, $e->getMessage());
		}
	}

	public function getTransMailHeaderToEdit ($idtransmailheader)
	{
		$sql = "SELECT idtransmailheader, name, contenthtml, contenttxt
					FROM transmailheader 
					WHERE idtransmailheader= :idtransmailheader";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmailheader', $idtransmailheader);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'id' => $rs->getInt('idtransmailheader'),
					'name' => $rs->getString('name'),
					'contenttxt' => $rs->getString('contenttxt'),
					'contenthtml' => $rs->getString('contenthtml')
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_NO_TEMPLATE_HEADER'));
		}
		return $Data;
	}

	public function editTransMailHeader ($submittedData, $idtransmailheader)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateTransMailHeader($submittedData, $idtransmailheader);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_HEADER_EDIT'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateTransMailHeader ($submittedData, $idtransmailheader)
	{
		$sql = 'UPDATE transmailheader 
					SET name= :name, 
						contenttxt= :contenttxt, contenthtml= :contenthtml, editid= :editid, viewid= :viewid
					WHERE idtransmailheader= :idtransmailheader';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idtransmailheader', $idtransmailheader);
		$stmt->setString('name', $submittedData['name']);
		$stmt->setString('contenttxt', $submittedData['contenttxt']);
		$stmt->setString('contenthtml', $submittedData['contenthtml']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$viewid = Helper::getViewId();
		if ($viewid > 0){
			$stmt->setInt('viewid', Helper::getViewId());
		}
		else{
			$stmt->setNull('viewid');
		}
		try{
			$stmt->executeUpdate();
			return true;
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANS_MAIL_HEADER_UPDATE'), 112, $e->getMessage());
		}
	}
}