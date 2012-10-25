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
 * $Id: translation.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class TranslationModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('translation', Array(
			'idtranslation' => Array(
				'source' => 'T.idtranslation'
			),
			'name' => Array(
				'source' => 'T.name',
				'prepareForAutosuggest' => true
			),
			'translation' => Array(
				'source' => 'TD.translation',
				'prepareForAutosuggest' => true
			),
			'adddate' => Array(
				'source' => 'T.adddate'
			),
			'adduser' => Array(
				'source' => 'CONCAT(UDA.firstname, \' \', UDA.surname)'
			),
			'editdate' => Array(
				'source' => 'T.editdate'
			),
			'edituser' => Array(
				'source' => 'CONCAT(UDE.firstname, \' \', UDE.surname)'
			)
		));
		$datagrid->setFrom('
				translation T
				LEFT JOIN translationdata TD ON T.idtranslation = TD.translationid AND TD.languageid = :languageid
				LEFT JOIN language L ON L.idlanguage = TD.languageid 
				LEFT JOIN `user` U ON T.addid = U.iduser
				LEFT JOIN `userdata` UDA ON U.iduser = UDA.userid
				LEFT JOIN `user` UE ON T.editid = UE.iduser
				LEFT JOIN `userdata` UDE ON U.iduser = UDE.userid
			');
	
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getTranslationNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('translation', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getTranslationForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteTranslation ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteTranslation'
		), $this->getName());
	}

	public function deleteTranslation ($id)
	{
		try{
			$this->flushCacheTranslations();
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idtranslation' => $id
			), $this->getName(), 'deleteTranslation');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXUpdateTranslation ($id, $translation)
	{
		$sql = 'UPDATE translation SET editid = :editid WHERE idtranslation = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		//FIXME: obsługa try/catch
		$stmt->executeUpdate();
		
		$sql = 'DELETE FROM translationdata WHERE translationid = :translationid AND languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('translationid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		//FIXME: obsługa try/catch
		$stmt->executeUpdate();
		
		$sql = 'INSERT INTO translationdata SET 
					translation=:translation,
					translationid = :translationid,
					languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('translationid', $id);
		$stmt->setString('translation', $translation);
		$stmt->setInt('languageid', Helper::getLanguageId());
		//FIXME: obsługa try/catch
		$stmt->executeUpdate();
		$this->flushCacheTranslations();
	}

	public function getTranslationView ($id)
	{
		$sql = "SELECT idtranslation AS id, name FROM translation
				WHERE idtranslation = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'language' => $this->getTranslation($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_NO_EXIST'));
		}
		return $Data;
	}

	public function getTranslation ($id)
	{
		$sql = "SELECT translation,languageid
					FROM translationdata
					WHERE translationid =:id";
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

	public function editTranslation ($Data, $id)
	{
		$sql = 'UPDATE translation SET name=:name, editid=:editid WHERE idtranslation = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
			$this->flushCacheTranslations();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_EDIT'), 13, $e->getMessage());
			return false;
		}
		
		$sql = 'DELETE FROM translationdata WHERE translationid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['translation'] as $key => $val){
			$sql = 'INSERT INTO translationdata (translationid,translation, languageid)
						VALUES (:translationid,:translation,:languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('translationid', $id);
			$stmt->setString('translation', $Data['translation'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_EDIT'), 13, $e->getMessage());
			}
		}
		return true;
	}

	public function addTranslation ($Data)
	{
		$sql = 'INSERT INTO translation (name,addid) VALUES (:name, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $Data['name']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_ADD'), 14, $e->getMessage());
		}
		
		$translationid = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($Data['translation'] as $key => $val){
			$sql = 'INSERT INTO translationdata (translationid,translation, languageid)
						VALUES (:translationid,:translation,:languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('translationid', $translationid);
			$stmt->setString('translation', $Data['translation'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_TRANSLATION_ADD'), 14, $e->getMessage());
			}
		}
		$this->flushCacheTranslations();
		return $translationid;
	}

	public function flushCacheTranslations ()
	{
		App::getModel('updater')->clearCache(ROOTPATH . DS . 'cache', false);
		Cache::destroyObject('translations');
	}
}