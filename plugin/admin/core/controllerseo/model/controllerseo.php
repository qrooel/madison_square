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
 * $Id: controllerseo.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class ControllerSeoModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('controllerseo', Array(
			'idcontroller' => Array(
				'source' => 'C.idcontroller'
			),
			'name' => Array(
				'source' => 'C.name'
			),
			'translation' => Array(
				'source' => 'CS.name',
				'prepareForAutosuggest' => true
			)
		));
		$datagrid->setFrom('
				controller C
				LEFT JOIN controllerseo CS ON C.idcontroller = CS.controllerid AND CS.languageid = :languageid
				LEFT JOIN language L ON L.idlanguage = CS.languageid 
			');
		$datagrid->setAdditionalWhere('
				mode = 0
			');
	
	}

	public function getTranslationNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('translation', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getControllerSeoForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXUpdateControllerSeo ($id, $name)
	{
		$sql = 'DELETE FROM controllerseo WHERE controllerid = :controllerid AND languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('controllerid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->executeUpdate();
		
		$sql = 'INSERT INTO controllerseo SET 
					name=:name,
					addid=:addid,
					controllerid = :controllerid,
					languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $name);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('controllerid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->executeUpdate();
		$this->flushCache();
	}

	public function getControllerSeoAll ()
	{
		$sql = 'SELECT name as translation FROM controllerseo WHERE languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'translation' => $rs->getString('translation')
			);
		}
		return $Data;
	}

	/**
	 *  Get information for chosen controller
	 *          
	 *  @param integer idcontroller
	 *  @return array with controllerseo name translations
	 *  @throws on error FrontendException object will be returned
	 *  @access public
	 */
	public function getControllerSeoView ($id)
	{
		$sql = "SELECT name, enable, IF(mode  = 0, 2, 1) AS mode
					FROM controller
					WHERE idcontroller = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'enable' => $rs->getInt('enable'),
				'mode' => $rs->getInt('mode'),
				'translation' => $this->getControllerTranslation($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_NO_EXIST'));
		}
		return $Data;
	}

	/**
	 *  Get name for chosen controllerseo
	 *          
	 *  @param integer idcontroller
	 *  @return array with controllerseo name
	 *  @access public
	 */
	public function getControllerTranslation ($id)
	{
		$sql = "SELECT name as translation, languageid
					FROM controllerseo
					WHERE controllerid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'translation' => $rs->getString('translation')
			);
		}
		return $Data;
	}

	public function updateControllerSeo ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->editController($Data, $id);
			$this->editControllerSeo($Data, $id);
			$this->flushCache();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLER_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function editController ($Data, $id)
	{
		$sql = 'UPDATE controller 
						SET name=:name, 
						mode=:mode, 
						enable=:enable,
						editid=:editid
					WHERE idcontroller = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setString('name', $Data['controller']);
		if ($Data['side'] == 1){
			$stmt->setInt('mode', 1);
		}
		else{
			$stmt->setInt('mode', 0);
		}
		if ($Data['enable'] == 1){
			$stmt->setInt('enable', 1);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLER_UPDATE'), 1, $e->getMessage());
		}
		return true;
	}

	/**
	 *  Update name for chosen controllerseo
	 *          
	 *  @param integer idcontroller
	 *  @param array 
	 *  @return array true
	 *  @access public
	 */
	public function editControllerSeo ($Data, $id)
	{
		$sql = 'DELETE FROM controllerseo WHERE controllerid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['translation'] as $key => $val){
			$sql = 'INSERT INTO controllerseo (name, languageid, addid, controllerid)
						VALUES (:name, :languageid, :addid, :controllerid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $Data['translation'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('controllerid', $id);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLER_SEO_TRANSLATION_EDIT'), 13, $e->getMessage());
			}
		}
		return true;
	}

	public function addNewControllerSeo ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$controllerId = $this->addController($Data);
			$this->addControllerSeo($Data, $controllerId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLER_ADD'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	public function addControllerSeo ($Data, $controllerid)
	{
		foreach ($Data['translation'] as $key => $value){
			$sql = 'INSERT INTO controllerseo (name, languageid, controllerid, addid)
						VALUES (:name, :languageid, :controllerid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $value);
			$stmt->setInt('languageid', $key);
			$stmt->setInt('controllerid', $controllerid);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLERSEO_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function addController ($Data)
	{
		$sql = 'INSERT INTO controller (name, version, description, enable, mode, addid)
					VALUES (:name, :version, :description, :enable, :mode, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['controller']);
		$stmt->setInt('version', 1);
		$stmt->setString('description', $Data['controller']);
		if ($Data['enable'] == 1){
			$stmt->setInt('enable', 1);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		if ($Data['side'] == 1){
			$stmt->setInt('mode', 1);
		}
		else{
			$stmt->setInt('mode', 0);
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTROLLER_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function flushCache ()
	{
		Cache::destroyObject('controllerseo');
	}

}
?>