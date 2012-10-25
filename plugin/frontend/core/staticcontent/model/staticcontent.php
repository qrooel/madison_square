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
 * $Id: staticcontent.php 655 2012-04-24 08:51:44Z gekosale $
 */

class staticcontentModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
		$this->viewid = Helper::getViewId();
		$this->languageid = Helper::getLanguageId();
		$this->_rawData = Array();
	}

	public function getStaticContent ($id)
	{
		$sql = "SELECT
					SC.contentcategoryid as id,
					SCT.topic, 
					SCT.content, 
					SC.publish, 
					SCV.viewid
				FROM staticcontent SC
				LEFT JOIN staticcontentview SCV ON SCV.staticcontentid = SC.idstaticcontent
				LEFT JOIN staticcontenttranslation SCT ON SC.idstaticcontent = SCT.staticcontentid AND SCT.languageid = :languageid
				WHERE SC.contentcategoryid = :id AND SC.publish = 1 AND SCV.viewid = :viewid
				ORDER BY SC.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'topic' => $rs->getString('topic'),
				'content' => $rs->getString('content'),
				'undercategorybox' => $this->getUnderCategoryBox($id),
				'seo' => strtolower(App::getModel('seo')->clearSeoUTF($rs->getString('topic')))
			);
		}
		
		return $Data;
	}

	public function getUnderCategoryBox ($id)
	{
		$sql = "SELECT C.idcontentcategory AS id, CCT.name
				FROM contentcategory C
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
				LEFT JOIN contentcategorytranslation CCT ON CCT.contentcategoryid = C.idcontentcategory
				WHERE C.contentcategoryid =:id AND CCV.viewid=:viewid AND languageid=:languageid
				ORDER BY C.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', $this->viewid);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	protected function _loadRawContentCategoriesFromDatabase ()
	{
		
		$sql = 'SELECT 
					C.idcontentcategory AS id, 
					CT.name AS name, 
					CCV.viewid, 
					C.contentcategoryid as parent,
					C.header,
					C.footer
				FROM contentcategory C
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
				WHERE CCV.viewid = :viewid
				ORDER BY C.hierarchy ASC
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'viewid' => $rs->getInt('viewid'),
				'parent' => $rs->getInt('parent'),
				'header' => $rs->getInt('header'),
				'footer' => $rs->getInt('footer'),
				'seo' => strtolower(App::getModel('seo')->clearSeoUTF($rs->getString('name')))
			);
		}
		$this->_rawData = $Data;
	
	}

	public function getContentCategoriesTree ()
	{
		if (($categories = Cache::loadObject('contentcategory')) === FALSE){
			$this->_loadRawContentCategoriesFromDatabase();
			$categories = $this->_parseCategorySubtree();
			Cache::saveObject('contentcategory', $categories, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		return $categories;
	}

	protected function _parseCategorySubtree ($level = 0, $parent = null)
	{
		$categories = Array();
		foreach ($this->_rawData as $category){
			if ($parent == null){
				if ($category['parent'] != ''){
					continue;
				}
			}
			elseif ($category['parent'] != $parent){
				continue;
			}
			$categories[] = Array(
				'id' => $category['id'],
				'header' => $category['header'],
				'footer' => $category['footer'],
				'name' => $category['name'],
				'seo' => $category['seo'],
				'children' => $this->_parseCategorySubtree($level + 1, $category['id'])
			);
		}
		return $categories;
	}

	public function getContentCategoryTree ()
	{
		
		$sql = 'SELECT C.idcontentcategory AS id, CT.name AS parentcategory, CCV.viewid, C.contentcategoryid
				FROM contentcategory C
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
				WHERE C.contentcategoryid  IS NULL AND CCV.viewid = :viewid
				ORDER BY C.hierarchy ASC';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		$tmp = Array();
		foreach ($Data as $index => $parent){
			$tmp = $this->getUnderCategory($parent['id']);
			$Data[$index]['child'] = $tmp;
		}
		return $Data;
	}

	public function getUnderCategory ($id)
	{
		$sql = "SELECT
						C.idcontentcategory AS id, 
						CT.name AS undercategory
					FROM contentcategory C 
					LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
					LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
					WHERE C.contentcategoryid=:id AND CCV.viewid = :viewid
					ORDER BY C.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'undercategory' => $rs->getString('undercategory'),
				'id' => $rs->getInt('id')
			);
		}
		return $Data;
	}

	public function getBoxHeadingName ($contentcategoryid)
	{
		$sql = "SELECT 
					CCT.name
				FROM contentcategorytranslation CCT
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = CCT.contentcategoryid
 				WHERE languageid = :languageid AND viewid = :viewid AND CCT.contentcategoryid =:contentcategoryid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$stmt->setInt('contentcategoryid', $contentcategoryid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'seo' => strtolower(App::getModel('seo')->clearSeoUTF($rs->getString('name')))
			);
		}
		return $Data;
	}

	public function getMetaData ($contentcategoryid)
	{
		$Data = Array();
		$sql = 'SELECT 
					CCT.name,
					CCT.keyword_title AS keyword_title, 
					IF(CCT.keyword = \'\',VT.keyword, CCT.keyword) AS keyword, 
					IF(CCT.keyword_description = \'\',VT.keyword_description,CCT.keyword_description) AS keyword_description 
				FROM contentcategorytranslation CCT
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = CCT.contentcategoryid
				LEFT JOIN viewtranslation VT ON VT.viewid = CCV.viewid
				WHERE CCT.contentcategoryid =:contentcategoryid AND CCT.languageid = :languageid';
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setInt('contentcategoryid', $contentcategoryid);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'keyword_title' => ($rs->getString('keyword_title') == NULL || $rs->getString('keyword_title') == '') ? $rs->getString('name') : $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description')
			);
		}
		return $Data;
	}
}