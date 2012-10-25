<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 652 $
 * $Author: gekosale $
 * $Date: 2012-03-06 22:10:50 +0100 (Wt, 06 mar 2012) $
 * $Id: checkpoint.php 652 2012-03-06 21:10:50Z gekosale $
 */

class checkpointModel extends Model {
	
	protected $_excludeDirectories = Array(
		'cache',
		'logs',
		'backup',
		'sql',
		'.settings',
		'.svn',
		'doc'
	);
	
	protected $_FilesToBackup = Array();
	protected $backupDirectory = 'backup/';
	protected $backupType = '';
	protected $_tarHandler;
	protected $_dbLimit = 100;
	protected $_dbLimitCurrent = 0;
	protected $_fhandler = NULL;

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
		$this->backupDirectory = ROOTPATH . $this->backupDirectory;
		require_once ('Archive/Tar.php');
		@ini_set('memory_limit', '512M');
		@set_time_limit(0);
	}

	protected function systemBackup ($dir, $dateToken) {
		$this->backupType = 'filesystem';
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.tar';
		$this->_tarHandler = new Archive_Tar($backupFile);
		$this->generateFiles();
	}

	protected function databaseBackup ($dir, $dateToken) {
		$this->backupType = 'database';
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.sql';
		try{
			$this->makeFileHandler($backupFile);
		}
		catch (Exception $e){
			throw $e;
		}
		$_ts = $this->getDatabaseInformationSchema(Db::getDatabaseName());
		while ($_ts->next()){
			$schema = $this->getTableStructureSchema($_ts->getString('table_name'));
			$this->writeToFile($schema[0]['create table'] . ";\n");
			$this->getTableData($_ts->getString('table_name'));
		}
		$this->destroyFileHandler();
	}

	protected function makeFileHandler ($filePath) {
		if (($this->_fhandler = fopen($filePath, 'a+')) === FALSE){
			throw new Exception('Nie można otworzyć pliku do zapisu');
		}
		return $this->_fhandler;
	}

	protected function writeToFile ($content) {
		if ($this->_fhandler === NULL){
			throw new Exception('Nie otworzono pliku');
		}
		flock($this->_fhandler, LOCK_EX);
		fwrite($this->_fhandler, $content);
		flock($this->_fhandler, LOCK_UN);
	}

	protected function destroyFileHandler () {
		fclose($this->_fhandler);
		$this->_fhandler = NULL;
	}

	public function getDateToken () {
		return date('dmY_His_U');
	}

	protected function createDirectory ($dateToken, $type) {
		if (! is_dir($this->backupDirectory . DS . $dateToken . '_' . $type)){
			if (mkdir($this->backupDirectory . DS . $dateToken . '_' . $type, 0700) === FALSE){
				throw new Exception('Cannot create checkpoint directory: ' . $this->backupDirectory . DS . $dateToken . '_' . $type);
			}
		}
	}

	public function makeCheckPoint () {
		$dateToken = $this->getDateToken();
		try{
			$this->createDirectory($dateToken);
			$this->systemBackup($this->backupDirectory . DS . $dateToken, $dateToken);
			$this->databaseBackup($this->backupDirectory . DS . $dateToken, $dateToken);
		}
		catch (Exception $e){
			throw $e;
		}
	}

	public function getDatabaseInformationSchema ($databaseName = FALSE) {
		if ($databaseName == FALSE){
			throw new Exception('No database name set');
		}
		$sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = :database';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('database', $databaseName);
		return $stmt->executeQuery();
	}

	public function getTableStructureSchema ($table = FALSE) {
		if ($table == FALSE){
			debug_print_backtrace();
			throw new Exception('No table name set');
		}
		$sql = 'SHOW CREATE TABLE :table';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setTable('table', $table);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	protected function getTableData ($table) {
		
		$sql = 'SELECT * FROM :table';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setTable('table', $table);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		foreach ($Data as $record){
			$keys = array_keys($record);
			$sqlKeys = Array();
			foreach ($keys as $key){
				$sqlKeys[] = '`' . $key . '`';
			}
			
			$sql2 = 'INSERT INTO :table (' . implode(',', $sqlKeys) . ') VALUES (:values)';
			$stmt2 = $this->registry->db->prepareStatement($sql2);
			$stmt2->setTable('table', $table);
			$stmt2->setINString('values', array_values($record));
			$this->writeToFile($stmt2->getSQLDebug(1) . ";\n");
		}
	}

	protected function backupToPhar () {
		$phar = new Phar($this->backupDirectory . 'gekosale_' . date('dmY_His_U') . '.phar', 0, 'gekosale.phar');
		$phar->buildFromDirectory(ROOTPATH);
		$phar->compress(Phar::GZ, '.phar.tar.gz');
	}

	public function restoreFromPhar ($fileName) {
		$phar = new Phar($this->backupDirectory . $fileName);
		$phar->extractTo($this->backupDirectory . DS . 'tmp' . DS);
	}

	protected function checkIfExclude ($fileName) {
		foreach ($this->_excludeDirectories as $dir){
			if (strpos($fileName, DS . $dir) > 0){
				return true;
			}
		}
		return false;
	}

	protected function generateFiles ($path = ROOTPATH) {
		$filesObj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		foreach ($filesObj as $file){
			if ($this->checkIfExclude($file->getPathName())){
				continue;
			}
			$this->_tarHandler->add($file->getPathName());
		}
	}

	public function getCheckPointDirectories () {
		$chk = array_diff(scandir($this->backupDirectory), Array(
			'.',
			'..',
			'.svn',
			'tmp'
		));
		$Dirs = Array();
		foreach ($chk as $dir){
			preg_match('/^(?<day>[0-9]{2})(?<month>[0-9]{2})(?<year>[0-9]{4})_(?<hour>[0-9]{2})(?<minute>[0-9]{2})(?<second>[0-9]{2})_(?<random>[0-9]*)$/', $dir, $matches, PREG_OFFSET_CAPTURE);
			$Dirs[] = $matches;
		}
		return $Dirs;
	}

	public function checkpointsFilesForJS () {
		$Data = $this->getCheckPointDirectories();
		$_JS = '[';
		foreach ($Data as $record){
			$_JS .= '{ date: \'' . date('Y-m-d G:i:s', mktime($record['hour'][0], $record['minute'][0], $record['second'][0], $record['month'][0], $record['day'][0], $record['year'][0])) . '\'},';
		}
		$_JS .= ']';
		return $_JS;
	}

	public function setDatabaseLimit ($limit, $flush = TRUE) {
		$this->_dbLimit = $limit;
		if ($flush !== FALSE){
			$this->flushDbLimitMin();
		}
	}

	public function getDatabaseLimit () {
		return $this->_dbLimit;
	}

	public function flushDbLimitCurrent () {
		$this->_dbLimitCurrent = 0;
	}

	public function getDbLimitCurrent () {
		return $this->_dbLimitCurrent;
	}

	public function setDbLimitCurrent ($current) {
		$this->_dbLimitCurrent = $current;
	}

	public function convertDbLimitCurrent () {
		$this->setDbLimitCurrent($this->getDbLimitCurrent() + $this->getDatabaseLimit());
		return $this->getDbLimitCurrent();
	}

	public function doLoadQuequeSQL () {
		
		$dateToken = $this->getDateToken();
		
		try{
			$this->createDirectory($dateToken, 'database');
		}
		catch (Exception $e){
			throw $e;
		}
		
		$this->registry->session->setActiveBackupDateToken($dateToken . '_database');
		
		$total = 0;
		$sql = 'SELECT table_name 
					FROM information_schema.tables 
					WHERE table_schema = :database AND TABLE_TYPE = :type';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('database', Db::getDatabaseName());
		$stmt->setString('type', 'BASE TABLE');
		$rs = $stmt->executeQuery();
		$Tables = Array();
		while ($rs->next()){
			$Tables[] = $rs->getString('table_name');
		}
		
		$this->registry->session->setActiveTablesForBackup($Tables);
		
		$total = count($Tables);
		
		if ($total > 0){
			return Array(
				'iTotal' => $total,
				'iCompleted' => 0
			);
		}
		else{
			return Array(
				'iTotal' => 0,
				'iCompleted' => 0
			);
		}
	}

	public function doProcessQuequeSQL ($request) {
		
		$dateToken = $this->registry->session->getActiveBackupDateToken();
		$dir = $this->backupDirectory . DS . $dateToken;
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.sql';
		
		try{
			$this->makeFileHandler($backupFile);
		}
		catch (Exception $e){
			throw $e;
		}
		
		$Tables = $this->registry->session->getActiveTablesForBackup();
		
		$chunkSize = intval($request['iChunks']);
		$startFrom = intval($request['iStartFrom']);
		$total = intval($request['iTotal']);
		$i = 0;
		
		while (($i + $startFrom) < $total){
			
			$schema = $this->getTableStructureSchema($Tables[$i + $startFrom]);
			$this->writeToFile($schema[0]['create table'] . ";\n");
			$this->getTableData($Tables[$i + $startFrom]);
			
			if (++ $i >= $chunkSize){
				
				return Array(
					'iStartFrom' => $i + $startFrom,
					'iTable' => $schema
				);
			}
		
		}
		
		return Array(
			'iStartFrom' => $i + $startFrom,
			'bFinished' => true
		);
	}

	public function doSuccessQuequeSQL ($request) {
		
		if ($request['bFinished']){
			$this->registry->session->unsetActiveBackupDateToken();
			$this->registry->session->unsetActiveTablesForBackup();
			return Array(
				'bCompleted' => true
			);
		}
	}

	public function doLoadQuequeFiles () {
		
		$dateToken = $this->getDateToken();
		$dir = $this->backupDirectory . DS . $dateToken;
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.txt';
		
		try{
			$this->createDirectory($dateToken, 'files');
		}
		catch (Exception $e){
			throw $e;
		}
		
		$this->registry->session->setActiveBackupDateToken($dateToken . '_files');
		$total = 0;
		$filesObj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOTPATH));
		
		$Files = Array();
		foreach ($filesObj as $file){
			
			$pathname = $file->getPathName();
			if ($this->checkIfExclude($pathname)){
				continue;
			}
			$Files[] = $pathname;
		}
		Cache::saveObject('checkpoint', $Files, Array(
			Cache::SESSION => 0,
			Cache::FILE => 1
		));
		
		$total = count($Files);
		if ($total > 0){
			return Array(
				'iTotal' => $total,
				'iCompleted' => 0
			);
		}
		else{
			return Array(
				'iTotal' => 0,
				'iCompleted' => 0
			);
		}
	}

	public function doProcessQuequeFiles ($request) {
		
		$dateToken = $this->registry->session->getActiveBackupDateToken();
		$dir = $this->backupDirectory . DS . $dateToken;
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.tar';
		
		$this->_tarHandler = new Archive_Tar($backupFile);
		
		$chunkSize = intval($request['iChunks']);
		$startFrom = intval($request['iStartFrom']);
		$total = intval($request['iTotal']);
		$i = 0;
		$Files = Cache::loadObject('checkpoint');
		while (($i + $startFrom) < $total){
			
			$fileTar = $Files[$i + $startFrom];
			$this->_tarHandler->add($fileTar);
			
			if (++ $i >= $chunkSize){
				
				return Array(
					'iStartFrom' => $i + $startFrom
				);
			}
		
		}
		
		return Array(
			'iStartFrom' => $i + $startFrom,
			'bFinished' => true
		);
	}

	public function doSuccessQuequeFiles ($request) {
		
		if ($request['bFinished']){
			$dateToken = $this->registry->session->getActiveBackupDateToken();
			Cache::destroyObject('files_' . $dateToken);
			$this->registry->session->unsetActiveBackupDateToken();
			return Array(
				'bCompleted' => true
			);
		}
	}
}