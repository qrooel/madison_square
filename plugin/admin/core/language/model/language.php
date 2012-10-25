<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o
 * licencji i autorach.
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
 * $Id: language.php 655 2012-04-24 08:51:44Z gekosale $
 */

class LanguageModel extends ModelWithDatagrid {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid) {
		$datagrid->setTableData('language', Array(
			'idlanguage' => Array(
				'source' => 'L.idlanguage'
			),
			'name' => Array(
				'source' => 'L.name'
			),
			'translation' => Array(
				'source' => 'L.translation',
				'processLanguage' => true
			),
			'currency' => Array(
				'source' => 'C.currencysymbol',
				'prepareForSelect' => true
			),
			'flag' => Array(
				'source' => 'L.flag'
			),
			'adddate' => Array(
				'source' => 'L.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'L.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				language L
				LEFT JOIN languageview LV ON LV.languageid = L.idlanguage
				LEFT JOIN currency C ON L.currencyid = C.idcurrency
				LEFT JOIN `user` U ON L.addid = U.iduser
				LEFT JOIN `userdata` UDA ON U.iduser = UDA.userid
				LEFT JOIN `user` UE ON L.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON U.iduser = UDE.userid
			');
		$datagrid->setGroupBy('
				L.idlanguage
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NOT NULL,LV.viewid = :viewid, 1)
			');
	}

	public function getDatagridFilterData () {
		return $this->getDatagrid()->getFilterData();
	}

	public function getLanguageForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteLanguage ($id, $datagrid) {
		$objResponse = new xajaxResponse();
		$this->deleteLanguage($datagrid);
		$this->flushCache();
		$objResponse->script('window.location.reload(true)');
		return $objResponse;
	}

	public function deleteLanguage ($id) {
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idlanguage' => $id
			), $this->getName(), 'deleteLanguage');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getLanguageALL () {
		$sql = 'SELECT 
						idlanguage AS id, 
						translation,
						name,
						flag 
					FROM language';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'translation' => $this->registry->core->getMessage($rs->getString('translation')),
				'flag' => $rs->getString('flag'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getLanguageALLToSelect () {
		$Data = $this->getLanguageALL();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $this->registry->core->getMessage($key['translation']);
		}
		return $tmp;
	}

	public function getLanguageView ($id) {
		$sql = "SELECT 
						idlanguage AS id, 
						name, 
						translation,
						currencyid,
						flag
					FROM language 
					WHERE idlanguage = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'currencyid' => $rs->getInt('currencyid'),
				'flag' => Array(
					'file' => $rs->getString('flag')
				),
				'translation' => $rs->getString('translation'),
				'view' => $this->LanguageView($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_LANGUAGE_NO_EXIST'));
		}
		return $Data;
	}

	public function LanguageView ($id) {
		$sql = "SELECT viewid
					FROM languageview
					WHERE languageid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function translationLanguage ($id) {
		$sql = 'SELECT languageid AS id
					FROM translation
					WHERE idtranslation=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function translationLanguageIds ($id) {
		$Data = $this->translationLanguage($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function updateLanguage ($Data, $id) {
		$sql = 'UPDATE language SET 
						name=:name, 
						translation=:translation, 
						currencyid=:currencyid, 
						flag=:flag,
						editid=:editid 
					WHERE idlanguage =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('translation', $Data['translation']);
		$stmt->setInt('currencyid', $Data['currencyid']);
		if (isset($Data['flag']['file'])){
			$stmt->setString('flag', $Data['flag']['file']);
		}
		else{
			$stmt->setNull('flag');
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LANGUAGE_EDIT'), 13, $e->getMessage());
			return false;
		}
		return true;
	}

	public function editLanguage ($Data, $id) {
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateLanguage($Data, $id);
			$this->updateLanguageView($Data['view'], $id);
			if (isset($Data['translations']['file']) && ($Data['translations']['file'] != '')){
				$this->importTranslationFromFile($Data['translations']['file'], $id);
			}
			$this->flushCache();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_DISPATCHMETHOD_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateLanguageView ($array, $id) {
		$sql = 'DELETE FROM languageview WHERE languageid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		foreach ($array as $value){
			$sql = 'INSERT INTO languageview (viewid, languageid, addid)
						VALUES (:viewid, :languageid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addLanguage ($Data) {
		$sql = 'INSERT INTO language SET
						name = :name, 
						translation = :translation, 
						currencyid=:currencyid, 
						flag=:flag,
						addid = :addid
					';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setString('translation', $Data['translation']);
		$stmt->setInt('currencyid', $Data['currencyid']);
		if (isset($Data['flag']['file'])){
			$stmt->setString('flag', $Data['flag']['file']);
		}
		else{
			$stmt->setNull('flag');
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LANGUAGE_ADD'), 14, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addNewLanguage ($Data) {
		$this->registry->db->setAutoCommit(false);
		try{
			$newLanguageId = $this->addLanguage($Data);
			$this->addLanguageView($Data['view'], $newLanguageId);
			if (isset($Data['copylanguage']) && ($Data['copylanguage'] > 0)){
				$this->addLanguageTranslation($Data['copylanguage'], $newLanguageId);
				$this->copyLayoutBoxTranslation($Data['copylanguage'], $newLanguageId);
			}
			if (isset($Data['translations']['file']) && ($Data['translations']['file'] != '')){
				$this->importTranslationFromFile($Data['translations']['file'], $newLanguageId);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LANGUAGE_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
		return true;
	}

	protected function addLanguageTranslation ($copyfrom, $copyto) {
		
		@set_time_limit(0);
		$sql = 'SELECT translation, translationid FROM translationdata WHERE languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $copyfrom);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$sql = 'INSERT INTO translationdata SET
							translation = :translation,
							translationid = :translationid,
							languageid = :languageid
						';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('translation', $rs->getString('translation'));
			$stmt->setInt('translationid', $rs->getInt('translationid'));
			$stmt->setInt('languageid', $copyto);
			$stmt->executeUpdate();
		}
	
	}

	protected function copyLayoutBoxTranslation ($copyfrom, $copyto) {
		
		@set_time_limit(0);
		$sql = 'SELECT layoutboxid, title FROM layoutboxtranslation WHERE languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $copyfrom);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$sql = 'INSERT INTO layoutboxtranslation SET
							layoutboxid = :layoutboxid,
							title = :title,
							languageid = :languageid
						';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('title', $rs->getString('title'));
			$stmt->setInt('layoutboxid', $rs->getInt('layoutboxid'));
			$stmt->setInt('languageid', $copyto);
			$stmt->executeUpdate();
		}
	
	}

	public function importTranslationFromFile ($file, $languageid) {
		$this->registry->db->setAutoCommit(false);
		@set_time_limit(0);
		try{
			$xml = simplexml_load_file(ROOTPATH . 'upload/' . $file);
			foreach ($xml->row as $row){
				$name = (string) $row->field[0];
				
				$sql = 'SELECT idtranslation as id, editid FROM translation WHERE name = :name';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', $name);
				$rs = $stmt->executeQuery();
				
				if ($rs->first()){
					if ($rs->getInt('editid') > 0){
					
					}
					else{
						$id = $rs->getInt('id');
						$sql = 'INSERT INTO translationdata SET
								translationid = :translationid,
								languageid = :languageid,
								translation = :translation
							ON DUPLICATE KEY UPDATE 
								translation = :translation
						';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('translation', (string) $row->field[1]);
						$stmt->setInt('translationid', $id);
						$stmt->setInt('languageid', $languageid);
						$stmt->executeUpdate();
					}
				
				}
				else{
					$sql = 'INSERT INTO translation SET
									name = :name,
									addid = :addid';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('name', $name);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					$rs = $stmt->executeQuery();
					$id = $stmt->getConnection()->getIdGenerator()->getId();
					$sql = 'INSERT INTO translationdata SET
									translationid = :translationid,
									languageid = :languageid,
									translation = :translation
							ON DUPLICATE KEY UPDATE 
									translation = :translation
						';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('translation', (string) $row->field[1]);
					$stmt->setInt('translationid', $id);
					$stmt->setInt('languageid', $languageid);
					$stmt->executeUpdate();
				}
			
			}
			Cache::destroyObject('translations');
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_LANGUAGE_IMPORT'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	
	}

	protected function addLanguageView ($array, $id) {
		foreach ($array as $value){
			$sql = 'INSERT INTO languageview (viewid, languageid, addid)
						VALUES (:viewid, :languageid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', $id);
			$stmt->setInt('viewid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function changeLanguage ($lang, $reload = false) {
		
		$objResponse = new xajaxResponse();
		$this->registry->session->setActiveMenuData(NULL);
		$sql = 'SELECT name FROM language WHERE idlanguage = :lang';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('lang', $lang);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->registry->session->setActiveLanguage($rs->getString('name'));
			$this->registry->session->setActiveLanguageId($lang);
		}
		if ($reload == true){
			$objResponse->script('window.location.reload(true)');
		}
		else{
			$objResponse->script('theDatagrid.LoadData();');
		}
		return $objResponse;
	}

	public function getLanguages () {
		$sql = 'SELECT 
					idlanguage AS id, 
					flag, 
					translation,
					viewid
				FROM language L
				LEFT JOIN languageview LV ON LV.languageid = L.idlanguage';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'id' => $rs->getInt('id'),
				'flag' => $rs->getString('flag'),
				'weight' => $rs->getInt('id'),
				'icon' => $rs->getString('flag'),
				'name' => $this->registry->core->getMessage($rs->getString('translation'))
			);
		}
		return $Data;
	}

	public function syncTranslation ($Data) {
		$this->registry->db->setAutoCommit(false);
		$xml = simplexml_load_file(ROOTPATH . 'upload/' . $Data['translations']['file']);
		preg_match('/_(?<lang>[a-z]{2}_[A-Z]{2}).xml$/', $Data['translations']['file'], $matches);
		$sql_select = 'SELECT idtranslation FROM translation WHERE name = :translation';
		$sql_insert_translation = 'INSERT INTO translation (name, addid) VALUES (:name, :userid)';
		$sql_insert_translationdata = 'INSERT INTO translationdata(translation, translationid, languageid) 
					VALUES (:translation, :translationid, (SELECT idlanguage FROM language WHERE name = :language))
					ON DUPLICATE KEY UPDATE translation = :translation';
		$current_idtranslation = 0;
		foreach ($xml->translation as $row){
			$stmt = $this->registry->db->prepareStatement($sql_select);
			$stmt->setString('translation', (string) $row->name);
			$rs = $stmt->executeQuery();
			if (! $rs->first()){
				$stmt = $this->registry->db->prepareStatement($sql_insert_translation);
				$stmt->setString('name', (string) $row->name);
				$stmt->setInt('userid', $this->registry->session->getActiveUserid());
				try{
					$rs = $stmt->executeUpdate();
					$current_idtranslation = $stmt->getConnection()->getIdGenerator()->getId();
				}
				catch (Exception $e){
					$this->registry->db->rollback();
					$this->registry->db->setAutoCommit(true);
					throw $e;
				}
			}
			else{
				$current_idtranslation = $rs->getInt('idtranslation');
			}
			$stmt = $this->registry->db->prepareStatement($sql_insert_translationdata);
			$stmt->setString('translation', (string) $row->translation);
			$stmt->setInt('translationid', $current_idtranslation);
			$stmt->setString('language', $matches['lang']);
			try{
				$rs = $stmt->executeUpdate();
			}
			catch (Exception $e){
				$this->registry->db->rollback();
				$this->registry->db->setAutoCommit(true);
				throw $e;
			}
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

	public function flushCache () {
		Cache::destroyObject('languages');
		Cache::destroyObject('translations');
	}
}