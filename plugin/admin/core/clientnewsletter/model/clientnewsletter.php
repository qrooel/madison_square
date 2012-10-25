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
 * $Id: clientnewsletter.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class clientnewsletterModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('clientnewsletter', Array(
			'idclientnewsletter' => Array(
				'source' => 'idclientnewsletter'
			),
			'email' => Array(
				'source' => 'email'
			),
			'adddate' => Array(
				'source' => 'adddate'
			),
			'active' => Array(
				'source' => 'IF( `active` = 1, \'TXT_ACTIVE\', \'TXT_INACTIVE\')',
				'processLanguage' => true
			)
		));
		$datagrid->setFrom('
				clientnewsletter 
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getClientNewsletterForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteClientNewsletter ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteClientNewsletter'
		), $this->getName());
	}

	public function deleteClientNewsletter ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idclientnewsletter' => $id
			), $this->getName(), 'deleteClientNewsletter');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnableClientNewsletter ($datagridId, $id)
	{
		try{
			$this->enableClientNewsletter($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableClientNewsletter ($datagridId, $id)
	{
		try{
			$this->disableClientNewsletter($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_USER')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableClientNewsletter ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception('ERR_CAN_NOT_DISABLE_YOURSELF');
		}
		$sql = 'UPDATE clientnewsletter SET active = 0 WHERE idclientnewsletter = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableClientNewsletter ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception($this->registry->core->getMessage('ERR_CAN_NOT_ENABLE_YOURSELF'));
		}
		$sql = 'UPDATE clientnewsletter SET active = 1 WHERE idclientnewsletter = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getClientsToSelect ()
	{
		$Data = $this->getClientsNewsletterAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['email'];
		}
		return $tmp;
	}

	public function getClientsNewsletterAll ()
	{
		$sql = 'SELECT idclientnewsletter as id, email FROM clientnewsletter WHERE active=1';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'email' => $rs->getString('email')
			);
		}
		return $Data;
	}

	public function getClientAll ()
	{
		$rs = $this->registry->db->executeQuery('SELECT idclientnewsletter AS id, email FROM clientnewsletter');
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'email' => $rs->getString('email')
			);
		}
		return $Data;
	}

	public function getClientAlltoSelect ()
	{
		$Data = $this->getClientAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['email'];
		}
		return $tmp;
	}

	public function getGroupsToSelect ()
	{
		$Data = $this->getClientGroupsAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}

	public function getClientGroupsAll ()
	{
		$sql = 'SELECT idclientgroupnewsletter as id, CG.name 
					FROM clientgroupnewsletter
					LEFT JOIN clientgroup CG ON CG.idclientgroup = clientgroupid
					GROUP BY name';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}
}