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
 * $Revision: 690 $
 * $Author: gekosale $
 * $Date: 2012-09-01 20:38:44 +0200 (So, 01 wrz 2012) $
 * $Id: gallery.php 690 2012-09-01 18:38:44Z gekosale $ 
 */

class GalleryModel extends FileUploader
{
	
	protected $galleryPath;
	protected $designPath;
	protected $orginalFilePath;
	protected $swfFilePath;
	protected $__DYNAMIC_METHODS;
	protected $keepProportion = 1;
	protected $__UNIQUE_ROOTPATHS = Array();
	
	protected $__ERROR_MSG = Array(
		0 => 'UPLOAD_ERR_OK',
		1 => 'UPLOAD_ERR_INI_SIZE',
		2 => 'UPLOAD_ERR_FORM_SIZE',
		3 => 'UPLOAD_ERR_PARTIAL',
		4 => 'UPLOAD_ERR_NO_FILE',
		6 => 'UPLOAD_ERR_NO_TMP_DIR',
		7 => 'UPLOAD_ERR_CANT_WRITE',
		8 => 'UPLOAD_ERR_EXTENSION'
	);

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->load();
		$this->setDynamicMethods();
	}

	public function load ()
	{
		$this->galleryPath = ROOTPATH . 'design/_gallery/';
		$this->designPath = DESIGNPATH . '_gallery/';
		$this->orginalFilePath = $this->galleryPath . '_orginal/';
		$this->swfFilePath = $this->galleryPath . '_swf/';
		$this->loadAllowedType(Array(
			'image',
			'application/octet-stream'
		));
		$this->colour = '#ffffff';
		$this->loadAllowedExtensions(Array(
			'jpg',
			'jpeg',
			'png',
			'gif',
			'swf',
			'psd',
			'octet-stream',
			'psd',
			'doc',
			'csv',
			'xls',
			'tgz',
			'rar',
			'zip',
			'pdf',
			'avi',
			'mov',
			'mpg',
			'mpeg'
		));
		return $this;
	}

	public function proportionOn ()
	{
		$this->keepProportion = 1;
	}

	public function proportionOff ()
	{
		$this->keepProportion = 0;
	}

	public function getProportion ()
	{
		return $this->keepProportion;
	}

	public function process ($file, $proportion = 1)
	{
		if ($proportion == 1){
			$this->proportionOn();
		}
		else{
			$this->proportionOff();
		}
		if (is_object($file)){
			$Data = $file->getValue();
		}
		else{
			$Data = $file;
		}
		if ($Data['error'] == 4)
			return 0;
		if ($Data['error'] == 0 && isset($Data['type'])){
			try{
				$this->registry->db->setAutoCommit(false);
				$this->tmpExtension = substr(strrchr($Data['name'], '.'), 1);
				$fileId = $this->insertFile($Data['name']);
				$path = $this->uploadFile($fileId, $Data['tmp_name']);
				if (! preg_match('/\.swf$/', $Data['name'])){
					foreach ($this->__DYNAMIC_METHODS as &$sizeing){
						$this->setImageCatalogPath($sizeing);
						if (isset($sizeing['width']) && isset($sizeing['height'])){
							$this->resizeAndSave($path, $sizeing['width'], $sizeing['height'], $sizeing['rootpath']);
						}
					}
				}
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_SAVE_FILE'), 23, $e->getMessage());
			}
		}
		else{
			$error = $this->registry->core->getMessage($this->__ERROR_MSG[$Data['error']]);
			switch ($Data['error']) {
				case 1:
					$error .= ' (' . $this->registry->core->getMessage('MAX_UPLOAD_FILE_SIZE_IS') . ': ' . ini_get('upload_max_filesize') . ')';
					break;
			}
			throw new CoreException($error, 23);
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $fileId;
	}

	public function uploadFile ($name, $tmp_name)
	{
		if (preg_match('/\.swf$/', $name)){
			$path = $this->swfFilePath . $this->insertedFileFullName;
		}
		else{
			$path = $this->orginalFilePath . $this->insertedFileFullName;
		}
		if (! move_uploaded_file($tmp_name, $path)){
			if (! copy($tmp_name, $path)){
				throw new Exception('Error while coping file from TEMP directory to gallery files.');
			}
		}
		return $path;
	}

	protected function deleteFiles ($filediskname, $fileextension, $filetype)
	{
		if (! array_key_exists($fileextension, $this->allowedExtensions)){
			throw new Exception('Wrong extension type');
		}
		if (! array_key_exists($filetype, $this->fileType)){
			throw new Exception('Wrong file type: ' . $filetype);
		}
		$this->generateRootPaths();
		foreach ($this->__UNIQUE_ROOTPATHS as $rp){
			@unlink($rp . $filediskname);
		}
		@unlink(ROOTPATH . 'design' . DS . '_virtualproduct' . DS . $filediskname);
	}

	protected function generateRootPaths ()
	{
		$this->__UNIQUE_ROOTPATHS = Array();
		foreach ($this->__DYNAMIC_METHODS as $method){
			$this->setImageCatalogPath($method);
			if (! in_array($method['rootpath'], $this->__UNIQUE_ROOTPATHS)){
				$this->__UNIQUE_ROOTPATHS[] = $method['rootpath'];
			}
		}
	}

	public function deleteFilesFromArray ($file)
	{
		if (! is_array($file)){
			throw new Exception('Wrong parameter type. Should be an array.');
		}
		try{
			$this->deleteFiles($file['filediskname'], $file['filextensioname'], $file['filetypename']);
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function setDynamicMethods ()
	{
		$this->__DYNAMIC_METHODS = $this->loadDynamicMethods();
		$this->generateRootPaths();
	}

	public function loadDynamicMethods ()
	{
		$sql = 'SELECT method, width, height, keepproportion, staticpath FROM gallerysettings';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getString('method')] = Array(
				'width' => $rs->getInt('width'),
				'height' => $rs->getInt('height'),
				'keepproportion' => $rs->getInt('keepproportion'),
				'staticpath' => $rs->getString('staticpath')
			);
		}
		return $Data;
	}

	public function __call ($name, $values)
	{
		if (isset($this->__DYNAMIC_METHODS[$name]) === FALSE){
			if (method_exists($this, $name) === FALSE){
				trigger_error('Unknown method: <b>' . $name . '</b> in ' . get_class($this) . ' class', E_USER_ERROR);
			}
			return call_user_func_array(array(
				$this,
				$name
			), $values);
		}
		$p = & $this->__DYNAMIC_METHODS[$name];
		$this->setImageCatalogPath($p);
		try{
			$File = $this->getFileById($values[0]);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_FILE_NOT_EXIST'), 8, 'Gallery file doesn\'t exists on disk');
		}
		$File['path'] = $p['designpath'] . $File['filediskname'];
		if (! file_exists($p['rootpath'] . $File['filediskname'])){
			if (! file_exists($this->orginalFilePath . $File['filediskname'])){
				return false;
			}
			$this->setImageCatalogPath($p);
			if (isset($p['width']) && isset($p['height'])){
				if (isset($values[1]) && $values[1] == 1){
					$watermark = 0;
				}
				else{
					$watermark = 1;
				}
				
				$this->resizeAndSave($this->orginalFilePath . $File['filediskname'], $p['width'], $p['height'], $p['rootpath'], $watermark);
			}
		}
		return $File;
	}

	protected function setImageCatalogPath (&$p)
	{
		if ($p['staticpath'] != ''){
			$p['designpath'] = $this->designPath . $p['staticpath'] . '/';
			$p['rootpath'] = $this->galleryPath . $p['staticpath'] . '/';
		}
		else{
			if (Helper::getViewId() > 0){
				$viewStr = '_' . Helper::getViewId();
			}
			else{
				$viewStr = '';
			}
			if (! is_dir($this->galleryPath . '_' . $p['width'] . '_' . $p['height'] . $viewStr . '/')){
				mkdir($this->galleryPath . '_' . $p['width'] . '_' . $p['height'] . $viewStr . '/', 0755);
			}
			$p['designpath'] = $this->designPath . '_' . $p['width'] . '_' . $p['height'] . $viewStr . '/';
			$p['rootpath'] = $this->galleryPath . '_' . $p['width'] . '_' . $p['height'] . $viewStr . '/';
		}
	}

	public function flushDynamicMethods ()
	{
		Cache::destroy('Gallery.methods');
		$this->__UNIQUE_ROOTPATHS = Array();
	}

protected function resizeAndSave ($path, $width, $height, $savePath, $watermark = 1)
	{
		try{
			$objImage = new Image($path);
		}
		catch (Exception $e){
			return false;
		}
		$layer = $this->registry->loader->getCurrentLayer();
		
		if ($this->keepProportion > 0){
			if ($objImage->imageWidth() > $objImage->imageHeight()){
				$objImage->resizeToWidth($width);
			}
			else{
				$objImage->resizeToHeight($height);
			}
			if ($watermark == 1){
				if (isset($layer['watermark']) && ! is_null($layer['watermark']) && strlen($layer['watermark']) > 4){
					$watermark = new Image(ROOTPATH . 'design/_images_frontend/core/logos/' . $layer['watermark']);
					if ($watermark->imageWidth() > ($objImage->imageWidth() / 2)){
						$watermark->resizeToWidthHeight($objImage->imageWidth() / 2, $objImage->imageHeight() / 2);
					}
					$objImage->watermark($watermark);
				}
			}
			$objImage->resizeCanvas($width, $height, Image::CENTERED, $this->colour);
		}
		else{
			$objImage->imageAutoResize($width, $height);
		}
		try{
			$objImage->save($this->insertedFileFullName, 80, $savePath);
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$objImage->imageCleanup();
	}

	public function getImagePath ($Photo)
	{
		return $Photo['path'];
	}

	public function getBindLockByFileId ($id)
	{
		$sql = 'SELECT count(idfile) AS num
					FROM `file` F 
					WHERE NOT EXISTS
					  (SELECT DISTINCT PP.photoid
					  FROM productphoto PP
					  WHERE F.idfile = PP.photoid)
					AND NOT EXISTS (SELECT DISTINCT C.idcategory
					  FROM category C WHERE F.idfile = C.photoid)
					AND NOT EXISTS (SELECT DISTINCT UD.userid
					  FROM userdata UD WHERE F.idfile = UD.photoid)
					AND NOT EXISTS (SELECT DISTINCT D.iddeliverer
					  FROM deliverer D WHERE F.idfile = D.photoid)
					AND NOT EXISTS (SELECT DISTINCT P.idproducer
					  FROM producer P WHERE F.idfile = P.photoid)
					AND idfile = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$rs->first();
		return $rs->getInt('num');
	}

	public function getRemoteImage ($url, $name, $keepproportion = 1)
	{
		$file = @GetImageSize($url);
		if (! is_array($file)){
			return;
		}
		$_Files['name'] = $name;
		$_Files['type'] = $file['mime'];
		$_Files['size'] = $file['bits'];
		$_Files['error'] = 0;
		$_Files['tmp_name'] = $_tmp = tempnam(sys_get_temp_dir(), 'GE');
		$curl = curl_init($url);
		$fp = fopen($_tmp, 'wb');
		$options = array(
			CURLOPT_FILE => $fp,
			CURLOPT_HEADER => 0,
			CURLOPT_TIMEOUT => 60
		);
		curl_setopt_array($curl, $options);
		curl_exec($curl);
		curl_close($curl);
		fclose($fp);
		try{
			return $this->process($_Files, $keepproportion);
		}
		catch (Exception $e){
			//			throw $e;
		}
	}
}