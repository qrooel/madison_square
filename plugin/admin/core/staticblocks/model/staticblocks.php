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
 * $Id: staticblocks.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class staticblocksModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('staticcontent', Array(
			'idstaticcontent' => Array(
				'source' => 'SC.idstaticcontent'
			),
			'publish' => Array(
				'source' => 'SC.publish'
			),
			'topic' => Array(
				'source' => 'SCT.topic',
				'prepareForAutosuggest' => true
			),
			'hierarchy' => Array(
				'source' => 'SC.hierarchy'
			),
			'name' => Array(
				'source' => 'CT.name',
				'prepareForSelect' => true
			)
		));
		$datagrid->setFrom('
				staticcontent SC
				LEFT JOIN staticcontentview SCV ON SCV.staticcontentid = SC.idstaticcontent
				LEFT JOIN staticcontenttranslation SCT ON SC.idstaticcontent = SCT.staticcontentid AND SCT.languageid = :languageid
				LEFT JOIN language L ON L.idlanguage=SCT.languageid
				LEFT JOIN contentcategory C ON C.idcontentcategory=SC.contentcategoryid
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
			');
		$datagrid->setGroupBy('
				idstaticcontent
			');
		
		if (Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
					SCV.viewid = :viewid
				');
		}
	}

	public function getTopicForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('topic', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getStaticBlocksForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteStaticBlocks ($datagrid, $id)
	{
		$this->deleteStaticBlocks($id);
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteStaticBlocks ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idstaticcontent' => $id
			), $this->getName(), 'deleteStaticBlocks');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnableStaticBlocks ($datagridId, $id)
	{
		try{
			$this->enableStaticBlocks($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableStaticBlocks ($datagridId, $id)
	{
		try{
			$this->disableStaticBlocks($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_STATICBLOCKS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableStaticBlocks ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception('ERR_CAN_NOT_DISABLE_YOURSELF');
		}
		$sql = 'UPDATE staticcontent SET publish = 0 WHERE idstaticcontent = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableStaticBlocks ($id)
	{
		if ($id == $this->registry->session->getActiveUserid()){
			throw new Exception('ERR_CAN_NOT_ENABLE_YOURSELF');
		}
		$sql = 'UPDATE staticcontent SET publish = 1 WHERE idstaticcontent = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getStaticBlocksView ($id)
	{
		$sql = "SELECT publish, contentcategoryid
					FROM staticcontent SC
					WHERE idstaticcontent = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'language' => $this->getStaticBlocksTranslation($id),
				'publish' => $rs->getInt('publish'),
				'contentcategory' => $rs->getInt('contentcategoryid'),
				'view' => $this->getStaticContentViews($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_STATIC_BLOCKS_NO_EXIST'));
		}
		return $Data;
	}

	public function getStaticContentViews ($id)
	{
		$sql = "SELECT viewid FROM staticcontentview WHERE staticcontentid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getStaticBlocksTranslation ($id)
	{
		$sql = "SELECT topic,content,languageid
					FROM staticcontenttranslation
					WHERE staticcontentid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'topic' => $rs->getString('topic'),
				'content' => $rs->getString('content')
			);
		}
		return $Data;
	}

	public function publish ()
	{
		$Data = Array(
			'1' => $this->registry->core->getMessage('TXT_YES'),
			'0' => $this->registry->core->getMessage('TXT_NO')
		);
		return $Data;
	}

	public function addNewStaticBlocks ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newStaticBlocksId = $this->addStaticBlocks($Data);
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addStaticBlocksView($Data['view'], $newStaticBlocksId);
			}
			$this->addStaticBlocksTranslation($Data, $newStaticBlocksId);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addStaticBlocks ($Data)
	{
		$sql = 'INSERT INTO staticcontent(contentcategoryid, publish, addid)
					VALUES (:contentcategoryid, :publish, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('contentcategoryid', $Data['contentcategory']);
		if (isset($Data['publish']) && $Data['publish'] == 1){
			$stmt->setInt('publish', 1);
		}
		else{
			$stmt->setInt('publish', 0);
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addStaticBlocksView ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO staticcontentview (staticcontentid ,viewid, addid)
						VALUES (:staticcontentid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('staticcontentid', $id);
			$stmt->setInt('viewid', $value);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_VIEW_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function addStaticBlocksTranslation ($Data, $id)
	{
		foreach ($Data['topic'] as $key => $val){
			$sql = 'INSERT INTO staticcontenttranslation (staticcontentid,topic, content, languageid)
						VALUES (:staticcontentid,:topic, :content, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticcontentid', $id);
			$stmt->setString('topic', $Data['topic'][$key]);
			$stmt->setString('content', $Data['content'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_TRANSLATIONS_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function editStaticBlocks ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateStaticBlocks($Data, $id);
			$this->updateStaticBlocksTranslation($Data, $id);
			$this->updateStaticBlocksView($Data['view'], $id);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateStaticBlocks ($Data, $id)
	{
		$sql = 'UPDATE staticcontent 
					SET contentcategoryid = :category,
						publish = :publish,
						editid = :editid 
					WHERE idstaticcontent = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		if (isset($Data['publish']) && $Data['publish'] == 1){
			$stmt->setInt('publish', 1);
		}
		else{
			$stmt->setInt('publish', 0);
		}
		if ($Data['contentcategoryid'] == 0){
			$stmt->setNull('category');
		}
		else{
			$stmt->setInt('category', $Data['contentcategoryid']);
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_EDIT'), 13, $e->getMessage());
			return false;
		}
	}

	public function updateStaticBlocksView ($Data, $id)
	{
		$sql = 'DELETE FROM staticcontentview WHERE staticcontentid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! empty($Data)){
			foreach ($Data as $value){
				$sql = 'INSERT INTO staticcontentview (staticcontentid,viewid, addid)
							VALUES (:staticcontentid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->setInt('staticcontentid', $id);
				$stmt->setInt('viewid', $value);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_VIEW_EDIT'), 4, $e->getMessage());
				}
			}
		}
	}

	public function doAJAXUpdateStaticblocks ($id, $hierarchy)
	{
		$sql = 'UPDATE staticcontent SET 
					hierarchy = :hierarchy
				WHERE idstaticcontent = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('hierarchy', $hierarchy);
		$stmt->executeUpdate();
	}

	public function updateStaticBlocksTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM staticcontenttranslation WHERE staticcontentid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['topic'] as $key => $val){
			$sql = 'INSERT INTO staticcontenttranslation (staticcontentid,topic, content, languageid)
						VALUES (:staticcontentid,:topic, :content, :languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('staticcontentid', $id);
			$stmt->setString('topic', $Data['topic'][$key]);
			$stmt->setString('content', $Data['content'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_STATICBLOCKS_TRANSLATION_ADD'), 4, $e->getMessage());
			}
		}
	}
}