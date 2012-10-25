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
 * $Id: sitemap.php 655 2012-04-24 08:51:44Z gekosale $
 */

class sitemapModel extends Model {
	
	protected $_rawData;

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
		$this->viewid = Helper::getViewId();
		$this->languageid = Helper::getLanguageId();
	}

	protected function _loadRawCategoriesFromDatabase ($levels) {
		$sql = '
				SELECT
					PC.idcategory AS id,
					PC.categoryid AS parent,
					CT.name AS label,
					CT.seo,
					COUNT( CP.ancestorcategoryid ) AS levels
				FROM
					category PC
					LEFT JOIN categorypath CP ON PC.categoryid = CP.categoryid
					LEFT JOIN viewcategory CV ON CV.categoryid = idcategory
					LEFT JOIN categorytranslation CT ON PC.idcategory = CT.categoryid AND CT.languageid = :languageid
				WHERE
					CV.viewid = :viewid AND PC.enable = 1
				GROUP BY id
				HAVING levels < :levels
				ORDER BY PC.distinction ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('levels', $levels);
		$rs = $stmt->executeQuery();
		$this->_rawData = $rs->getAllRows();
	}

	protected function _parseCategorySubtree ($maxLevels = 0, $level = 0, $parent = null, $current = false) {
		$categories = Array();
		if (($maxLevels == 0) || ($level <= $maxLevels)){
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
					'label' => $category['label'],
					'seo' => $category['seo'],
					'children' => $this->_parseCategorySubtree($maxLevels, $level + 1, $category['id'], $current)
				);
			}
		}
		return $categories;
	}

	public function getContentCategoryTree () {
		$sql = 'SELECT C.idcontentcategory AS id, CT.name AS parentcategory,CT.seo, CCV.viewid, C.contentcategoryid
							FROM contentcategory C
							LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
							LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
							WHERE C.contentcategoryid  IS NULL AND CCV.viewid = :viewid AND';
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

	public function getUnderCategory ($id) {
		$sql = "SELECT
						C.idcontentcategory AS id, 
						CT.name AS undercategory
					FROM contentcategory C 
					LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
					LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
					WHERE C.contentcategoryid=:id AND CCV.viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'undercategory' => $rs->getString('undercategory'),
				'seo' => App::getModel('seo')->clearSeoUTF($rs->getString('undercategory')),
				'id' => $rs->getInt('id')
			);			// 'pages' => $this->getStaticContent($rs->getInt('id'))
			
		}
		return $Data;
	}

	public function getStaticContent ($id) {
		$sql = "SELECT 
						SC.contentcategoryid as id,
						SCT.topic, 
						SCT.content, 
						SC.publish, 
						SCV.viewid
					FROM staticcontent SC
					LEFT JOIN staticcontentview SCV ON SCV.staticcontentid = SC.idstaticcontent
					LEFT JOIN staticcontenttranslation SCT ON SC.idstaticcontent = SCT.staticcontentid AND SCT.languageid = :languageid
					WHERE SC.contentcategoryid=:id AND SC.publish = 1 AND SCV.viewid = :viewid";
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
				'seo' => App::getModel('seo')->clearSeoUTF($rs->getString('topic')),
				'content' => $rs->getString('content')
			);
		}
		return $Data;
	}

	public function getSitemapCategories ($Params) {
		
		if (($categories = Cache::loadObject('sitemapcategories')) === FALSE){
			$this->_loadRawCategoriesFromDatabase($Params['categoryTreeLevels']);
			$categories = $this->_parseCategorySubtree($Params['categoryTreeLevels']);
			Cache::saveObject('sitemapcategories', $categories, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		
		return $categories;
	}

	public function getCategories ($levels) {
		$sql = "SELECT 
				CONCAT(:url,:seo,'/',CT.seo) as loc,
				IF(C.editdate IS NULL,DATE_FORMAT(C.adddate,'%Y-%m-%d'),DATE_FORMAT(C.editdate,'%Y-%m-%d')) as lastmod,
				COUNT(CP.`order`) AS levels
				FROM category C
				LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
				LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
				LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
				WHERE VC.viewid = :viewid
				GROUP BY CP.categoryid
				HAVING levels < :levels
				ORDER BY C.distinction ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', URL);
		$stmt->setString('seo', App::getRegistry()->core->getControllerNameForSeo('categorylist'));
		$stmt->setString('levels', $levels);
		$stmt->setString('viewid', $this->viewid);
		$stmt->setInt('languageid', $this->languageid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'loc' => $rs->getString('loc'),
				'lastmod' => $rs->getString('lastmod')
			);
		}
		return $Data;
	}

	public function getProducts ($limit) {
		$sql = "SELECT 
				CONCAT(:url,:seo,'/',PT.seo) as loc,
				IF(P.editdate IS NULL,DATE_FORMAT(P.adddate,'%Y-%m-%d'),DATE_FORMAT(P.editdate,'%Y-%m-%d')) as lastmod
				FROM product P
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid 
				WHERE P.enable = 1 AND VC.viewid = :viewid AND IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
				GROUP BY P.idproduct
				";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', URL);
		$stmt->setString('viewid', $this->viewid);
		$stmt->setString('seo', App::getRegistry()->core->getControllerNameForSeo('productcart'));
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setLimit($limit);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'loc' => $rs->getString('loc'),
				'lastmod' => $rs->getString('lastmod')
			);
		}
		return $Data;
	}

	public function getNews () {
		$sql = "SELECT 
				CONCAT(:url,:seo,'/',N.idnews,'/',NT.seo) as loc,
				IF(N.editdate IS NULL,DATE_FORMAT(N.adddate,'%Y-%m-%d'),DATE_FORMAT(N.editdate,'%Y-%m-%d')) as lastmod
				FROM news N
				LEFT JOIN newstranslation NT ON N.idnews = NT.newsid AND NT.languageid = :languageid
				WHERE N.publish = 1
				GROUP BY N.idnews";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', URL);
		$stmt->setString('languageid', Helper::getLanguageId());
		$stmt->setString('seo', App::getRegistry()->core->getControllerNameForSeo('news'));
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'loc' => $rs->getString('loc'),
				'lastmod' => $rs->getString('lastmod')
			);
		}
		return $Data;
	}

	public function getPages () {
		$sql = "SELECT CONCAT(:url,:seo,'/',C.idcontentcategory) as loc,CT.name,
				DATE_FORMAT(C.adddate,'%Y-%m-%d') as lastmod
				FROM contentcategory C
				LEFT JOIN contentcategoryview CCV ON CCV.contentcategoryid = C.idcontentcategory
				LEFT JOIN contentcategorytranslation CT ON CT.contentcategoryid = C.idcontentcategory AND CT.languageid = :languageid
				WHERE CCV.viewid = :viewid ";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', URL);
		$stmt->setInt('languageid', $this->languageid);
		$stmt->setInt('viewid', $this->viewid);
		$stmt->setString('seo', App::getRegistry()->core->getControllerNameForSeo('staticcontent'));
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'loc' => $rs->getString('loc') . '/' . strtolower(Core::clearUTF($rs->getString('name'))),
				'lastmod' => $rs->getString('lastmod')
			);
		}
		return $Data;
	}

	public function generateSitemap ($id) {
		
		$sql = "SELECT * 
				FROM sitemaps 
				WHERE idsitemaps = :id
				";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			
			$xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
			
			if ($rs->getInt('publishforcategories')){
				$Categories = $this->getCategories(4);
				foreach ($Categories as $category){
					$node = $xml->addChild('url');
					$node->addChild('loc', $category['loc']);
					$node->addChild('lastmod', $category['lastmod']);
					$node->addChild('changefreq', 'weekly');
					$node->addChild('priority', $rs->getString('priorityforcategories'));
				}
			}
			
			if ($rs->getInt('publishforproducts')){
				$Products = $this->getProducts(5000);
				foreach ($Products as $product){
					$node = $xml->addChild('url');
					$node->addChild('loc', $product['loc']);
					$node->addChild('lastmod', $product['lastmod']);
					$node->addChild('changefreq', 'weekly');
					$node->addChild('priority', $rs->getString('priorityforproducts'));
				}
			}
			
			if ($rs->getInt('publishforproducers')){
			
			}
			
			if ($rs->getInt('publishfornews')){
				$News = $this->getNews();
				foreach ($News as $news){
					$node = $xml->addChild('url');
					$node->addChild('loc', $news['loc']);
					$node->addChild('lastmod', $news['lastmod']);
					$node->addChild('changefreq', 'weekly');
					$node->addChild('priority', $rs->getString('priorityfornews'));
				}
			}
			
			if ($rs->getInt('publishforpages')){
				$Pages = $this->getPages();
				foreach ($Pages as $page){
					$node = $xml->addChild('url');
					$node->addChild('loc', $page['loc']);
					$node->addChild('lastmod', $page['lastmod']);
					$node->addChild('changefreq', 'weekly');
					$node->addChild('priority', $rs->getString('priorityforpages'));
				}
			
			}
		}
		header('Content-type: text/xml; charset=utf-8');
		header('Cache-Control: max-age=0');
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;
		$domnode = dom_import_simplexml($xml);
		$domnode = $doc->importNode($domnode, true);
		$domnode = $doc->appendChild($domnode);
		echo $doc->saveXML();
	}

}