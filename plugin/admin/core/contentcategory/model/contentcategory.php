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
 * $Id: contentcategory.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class contentcategoryModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('contentcategory', Array(
			'idcontentcategory' => Array(
				'source' => 'C.idcontentcategory'
			),
			'name' => Array(
				'source' => 'CT.name',
				'prepareForAutosuggest' => true
			),
			'url' => Array(
				'source' => 'CONCAT(C.idcontentcategory,\'<::>\',CT.name)',
				'processFunction' => Array(
					$this,
					'getCategoryUrl'
				)
			),
			'adddate' => Array(
				'source' => 'C.adddate'
			),
			'hierarchy' => Array(
				'source' => 'C.hierarchy'
			)
		));
		$datagrid->setFrom('
				contentcategory C
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
			');
		$datagrid->setGroupBy('
				idcontentcategory
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NOT NULL,CCV.viewid = :viewid,1)
			');
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getContentCategoryForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteContentCategory ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteContentCategory'
		), $this->getName(), Array(
			'contentcategory',
			'sitemapcategories'
		));
	}

	public function deleteContentCategory ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idcontentcategory' => $id
			), $this->getName(), 'deleteContentCategory');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getCategoryUrl ($Data)
	{
		$url = explode('<::>', $Data);
		$seo = strtolower(App::getModel('seo')->clearSeoUTF($url[1]));
		return App::getURLAdress() . $this->registry->core->getFrontendControllerNameForSeo('staticcontent') . '/' . $url[0] . '/' . $seo;
	}

	public function getContentCategoryALL ($exclude = 0)
	{
		$sql = 'SELECT 
					C.idcontentcategory AS id, 
					CT.name as contentcategory ,
					C.hierarchy,
					C.contentcategoryid AS parent
				FROM contentcategory C
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($rs->getInt('id') != $exclude){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'hierarchy' => $rs->getInt('hierarchy'),
					'contentcategory' => $rs->getString('contentcategory'),
					'parent' => $rs->getInt('parent')
				);
			}
		}
		return $Data;
	}

	public function getContentCategoryALLToSelect ()
	{
		$Data = $this->getContentCategoryALL();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['contentcategory'];
		}
		return $tmp;
	}

	public function addNewContentCategory ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$newContentCategoryId = $this->addContentCategory($Data);
			if (is_array($Data['view']) && ! empty($Data['view'])){
				$this->addContentCategoryView($Data['view'], $newContentCategoryId);
			}
			$this->addContentCategoryTranslation($Data, $newContentCategoryId);
			$event = new sfEvent($this, 'admin.contentcategory.model.save', Array(
				'id' => $newContentCategoryId,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
			$this->registry->dispatcher->notify($event);
			$this->flushCache();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_ADD'), 11, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addContentCategory ($Data)
	{
		$sql = 'INSERT INTO contentcategory(contentcategoryid,header,footer, addid)
					VALUES (:contentcategoryid,:header,:footer, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($Data['contentcategoryid'] == 0){
			$stmt->setNull('contentcategoryid');
		}
		else{
			$stmt->setInt('contentcategoryid', $Data['contentcategoryid']);
		}
		if (isset($Data['header']) && $Data['header'] == 1){
			$stmt->setInt('header', 1);
		}
		else{
			$stmt->setInt('header', 0);
		}
		if (isset($Data['footer']) && $Data['footer'] == 1){
			$stmt->setInt('footer', 1);
		}
		else{
			$stmt->setInt('footer', 0);
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATGEORY_ADD'), 4, $e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function addContentCategoryTranslation ($Data, $id)
	{
		foreach ($Data['name'] as $key => $value){
			$sql = 'INSERT INTO contentcategorytranslation (contentcategoryid,name,keyword_title, keyword,keyword_description,languageid)
						VALUES (:contentcategoryid,:name,:keyword_title, :keyword,:keyword_description,:languageid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('contentcategoryid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('keyword_title', $Data['keyword_title'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyword_description'][$key]);
			$stmt->setInt('languageid', $key);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_TRANSLATION_ADD'), 13, $e->getMessage());
			}
		}
	}

	public function addContentCategoryView ($Data, $contentcategoryid)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO contentcategoryview (contentcategoryid, viewid, addid)
						VALUES (:contentcategoryid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('contentcategoryid', $contentcategoryid);
			$stmt->setInt('viewid', $value);
			try{
				echo $stmt->getSQLDebug();
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATGEORY_VIEW_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function getContentCategoryView ($id)
	{
		$sql = "SELECT 
					C.idcontentcategory AS id,
					C.header,
					C.footer,
					CT.name, 
					IF ( C.contentcategoryid > 0, C.contentcategoryid, 0) as catid
				FROM contentcategory C 
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
				WHERE C.idcontentcategory = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'language' => $this->getContentCategoryTranslation($id),
				'contentcategory' => $rs->getInt('catid'),
				'header' => $rs->getInt('header'),
				'footer' => $rs->getInt('footer'),
				'view' => $this->getContentCategoryViews($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_NO_EXIST'));
		}
		return $Data;
	}

	public function getContentCategoryViews ($id)
	{
		$sql = "SELECT viewid
					FROM contentcategoryview
					WHERE contentcategoryid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getContentCategoryTranslation ($id)
	{
		$sql = "SELECT 
					name,
					keyword_title, 
					keyword,
					keyword_description, 
					languageid
				FROM contentcategorytranslation
				WHERE contentcategoryid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}

	public function editContentCategory ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->updateContentCategory($Data, $id);
			$this->updateContentCategoryTranslation($Data, $id);
			$this->updateContentCategoryView($Data['view'], $id);
			$event = new sfEvent($this, 'admin.contentcategory.model.save', Array(
				'id' => $id,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
			$this->flushCache();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_EDIT'), 125, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function updateContentCategory ($Data, $id)
	{
		$sql = 'UPDATE contentcategory SET 
					header = :header,
					footer = :footer,
					contentcategoryid=:contentcategoryid, 
					editid=:editid 
				WHERE idcontentcategory =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($Data['contentcategoryid'] == 0){
			$stmt->setNull('contentcategoryid');
		}
		else{
			$stmt->setInt('contentcategoryid', $Data['contentcategoryid']);
		}
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		if (isset($Data['header']) && $Data['header'] == 1){
			$stmt->setInt('header', 1);
		}
		else{
			$stmt->setInt('header', 0);
		}
		if (isset($Data['footer']) && $Data['footer'] == 1){
			$stmt->setInt('footer', 1);
		}
		else{
			$stmt->setInt('footer', 0);
		}
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_EDIT'), 13, $e->getMessage());
		}
	}

	public function updateContentCategoryTranslation ($Data, $id)
	{
		$sql = 'DELETE FROM contentcategorytranslation WHERE contentcategoryid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		$this->addContentCategoryTranslation($Data, $id);
	}

	public function updateContentCategoryView ($Data, $id)
	{
		$sql = 'DELETE FROM contentcategoryview WHERE contentcategoryid = :id';
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
				$sql = 'INSERT INTO contentcategoryview (contentcategoryid, viewid, addid)
							VALUES (:contentcategoryid, :viewid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->setInt('contentcategoryid', $id);
				$stmt->setInt('viewid', $value);
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CONTENT_CATEGORY_VIEW_EDIT'), 4, $e->getMessage());
				}
			}
		}
	}

	public function doAJAXUpdateContentCategory ($id, $hierarchy)
	{
		$sql = 'UPDATE contentcategory SET 
					hierarchy = :hierarchy
				WHERE idcontentcategory = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('hierarchy', $hierarchy);
		$stmt->executeUpdate();
		$this->flushCache();
	}

	public function flushCache ()
	{
		Cache::destroyObject('contentcategory');
		Cache::destroyObject('sitemapcategories');
	}
}