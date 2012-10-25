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
 * $Id: productstatus.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class productstatusModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('productstatus', Array(
			'idproductstatus' => Array(
				'source' => 'PS.idproductstatus'
			),
			'name' => Array(
				'source' => 'PS.name'
			),
			'adddate' => Array(
				'source' => 'PS.adddate'
			)
		));
		$datagrid->setFrom('
			`productstatus` PS
		');
		
		$datagrid->setGroupBy('
			PS.idproductstatus
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

	public function getProductstatusForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getProductstatusView ($id)
	{
		$sql = "SELECT
					idproductstatus AS id, 
					name
				FROM productstatus
				WHERE idproductstatus =:id";
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
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCTSTATUS_NO_EXIST'));
		}
		return $Data;
	}

	public function getProductstatusAll ($clean = true)
	{
		$sql = "SELECT
					idproductstatus AS id, name
				FROM productstatus
				ORDER BY name ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if($clean){
			$Data[0] = $this->registry->core->getMessage('TXT_CLEAR');
		}
		while ($rs->next()){
			$Data[$rs->getInt('id')] = $rs->getString('name');
		
		}
		return $Data;
	}

	public function doAJAXDeleteProductStatus ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteProductStatus'
		), $this->getName());
	}

	public function deleteProductStatus ($id)
	{
		App::getRegistry()->session->unsetActiveDeleteRecords();
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idproductstatus' => $id
			), $this->getName(), 'deleteProductStatus');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewProductStatus ($Data)
	{
		$sql = 'INSERT INTO productstatus (name,addid) VALUES (:name,:addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_ADD'), 11, $e->getMessage());
		}
	
	}

	public function editProductStatus ($Data, $id)
	{
		$sql = 'UPDATE productstatus set name = :name WHERE idproductstatus = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_ORDERSTATUS_ADD'), 11, $e->getMessage());
		}
	
	}
}