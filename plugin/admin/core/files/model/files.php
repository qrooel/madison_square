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
 * $Id: files.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class FilesModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('file', Array(
			'idfile' => Array(
				'source' => 'F.idfile'
			),
			'filename' => Array(
				'source' => 'F.name',
				'prepareForAutosuggest' => true
			),
			'fileextension' => Array(
				'source' => 'FE.name',
				'prepareForSelect' => true
			),
			'filetype' => Array(
				'source' => 'FT.name',
				'prepareForSelect' => true
			),
			'adddate' => Array(
				'source' => 'F.adddate'
			),
			'bindcount' => Array(
				'source' => 'COUNT(PP.photoid)+COUNT(CAT.photoid)+COUNT(UD.photoid)+COUNT(DEL.photoid)+COUNT(PRO.photoid)',
				'filter' => 'having'
			),
			'thumb' => Array(
				'source' => 'F.idfile',
				'processFunction' => Array(
					$this,
					'getThumbPathForId'
				)
			)
		));
		$datagrid->setFrom('
				`file` F
				INNER JOIN `filetype` FT ON FT.idfiletype = F.filetypeid
				INNER JOIN `fileextension` FE ON FE.idfileextension = F.fileextensionid
				LEFT JOIN category CAT ON CAT.photoid = F.idfile
				LEFT JOIN productphoto PP ON PP.photoid = F.idfile
				LEFT JOIN userdata UD ON UD.photoid = F.idfile
				LEFT JOIN deliverer DEL ON DEL.photoid = F.idfile
				LEFT JOIN producer PRO ON PRO.photoid = F.idfile

				
			');
		$datagrid->setGroupBy('
				F.idfile
			');
	}

	public function getThumbPathForId ($id)
	{
		try{
			$image = App::getModel('gallery')->getSmallImageById($id);
		}
		catch (Exception $e){
			$image = Array(
				'path' => ''
			);
		}
		return $image['path'];
	}

	public function getFilenameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('filename', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getFilesForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function deleteAJAXUnbindPhotos ()
	{
		$objResponse = new xajaxResponse();
		$this->registry->db->setAutoCommit(false);
		$sql = 'SELECT CONCAT(F.idfile, \'.\', FE.name) filename, F.idfile id, FE.name fileextension, FT.name filetype
					FROM `file` F 
					INNER JOIN fileextension FE ON FE.idfileextension = F.fileextensionid
					INNER JOIN filetype FT ON FT.idfiletype = F.filetypeid
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
					';
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$rs = $stmt->executeQuery();
			App::getModel('gallery')->deleteFilesFromArray($rs->getAllRows());
		}
		catch (Exception $e){
			$objResponse->alert($e->getMessage());
			$this->registry->db->rollback();
			$this->registry->db->setAutoCommit(true);
			return $objResponse;
		}
		$sql = 'DELETE F
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
					';
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			$objResponse->alert($e->getMessage());
			$this->registry->db->rollback();
			$this->registry->db->setAutoCommit(true);
			return $objResponse;
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return $objResponse;
	}

	public function doAJAXDeleteFiles ($datagridName = 'list-files', $id = NULL)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $this->getDatagrid()->deleteRow($datagridName, $id, Array(
				$this,
				'deleteFile'
			), $this->getName());
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	//FIXME: grupowe usuwanie zdjęć, synchronizacja
	public function deleteFile ($Data)
	{
		if (! is_array($Data)){
			$Data = Array(
				$Data
			);
		}
		$fileData = Array();
		foreach ($Data as $fileid){
			$filesData[] = App::getModel('gallery')->getFileById($fileid);
		}
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			$dbtracker->disableAutoCommit();
			if ($dbtracker->run(Array(
				'idfile' => $Data
			), $this->getName(), 'deleteFile') === true){
				foreach ($filesData as $file){
					App::getModel('gallery')->deleteFilesFromArray($file);
				}
				$this->registry->db->commit();
				$dbtracker->enableAutoCommit();
				return true;
			}
			$this->registry->db->rollback();
			$dbtracker->enableAutoCommit();
			return false;
		}
		catch (Exception $e){
			$this->registry->db->rollback();
			$dbtracker->enableAutoCommit();
			throw new Exception($e->getMessage());
		}
	}
}