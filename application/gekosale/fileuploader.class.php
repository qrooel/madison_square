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
 * $Id: fileuploader.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

abstract class FileUploader extends Model
{
	
	protected $registry;
	protected $fileType = NULL;
	protected $allowedExtensions = Array();
	protected $uploadFile = '/_upload/';
	protected $tmpExtension;
	protected $insertedFileFullName;
	protected $files;
	const MIME = 'application/octet-stream';

	public function __construct ($registry)
	{
		$this->registry = $registry;
		$this->setFiles();
	}

	final protected function loadAllowedType ($type)
	{
		$sql = 'SELECT idfiletype AS id, name FROM filetype WHERE name IN (:name)
					AND active = 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINString('name', $type);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$this->fileType[$rs->getString('name')] = $rs->getInt('id');
		}
	}

	final protected function loadAllowedExtensions ($extensions)
	{
		$sql = 'SELECT idfileextension AS id, name FROM fileextension WHERE name IN (:ext)
					AND active = 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINString('ext', $extensions);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$this->allowedExtensions[$rs->getString('name')] = $rs->getInt('id');
		}
	}

	final protected function insertFile ($name)
	{
		$sql = 'INSERT INTO file(name, filetypeid, fileextensionid,viewid, addid)
				VALUES (:name, :filetypeid, :fileextensionid,:viewid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', Core::clearUTF($name));
		$stmt->setInt('filetypeid', current($this->fileType));
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('fileextensionid', $this->allowedExtensions[strtolower($this->tmpExtension)]);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$idFile = $stmt->getConnection()->getIdGenerator()->getId();
		$this->insertedFileFullName = $idFile . '.' . $this->tmpExtension;
		Cache::destroyObject('files');
		$this->setFiles();
		return $idFile;
	}

	public function process ($file)
	{
		if (is_object($file)){
			$Data = $file->getValue();
		}
		else{
			$Data = $file;
		}
		if ($Data['error'] == 0 && isset($Data['type'])){
			try{
				$this->check($Data['type'], $Data['name']);
			}
			catch (Exception $e){
				throw $e;
			}
		}
		else{
			return false;
		}
	}

	final protected function check ($type, $fileName)
	{
		if ($type == self::MIME){
			$_fileType['type'] = self::MIME;
			$_fileType['extension'] = $this->getFileExtension($fileName);
		}
		else{
			preg_match('/^(?<type>[a-z]*)\/(?<extension>[a-z\-]*)$/', $type, $_fileType);
		}
		if (! isset($_fileType['type']) || ! isset($_fileType['extension'])){
			throw new Exception('File type or exception error');
		}
		else{
			if (! array_key_exists($_fileType['type'], $this->fileType)){
				throw new Exception('File type not match');
			}
			if (! array_key_exists($_fileType['extension'], $this->allowedExtensions)){
				throw new Exception('File extension not match');
			}
			if ($_fileType['extension'] == 'jpeg')
				$_fileType['extension'] = 'jpg';
			$this->tmpExtension = $_fileType['extension'];
		}
		return true;
	}

	final public function getType ()
	{
		return $this->fileType;
	}

	final public function getAllowedExtensions ()
	{
		return $this->allowedExtensions;
	}

	public function setFiles ()
	{
		$sql = 'SELECT 
							idfile, 
							F.name AS filename, 
							F.fileextensionid, 
							F.filetypeid,
							FE.name AS filextensioname, 
							FT.name AS filetypename, 
							concat(idfile,\'.\', FE.name) AS filediskname 
						FROM file F 
						LEFT JOIN fileextension FE ON FE.idfileextension = F.fileextensionid
						LEFT JOIN filetype FT ON FT.idfiletype = F.filetypeid
						GROUP BY F.idfile';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$this->files[$rs->getInt('idfile')] = Array(
				'idfile' => $rs->getInt('idfile'),
				'filename' => $rs->getString('filename'),
				'fileextensionid' => $rs->getInt('fileextensionid'),
				'filetypeid' => $rs->getInt('filetypeid'),
				'filextensioname' => $rs->getString('filextensioname'),
				'filetypename' => $rs->getString('filetypename'),
				'filediskname' => $rs->getString('filediskname')
			);
		
		}
	}

	final public function getFileById ($id)
	{
		if ($id > 0 && (isset($this->files[$id]))){
		
		}
		else{
			$id = 1;
		}
		return $this->files[$id];
	}

	final protected function getFileByName ($name)
	{
		$sql = 'SELECT idfile, F.name AS filename, F.fileextensionid, F.filetypeid,
				FE.name AS filextensioname, FT.name AS filetypename 
				FROM file F WHERE F.name = :name
				LEFT JOIN fileextension FE ON FE.idfileextension = F.fileextensionid
				LEFT JOIN filetypeid FT ON FT.idfiletype = F.filetypeid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $name);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		if (isset($Data[0])){
			return $Data[0];
		}
		throw new CoreException($this->registry->core->getMessage('ERR_FILE_NOT_EXIST'), 8, $e->getMessage());
	}

	public function getFileExtension ($fileName)
	{
		preg_match('/.(?<ext>[a-z]{1,4})$/', $fileName, $matches);
		if (isset($matches['ext'])){
			return $matches['ext'];
		}
		throw new Exception('Extension error');
	}

	abstract function load ();
}
?>