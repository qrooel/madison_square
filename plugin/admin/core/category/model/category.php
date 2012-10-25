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
 * $Revision: 694 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:25:00 +0200 (Cz, 06 wrz 2012) $
 * $Id: category.php 694 2012-09-06 21:25:00Z gekosale $
 */
class categoryModel extends Model {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	public function doAJAXDeleteCategory ($id, $datagrid) {
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteCategory'
		), $this->getName());
	}

	public function deleteCategory ($id) {
		try{
			$integrationModels = App::getModel('integration')->getIntegrationModelAll();
			foreach ($integrationModels as $key => $model){
				if (method_exists(App::getModel('integration/' . $model['model']), 'Delete')){
					App::getModel('integration/' . $model['model'])->Delete($id['id']);
				}
			}
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			$categoryChild = $this->getChildCategoriesTreeById($id['id']);
			foreach ($categoryChild as $record){
				foreach ($integrationModels as $key => $model){
					if (method_exists(App::getModel('integration/' . $model['model']), 'Delete')){
						App::getModel('integration/' . $model['model'])->Delete($record);
					}
				}
				$dbtracker->run(Array(
					'idcategory' => $record
				), $this->getName(), 'deleteCategory');
			}
			$dbtracker->run(Array(
				'idcategory' => $id['id']
			), $this->getName(), 'deleteCategory');
			return $this->flushCache();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getCategoryView ($id) {
		$sql = 'SELECT C.idcategory AS id, C.categoryid AS catid, C.photoid, C.distinction,C.enable
				FROM category C
				WHERE C.idcategory=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'catid' => $rs->getInt('catid'),
				'photoid' => $rs->getString('photoid'),
				'distinction' => $rs->getInt('distinction'),
				'enable' => $rs->getInt('enable'),
				'language' => $this->getCategoryTranslation($id),
				'view' => $this->getCategoryViews($id)
			);
			return $Data;
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getCategoryViews ($id) {
		$sql = "SELECT viewid
					FROM viewcategory
					WHERE categoryid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('viewid');
		}
		return $Data;
	}

	public function getCategoryTranslation ($id) {
		$sql = "SELECT 
					name,
					shortdescription, 
					description, 
					seo, 
					languageid, 
					keyword_title, 
					keyword, 
					keyword_description
				FROM categorytranslation
				WHERE categoryid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'seo' => $rs->getString('seo'),
				'keywordtitle' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyworddescription' => $rs->getString('keyword_description')
			);
		}
		
		return $Data;
	}

	public function addEmptyCategory ($request) {
		$data = Array(
			'categoryid' => isset($request['parent']) ? $request['parent'] : null,
			'shortdescription' => null,
			'description' => null,
			'discount' => null,
			'photo' => null,
			'name' => (isset($request['name']) && strlen($request['name'])) ? $request['name'] : $this->registry->core->getMessage('TXT_NEW_CATEGORY')
		);
		return Array(
			'id' => $this->addCategory($data)
		);
	}

	public function changeCategoryOrder ($request) {
		if (! isset($request['items']) || ! is_array($request['items'])){
			throw new Exception('No data received.');
		}
		$sql = '
				UPDATE
					category
				SET
					categoryid = :categoryid,
					distinction = :distinction
				WHERE
					idcategory = :id
			';
		foreach ($request['items'] as $item){
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $item['id']);
			$stmt->setInt('distinction', $item['weight']);
			if (! isset($item['parent']) || empty($item['parent'])){
				$stmt->setNull('categoryid', $item['parent']);
			}
			else{
				$stmt->setInt('categoryid', $item['parent']);
			}
			$stmt->executeUpdate();
		}
		$this->getCategoriesPathById();
		$this->flushCache();
		return Array(
			'status' => $this->registry->core->getMessage('TXT_CATEGORY_ORDER_SAVED')
		);
	}

	public function editCategory ($Data, $id) {
		$this->registry->db->setAutoCommit(false);
		
		$sql = 'UPDATE category SET 
					categoryid=:categoryid, 
					distinction = :distinction,
					enable = :enable
				WHERE idcategory = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		if (($Data['categoryid']) > 0){
			$stmt->setInt('categoryid', $Data['categoryid']);
		}
		else{
			$stmt->setNull('categoryid');
		}
		if (isset($Data['enable']) && $Data['enable'] == 1){
			$stmt->setInt('enable', $Data['enable']);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		$stmt->setInt('distinction', $Data['distinction']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
			return false;
		}
		
		if ($Data['photo']['unmodified'] == 0){
			$sql = 'UPDATE category SET photoid = :photo
					WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			if (($Data['photo'][0]) > 0){
				$stmt->setInt('photo', $Data['photo'][0]);
			}
			else{
				$stmt->setNull('photo');
			}
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				return false;
			}
		}
		$sql = 'DELETE FROM categorytranslation WHERE categoryid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO categorytranslation (categoryid,name,shortdescription, description, languageid, seo, keyword_title, keyword, keyword_description)
						VALUES (:categoryid,:name,:shortdescription, :description, :languageid, :seo, :keyword_title, :keyword, :keyword_description)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('keyword_title', $Data['keywordtitle'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyworddescription'][$key]);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
			}
		}
		
		$sql = 'DELETE FROM viewcategory WHERE categoryid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['view'] as $key => $val){
			$sql = 'INSERT INTO viewcategory (categoryid,viewid, addid)
						VALUES (:categoryid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('categoryid', $id);
			$stmt->setInt('viewid', $val);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_VIEW_ADD'), 4, $e->getMessage());
			}
		}
		
		$sql = 'DELETE FROM productcategory WHERE categoryid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeUpdate();
		
		foreach ($Data['products'] as $key => $val){
			$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
					VALUES (:productid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $val);
			$stmt->setInt('categoryid', $id);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_ADD'), 11, $e->getMessage());
			}
		}
		
		$sql = 'UPDATE category SET enable = :enable WHERE idcategory IN (SELECT categoryid FROM categorypath WHERE ancestorcategoryid = :id)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		if (isset($Data['enable']) && $Data['enable'] == 1){
			$stmt->setInt('enable', $Data['enable']);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		$stmt->executeUpdate();
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		
		$event = new sfEvent($this, 'admin.category.model.save', Array(
			'id' => $id,
			'data' => $Data
		));
		$this->registry->dispatcher->notify($event);
		
		$this->getCategoriesPathById();
		$this->flushCache();
		return true;
	}

	public function addCategory ($Data) {
		$sql = 'INSERT INTO category (categoryid, addid)
					VALUES (:categoryid, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($Data['categoryid'] != 0){
			$stmt->setInt('categoryid', $Data['categoryid']);
		}
		else{
			$stmt->setNull('categoryid');
		}
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_ADD'), 3003, $e->getMessage());
		}
		
		$categoryid = $stmt->getConnection()->getIdGenerator()->getId();
		
		if ($Data['photo']['unmodified'] == 0){
			$sql = 'UPDATE category SET photoid = :photo
					WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $categoryid);
			if (($Data['photo'][0]) > 0){
				$stmt->setInt('photo', $Data['photo'][0]);
			}
			else{
				$stmt->setNull('photo');
			}
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				return false;
			}
		}
		else{
			$sql = 'UPDATE category SET photoid = (SELECT photoid FROM category WHERE idcategory = :previous)
					WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $categoryid);
			$stmt->setInt('previous', $this->registry->core->getParam());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				return false;
			}
		}
		
		$this->getCategoriesPathById();
		
		$sql = 'INSERT INTO categorytranslation (categoryid,name,shortdescription, description, languageid)
				VALUES (:categoryid,:name,:shortdescription, :description, :languageid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', $categoryid);
		$stmt->setString('name', Core::clearNonAlpha($Data['name']));
		$stmt->setString('shortdescription', $Data['shortdescription']);
		$stmt->setString('description', $Data['description']);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->executeUpdate();
		
		$seo = App::getModel('seo')->doAJAXCreateSeoCategory(Array(
			'name' => Core::clearNonAlpha($Data['name']),
			'language' => Helper::getLanguageId(),
			'id' => $categoryid
		));
		
		$sql = 'UPDATE categorytranslation SET seo = :seo WHERE categoryid = :categoryid AND languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', $categoryid);
		$stmt->setString('seo', $seo['seo']);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->executeUpdate();
		
		$this->flushCache();
		return $categoryid;
	}

	public function getPhotoCategoryById ($id) {
		$sql = 'SELECT photoid
					FROM category C 
					LEFT JOIN file F ON F.idfile = C.photoid
					WHERE C.idcategory=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data[] = $rs->getInt('photoid');
		}
		return $Data;
	}

	public function getPhotos (&$category) {
		if (! is_array($category)){
			throw new Exception('Wrong array given');
		}
		foreach ($category['photo'] as $photo){
			$category['photo']['small'][] = App::getModel('gallery')->getSmallImageById($photo['photoid']);
		}
	}

	public function getChildCategoriesTreeById ($id, &$childCategories = NULL) {
		$sql = 'SELECT idcategory FROM category WHERE categoryid = :categoryid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('categoryid', $id);
		$rs = $stmt->executeQuery();
		if ($childCategories === NULL){
			$childCategories = Array();
		}
		while ($rs->next()){
			$childCategories[] = $rs->getInt('idcategory');
			$this->getChildCategoriesTreeById($rs->getInt('idcategory'), $childCategories);
		}
		return array_reverse($childCategories);
	}

	public function getParentCategoriesTreeById ($id, &$parentCategories = NULL) {
		$sql = 'SELECT categoryid FROM category WHERE idcategory IN (:id)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($parentCategories === NULL){
			$parentCategories = Array();
		}
		while ($rs->next()){
			$parentCategories[] = $rs->getInt('categoryid');
			$this->getParentCategoriesTreeById(Array(
				$rs->getInt('categoryid')
			), $parentCategories);
		}
		return array_reverse($parentCategories);
	}

	public function getChildCategories ($parentCategory = 0, $active = Array()) {
		$globaluser = $this->registry->session->getActiveUserIsGlobal();
		
		if ($parentCategory == 0){
			if (count($active) > 0){
				$parentTree = $this->getParentCategoriesTreeById($active);
				$sql = 'SELECT
							C.idcategory AS id, 
							C.distinction, 
							C.categoryid AS parent, 
							CT.name AS categoryname, 
							(SELECT COUNT( idcategory ) 
								FROM category
								WHERE categoryid = C.idcategory
							) AS haschildren
						FROM category C
						LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid
						LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
						WHERE (C.categoryid IN (:parent) OR C.categoryid IS NULL) 
					';
			}
			else{
				$sql = 'SELECT
							C.idcategory AS id, 
							C.distinction, 
							C.categoryid AS parent, 
							CT.name AS categoryname, 
							(SELECT COUNT( idcategory ) 
								FROM category
								WHERE categoryid = C.idcategory
							) AS haschildren
						FROM category C
						LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid
						LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
						WHERE C.categoryid IS NULL 
					';
			}
		}
		else{
			$sql = 'SELECT
						C.idcategory AS id, 
						C.distinction, 
						C.categoryid AS parent, 
						CT.name AS categoryname, 
						(SELECT COUNT( idcategory ) 
							FROM category
							WHERE categoryid = C.idcategory
						) AS haschildren
						FROM category C
						LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid
						LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
						WHERE C.categoryid = :parent 
					';
		}
		if (Helper::getViewId() > 0){
			$sql .= ' AND VC.viewid IN (:viewids) ';
		}
		$sql .= 'GROUP BY C.idcategory ORDER BY C.distinction ASC';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setInt('parent', $parentCategory);
		if (isset($parentTree)){
			$stmt->setINInt('parent', $parentTree);
		}
		if ((! isset($parentTree) || empty($parentTree)) && empty($parentCategory)){
			$stmt->setNull('parent');
		}
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('categoryname'),
				'hasChildren' => ($rs->getInt('haschildren') > 0) ? true : false,
				'parent' => ($rs->getInt('parent') == 0) ? null : $rs->getInt('parent'),
				'weight' => $rs->getInt('distinction')
			);
		}
		return $Data;
	}

	public function getParentCategories ($parentCategory = 0) {
		$sql = 'SELECT
					C.idcategory AS id, 
					C.distinction, 
					CT.name AS categoryname
				FROM category C
				LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid
				LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
				WHERE C.categoryid IS NULL
		';
		
		if (Helper::getViewId() > 0){
			$sql .= ' AND VC.viewid IN (:viewids) ';
		}
		$sql .= 'GROUP BY C.idcategory ORDER BY C.distinction ASC';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setINInt('viewids', Helper::getViewIds());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('id')] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('categoryname'),
				'hasChildren' => false,
				'parent' => null,
				'weight' => $rs->getInt('distinction')
			);
		}
		return $Data;
	}

	public function getParentCategoriesPathById ($id, &$parentCategories = NULL) {
		$sql = 'SELECT categoryid FROM category WHERE idcategory = :id AND categoryid IS NOT NULL';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($parentCategories === NULL){
			$parentCategories = Array();
		}
		while ($rs->next()){
			$parentCategories[] = $rs->getInt('categoryid');
			$this->getParentCategoriesPathById($rs->getInt('categoryid'), $parentCategories);
		}
		return array_reverse($parentCategories);
	}

	public function getCategoriesPathById () {
		$this->registry->db->setAutoCommit(false);
		
		$sql = 'TRUNCATE categorypath';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->executeQuery();
		
		$sql = 'SELECT idcategory AS id, categoryid AS parent FROM category';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		$parents = Array();
		foreach ($Data as $category){
			if ($category['parent']){
				$parents[$category['id']] = $category['parent'];
			}
			else{
				$parents[$category['id']] = null;
			}
		}
		$alreadyAdded = Array();
		foreach ($parents as $category => $ancestor){
			$order = 0;
			$ancestor = $category;
			for ($i = 0; $i < 50; $i ++){
				if (! isset($alreadyAdded[$category]) || ! isset($alreadyAdded[$category][$ancestor]) || ! $alreadyAdded[$category][$ancestor]){
					$sql = '
							INSERT INTO categorypath
							SET 
								categoryid = :categoryid, 
								ancestorcategoryid = :ancestorcategoryid, 
								`order` = :order
						';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('categoryid', $category);
					$stmt->setInt('ancestorcategoryid', $ancestor);
					$stmt->setInt('order', $order ++);
					$rs = $stmt->executeQuery();
					$alreadyAdded[$category][$ancestor] = true;
				}
				if ($parents[$ancestor] == null){
					break;
				}
				$ancestor = $parents[$ancestor];
			}
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->flushCache();
	}

	public function checkParentValue ($value, $params) {
		if ($value == $params['categoryid']){
			return false;
		}
		return true;
	}

	public function flushCache () {
		Cache::destroyObject('categories');
		Cache::destroyObject('sitemapcategories');
	}

	public function duplicateCategory ($Data) {
		$sql = 'INSERT INTO category (categoryid,distinction,enable, addid)
					VALUES (:categoryid,:distinction,:enable, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($Data['categoryid'] != 0){
			$stmt->setInt('categoryid', $Data['categoryid']);
		}
		else{
			$stmt->setNull('categoryid');
		}
		if (isset($Data['enable']) && $Data['enable'] == 1){
			$stmt->setInt('enable', $Data['enable']);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		$stmt->setInt('distinction', $Data['distinction']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_ADD'), 3003, $e->getMessage());
		}
		
		$categoryid = $stmt->getConnection()->getIdGenerator()->getId();
		
		if ($Data['photo']['unmodified'] == 0){
			$sql = 'UPDATE category SET photoid = :photo
					WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $categoryid);
			if (($Data['photo'][0]) > 0){
				$stmt->setInt('photo', $Data['photo'][0]);
			}
			else{
				$stmt->setNull('photo');
			}
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				return false;
			}
		}
		else{
			$sql = 'UPDATE category SET photoid = :photoid
					WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $categoryid);
			$photo = $this->getPhotoCategoryById((int) $this->registry->core->getParam());
			$stmt->setInt('photoid', $photo[0]);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				return false;
			}
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO categorytranslation (categoryid,name,shortdescription, description, languageid, seo, keyword_title, keyword, keyword_description)
						VALUES (:categoryid,:name,:shortdescription, :description, :languageid, :seo, :keyword_title, :keyword, :keyword_description)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $categoryid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setString('seo', $Data['seo'][$key]);
			$stmt->setString('keyword_title', $Data['keywordtitle'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyworddescription'][$key]);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
			}
		}
		
		foreach ($Data['view'] as $key => $val){
			$sql = 'INSERT INTO viewcategory (categoryid,viewid, addid)
						VALUES (:categoryid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('categoryid', $categoryid);
			$stmt->setInt('viewid', $val);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
			}
		}
		
		$this->flushCache();
		return true;
	}

	public function getProductsDataGrid ($id) {
		$sql = "SELECT 
					productid
 				FROM productcategory
				WHERE categoryid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('productid');
		}
		return $Data;
	}

	public function getCategoryAll () {
		$sql = 'SELECT
					C.idcategory AS id,
					C.categoryid AS parent, 
					CT.name as categoryname,
					CT.seo as seo,
					CT.keyword_title,
					CT.keyword,
					CT.keyword_description,
					C.distinction
				FROM category C
				LEFT JOIN categorytranslation CT ON CT.categoryid = C.idcategory AND CT.languageid = :languageid
		';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $this->registry->session->getActiveLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'categoryname' => $rs->getString('categoryname'),
				'seo' => $rs->getString('seo'),
				'keywordtitle' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyworddescription' => $rs->getString('keyword_description'),
				'distinction' => $rs->getInt('distinction'),
				'parent' => $rs->getInt('parent')
			);
		}
		return $Data;
	}
}