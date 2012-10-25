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
 * $Id: autosuggest.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class AutosuggestModel extends Model
{
	
	protected $db;
	
	protected $queryColumns;
	protected $queryColumnsOptions;
	protected $queryTable;
	protected $queryFrom;
	protected $queryWhere;
	protected $queryAdditionalWhere;
	protected $queryOrder;
	protected $queryGroupBy;
	protected $encryptionKey;
	protected $languageId;
	protected $sqlParams;
	protected $viewId;
	protected $viewIds;

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->db = $this->registry->db;
		$this->queryColumns = Array();
		$this->queryColumnsOptions = Array();
		$this->queryTable = '';
		$this->queryFrom = '';
		$this->queryWhere = '';
		$this->queryAdditionalWhere = '';
		$this->queryGroupBy = '';
		$this->encryptionKey = $this->registry->session->getActiveEncryptionKeyValue();
		$this->languageId = Helper::getLanguageId();
		$this->sqlParams = Array();
		$this->viewId = (! is_null(Helper::getViewId())) ? Helper::getViewId() : 0;
		$this->viewIds = Helper::getViewIds();
	}

	public function setTableData ($table, $columns)
	{
		$this->queryTable = $table;
		$this->queryColumnsOptions = $columns;
		$this->queryColumns = array_keys($columns);
	}

	public function setEncryptionKey ($encryptionKey)
	{
		$this->encryptionKey = $encryptionKey;
	}

	public function setLanguageId ($languageId)
	{
		$this->languageId = $languageId;
	}

	public function setSQLParams ($params)
	{
		$this->sqlParams = $params;
	}

	public function setViewId ($viewId)
	{
		$this->viewId = $viewId;
	}

	public function setViewIds ($viewIds)
	{
		$this->viewIds = $viewIds;
	}

	public function setFrom ($from)
	{
		$this->queryFrom = $from;
	}

	public function setWhere ($where)
	{
		$this->queryWhere = $where;
	}

	public function setAdditionalWhere ($additionalWhere)
	{
		$this->queryAdditionalWhere = $additionalWhere;
	}

	public function setOrder ($order)
	{
		$this->queryOrder = $order;
	}

	public function setGroupBy ($groupBy)
	{
		$this->queryGroupBy = $groupBy;
	}

	public function getSuggestions ($request, $processFunction)
	{
		if (empty($this->queryFrom)){
			$this->queryFrom = $this->queryTable;
		}
		$rows = $this->getSelectedRows($request);
		$rowData = $this->processRows($rows, $request);
		$objResponse = new xajaxResponse();
		$objResponse->script($processFunction . '({id:"' . (isset($request['id']) ? $request['id'] : '') . '",q:\'' . $request['q'] . '\',s:[' . implode(',', $rowData) . ']});');
		return $objResponse;
	}

	protected function getSelectedRows ($request)
	{
		list($idColumn, $groupBy, $orderBy, $orderDir, $conditionString, $conditions, $additionalConditionString, $havingString, $having) = $this->getQueryData($request);
		$sql = "SELECT DISTINCT {$this->getColumnsString()} FROM {$this->queryFrom}{$conditionString}{$additionalConditionString}{$groupBy}{$havingString} ORDER BY {$orderBy} {$orderDir}";
		$stmt = $this->db->prepareStatement($sql);
		$stmt->setOffset(isset($request['starting_from']) ? $request['starting_from'] : 0);
		$stmt->setLimit(isset($request['limit']) ? $request['limit'] : (int) $request['n']);
		foreach ($conditions as $i => &$part){
			$stmt->set('value' . $i, $part['value']);
		}
		if ($this->encryptionKey != ''){
			$stmt->set('encryptionkey', $this->encryptionKey);
		}
		$stmt->set('languageid', $this->languageId);
		
		foreach ($this->sqlParams as $key => $val){
			
			if (is_array($val)){
				$stmt->setINInt($key, $val);
			}
			else{
				$stmt->set($key, $val);
			}
		}
		
		if ($this->viewId > 0){
			$stmt->setInt('viewid', $this->viewId);
		}
		else{
			$stmt->setNull('viewid');
		}
		
		$stmt->setINInt('viewids', $this->viewIds);
		
		$rs = $stmt->executeQuery();
		$rows = Array();
		$rows = $rs->getAllRows();
		return $rows;
	}

	protected function getQueryData ($request)
	{
		$idColumn = isset($this->queryColumnsOptions[$this->queryColumns[0]]['source']) ? $this->queryColumnsOptions[$this->queryColumns[0]]['source'] : $this->queryColumns[0];
		$groupBy = ! empty($this->queryGroupBy) ? ' GROUP BY ' . $this->queryGroupBy : '';
		$orderBy = $this->queryOrder;
		$orderDir = '';
		$conditionsString = ' WHERE ' . str_replace(':query', ':value0', $this->queryWhere);
		$conditions = Array(
			Array(
				'value' => $request['q'] . '%'
			)
		);
		$additionalConditionString = $this->getAdditionalConditionsString();
		$havingString = '';
		$having = Array();
		return Array(
			$idColumn,
			$groupBy,
			$orderBy,
			$orderDir,
			$conditionsString,
			$conditions,
			$additionalConditionString,
			$havingString,
			$having
		);
	}

	protected function getColumnsString ($limit = 0)
	{
		$string = '';
		foreach ($this->queryColumnsOptions as $name => $options){
			if (isset($options['source'])){
				if (isset($options['encrypted']) and $options['encrypted']){
					$string .= 'AES_DECRYPT(' . $options['source'] . ', :encryptionkey) AS ' . $name;
				}
				else{
					$string .= $options['source'] . ' AS ' . $name;
				}
			}
			else{
				if (isset($options['encrypted']) and $options['encrypted']){
					$string .= 'AES_DECRYPT(' . $name . ', :encryptionkey) AS ' . $name;
				}
				else{
					$string .= $name;
				}
			}
			$string .= ', ';
			if (-- $limit == 0)
				break;
		}
		return substr($string, 0, - 2);
	}

	protected function getAdditionalConditionsString ()
	{
		$condition = '';
		if ($this->queryAdditionalWhere != ''){
			$condition .= ' AND ' . $this->queryAdditionalWhere;
		}
		return $condition;
	}

	protected function processRows ($rows, $request)
	{
		$rowData = Array();
		foreach ($rows as $row){
			$columns = Array();
			foreach ($row as $param => $value){
				if (isset($this->queryColumnsOptions[$param]) and isset($this->queryColumnsOptions[$param]['processLanguage']) and $this->queryColumnsOptions[$param]['processLanguage']){
					$value = $this->registry->core->getMessage($value);
				}
				if ($param == 'name'){
					$value = '<strong>' . substr($value, 0, strlen($request['q'])) . '</strong>' . substr($value, strlen($request['q']));
				}
				$columns[] = $param . ': "' . addslashes($value) . '"';
			}
			$rowData[] = '{' . implode(', ', $columns) . '}';
		}
		return $rowData;
	}

}