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
 * $Id: dataset.php 655 2012-04-24 08:51:44Z gekosale $
 */

class DatasetModel extends Model
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->queryColumns = Array();
		$this->queryFrom = '';
		$this->queryGroupBy = '';
		$this->queryOrderBy = '';
		$this->queryLimit = 100;
		$this->queryOffset = 0;
		$this->pagination = 100;
		$this->currentPage = 0;
		$this->sqlParams = Array();
		$this->encryptionKey = $this->registry->session->getActiveEncryptionKeyValue();
		$this->languageId = Helper::getLanguageId();
		$this->viewId = (! is_null(Helper::getViewId())) ? Helper::getViewId() : 0;
		$this->queryAdditionalWhere = '';
		$this->DataSet = Array();
		$this->cacheEnabled = Array(
			'enabled' => false,
			'lifetime' => 3600,
			'cacheid' => null
		);
		$this->layerData = $this->registry->loader->getCurrentLayer();
	}

	public function flushCache ()
	{
		$dir = ROOTPATH . 'serialization' . DS;
		$file = 'Cache.Dataset_';
		foreach (glob($dir . $file . '*') as $key => $fn){
			if (is_file($fn)){
				@unlink($fn);
			}
		}
	}

	public function processPrice ($price)
	{
		if ($price < 0){
			return ($this->layerData['negativepreffix'] . number_format(abs($price), $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . $this->layerData['negativesuffix']);
		}
		return ($this->layerData['positivepreffix'] . number_format($price, $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . $this->layerData['positivesuffix']);
	}

	public function setTableData ($columns)
	{
		$this->queryColumns = $columns;
	}

	public function setSQLParams ($params)
	{
		$this->sqlParams = $params;
	}

	public function setFrom ($from)
	{
		$this->queryFrom = $from;
	}

	public function setLimit ($limit)
	{
		$this->queryLimit = $limit;
	}

	public function setPagination ($items)
	{
		$this->pagination = $items;
	}

	public function setCurrentPage ($current)
	{
		if ($current){
			$this->currentPage = $current;
		}
		else{
			$this->currentPage = 1;
		}
	}

	public function setOffset ($offset)
	{
		$this->queryOffset = $offset;
	}

	public function setViewId ($viewId)
	{
		$this->viewId = $viewId;
	}

	public function setOrderBy ($default, $order)
	{
		if ($order){
			$this->queryOrderBy = $order;
		}
		else{
			$this->queryOrderBy = $default;
		}
		$this->DataSet['orderBy'] = $this->queryOrderBy;
	}

	public function setOrderDir ($default, $dir)
	{
		if ($dir){
			$this->queryOrderDir = $dir;
		}
		else{
			$this->queryOrderDir = $default;
		}
		$this->DataSet['orderDir'] = $this->queryOrderDir;
	}

	public function setGroupBy ($groupby)
	{
		$this->queryGroupBy = $groupby;
	}

	public function setAdditionalWhere ($additionalWhere)
	{
		$this->queryAdditionalWhere = $additionalWhere;
	}

	public function setCache ($cache)
	{
		$this->cache = $cache;
	}

	protected function processRows ($rows)
	{
		
		while ($row = current($rows)){
			foreach ($row as $param => $value){
				if (isset($this->queryColumns[$param]['processPrice'])){
					$rows[key($rows)][$param] = $this->processPrice($value);
				}
				if (isset($this->queryColumns[$param]['processFunction'])){
					$rows[key($rows)][$param] = call_user_func($this->queryColumns[$param]['processFunction'], $value);
				}
			}
			next($rows);
		}
		$this->DataSet['rows'] = $rows;
	}

	public function getTotalRecords ()
	{
		$sql = "SELECT FOUND_ROWS() as total";
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException('ERR_DATASET_GET_TOTAL', 12, $e->getMessage());
		}
		$rs->first();
		$this->DataSet['total'] = $rs->getInt('total');
	
	}

	public function getData ()
	{
		
		$this->queryLimit = $this->pagination;
		$this->queryOffset = $this->currentPage * $this->pagination - $this->pagination;
		
		$bFilteredOrderBy = false;
		foreach ($this->queryColumns as $column => $options){
			if($this->queryOrderBy == $column || $this->queryOrderBy == 'random'){
				$bFilteredOrderBy = true;
			}
			if (isset($options['encrypted']) && ($options['encrypted']) && ($this->encryptionKey != '')){
				$columns[] = "AES_DECRYPT({$options['source']}, :encryptionkey) AS {$column}";
			}
			else{
				$columns[] = "{$options['source']} AS {$column}";
			}
		}
		if($bFilteredOrderBy == false){
			throw new Exception('Column not found.');
		}
		$columns[0] = 'SQL_CALC_FOUND_ROWS ' . $columns[0];
		$sqlColumns = implode(",\n", $columns);
		$sqlFrom = $this->queryFrom;
		$sqlGroupBy = $this->queryGroupBy;
		
		$selectString = "SELECT {$sqlColumns}";
		$fromString = " FROM {$sqlFrom}";
		$whereString = "";
		if ($this->queryAdditionalWhere != ''){
			$whereString = ' WHERE ' . $this->queryAdditionalWhere;
		}
		$groupString = " GROUP BY {$sqlGroupBy}";
		$sql = $selectString . $fromString . $whereString . $groupString;
		$stmt = $this->registry->db->prepareStatement($sql);
		if($this->queryOrderBy == 'random'){
			$stmt->setOrderby('RAND()');
			$stmt->setLimit($this->pagination);
		}else{
			$stmt->setOrderby($this->queryOrderBy);
			$stmt->setOrderdir($this->queryOrderDir);
			$stmt->setLimit($this->pagination);
			$stmt->setOffset($this->queryOffset);
		}
		$stmt->setInt('languageid', $this->languageId);
		if ($this->encryptionKey != ''){
			$stmt->set('encryptionkey', $this->encryptionKey);
		}
		foreach ($this->sqlParams as $key => $val){
			
			if (is_array($val)){
				if(isset($val[0]) && is_numeric($val[0])){
					$stmt->setINInt($key,$val);
				}elseif(isset($val[0]) && is_string($val[0])){
					$stmt->setINString($key, $val);
				}else{
					$stmt->setINInt($key, Array(0));
				}
				
			}
			else{
				if (is_int($val)){
					$stmt->setInt($key, $val);
				}
				elseif (is_null($val)){
					$stmt->setNull($key, $val);
				}
				elseif (is_float($val)){
					$stmt->setFloat($key, $val);
				}
				elseif (is_string($val)){
					$stmt->setString($key, $val);
				}
				else{
					$stmt->set($key, $val);
				}
			}
		}
		
		if ($this->viewId > 0){
			$stmt->setInt('viewid', $this->viewId);
		}
		else{
			$stmt->setNull('viewid');
		}
		$stmt->setInt('clientgroupid', $this->registry->session->getActiveClientGroupid());
		$stmt->setString('today', date("Y-m-d"));
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$cacheid = md5(base64_encode($stmt->getSQLDebug(2) . json_encode($this->sqlParams)) . $this->pagination . $this->queryOffset . $this->queryOrderBy . $this->queryOrderDir);
		if (($this->DataSet = Cache::loadObject('dataset_' . $cacheid)) === FALSE){
			try{
				$rs = $stmt->executeQuery();
			}
			catch (Exception $e){
				throw new FrontendException('ERR_DATASET_GET_DATA', 12, $e->getMessage());
			}
			$rows = Array();
			$rows = $rs->getAllRows();
			$this->getTotalRecords();
			$this->processRows($rows);
//			Cache::saveObject('dataset_' . $cacheid, $this->DataSet, Array(
//				Cache::SESSION => 0,
//				Cache::FILE => 1
//			));
		}
		
		$pages = ceil($this->DataSet['total'] / $this->pagination);
		if ($pages == 0){
			$this->DataSet['totalPages'] = range(1, 1, 1);
			$this->DataSet['activePage'] = 1;
			$this->DataSet['lastPage'] = 1;
			$this->DataSet['previousPage'] = 1;
			$this->DataSet['nextPage'] = 1;
		}
		else{
			$this->DataSet['totalPages'] = range(1, $pages, 1);
			$this->DataSet['activePage'] = $this->currentPage;
			$this->DataSet['lastPage'] = $pages;
			$this->DataSet['previousPage'] = $this->currentPage - 1;
			$this->DataSet['nextPage'] = $this->currentPage + 1;
		}
	
	}

	public function getDatasetRecords ()
	{
		$this->getData();
		return $this->DataSet;
	}

}