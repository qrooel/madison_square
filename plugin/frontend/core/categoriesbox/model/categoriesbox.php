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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: categoriesbox.php 687 2012-09-01 12:02:47Z gekosale $
 */

class CategoriesBoxModel extends Model
{
	
	protected $_rawData;
	protected $_currentCategoryId;

	public function getCategoriesTree ()
	{
		if (($categories = Cache::loadObject('categories')) === FALSE){
			if (! isset($this->_rawData) || ! is_array($this->_rawData)){
				$this->_loadRawCategoriesFromDatabase();
			}
			$categories = $this->_parseCategorySubtree();
			Cache::saveObject('categories', $categories, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		return $categories;
	}

	protected function _loadRawCategoriesFromDatabase ()
	{
		$sql = '
				SELECT
					C.idcategory AS id,
					C.categoryid AS parent,
					C.photoid,
					CT.name AS label,
					CT.description,
					CT.shortdescription,
					CT.seo,
          			COUNT(DISTINCT PC.productid) AS totalproducts
				FROM
					category C
					INNER JOIN viewcategory CV ON CV.categoryid = idcategory
					LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
          			LEFT JOIN categorypath CP ON CP.ancestorcategoryid = C.idcategory
          			LEFT JOIN productcategory PC ON CP.categoryid = PC.categoryid AND PC.categoryid IN (SELECT categoryid FROM viewcategory WHERE viewid = :viewid)
          			LEFT JOIN product P ON P.idproduct = PC.productid
          			LEFT JOIN producerview PV ON PV.producerid = P.producerid
				WHERE
					CV.viewid = :viewid AND C.enable = 1
					AND IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1) AND
					P.enable = 1
				GROUP BY id ORDER BY C.distinction ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$this->_rawData = $rs->getAllRows();
	}

	protected function getProducers ($id)
	{
		$sql = 'SELECT 
					PT.name, 
					PT.seo,
					PC.categoryid
				FROM `producertranslation` PT
				LEFT JOIN product P ON P.producerid = PT.producerid 
				LEFT JOIN productcategory PC ON P.idproduct = PC.productid
				LEFT JOIN categorypath CP ON CP.categoryid = PC.categoryid
				WHERE PT.languageid = :languageid AND CP.ancestorcategoryid = :id
				GROUP BY PT.producerid
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo')
			);
		}
		return $Data;
	}

	protected function _parseCategorySubtree ($parent = null)
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
			$producers = Array();
			if ($parent == null){
				$producers = $this->getProducers($category['id']);
			}
			$categories[] = Array(
				'id' => $category['id'],
				'label' => $category['label'],
				'seo' => $category['seo'],
				'producers' => $producers,
				'shortdescription' => $category['shortdescription'],
				'description' => $category['description'],
				'url' => $this->registry->core->getUrlMapCategoryList($category['id']),
				'totalproducts' => $category['totalproducts'],
				'photo' => App::getModel('categorylist')->getImagePath($category['photoid']),
				'children' => $this->_parseCategorySubtree($category['id'])
			);
		}
		return $categories;
	}

	public function getCategoryPathForProductById ($name)
	{
		$sql = 'SELECT 
					CP.ancestorcategoryid 
				FROM categorypath CP
				LEFT JOIN productcategory PC ON PC.categoryid = CP.categoryid
				LEFT JOIN producttranslation PT ON PT.productid = PC.productid AND PT.languageid = :languageid
				WHERE PT.seo = :name
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $name);
		$stmt->setInt('languageid', Helper::getLanguageId());
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = $rs->getInt('ancestorcategoryid');
			}
			return $Data;
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
	}

	public function getCurrentCategoryPath ($seo)
	{
		$sql = 'SELECT 
					CP.ancestorcategoryid 
				FROM categorypath CP
				LEFT JOIN categorytranslation CT ON CT.categoryid = CP.categoryid AND CT.languageid = :languageid
				WHERE CT.seo = :seo
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('seo', $seo);
		$stmt->setInt('languageid', Helper::getLanguageId());
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = $rs->getInt('ancestorcategoryid');
			}
			return $Data;
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
	}

}