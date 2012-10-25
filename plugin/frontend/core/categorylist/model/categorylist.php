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
 * $Id: categorylist.php 655 2012-04-24 08:51:44Z gekosale $
 */

class categorylistModel extends Model
{

	public function getCategoryNameTop ($id)
	{
		$sql = "SELECT
					name AS parentname,
					seo
				FROM categorytranslation
				WHERE categoryid =:id AND languageid = :languageid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $id,
				'parentname' => $rs->getString('parentname'),
				'seo' => $rs->getString('seo')
			);
		}
		return $Data;
	}

	public function getImagePath ($id)
	{
		if ($id > 0){
			return App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($id, 0));
		}
	}

	public function getCategoryMenuTop ($id)
	{
		$sql = "SELECT 
					C.idcategory, 
					CT.name,
					CT.seo,
					C.photoid,
					CT.shortdescription,
					CT.description,
     				(SELECT count(productid) FROM productcategory WHERE categoryid = idcategory) AS qry
				FROM category C
				LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
				LEFT JOIN categorytranslation CT ON CT.categoryid = idcategory AND CT.languageid = :languageid
				WHERE C.categoryid=:id AND VC.viewid=:viewid AND C.enable = 1
				ORDER BY C.distinction ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'idcategory' => $rs->getInt('idcategory'),
				'qry' => $rs->getInt('qry'),
				'seo' => $rs->getString('seo'),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'photo' => $this->getImagePath($rs->getInt('photoid'))
			);
		}
		return $Data;
	}

	public function getCategoryData ($id)
	{
		$sql = 'SELECT 
					IF(CT.keyword_title IS NULL OR CT.keyword_title = \'\', CT.name, CT.keyword_title)  AS keyword_title, 
					IF(CT.keyword = \'\',VT.keyword, CT.keyword) AS keyword, 
					IF(CT.keyword_description = \'\',VT.keyword_description,CT.keyword_description) AS keyword_description 
				FROM categorytranslation CT
				LEFT JOIN category C ON CT.categoryid = C.idcategory
				LEFT JOIN viewcategory VC ON VC.categoryid = CT.categoryid
				LEFT JOIN viewtranslation VT ON VT.viewid = VC.viewid
				WHERE CT.categoryid = :categoryid AND CT.languageid = :languageid AND C.enable = 1';
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setInt('categoryid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function getMetadataForCategory ()
	{
		
		if ($this->registry->core->getParam() === NULL)
			return '';
		$params = explode(',', $this->registry->router->getParams());
		
		if (! is_numeric($params[0])){
			$category = $this->getCategoryIdBySeo($params[0]);
			if (isset($category['id'])){
				$Data = $this->getCategoryData($category['id']);
			}
			else{
				App::redirect('');
			}
		}
		else{
			$Data = $this->getCategoryData((int) $this->registry->core->getParam());
		}
		if (isset($Data[0])){
			return $Data[0];
		}
		return App::getModel('seo')->getMetadataForPage();
	}

	public function getCategoryIdBySeo ($seo)
	{
		$sql = "SELECT
					CT.categoryid,
					CT.name,
					CT.seo,
					CT.shortdescription,
					CT.description,
					C.photoid,
					C.categoryid AS parent
				FROM categorytranslation CT
				LEFT JOIN category C ON CT.categoryid = C.idcategory
				LEFT JOIN viewcategory VC ON CT.categoryid = VC.categoryid 
				WHERE CT.seo =:seo AND CT.languageid = :languageid AND VC.viewid = :viewid AND C.enable = 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('seo', $seo);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('categoryid'),
				'parent' => $rs->getInt('parent'),
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo'),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'photo' => $this->getImagePath($rs->getInt('photoid'))
			);
		}
		return $Data;
	
	}
	
	public function getCategoryById ($id)
	{
		$sql = "SELECT
					CT.categoryid,
					CT.name,
					CT.seo,
					CT.shortdescription,
					CT.description,
					C.photoid,
					C.categoryid AS parent
				FROM categorytranslation CT
				LEFT JOIN category C ON CT.categoryid = C.idcategory
				LEFT JOIN viewcategory VC ON CT.categoryid = VC.categoryid 
				WHERE C.idcategory =:id AND CT.languageid = :languageid AND VC.viewid = :viewid AND C.enable = 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('categoryid'),
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo'),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'photo' => $this->getImagePath($rs->getInt('photoid'))
			);
		}
		return $Data;
	
	}

}