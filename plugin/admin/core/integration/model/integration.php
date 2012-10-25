<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o
 * licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 682 $
 * $Author: gekosale $
 * $Date: 2012-04-25 22:06:17 +0200 (Śr, 25 kwi 2012) $
 * $Id: integration.php 682 2012-04-25 20:06:17Z gekosale $
 */

class IntegrationModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('integration', Array(
			'idintegration' => Array(
				'source' => 'idintegration'
			),
			'name' => Array(
				'source' => 'name',
				'prepareForAutosuggest' => true
			),
			'symbol' => Array(
				'source' => 'symbol'
			)
		));
		
		$datagrid->setFrom('
			integration
		');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getControllerForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('controller', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getIntegrationForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getIntegrationModelById ($id)
	{
		$sql = 'SELECT symbol FROM integration WHERE idintegration = :idintegration';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idintegration', $id);
		$rs = $stmt->executeQuery();
		$controller = null;
		while ($rs->next()){
			$controller = $rs->getString('symbol');
		}
		return $controller;
	}

	public function getIntegrationModelAll ()
	{
		$sql = 'SELECT name, symbol FROM integration ';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'model' => $rs->getString('symbol')
			);
		}
		return $Data;
	}

	public function getIntegrationView ($id)
	{
		$sql = "SELECT * FROM integrationwhitelist
				WHERE integrationid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array( 
			'whitelist' => Array()
		);
		while ($rs->next()){
			$Data['whitelist']['ip'][] = $rs->getString('ipaddress');
		}
		return $Data;
	}

	public function editIntegration ($Data, $id)
	{
		$sql = 'DELETE FROM integrationwhitelist WHERE integrationid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['ip'] as $key => $value){
			$sql = 'INSERT INTO integrationwhitelist (integrationid, ipaddress)
					VALUES (:integrationid, :ipaddress)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('integrationid', $id);
			$stmt->setString('ipaddress', $value);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_POLL_ANSWERS_ADD'), 1225, $e->getMessage());
			}
		}
		return $Data;
	}
}