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
 * $Revision: 222 $
 * $Author: gekosale $
 * $Date: 2011-06-25 15:20:08 +0200 (So, 25 cze 2011) $
 * $Id: categorylist.php 222 2011-06-25 13:20:08Z gekosale $
 */

class producerlistModel extends Model
{

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
					C.photoid
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