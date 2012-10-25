<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: virtualproductfiles.php 687 2012-09-01 12:02:47Z gekosale $
 */
class VirtualProductFilesModel extends FileUploader {
	protected $_path;

	public function __construct ($registry) {
		parent::__construct($registry);
		$this->load();
	}

	public function load () {
		$this->_path = ROOTPATH . 'design' . DS . '_virtualproduct' . DS;
		$this->loadAllowedType(Array(
			'image',
			self::MIME
		));
		$this->loadAllowedExtensions(Array(
			'jpg',
			'jpeg',
			'png',
			'gif',
			'psd',
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

	public function insert ($file) {
		try{
			$id = $this->insertVirtualProductFile($file);
			$this->AddToFilesystem($file, $id);
			return $id;
		}
		catch (Exception $e){
			throw new CoreException($e);
		}
	}

	public function AddToFilesystem ($file, $id) {
		$filepath = $this->_path . $id . '.' . $this->getFileExtension($file['name']);
		if (! move_uploaded_file($file['tmp_name'], $filepath)){
			throw new Exception('File upload unsuccessful.');
		}
	}

	public function insertVirtualProductFile ($file) {
		try{
			return $this->insertFile($file['name']);
		}
		catch (Exception $e){
			throw $e;
		}
	}
}