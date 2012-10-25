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
 * $Id: datagrid.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class DatagridModel extends Model
{
	
	protected $db;
	
	protected $queryColumns;
	protected $queryColumnsOptions;
	protected $queryTable;
	protected $queryFrom;
	protected $queryGroupBy;
	protected $queryAdditionalWhere;
	protected $encryptionKey;
	protected $languageId;
	protected $sqlParams;
	protected $viewId;
	protected $viewIds;
	protected $autosuggests;
	protected $warnings;
	protected $additionalRows;

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->db = $this->registry->db;
		$this->queryColumns = Array();
		$this->queryColumnsOptions = Array();
		$this->queryTable = '';
		$this->queryFrom = '';
		$this->queryGroupBy = '';
		$this->queryAdditionalWhere = '';
		$this->autosuggests = Array();
		$this->encryptionKey = $this->registry->session->getActiveEncryptionKeyValue();
		$this->languageId = Helper::getLanguageId();
		$this->sqlParams = Array();
		$this->viewId = (! is_null(Helper::getViewId())) ? Helper::getViewId() : 0;
		$this->viewIds = Helper::getViewIds();
		$this->warnings = Array();
	}

	public function setAdditionalRows ($rows)
	{
		$this->additionalRows = $rows;
	}

	public function getFilterSuggestions ($field, $request, $processFunction)
	{
		if (! isset($this->autosuggests[$field])){
			$objResponse = new xajaxResponse();
			$objResponse->script('
					' . $processFunction . '({
						data_id: ""
					});
				');
			return $objResponse;
		}
		return $this->autosuggests[$field]->getSuggestions($request, $processFunction);
	}

	public function getFilterData ()
	{
		$filters = Array();
		foreach ($this->queryColumnsOptions as $name => $options){
			if (isset($options['prepareForSelect']) and $options['prepareForSelect']){
				$possibilities = Array(
					"{id: '', caption: ''}"
				);
				$query = 'SELECT DISTINCT ';
				if (isset($options['source'])){
					$query .= $options['source'];
				}
				else{
					$query .= $name;
				}
				$query .= ' AS possibility FROM ' . $this->queryFrom . ' ORDER BY possibility';
				$stmt = $this->db->prepareStatement($query);
				$stmt->set('languageid', $this->languageId);
				
				if ($this->viewId > 0){
					$stmt->setInt('viewid', $this->viewId);
				}
				else{
					$stmt->setNull('viewid');
				}
				
				$stmt->setINInt('viewids', $this->viewIds);
				
				foreach ($this->sqlParams as $key => $val){
					
					if (is_array($val)){
						$stmt->setINInt($key, $val);
					}
					else{
						$stmt->set($key, $val);
					}
				}
				
				$rs = $stmt->executeQuery();
				while ($rs->next()){
					$caption = addslashes($rs->getString('possibility'));
					if (isset($options['processLanguage']) and $options['processLanguage']){
						$caption = addslashes($this->registry->core->getMessage($caption));
					}
					$id = addslashes($rs->getString('possibility'));
					$possibilities[] = "{id: '{$id}', caption: '{$caption}'}";
				}
				$filters[$name] = implode(', ', $possibilities);
			}
			else 
				if (isset($options['prepareForTree']) and $options['prepareForTree']){
					$filters[$name] = json_encode($options['first_level']);
				}
		}
		return $filters;
	}

	public function setTableData ($table, $columns)
	{
		$this->queryTable = $table;
		$this->queryColumnsOptions = $columns;
		$this->queryColumns = array_keys($columns);
		$this->processFilters();
	}

	public function processFilters ()
	{
		foreach ($this->queryColumnsOptions as $name => $options){
			if (isset($options['prepareForAutosuggest']) and $options['prepareForAutosuggest']){
				$source = (isset($options['source']) and $options['source']) ? $options['source'] : $name;
				if (isset($options['encrypted']) and $options['encrypted']){
					$source = 'AES_DECRYPT(' . $source . ', :encryptionkey)';
				}
				$this->autosuggests[$name] = App::getModel('autosuggest/autosuggest');
				$this->autosuggests[$name]->setTableData($this->queryTable, Array(
					'name' => Array(
						'source' => $source
					)
				));
				$this->autosuggests[$name]->setFrom($this->queryFrom);
				$this->autosuggests[$name]->setWhere('
						' . $source . ' LIKE :query
					');
				$this->autosuggests[$name]->setOrder('
						name ASC
					');
				
				$this->autosuggests[$name]->setGroupBy($this->queryGroupBy);
				$this->autosuggests[$name]->setAdditionalWhere($this->queryAdditionalWhere);
				$this->autosuggests[$name]->setEncryptionKey($this->encryptionKey);
				$this->autosuggests[$name]->setLanguageId($this->languageId);
				$this->autosuggests[$name]->setSQLParams($this->sqlParams);
				$this->autosuggests[$name]->setViewId($this->viewId);
				$this->autosuggests[$name]->setViewIds($this->viewIds);
			}
		}
	}

	public function setEncryptionKey ($encryptionKey)
	{
		$this->encryptionKey = $encryptionKey;
		$this->processFilters();
	}

	public function setLanguageId ($languageId)
	{
		$this->languageId = $languageId;
		$this->processFilters();
	}

	public function setSQLParams ($params)
	{
		$this->sqlParams = $params;
		$this->processFilters();
	}

	public function setViewId ($viewId)
	{
		$this->viewId = $viewId;
		$this->processFilters();
	}

	public function setViewIds ($viewIds)
	{
		$this->viewIds = $viewIds;
		$this->processFilters();
	}

	public function setFrom ($from)
	{
		$this->queryFrom = $from;
		$this->processFilters();
	}

	public function setGroupBy ($groupBy)
	{
		$this->queryGroupBy = $groupBy;
		$this->processFilters();
	}

	public function setAdditionalWhere ($additionalWhere)
	{
		$this->queryAdditionalWhere = $additionalWhere;
		$this->processFilters();
	}

	public function refresh ($datagridId)
	{
		$objResponse = new xajaxResponse();
		$objResponse->script('' . 'try {' . 'GF_Datagrid.ReturnInstance(' . (int) $datagridId . ').LoadData();' . '}' . 'catch (xException) {' . 'GF_Debug.HandleException(xException);' . '}' . '');
		return $objResponse;
	}

	public function getData ($request, $processFunction)
	{
		$this->warnings = Array();
		try{
			$objResponse = new xajaxResponse();
			if (empty($this->queryFrom)){
				$this->queryFrom = $this->queryTable;
			}
			try{
				$rows = $this->getSelectedRows($request);
			}
			catch (Exception $e){
				$rows = Array();
				$this->warnings[] = $e->getMessage();
			}
			if (isset($this->additionalRows) and is_array($this->additionalRows) and count($this->additionalRows)){
				$rows = array_merge($this->additionalRows, $rows);
			}
			$rowData = $this->processRows($rows);
			$objResponse->script('' . '' . $processFunction . '({' . 'data_id: "' . (isset($request['id']) ? $request['id'] : '') . '",' . 'rows_num: ' . count($rows) . ',' . 'starting_from: ' . (isset($request['starting_from']) ? $request['starting_from'] : 0) . ',' . 'total: ' . $this->getTotalRows($request) . ',' . 'filtered: ' . $this->getFilteredRows($request) . ',' . 'rows: [' . implode(', ', $rowData) . ']' . '});' . '');
			foreach ($this->warnings as $warning){
				$objResponse->script("GWarning('" . App::getRegistry()->core->getMessage('ERR_PROBLEM_DURING_AJAX_EXECUTION') . "', '" . preg_replace('/(\n|\r)+/', '\n', nl2br(addslashes($warning))) . "');");
			}
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GError('" . App::getRegistry()->core->getMessage('ERR_PROBLEM_DURING_AJAX_EXECUTION') . "', '" . preg_replace('/(\n|\r)+/', '\n', nl2br(addslashes($e->getMessage()))) . "');");
		}
		return $objResponse;
	}

	public function deleteRow ($datagridId, $rowId, $deleteFunction, $controllerName, $flushCache = Array())
	{
		$objResponse = new xajaxResponse();
		if ($this->registry->right->checkDeletePermission($controllerName) === FALSE){
			$objResponse->alert('Nie masz uprawnień');
			return $objResponse;
		}
		
		try{
			if (is_array($deleteFunction)){
				$state = $deleteFunction[0]->$deleteFunction[1]($rowId);
			}
			else{
				$state = $deleteFunction($rowId);
			}
			if ($state){
				foreach ($flushCache as $cache){
					Cache::destroyObject($cache);
				}
				$objResponse->script("try { GF_Datagrid.ReturnInstance({$datagridId}).LoadData(); GF_Datagrid.ReturnInstance({$datagridId}).ClearSelection(); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
			}
			else{
				$conflicts = end(DBTracker::getDeleteRecords());
				$conflictsString = $this->registry->core->getMessage('TXT_UNABLE_TO_DELETE_RECORD_DESC');
				$conflictsString .= '<ul style="list-style: none;">';
				foreach ($conflicts as $tableName => $table){
					$conflictsString .= '<li>';
					$conflictsString .= '<h3>' . $this->registry->core->getMessage($table['errorMsg']) . '</h3>';
					$conflictsString .= '<ul style="list-style: none;">';
					for ($i = 0; $i < $table['recordCount']; $i ++){
						$conflictsString .= '<li>';
						foreach ($table['columns'] as $column){
							$conflictsString .= $column['values'][$i] . ' ';
						}
						$conflictsString .= '</li>';
					}
					$conflictsString .= '</ul>';
					$conflictsString .= '</li>';
				}
				$conflictsString .= '</ul>';
				$objResponse->script("try { GF_Alert('{$this->registry->core->getMessage('TXT_UNABLE_TO_DELETE_RECORD')}', '{$conflictsString}'); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
			}
		}
		catch (Exception $e){
			$objResponse->script("GWarning('" . App::getRegistry()->core->getMessage('ERR_PROBLEM_DURING_AJAX_EXECUTION') . "', '" . preg_replace('/(\n|\r)+/', '\n', nl2br(addslashes($e->getMessage()))) . "');");
		}
		return $objResponse;
	}

	protected function getSelectedRows ($request)
	{
		list($idColumn, $groupBy, $orderBy, $orderDir, $conditionString, $conditions, $additionalConditionString, $havingString, $having) = $this->getQueryData($request);
		$sql = "SELECT {$this->getColumnsString()} FROM {$this->queryFrom}{$conditionString}{$additionalConditionString}{$groupBy}{$havingString} ORDER BY {$orderBy} {$orderDir}";
		$stmt = $this->db->prepareStatement($sql);
		$stmt->setOffset(isset($request['starting_from']) ? $request['starting_from'] : 0);
		$stmt->setLimit(isset($request['limit']) ? $request['limit'] : 10);
		foreach ($conditions as $i => &$part){
			if (is_array($part['value'])){
				foreach ($part['value'] as $j => &$subpart){
					$stmt->set('value' . $i . '_' . $j, $subpart);
				}
			}
			else{
				$stmt->set('value' . $i, $part['value']);
			}
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

	protected function getTotalRows ($request)
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
		return $rs->getInt('total');
	}

	protected function getFilteredRows ($request)
	{
		list($idColumn, $groupBy, $orderBy, $orderDir, $conditionString, $conditions, $additionalConditionString, $havingString, $having) = $this->getQueryData($request);
		if (empty($groupBy)){
			$sqlTotal = "SELECT count({$idColumn}) AS total FROM {$this->queryFrom}{$conditionString}{$additionalConditionString}{$groupBy}{$havingString}";
		}
		else{
			$sqlTotal = "SELECT count(*) as total FROM (SELECT count({$idColumn}) AS total FROM {$this->queryFrom}{$conditionString}{$additionalConditionString}{$groupBy}{$havingString}) AS a";
		}
		//			
		$stmtTotal = $this->db->prepareStatement($sqlTotal);
		foreach ($conditions as $i => &$part){
			if (is_array($part['value'])){
				foreach ($part['value'] as $j => &$subpart){
					$stmtTotal->set('value' . $i . '_' . $j, $subpart);
				}
			}
			else{
				$stmtTotal->set('value' . $i, $part['value']);
			}
		}
		if ($this->encryptionKey != ''){
			$stmtTotal->set('encryptionkey', $this->encryptionKey);
		}
		$stmtTotal->set('languageid', $this->languageId);
		
		foreach ($this->sqlParams as $key => $val){
			
			if (is_array($val)){
				$stmtTotal->setINInt($key, $val);
			}
			else{
				$stmtTotal->set($key, $val);
			}
		}
		
		if ($this->viewId > 0){
			$stmtTotal->setInt('viewid', $this->viewId);
		}
		else{
			$stmtTotal->setNull('viewid');
		}
		$stmtTotal->setINInt('viewids', $this->viewIds);
		
		$rs = $stmtTotal->executeQuery();
		$totalRows = 0;
		while ($rs->next()){
			$totalRows = $rs->getInt('total');
		}
		return $totalRows;
	}

	protected function processRows ($rows)
	{
		$rowData = Array();
		foreach ($rows as $row){
			$columns = Array();
			foreach ($row as $param => $value){
				if (isset($this->queryColumnsOptions[$param]) and isset($this->queryColumnsOptions[$param]['processLanguage']) and $this->queryColumnsOptions[$param]['processLanguage']){
					$value = $this->registry->core->getMessage($value);
				}
				elseif (isset($this->queryColumnsOptions[$param]) and isset($this->queryColumnsOptions[$param]['processFunction']) and $this->queryColumnsOptions[$param]['processFunction']){
					try{
						$value = call_user_func($this->queryColumnsOptions[$param]['processFunction'], $value);
					}
					catch (Exception $e){
						if (! in_array($e->getMessage(), $this->warnings)){
							$this->warnings[] = $e->getMessage();
						}
					}
				}
				$columns[] = $param . ': "' . addslashes($value) . '"';
			}
			$rowData[] = '{' . implode(', ', $columns) . '}';
		}
		return $rowData;
	}

	protected function getQueryData ($request)
	{
		$idColumn = isset($this->queryColumnsOptions[$this->queryColumns[0]]['source']) ? $this->queryColumnsOptions[$this->queryColumns[0]]['source'] : $this->queryColumns[0];
		$groupBy = ! empty($this->queryGroupBy) ? ' GROUP BY ' . $this->queryGroupBy : '';
		$orderBy = (isset($request['order_by']) and in_array($request['order_by'], $this->queryColumns)) ? $request['order_by'] : $this->queryColumns[0];
		$orderDir = (isset($request['order_dir']) and ($request['order_dir'] == 'desc')) ? 'DESC' : 'ASC';
		$conditionsString = '';
		$conditions = Array();
		if (isset($request['where']) and is_array($request['where'])){
			$conditions = $request['where'];
			$conditionsString = $this->getConditionsString($conditions);
		}
		$additionalConditionString = $this->getAdditionalConditionsString($conditionsString);
		$havingString = '';
		$having = Array();
		if (isset($request['where']) and is_array($request['where'])){
			$having = $request['where'];
			$havingString = $this->getHavingString($conditions);
		}
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
		return 'SQL_CALC_FOUND_ROWS '.substr($string, 0, - 2);
	}

	protected function getConditionsString ($conditions)
	{
		$condition = '';
		$parts = Array();
		foreach ($conditions as $i => &$part){
			if (! in_array($part['column'], $this->queryColumns)){
				unset($part);
				continue;
			}
			if (isset($this->queryColumnsOptions[$part['column']]['filter']) and ($this->queryColumnsOptions[$part['column']]['filter'] == 'having')){
				unset($part);
				continue;
			}
			$suffix = '';
			switch ($part['operator']) {
				case 'NE':
					$operator = '!=';
					break;
				case 'LE':
					$operator = '<=';
					break;
				case 'GE':
					$operator = '>=';
					break;
				case 'LIKE':
					$operator = 'LIKE';
					break;
				case 'IN':
					$operator = '=';
					break;
				default:
					$operator = '=';
			}
			if (isset($this->queryColumnsOptions[$part['column']]['source'])){
				if (isset($this->queryColumnsOptions[$part['column']]['encrypted']) and $this->queryColumnsOptions[$part['column']]['encrypted']){
					$columnSource = 'AES_DECRYPT(' . $this->queryColumnsOptions[$part['column']]['source'] . ', :encryptionkey)';
				}
				else{
					$columnSource = $this->queryColumnsOptions[$part['column']]['source'];
				}
			}
			else{
				$columnSource = $part['column'];
			}
			if (is_array($part['value'])){
				$subparts = Array();
				foreach ($part['value'] as $j => &$subpart){
					$subparts[] = '(' . $columnSource . ' ' . $operator . ' :value' . $i . '_' . $j . $suffix . ')';
				}
				if (count($subparts)){
					$parts[] = '(' . implode(' OR ', $subparts) . ')';
				}
				else{
					$parts[] = '(0)';
				}
			}
			else{
				$parts[] = '(' . $columnSource . ' ' . $operator . ' :value' . $i . $suffix . ')';
			}
		}
		if (count($parts) && ($parts[0] != '()')){
			$condition = ' WHERE ' . implode(' AND ', $parts);
		}
		return $condition;
	}

	protected function getAdditionalConditionsString ($conditionsString)
	{
		$condition = '';
		if ($this->queryAdditionalWhere != ''){
			if ($conditionsString != ''){
				$condition .= ' AND ' . $this->queryAdditionalWhere;
			}
			else{
				$condition = ' WHERE ' . $this->queryAdditionalWhere;
			}
		}
		
		return $condition;
	}

	protected function getHavingString ($conditions)
	{
		$condition = '';
		$parts = Array();
		foreach ($conditions as $i => &$part){
			if (! in_array($part['column'], $this->queryColumns)){
				unset($part);
				continue;
			}
			if (! isset($this->queryColumnsOptions[$part['column']]['filter']) or ($this->queryColumnsOptions[$part['column']]['filter'] != 'having')){
				unset($part);
				continue;
			}
			switch ($part['operator']) {
				case 'LE':
					$operator = '<=';
					break;
				case 'GE':
					$operator = '>=';
					break;
				case 'LIKE':
					$operator = 'LIKE';
					break;
				case 'IN':
					$operator = '=';
					break;
				default:
					$operator = '=';
			}
			
			if (isset($this->queryColumnsOptions[$part['column']]['source'])){
				if (isset($this->queryColumnsOptions[$part['column']]['encrypted']) and $this->queryColumnsOptions[$part['column']]['encrypted']){
					$columnSource = 'AES_DECRYPT(' . $this->queryColumnsOptions[$part['column']]['source'] . ', :encryptionkey)';
				}
				else{
					$columnSource = $this->queryColumnsOptions[$part['column']]['source'];
				}
			}
			else{
				$columnSource = $part['column'];
			}
			if (is_array($part['value'])){
				$subparts = Array();
				foreach ($part['value'] as $j => &$subpart){
					$subparts[] = '(' . $columnSource . ' ' . $operator . ' :value' . $i . '_' . $j . ')';
				}
				$parts[] = '(' . implode(' OR ', $subparts) . ')';
			}
			else{
				$parts[] = '(' . $columnSource . ' ' . $operator . ' :value' . $i . ')';
			}
		}
		if (count($parts))
			$condition = ' HAVING ' . implode(' AND ', $parts);
		return $condition;
	}

}