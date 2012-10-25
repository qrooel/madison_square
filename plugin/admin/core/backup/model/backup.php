<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 * 
 * $Revision: 650 $
 * $Author: gekosale $
 * $Date: 2012-02-21 22:18:00 +0100 (Wt, 21 lut 2012) $
 * $Id: backup.php 650 2012-02-21 21:18:00Z gekosale $ 
 */

class BackupModel extends Model
{
	
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
	protected $_tarHandler;
	protected $_dbLimit = 100;
	protected $_dbLimitCurrent = 0;
	protected $_fhandler = NULL;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
		$this->backupDirectory = ROOTPATH . $this->backupDirectory;
		require_once ('Archive/Tar.php');
		@ini_set('memory_limit', '512M');
		@set_time_limit(0);
	}

	protected function systemBackup ($dir, $dateToken)
	{
		$backupFile = $dir . DS . 'gekosale_' . $dateToken . '.tar';
		$this->_tarHandler = new Archive_Tar($backupFile);
		$this->generateFiles();
	}

	protected function databaseBackup ($dir, $dateToken)
	{
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

	protected function makeFileHandler ($filePath)
	{
		if (($this->_fhandler = fopen($filePath, 'w+')) === FALSE){
			throw new Exception('Nie można otworzyć pliku do zapisu');
		}
		return $this->_fhandler;
	}

	protected function writeToFile ($content)
	{
		if ($this->_fhandler === NULL){
			throw new Exception('Nie otworzono pliku');
		}
		flock($this->_fhandler, LOCK_EX);
		fwrite($this->_fhandler, $content);
		flock($this->_fhandler, LOCK_UN);
	}

	protected function destroyFileHandler ()
	{
		fclose($this->_fhandler);
		$this->_fhandler = NULL;
	}

	public function getDateToken ()
	{
		return date('dmY_His_U');
	}

	protected function createDirectory ($dateToken)
	{
		if (! is_dir($this->backupDirectory . DS . $dateToken)){
			if (mkdir($this->backupDirectory . DS . $dateToken, 0700) === FALSE){
				throw new Exception('Cannot create checkpoint directory: ' . $this->backupDirectory . DS . $dateToken);
			}
		}
	}

	public function makeCheckPoint ()
	{
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

	public function getDatabaseInformationSchema ($databaseName = FALSE)
	{
		if ($databaseName == FALSE){
			throw new Exception('No database name set');
		}
		$sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = :database';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('database', $databaseName);
		return $stmt->executeQuery();
	}

	public function getTableStructureSchema ($table = FALSE)
	{
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

	protected function getTableData ($table)
	{
		$sql = 'SELECT count(*) AS rownum FROM :table';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setTable('table', $table);
		$rs = $stmt->executeQuery();
		$rs->first();
		$__recordCount = $rs->getInt('rownum');
		while ($this->getDbLimitCurrent() <= $__recordCount){
			$sql = 'SELECT * FROM :table LIMIT :limitMin, :limitMax';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setTable('table', $table);
			$stmt->setInt('limitMin', $this->getDbLimitCurrent());
			$stmt->setInt('limitMax', $this->getDatabaseLimit());
			$rs = $stmt->executeQuery();
			$Data = Array();
			foreach ($rs->getAllRows() as $record){
				$sql2 = 'INSERT INTO :table (:columns) VALUES (:values)';
				$stmt2 = $this->registry->db->prepareStatement($sql2);
				$stmt2->setTable('table', $table);
				$keys = array_keys($record);
				$sqlKeys = Array();
				foreach ($keys as $key){
					$sqlKeys[] = '`' . $key . '`';
				}
				$stmt2->setString('columns', implode(',', $sqlKeys));
				$stmt2->setINString('values', array_values($record));
				$this->writeToFile($stmt2->getSQLDebug(1) . ";\n");
			}
			$this->convertDbLimitCurrent();
			unset($rs);
			$Data = Array();
		}
		$this->flushDbLimitCurrent();
	}

	protected function backupToPhar ()
	{
		$phar = new Phar($this->backupDirectory . 'gekosale_' . date('dmY_His_U') . '.phar', 0, 'gekosale.phar');
		$phar->buildFromDirectory(ROOTPATH);
		$phar->compress(Phar::GZ, '.phar.tar.gz');
	}

	public function restoreFromPhar ($fileName)
	{
		$phar = new Phar($this->backupDirectory . $fileName);
		$phar->extractTo($this->backupDirectory . DS . 'tmp' . DS);
	}

	protected function checkIfExclude ($fileName)
	{
		foreach ($this->_excludeDirectories as $dir){
			if (strpos($fileName, DS . $dir) > 0){
				return true;
			}
		}
		return false;
	}

	protected function generateFiles ($path = ROOTPATH)
	{
		$filesObj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		foreach ($filesObj as $file){
			if ($this->checkIfExclude($file->getPathName())){
				continue;
			}
			$this->_tarHandler->add($file->getPathName());
		}
	}

	public function getCheckPointDirectories ()
	{
		$chk = array_diff(scandir($this->backupDirectory), Array(
			'.',
			'..',
			'.svn',
			'tmp'
		));
		$Dirs = Array();
		foreach ($chk as $dir){
			if (preg_match('/^(?<day>[0-9]{2})(?<month>[0-9]{2})(?<year>[0-9]{4})_(?<hour>[0-9]{2})(?<minute>[0-9]{2})(?<second>[0-9]{2})_(?<random>[0-9]*)_(?<type>[a-z]*)$/', $dir, $matches, PREG_OFFSET_CAPTURE)){
				$Dirs[$dir] = $matches;
			}
		}
		return $Dirs;
	}

	public function checkpointsFilesForJS ()
	{
		$Data = $this->getCheckPointDirectories();
		$_JS = '[';
		foreach ($Data as $key => $record){
			if (isset($record['hour'][0]) && isset($record['type'][0])){
				$_JS .= '{ id:\'' . $key . '\', date: \'' . date('Y-m-d G:i:s', mktime($record['hour'][0], $record['minute'][0], $record['second'][0], $record['month'][0], $record['day'][0], $record['year'][0])) . '\', type: \'' . $record['type'][0] . '\'},';
			}
		}
		$_JS .= ']';
		return $_JS;
	}

	public function setDatabaseLimit ($limit, $flush = TRUE)
	{
		$this->_dbLimit = $limit;
		if ($flush !== FALSE){
			$this->flushDbLimitMin();
		}
	}

	public function getDatabaseLimit ()
	{
		return $this->_dbLimit;
	}

	public function flushDbLimitCurrent ()
	{
		$this->_dbLimitCurrent = 0;
	}

	public function getDbLimitCurrent ()
	{
		return $this->_dbLimitCurrent;
	}

	public function setDbLimitCurrent ($current)
	{
		$this->_dbLimitCurrent = $current;
	}

	public function convertDbLimitCurrent ()
	{
		$this->setDbLimitCurrent($this->getDbLimitCurrent() + $this->getDatabaseLimit());
		return $this->getDbLimitCurrent();
	}

	public function deleteCheckpoint ($name)
	{
		$objResponse = new xajaxResponse();
		if (is_array($name) && count($name) > 0){
			foreach ($name as $key => $file){
				$dir = ROOTPATH . 'backup' . DS . $file;
				if (strpos($file, '..') === FALSE){
					$dir = ROOTPATH . 'backup' . DS . $file;
					if (is_dir($dir)){
						$this->clearDir($dir, true);
					}
				}
			}
		}
		else{
			$dir = ROOTPATH . 'backup' . DS . $name;
			if (strpos($name, '..') === FALSE){
				$dir = ROOTPATH . 'backup' . DS . $name;
				if (is_dir($dir)){
					$this->clearDir($dir, true);
				}
			}
		}
		
		$objResponse->script('window.location.reload(false);');
		return $objResponse;
	}

	public function clearDir ($dir, $DeleteMe)
	{
		if (! $dh = @opendir($dir))
			return;
		while (false !== ($obj = readdir($dh))){
			if ($obj == '.' || $obj == '..')
				continue;
			if (! @unlink($dir . '/' . $obj))
				$this->clearDir($dir . '/' . $obj, true);
		}
		
		closedir($dh);
		if ($DeleteMe){
			@rmdir($dir);
		}
	}
}
