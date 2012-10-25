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
 * $Id: breadcrumb.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class breadcrumbModel extends Model
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
	}

	public function getPageLinks ()
	{
		$Data = Array();
		$controller = $this->registry->router->getCurrentController();
		if (method_exists($this, $controller) == true){
			$Data = call_user_func(Array(
				$this,
				$controller
			), (int) $this->registry->core->getParam());
		}
		else{
			if ($this->registry->router->getCurrentController() != 'mainside'){
				$Data = call_user_func(Array(
					$this,
					'getDefault'
				), (int) $this->registry->core->getParam());
			}
		}
		
		$Breadcrumb = Array();
		$Breadcrumb[] = Array(
			'link' => '',
			'title' => $this->registry->core->getMessage('TXT_MAINSIDE')
		);
		
		foreach ($Data as $key => $link){
			$Breadcrumb[] = array(
				'link' => $link['link'],
				'title' => $link['title']
			);
		}
		
		return $Breadcrumb;
	}

	protected function getDefault ($id)
	{
		$Data = Array();
		$sql = "SELECT
					description
				FROM controller
				WHERE name = :controller AND mode = 0";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('controller', $this->registry->router->getCurrentController());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data[] = Array(
				'link' => $this->registry->core->getControllerNameForSeo($this->registry->router->getCurrentController()),
				'title' => $this->registry->core->getMessage($rs->getString('description'))
			);
		}
		return $Data;
	}

	protected function categorylist ($id)
	{
		$params = explode(',', $this->registry->router->getParams());
		if (! is_numeric($params[0])){
			$category = App::getModel('categorylist')->getCategoryIdBySeo($params[0]);
			if (! isset($category['id'])){
				App::redirectSeo(App::getURLAdress());
			}
			$id = $category['id'];
		}
		else{
			$id = (int) $this->registry->core->getParam();
		}
		
		$sql = "SELECT
					CONCAT(:seo,'/',IF(CT.seo IS NOT NULL, CT.seo,'')) AS link, 
					CT.name AS title
				FROM categorypath CP
				LEFT JOIN categorytranslation CT ON CP.ancestorcategoryid = CT.categoryid AND CT.languageid = :languageid
				WHERE CP.categoryid = :categoryid
				ORDER BY CP.order DESC";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('seo', $this->registry->core->getControllerNameForSeo('categorylist'));
		$stmt->setInt('categoryid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'link' => $rs->getString('link'),
				'title' => $rs->getString('title')
			);
		}
		return $Data;
	}

	protected function productcart ($id)
	{
		
		$params = $this->registry->router->getParams();
		if (! is_numeric($params)){
			$this->productid = App::getModel('product')->getProductIdBySeo($params);
		}
		else{
			$this->productid = (int) $this->registry->core->getParam();
		}
		
		$sql = "SELECT 
					PC.categoryid 
				FROM productcategory PC 
				LEFT JOIN category C ON PC.categoryid = C.idcategory
				WHERE PC.productid = :productid AND C.enable = 1
				LIMIT 1";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setInt('productid', $this->productid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$sql = "SELECT
						CONCAT(:seo,'/',IF(CT.seo IS NOT NULL, CT.seo,'')) AS link, 
						CT.name AS title
					FROM categorypath CP
					LEFT JOIN categorytranslation CT ON CP.ancestorcategoryid = CT.categoryid AND CT.languageid = :languageid
					WHERE CP.categoryid = :categoryid
					ORDER BY CP.order DESC";
			$stmt = $this->registry->db->preparestatement($sql);
			$stmt->setString('seo', $this->registry->core->getControllerNameForSeo('categorylist'));
			$stmt->setInt('categoryid', $rs->getInt('categoryid'));
			$stmt->setInt('languageid', Helper::getLanguageId());
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'link' => $rs->getString('link'),
					'title' => $rs->getString('title')
				);
			}
		}
		$sql = "SELECT
					CONCAT(:seo,'/',IF(PT.seo IS NOT NULL, PT.seo,'')) AS link, 
					PT.name AS title
				FROM producttranslation PT 
				WHERE PT.productid = :productid AND PT.languageid = :languageid
				";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('seo', $this->registry->core->getControllerNameForSeo('productcart'));
		$stmt->setInt('productid', $this->productid);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data[] = Array(
				'link' => $rs->getString('link'),
				'title' => $rs->getString('title')
			);
		}
		return $Data;
	
	}

	protected function producerlist ($id)
	{
		
		$this->params = explode(',', $this->registry->router->getParams());
		$Data = Array();
		if ($this->registry->core->getParam() != '' && !is_numeric($this->params[0])){
			$producer = App::getModel('producerlistbox')->getProducerBySeo($this->params[0]);
			$Data[] = Array(
				'link' => $this->registry->core->getControllerNameForSeo($this->registry->router->getCurrentController()) . '/' . $producer['seo'],
				'title' => $producer['name']
			);
		}
		else{
			$Data[] = Array(
				'link' => $this->registry->core->getControllerNameForSeo($this->registry->router->getCurrentController()),
				'title' => $this->registry->core->getMessage('TXT_PRODUCER')
			);
		}
		
		return $Data;
	}

	public function news ($id)
	{
		$Data = Array();
		$Data[] = Array(
			'link' => $this->registry->core->getControllerNameForSeo($this->registry->router->getCurrentController()),
			'title' => $this->registry->core->getMessage('TXT_NEWS')
		);
		$sql = "SELECT
				CONCAT(:seo,'/',NT.newsid,'/',NT.seo) AS link,
				NT.topic AS title
				FROM newstranslation NT WHERE NT.newsid = :id AND NT.languageid = :languageid";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('seo', $this->registry->core->getControllerNameForSeo('news'));
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'link' => $rs->getString('link'),
				'title' => $rs->getString('title')
			);
		}
		return $Data;
	}

	public function staticcontent ($id)
	{
		$sql = "SELECT
				CONCAT(:seo,'/',CC.idcontentcategory) AS link,
				CCT.name AS title
				FROM contentcategory CC
				LEFT JOIN contentcategorytranslation CCT ON CCT.contentcategoryid = CC.idcontentcategory AND CCT.languageid = :languageid
				WHERE CC.idcontentcategory = :id OR CC.idcontentcategory = (SELECT contentcategoryid FROM contentcategory WHERE idcontentcategory = :id)";
		$stmt = $this->registry->db->preparestatement($sql);
		$stmt->setString('seo', $this->registry->core->getControllerNameForSeo('staticcontent'));
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'link' => $rs->getString('link'),
				'title' => $rs->getString('title')
			);
		}
		return $Data;
	}

}