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
 * $Id: sitemaps.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class sitemapsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('sitemaps', Array(
			'idsitemaps' => Array(
				'source' => 'S.idsitemaps'
			),
			'name' => Array(
				'source' => 'S.name'
			),
			'pingserver' => Array(
				'source' => 'S.pingserver'
			),
			'lastupdate' => Array(
				'source' => 'S.lastupdate'
			),
			'adddate' => Array(
				'source' => 'S.adddate'
			)
		));
		$datagrid->setFrom('
				sitemaps S
			');
		$datagrid->setGroupBy('
				S.idsitemaps
			');
	}

	public function doAJAXRefreshSitemaps ($datagridId, $id)
	{
		try{
			$this->refreshSitemaps($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_REFRESH_SITEMAPS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function refreshSitemaps ($id)
	{
		
		$sql = 'SELECT REPLACE(pingserver,\'{SITEMAP_URL}\',CONCAT(:url,\'sitemap/\',:id)) as pingserver
					FROM sitemaps 
					WHERE idsitemaps = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('url', URL);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $rs->getString('pingserver'));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_exec($ch);
			curl_close($ch);
		
		}
		
		$sql = 'UPDATE sitemaps SET lastupdate = now() WHERE idsitemaps = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	
	}

	public function getTopicForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('topic', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSitemapsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteSitemaps ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteSitemaps'
		), $this->getName());
	}

	public function deleteSitemaps ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idsitemaps' => $id
			), $this->getName(), 'deleteSitemaps');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getSitemapsView ($id)
	{
		$sql = "SELECT 
						name, 
						publishforcategories, 
						priorityforcategories, 
						publishforproducts, 
						priorityforproducts, 
						publishforproducers, 
						priorityforproducers, 
						publishfornews, 
						priorityfornews, 
						publishforpages, 
						priorityforpages, 
						pingserver
					FROM sitemaps
					WHERE idsitemaps =:id
					";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'pingserver' => $rs->getString('pingserver'),
				'publishforcategories' => $rs->getInt('publishforcategories'),
				'priorityforcategories' => $rs->getString('priorityforcategories'),
				'publishforproducts' => $rs->getInt('publishforproducts'),
				'priorityforproducts' => $rs->getString('priorityforproducts'),
				'publishforproducers' => $rs->getInt('publishforproducers'),
				'priorityforproducers' => $rs->getString('priorityforproducers'),
				'publishfornews' => $rs->getInt('publishfornews'),
				'priorityfornews' => $rs->getString('priorityfornews'),
				'publishforpages' => $rs->getInt('publishforpages'),
				'priorityforpages' => $rs->getString('priorityforpages')
			);
		}
		return $Data;
	}

	public function addSitemaps ($Data)
	{
		$sql = 'INSERT INTO sitemaps SET
						name = :name, 
						publishforcategories = :publishforcategories, 
						priorityforcategories = :priorityforcategories, 
						publishforproducts = :publishforproducts, 
						priorityforproducts = :priorityforproducts, 
						publishforproducers = :publishforproducers, 
						priorityforproducers = :priorityforproducers, 
						publishfornews = :publishfornews, 
						priorityfornews = :priorityfornews, 
						publishforpages = :publishforpages, 
						priorityforpages = :priorityforpages, 
						addid = :addid, 
						pingserver = :pingserver';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('pingserver', $Data['pingserver']);
		
		if (isset($Data['publishforcategories'])){
			$stmt->setInt('publishforcategories', 1);
			$stmt->setString('priorityforcategories', $Data['priorityforcategories']);
		}
		else{
			$stmt->setInt('publishforcategories', 0);
			$stmt->setNull('priorityforcategories');
		}
		
		if (isset($Data['publishforproducts'])){
			$stmt->setInt('publishforproducts', 1);
			$stmt->setInt('priorityforproducts', $Data['priorityforproducts']);
		}
		else{
			$stmt->setInt('publishforproducts', 0);
			$stmt->setNull('priorityforproducts');
		}
		
		if (isset($Data['publishforproducers'])){
			$stmt->setInt('publishforproducers', 1);
			$stmt->setString('priorityforproducers', $Data['priorityforproducers']);
		}
		else{
			$stmt->setInt('publishforproducers', 0);
			$stmt->setNull('priorityforproducers');
		}
		
		if (isset($Data['publishfornews'])){
			$stmt->setInt('publishfornews', 1);
			$stmt->setString('priorityfornews', $Data['priorityfornews']);
		}
		else{
			$stmt->setInt('publishfornews', 0);
			$stmt->setNull('priorityfornews');
		}
		
		if (isset($Data['publishforpages'])){
			$stmt->setInt('publishforpages', 1);
			$stmt->setString('priorityforpages', $Data['priorityforpages']);
		}
		else{
			$stmt->setInt('publishforpages', 0);
			$stmt->setNull('priorityforpages');
		}
		
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SITEMAPS_ADD'), 4, $e->getMessage());
		}
		
		return true;
	}

	public function editSitemaps ($Data, $id)
	{
		$sql = 'UPDATE sitemaps SET
						name = :name, 
						publishforcategories = :publishforcategories, 
						priorityforcategories = :priorityforcategories, 
						publishforproducts = :publishforproducts, 
						priorityforproducts = :priorityforproducts, 
						publishforproducers = :publishforproducers, 
						priorityforproducers = :priorityforproducers, 
						publishfornews = :publishfornews, 
						priorityfornews = :priorityfornews, 
						publishforpages = :publishforpages, 
						priorityforpages = :priorityforpages, 
						editid = :editid, 
						pingserver = :pingserver
					WHERE idsitemaps = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('pingserver', $Data['pingserver']);
		
		if (isset($Data['publishforcategories'])){
			$stmt->setInt('publishforcategories', 1);
			$stmt->setString('priorityforcategories', $Data['priorityforcategories']);
		}
		else{
			$stmt->setInt('publishforcategories', 0);
			$stmt->setNull('priorityforcategories');
		}
		
		if (isset($Data['publishforproducts'])){
			$stmt->setInt('publishforproducts', 1);
			$stmt->setString('priorityforproducts', $Data['priorityforproducts']);
		}
		else{
			$stmt->setInt('publishforproducts', 0);
			$stmt->setNull('priorityforproducts');
		}
		
		if (isset($Data['publishforproducers'])){
			$stmt->setInt('publishforproducers', 1);
			$stmt->setString('priorityforproducers', $Data['priorityforproducers']);
		}
		else{
			$stmt->setInt('publishforproducers', 0);
			$stmt->setNull('priorityforproducers');
		}
		
		if (isset($Data['publishfornews'])){
			$stmt->setInt('publishfornews', 1);
			$stmt->setString('priorityfornews', $Data['priorityfornews']);
		}
		else{
			$stmt->setInt('publishfornews', 0);
			$stmt->setNull('priorityfornews');
		}
		
		if (isset($Data['publishforpages'])){
			$stmt->setInt('publishforpages', 1);
			$stmt->setString('priorityforpages', $Data['priorityforpages']);
		}
		else{
			$stmt->setInt('publishforpages', 0);
			$stmt->setNull('priorityforpages');
		}
		
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SITEMAPS_EDIT'), 4, $e->getMessage());
		}
		
		return true;
	}

	public function getLayersAll ()
	{
		$sql = "SELECT V.idview AS id, V.name
					FROM view V
					GROUP BY V.idview";
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