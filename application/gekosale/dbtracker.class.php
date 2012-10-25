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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: dbtracker.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class DBTracker
{
	
	protected $db;
	protected $Data = Array();
	protected $registry;
	protected $xmlDir = 'mysqltracker/mysql.xml';
	protected $queries;
	protected $mainElements = Array(
		'table',
		'column',
		'status',
		'controller'
	);
	protected $conditionElements = Array(
		'column'
	);
	protected $loop = 0;
	protected $sqlSelect = 'SELECT :columns FROM :table';
	protected $sqlPrepareSelects = Array();
	protected $sqlPrepareDelete = Array();
	protected $sqlWhereCondition = ' WHERE :condition';
	protected $sqlDelete = 'DELETE FROM :table WHERE :condition';
	protected $columns = Array();
	protected $columnsNames = Array();
	protected $currentTable = '';
	protected $prefixNames = Array(
		'column' => 'column_',
		'value' => 'value_',
		'table' => 'table_',
		'conditioncolumn' => 'cond_',
		'conditionvalue' => 'condval_'
	);
	protected $statementObjectName = 'MySQLiPreparedStatement';
	protected $resultSetObjectName = 'MySQLiResultSet';
	protected $eventLevels = Array(
		'RELATED',
		'NOTICE',
		'WARNING',
		'ERROR',
		'NONE'
	);
	protected $eventBreakLvl = 'ERROR';
	protected $errorRecords = Array();
	protected $bindRecordsBreak = 0;
	protected $skipBreak = false;
	protected $autoCommit = true;

	public function __construct (&$registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}

	public function load ($path)
	{
		$xmlParser = new xmlParser();
		$xmlParser->parseFast(ROOTPATH . '/plugin/' . $path . $this->xmlDir);
		$this->registerXajaxMethods();
		$this->queries = $xmlParser->getValue('queries');
	}

	public function getEventLevel ($name)
	{
		if (! in_array($name, $this->eventLevels)){
			throw new Exception('Event error level not exists: ' . $name);
		}
		return $name;
	}

	public function run ($Data, $modelName = 'undefined', $methodName = 'undefined', $errLvl = 'ERROR', $skipBreak = false)
	{
		$this->Data = $Data;
		foreach ($this->queries as $index => $query){
			if (is_array($query)){
				foreach ($query as $record){
					if (isset($record->alias)){
						$this->currentTable = (string) $record->alias;
					}
					else{
						$this->currentTable = (string) $record->table;
					}
					if (isset($this->columns[$this->currentTable])){
						throw new Exception('Table name: ' . $this->currentTable . ' already exists. Please use alias definition.');
					}
					try{
						$this->xmlDebugger($record);
						foreach ($record->column as $column => $row){
							$this->addColumns((array) $row, (string) $this->currentTable);
						}
						if (isset($record->where->condition)){
							foreach ($record->where->condition as $condition){
								$this->addColumns((array) $condition->column, (string) $this->currentTable, (array) $condition, 1);
							}
						}
						if (isset($record->alias)){
							$this->setRealColumnName($this->currentTable, (string) $record->table);
						}
						$this->setEventLevel((string) $record->status, $this->currentTable, (string) $record->errormsg);
						$this->setController($this->currentTable, (string) $record->controller);
						$this->setInputData($this->currentTable, $this->Data);
						$this->setModelName($this->currentTable, $modelName);
						$this->setMethodName($this->currentTable, $methodName);
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
					++ $this->loop;
				}
			}
			else{
				if (isset($query->alias)){
					$this->currentTable = (string) $query->alias;
				}
				else{
					$this->currentTable = (string) $query->table;
				}
				if (isset($this->columns[$this->currentTable])){
					throw new Exception('Table name: ' . $this->currentTable . ' already exists. Please use alias definition.');
				}
				try{
					$this->xmlDebugger($query);
					foreach ($query->column as $column => $row){
						$this->addColumns((array) $row, (string) $query->table);
					}
					if (isset($query->where->condition)){
						foreach ($query->where->condition as $condition){
							$this->addColumns((array) $condition->column, (string) $query->table, (array) $condition, 1);
						}
					}
					if (isset($record->alias)){
						$this->setRealColumnName($this->currentTable, (string) $record->table);
					}
					$this->setEventLevel((string) $query->status, $this->currentTable, (string) $query->errormsg);
					$this->setController($this->currentTable, (string) $query->controller);
					$this->setInputData($this->currentTable, $this->Data);
					$this->setModelName($this->currentTable, $modelName);
					$this->setMethodName($this->currentTable, $methodName);
				}
				catch (Exception $e){
					throw new Exception($e->getMessage());
				}
			}
		
		}
		try{
			$this->prepareSQLQuery();
			$this->statementExecution();
			$this->setErrorLevel($errLvl);
			$this->setSkipBreak($skipBreak);
			if ($this->bindRecordsBreak == 0){
				$this->recordChecker();
			}
			else{
				$this->flushErrorRecords();
			}
			return $this->autoDelete();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function generateErrorRecords ()
	{
		$this->recordChecker();
	}

	protected function recordChecker ()
	{
		foreach ($this->columns as $index => $column){
			if ($column['status'] == $this->eventBreakLvl && isset($column['recordCount']) && $column['recordCount'] > 0 && $this->skipBreak == false){
				$this->errorRecords[$index] = $column;
			}
		}
		$this->saveErrorRecords();
	}

	protected function saveErrorRecords ()
	{
		if (($tmp = $this->registry->session->getActiveDeleteRecords()) !== NULL){
			if (($pIndex = $this->compareErrorRecordsByInput($tmp, $this->errorRecords)) === false){
				$tmp[] = $this->errorRecords;
				$this->registry->session->setActiveDeleteRecords($tmp);
			}
			else{
				$tmp[$pIndex] = $this->errorRecords;
				$this->registry->session->setActiveDeleteRecords($tmp);
			}
		}
		else{
			$tmp[] = $this->errorRecords;
			$this->registry->session->setActiveDeleteRecords($tmp);
		}
	}

	protected function setInputData ($table, $Data)
	{
		$this->columns[$table]['input'] = $Data;
	}

	protected function setMethodName ($table, $methodName)
	{
		$this->columns[$table]['method'] = $methodName;
	}

	protected function setModelName ($table, $modelName)
	{
		$this->columns[$table]['model'] = $modelName;
	}

	public static function remove ($Array)
	{
		$Data = App::getRegistry()->session->getActiveDeleteRecords();
		if (! is_array($Data))
			return false;
		foreach ($Data as $table => $column){
			if (count(array_diff_assoc($column['input'], $Array)) == 0){
				DBTracker::flushErrorRecordsByTableName($table);
			}
		}
		if (count($Data) == 0){
			DBTracker::flushErrorRecords();
		}
	}

	public static function flushErrorRecords ($Data = NULL)
	{
		if ($Data == NULL){
			App::getRegistry()->session->unsetActiveDeleteRecords();
		}
		else{
			$storedErrors = App::getRegistry()->session->getActiveDeleteRecords();
			if ($storedErrors !== NULL){
				foreach ($storedErrors as $index => $recordBlock){
					foreach ($recordBlock as $table => $record){
						if (count(array_diff($record['input'], $Data)) == 0){
							unset($storedErrors[$index]);
						}
					}
				}
			}
			App::getRegistry()->session->setActiveDeleteRecords($storedErrors);
		}
	}

	public static function flushErrorRecordsByTableName ($name)
	{
		$Data = App::getRegistry()->session->getActiveDeleteRecords();
		if (isset($Data[$name])){
			unset($Data[$name]);
		}
		else{
			throw new Exception('Record table not exists');
		}
		if (count($Data) == 0){
			DBTracker::flushErrorRecords();
		}
		else{
			App::getRegistry()->session->setActiveDeleteRecords($Data);
		}
	}

	protected function getDependRecords ()
	{
		return $this->errorRecords;
	}

	protected function xmlDebugger ($record)
	{
		$diff = array_keys((array) $record);
		if (count(($tmp = array_diff($this->mainElements, $diff))) > 0){
			throw new Exception('DBTracker: params not set properly in query loop: ' . $this->loop . ' params: ' . implode(',', $tmp));
		}
		if (in_array('where', $diff)){
			$diff_condition = array_keys((array) $record->where->condition);
		}
	}

	protected function flush ()
	{
		$this->sqlSelect = 'SELECT :columns FROM :table';
		$this->sqlWhereCondition = ' WHERE :condition';
		$this->sqlDelete = 'DELETE FROM :table WHERE :condition';
	}

	protected function addColumns ($column, $table, $params = Array(), $condition = 0)
	{
		try{
			if ($this->columnNameChecker($column[0], $table) == false){
				$this->columns[$table]['columns'][] = Array(
					'name' => $column[0],
					'type' => $this->getAttribute($column, 'type'),
					'encryption' => $this->getEncryptionStatus($column)
				);
			}
			if ($condition == 1){
				$record = Array(
					'name' => $column[0],
					'type' => $this->getAttribute($column, 'type'),
					'encryption' => $this->getEncryptionStatus($column)
				);
				if (count($params) > 0){
					unset($params['column']);
					$record = array_merge($record, $params);
				}
				$this->columns[$table]['condition'][] = $record;
			}
		}
		catch (Exception $e){
			throw new Exception('DBTracker: No value type set for column: ' . $column[0]);
		}
	}

	protected function setEventLevel ($errLvl, $table, $errormsg = 'undefined')
	{
		$this->columns[$table]['status'] = $this->getEventLevel($errLvl);
		$this->columns[$table]['errorMsg'] = $errormsg;
	}

	protected function setRealColumnName ($table, $realColumnName)
	{
		$this->columns[$table]['realColumnName'] = $realColumnName;
	}

	protected function getRealColumnName ($Data)
	{
		if (isset($Data['realColumnName'])){
			return $Data['realColumnName'];
		}
		else{
			return false;
		}
	
	}

	protected function setController ($table, $controller)
	{
		$this->columns[$table]['controller'] = $controller;
	}

	protected function getColumns ()
	{
		return $this->columns;
	}

	protected function getAttribute ($column, $attr)
	{
		if (! isset($column['@attributes'][$attr])){
			throw new Exception('No attribute set.');
		}
		return $column['@attributes'][$attr];
	}

	protected function getEncryptionStatus ($column)
	{
		try{
			return $this->getAttribute($column, 'encryption');
		}
		catch (Exception $e){
			return false;
		}
	}

	protected function prepareSQLQuery ()
	{
		$sql = $this->sqlSelect;
		array_walk($this->columns, Array(
			$this,
			'getColumnNames'
		));
		reset($this->columns);
		foreach ($this->columnsNames as $sort){
			$query = str_replace(':columns', implode(',', $sort['columns']), $sql);
			if (isset($sort['condition'])){
				$query .= $this->sqlWhereCondition;
				$query = str_replace(':condition', implode(' AND ', $sort['condition']), $query);
			}
			if (($alias = $this->getRealColumnName(current($this->columns))) === false){
				$query = str_replace('table', $this->getPrefixByName('table') . key($this->columns), $query);
			}
			else{
				$query = str_replace('table', $this->getPrefixByName('table') . $alias, $query);
			}
			$this->sqlPrepareSelects[] = $query;
			next($this->columns);
		}
	}

	protected function getColumnNames ($elem, $key)
	{
		$tmp = Array();
		if (isset($elem['columns'])){
			foreach ($elem['columns'] as $row){
				if (isset($row['encryption']) && $row['encryption'] == 1 && $this->registry->session->getActiveEncryptionKeyValue() != NULL){
					$tmp['columns'][] = 'AES_DECRYPT(:' . $this->getPrefixByName('column') . $row['name'] . ',\'' . $this->registry->session->getActiveEncryptionKeyValue() . '\') AS ' . $row['name'];
				}
				else{
					$tmp['columns'][] = ':' . $this->getPrefixByName('column') . $row['name'];
				}
			}
		}
		reset($tmp);
		if (isset($elem['condition'])){
			foreach ($elem['condition'] as $row){
				$tmp['condition'][] = ':' . $this->getPrefixByName('conditioncolumn') . $row['name'] . ' IN (:' . $this->getPrefixByName('conditionvalue') . $row['name'] . ')';
			}
		}
		else{
			next($tmp);
		}
		$this->columnsNames[] = $tmp;
	}

	protected function statementExecution ()
	{
		if (count($this->sqlPrepareSelects) == 0){
			throw new Exception('No queries for PrepareStatement found.');
		}
		foreach ($this->columns as $table => $block){
			$stmt = $this->db->prepareStatement(current($this->sqlPrepareSelects));
			foreach ($block['columns'] as $column){
				$this->bindColumnsToStatement($column, $stmt);
			}
			foreach ($block['condition'] as $condition){
				$this->bindConditionColumnsToStatement($condition, $stmt);
			}
			if (($alias = $this->getRealColumnName($block)) === false){
				$this->bindTableToStatement($table, $stmt);
			}
			else{
				$this->bindTableToStatement($alias, $stmt);
			}
			try{
				if ($this->bindRecordsBreak == 1){
					$this->bindRecordsBreak = 0;
				}
				else{
					$this->resultSetParser($table, $stmt->executeQuery());
					$this->reletedRecordExistsCheck($table);
				}
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
			next($this->sqlPrepareSelects);
		}
	}

	protected function reletedRecordExistsCheck ($table)
	{
		if ($this->columns[$table]['status'] == $this->getEventLevel('RELATED') && $this->columns[$table]['recordCount'] == 0){
			throw new Exception('No records to delete');
		}
	}

	public function resultSetParser ($table, $rs)
	{
		if (! is_object($rs) || get_class($rs) != $this->getResultSetName()){
			throw new Exception('Wrong ResultSet object');
		}
		if (! isset($this->columns[$table])){
			throw new Exception('No record for table: ' . $table);
		}
		$this->columns[$table]['recordCount'] = $rs->getRecordCount();
		$Data = $rs->getAllRows();
		foreach ($this->columns[$table]['columns'] as $index => $column){
			$this->columns[$table]['columns'][$index]['values'] = $this->getResultRecords($column['name'], $column['type'], $Data);
		}
		unset($tmp);
	}

	public function getPrefixByName ($name)
	{
		if (isset($this->prefixNames[$name])){
			return $this->prefixNames[$name];
		}
		throw new Exception('No prefix set for name: ' . $name);
	}

	public function getPrepareStatementName ()
	{
		return $this->statementObjectName;
	}

	public function getResultSetName ()
	{
		return $this->resultSetObjectName;
	}

	protected function bindColumnsToStatement ($column, $stmt)
	{
		if (! is_object($stmt) || get_class($stmt) != $this->getPrepareStatementName()){
			throw new Exception('Wrong PreparedStatement object');
		}
		$stmt->setColumn($this->getPrefixByName('column') . $column['name'], $column['name']);
	}

	protected function bindTableToStatement ($tablename, $stmt)
	{
		if (! is_object($stmt) || get_class($stmt) != $this->getPrepareStatementName()){
			throw new Exception('Wrong PreparedStatement object');
		}
		$stmt->setColumn($this->getPrefixByName('table') . $tablename, $tablename);
	}

	protected function bindConditionColumnsToStatement ($column, $stmt)
	{
		if (! is_object($stmt) || get_class($stmt) != $this->getPrepareStatementName()){
			throw new Exception('Wrong PreparedStatement object');
		}
		$stmt->setColumn($this->getPrefixByName('conditioncolumn') . $column['name'], $column['name']);
		if (! isset($column['value'])){
			if (! isset($this->Data[$column['name']])){
				throw new Exception('No value for condition column: ' . $column['name']);
			}
			else{
				$this->bindValue($column['name'], $column['type'], $this->Data[$column['name']], $stmt);
			}
		}
		else{
			if (isset($column['bind'])){
				$recordValues = $this->getValues($column['value'], $column['bind']);
				if (! isset($recordValues['values']) || count($recordValues['values']) == 0){
					$this->bindRecordsBreak = 1;
				}
				else{
					$this->bindValue($column['name'], $recordValues['type'], $recordValues['values'], $stmt);
				}
			}
			else{
				$this->bindValue($column['name'], $column['type'], $column['value'], $stmt);
			}
		}
	}

	protected function getValues ($recordName, $tableName)
	{
		if (! isset($this->columns[$tableName])){
			throw new Exception('No data for table: ' . $tableName);
		}
		foreach ($this->columns[$tableName]['columns'] as $record){
			if ($record['name'] == $recordName){
				return $record;
			}
			throw new Exception('No value for column: ' . $recordName . ' in table: ' . $tableName);
		}
	}

	protected function bindValue ($name, $type, $value = 0, $stmt)
	{
		if (! is_object($stmt) || get_class($stmt) != $this->getPrepareStatementName()){
			throw new Exception('Wrong PreparedStatement object');
		}
		switch ($type) {
			case 'int':
			case 'integer':
				if (! is_array($value)){
					$stmt->setInt($this->getPrefixByName('conditionvalue') . $name, $value);
				}
				else{
					$stmt->setINInt($this->getPrefixByName('conditionvalue') . $name, $value);
				}
				break;
			case 'string':
			case 'varchar':
				if (! is_array($value)){
					$stmt->setString($this->getPrefixByName('conditionvalue') . $name, $value);
				}
				else{
					$stmt->setINString($this->getPrefixByName('conditionvalue') . $name, $value);
				}
				break;
			default:
				throw new Exception('Wrong value type set for column: ' . $name);
		}
	}

	protected function getResultRecords ($name, $type, $Data)
	{
		$tmp = Array();
		foreach ($Data as $index => $record){
			$tmp[] = $record[$name];
		}
		return $tmp;
	}

	protected function columnNameChecker ($name, $table = NULL)
	{
		if ($table != NULL){
			if (! isset($this->columns[$table])){
				return false;
			}
			foreach ($this->columns[$table]['columns'] as $column){
				if ($column['name'] == $name){
					return true;
				}
			}
		}
		else{
			foreach ($this->columns as $index => $columns){
				foreach ($columns as $record){
					if (! is_array($record)){
						return false;
					}
					foreach ($record as $column){
						if ($column['name'] == $name){
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	public function setErrorLevel ($errLvl)
	{
		if (! in_array($errLvl, $this->eventLevels)){
			throw new Exception('No such error level: ' . $errLvl);
		}
		$this->eventBreakLvl = $errLvl;
	}

	public function setSkipBreak ($skipBreak)
	{
		$this->skipBreak = $skipBreak;
	}

	protected function autoDelete ()
	{
		if (count($this->errorRecords) > 0){
			return false;
		}
		$loop = 0;
		foreach ($this->columns as $table => $columns){
			$query = $this->sqlDelete;
			$query = str_replace('table', $this->getPrefixByName('table') . $table, $query);
			foreach ($this->columnsNames[$loop]['condition'] as $condition){
				$query = str_replace(':condition', implode(' AND ', $this->columnsNames[$loop]['condition']), $query);
				$this->sqlPrepareDelete[$table] = $query;
			}
			++ $loop;
		}
		try{
			$this->bindConditionColumnsToDelete();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$this->columns = Array();
		$this->columnsNames = Array();
		return true;
	}

	protected function bindConditionColumnsToDelete ()
	{
		$this->db->setAutoCommit(false);
		if (count($this->sqlPrepareDelete) == 0){
			throw new Exception('No delete queries generated.');
		}
		foreach (array_reverse($this->sqlPrepareDelete) as $table => $query){
			$stmt = $this->db->prepareStatement($query);
			foreach (array_reverse($this->columns[$table]['columns']) as $record){
				if (isset($record['values']) && count($record['values']) > 0){
					$record['values'] = array_unique($record['values']);
					$stmt->setColumn($this->getPrefixByName('table') . $table, $table);
					$stmt->setColumn($this->getPrefixByName('conditioncolumn') . $record['name'], $record['name']);
					$stmt->setINInt($this->getPrefixByName('conditionvalue') . $record['name'], $record['values']);
					try{
						$deletedRows = $stmt->executeUpdate();
					}
					catch (Exception $e){
						$this->db->rollback();
						$this->db->setAutoCommit(true);
						throw new Exception($e->getMessage());
					}
				}
			}
		}
		if ($this->getAutoCommit() === true){
			$this->db->commit();
			$this->db->setAutoCommit(true);
		}
		return $deletedRows;
	}

	public static function getDeleteRecords ($Data = NULL)
	{
		$Saved = App::getRegistry()->session->getActiveDeleteRecords();
		if ($Saved === NULL){
			return false;
		}
		if ($Data === NULL){
			return $Saved;
		}
		foreach ($Saved as $index => $recordBlock){
			foreach ($recordBlock as $record){
				if (count(array_diff($record['input'], $Data)) == 0){
					return $recordBlock;
				}
			}
		}
	}

	protected function compareErrorRecordsByInput ($oldRecords, $Data)
	{
		foreach ($oldRecords as $index => $recordBlock){
			foreach ($Data as $table => $dataRecordBlock){
				if (isset($recordBlock[$table]['input'])){
					if (count(array_diff($recordBlock[$table]['input'], $dataRecordBlock['input'])) == 0){
						return $index;
					}
				}
			}
		}
		return false;
	}

	public static function deleteRecordValue ($table, $rowname, $value)
	{
		$Data = DBTracker::getDeleteRecords();
		$values = Array();
		if (count($Data) == 0){
			throw new Exception('No records to delete');
		}
		if (isset($Data[$table]['columns'])){
			foreach ($Data[$table]['columns'] as $index => $record){
				if (in_array($rowname, $record)){
					if (($rowNum = array_search($value, $record['values'])) !== false){
						$values[] = $rowNum;
					}
				}
			}
			if (count($values) > 0){
				foreach ($Data[$table]['columns'] as $index => $record){
					foreach ($values as $key){
						unset($Data[$table]['columns'][$index]['values'][$key]);
					}
				}
			}
			else{
				throw new Exception('Record not found');
			}
		}
		App::getRegistry()->session->setActiveDeleteRecords($Data);
	}

	public function registerXajaxMethods ()
	{
		$this->registry->xajax->registerFunction(array(
			'updateConflictResolver',
			App::getModel('conflictresolver'),
			'update'
		));
		$this->registry->xajax->registerFunction(array(
			'retryConflictResolver',
			App::getModel('conflictresolver'),
			'retry'
		));
		$this->registry->xajax->registerFunction(array(
			'cancelConflictResolver',
			App::getModel('conflictresolver'),
			'cancel'
		));
	}

	public function getAutoCommit ()
	{
		return $this->autoCommit;
	}

	public function enableAutoCommit ()
	{
		$this->autoCommit = true;
	}

	public function disableAutoCommit ()
	{
		$this->autoCommit = false;
	}
}