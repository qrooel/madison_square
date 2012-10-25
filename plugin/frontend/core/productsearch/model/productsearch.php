<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2008-2011 Gekosale. Zabronione jest usuwanie informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version. 
 * 
 * 
 * $Revision: 692 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:10:30 +0200 (Cz, 06 wrz 2012) $
 * $Id: productsearch.php 692 2012-09-06 21:10:30Z gekosale $
 */

class productsearchModel extends ModelWithDataset
{

	public function initDataset ($dataset)
	{
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		
		if (! empty($clientGroupId)){
			
			$dataset->setTableData(Array(
				'id' => Array(
					'source' => 'P.idproduct'
				),
				'name' => Array(
					'source' => 'PT.name'
				),
				'ean' => Array(
					'source' => 'P.ean'
				),
				'delivelercode' => Array(
					'source' => 'P.delivelercode'
				),
				'score' => Array(
					'source' => '1'
				),
				'shortdescription' => Array(
					'source' => 'PT.shortdescription'
				),
				'stock' => Array(
					'source' => 'P.stock'
				),
				'seo' => Array(
					'source' => 'PT.seo'
				),
				'pricenetto' => Array(
					'source' => 'IF(PGP.groupprice = 1, 
								 	PGP.sellprice, 
								 	P.sellprice
								 ) * CR.exchangerate',
					'processPrice' => true
				),
				'price' => Array(
					'source' => 'IF(PGP.groupprice = 1, 
									PGP.sellprice, 
									P.sellprice
								 ) * (1 + (V.value / 100)) * CR.exchangerate',
					'processPrice' => true
				),
				'buypricenetto' => Array(
					'source' => 'P.buyprice * CR.exchangerate',
					'processPrice' => true
				),
				'buyprice' => Array(
					'source' => 'P.buyprice * (1 + (V.value / 100)) * CR.exchangerate',
					'processPrice' => true
				),
				'discountpricenetto' => Array(
					'source' => 'IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
								 	PGP.discountprice,
								 	IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, NULL)
								 ) * CR.exchangerate',
					'processPrice' => true
				),
				'discountprice' => Array(
					'source' => 'IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
								 	PGP.discountprice,
								 	IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, NULL)
								 ) * (1 + (V.value / 100)) * CR.exchangerate',
					'processPrice' => true
				),
				'photo' => Array(
					'source' => 'Photo.photoid',
					'processFunction' => Array(
						App::getModel('product'),
						'getImagePath'
					)
				),
				'opinions' => Array(
					'source' => 'COUNT(DISTINCT PREV.idproductreview)'
				),
				'rating' => Array(
					'source' => 'IF(CEILING(AVG(PRANGE.value)) IS NULL, 0, CEILING(AVG(PRANGE.value)))'
				),
				'new' => Array(
					'source' => 'IF(PN.active = 1 AND (PN.enddate IS NULL OR PN.enddate >= CURDATE()), 1, 0)'
				),
				'dateto' => Array(
					'source' => 'IF(PGP.promotionend IS NOT NULL, PGP.promotionend, IF(P.promotionend IS NOT NULL, P.promotionend, NULL))'
				)
			));
			
			$dataset->setFrom('
				product P
				LEFT JOIN productcategory PC ON P.idproduct = PC.productid
				INNER JOIN category C ON PC.categoryid = C.idcategory AND C.enable = 1
				INNER JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid = :clientgroupid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
		
		}
		else{
			
			$dataset->setTableData(Array(
				'id' => Array(
					'source' => 'P.idproduct'
				),
				'name' => Array(
					'source' => 'PT.name'
				),
				'ean' => Array(
					'source' => 'P.ean'
				),
				'delivelercode' => Array(
					'source' => 'P.delivelercode'
				),
				'score' => Array(
					'source' => '1'
				),
				'shortdescription' => Array(
					'source' => 'PT.shortdescription'
				),
				'stock' => Array(
					'source' => 'P.stock'
				),
				'seo' => Array(
					'source' => 'PT.seo'
				),
				'pricenetto' => Array(
					'source' => 'P.sellprice * CR.exchangerate',
					'processPrice' => true
				),
				'price' => Array(
					'source' => 'P.sellprice * (1 + (V.value / 100)) * CR.exchangerate',
					'processPrice' => true
				),
				'buypricenetto' => Array(
					'source' => 'ROUND(P.buyprice * CR.exchangerate, 2)',
					'processPrice' => true
				),
				'buyprice' => Array(
					'source' => 'ROUND((P.buyprice + (P.buyprice * V.`value`)/100) * CR.exchangerate, 2)',
					'processPrice' => true
				),
				'discountpricenetto' => Array(
					'source' => 'IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * CR.exchangerate, NULL)',
					'processPrice' => true
				),
				'discountprice' => Array(
					'source' => 'IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * (1 + (V.value / 100)) * CR.exchangerate, NULL)',
					'processPrice' => true
				),
				'photo' => Array(
					'source' => 'Photo.photoid',
					'processFunction' => Array(
						App::getModel('product'),
						'getImagePath'
					)
				),
				'opinions' => Array(
					'source' => 'COUNT(DISTINCT PREV.idproductreview)'
				),
				'rating' => Array(
					'source' => 'IF(CEILING(AVG(PRANGE.value)) IS NULL, 0, CEILING(AVG(PRANGE.value)))'
				),
				'new' => Array(
					'source' => 'IF(PN.active = 1 AND (PN.enddate IS NULL OR PN.enddate >= CURDATE()), 1, 0)'
				),
				'dateto' => Array(
					'source' => 'IF(P.promotionend IS NOT NULL, P.promotionend, NULL)'
				)
			));
			
			$dataset->setFrom('
				product P
				LEFT JOIN productcategory PC ON P.idproduct = PC.productid
				INNER JOIN category C ON PC.categoryid = C.idcategory AND C.enable = 1
				INNER JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
			');
		
		}
		
		$dataset->setAdditionalWhere('
			IF(:categoryid > 0, PC.categoryid = :categoryid, 1) AND 
			IF(:filterbyproducer > 0, P.producerid IN (:producer), 1) AND
			IF(:pricefrom > 0, (P.sellprice * (1 + (V.`value`/100))) > :pricefrom, 1) AND
			IF(:priceto > 0, :priceto > (P.sellprice * (1 + (V.`value`/100))), 1) AND
			(P.ean LIKE :name OR P.delivelercode LIKE :name OR PT.name LIKE :name) AND
			IF(:enablelayer > 0, P.idproduct IN (:products), 1) AND
			P.enable = 1
		');
		
		$dataset->setGroupBy('
			P.idproduct
		');
		
		$dataset->setSQLParams(Array(
			'categoryid' => (int) $this->registry->core->getParam(),
			'producer' => 0,
			'pricefrom' => 0,
			'priceto' => 0,
			'name' => '',
			'enablelayer' => 0,
			'products' => Array()
		));
	
	}

	public function getProductDataset ()
	{
		return $this->getDataset()->getDatasetRecords();
	}

	public function search ($phrase = '', $categoryid = 0, $type = 0, $sellpricemin = 0, $sellpricemax = 0, $producer = 0, $client = 0)
	{
		$sql = 'SELECT 
						PS.name, 
						PS.description, 
						PS.shortdescription, 
						PS.productid, 
						P.sellprice as pricewithoutvat, 
						P.stock, 
						(SELECT ROUND(P.sellprice+(P.sellprice*vat.`value`)/100, 2)) AS price,
						PROD.idproducer, 
						PRODT.seo AS producerwww, 
						PRODT.name AS producername, 
						vat.`value`, 
						PHOTO.photoid AS mainphotoid
				FROM
					productsearch PS
					LEFT JOIN product P ON PS.productid = P.idproduct
					LEFT JOIN productcategory PC ON PC.productid = P.idproduct
					LEFT JOIN producer AS PROD ON PROD.idproducer = P.producerid
					LEFT JOIN producertranslation AS PRODT ON PRODT.producerid = PROD.idproducer
					LEFT JOIN vat AS vat ON vat.idvat = P.vatid
					LEFT JOIN productphoto PHOTO ON PHOTO.productid = P.idproduct
					LEFT JOIN clientdata AS CD ON CD.clientid = :clientid
					LEFT JOIN clientgroup AS CG ON CG.idclientgroup = CD.clientgroupid
				WHERE
					PHOTO.mainphoto = 1
					AND IF(:categoryid > 0, PC.categoryid = :categoryid, 1)
					AND IF(:producerid > 0, idproducer = :producerid, 1)
					AND PS.enable = 1
					AND PS.languageid = :languageid
					AND MATCH(PS.name, PS.description, PS.shortdescription, PS.producername, attributes) AGAINST(:phrase) > 0
					AND P.sellprice BETWEEN :sellpricemin AND :sellpricemax
				GROUP BY
					P.idproduct
				ORDER BY MATCH(PS.name, PS.description, PS.shortdescription, PS.producername, attributes) AGAINST(:phrase) DESC
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setString('phrase', $phrase);
		$stmt->setInt('categoryid', $categoryid);
		$stmt->setInt('type', $type);
		$stmt->setInt('sellpricemin', $sellpricemin);
		$stmt->setInt('sellpricemax', $sellpricemax);
		$stmt->setInt('producerid', $producer);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		foreach ($Data as $key => $value){
			try{
				$Image = App::getModel('gallery')->getSmallImageById($value['mainphotoid']);
				$Data[$key]['photo'] = App::getModel('gallery')->getImagePath($Image);
			}
			catch (Exception $e){
				echo $e->getMessage();
			}
		}
		return $Data;
	}

	public function getAllTags ()
	{
		$Data = Array();
		$sql = "SELECT name, textcount, idtags, viewid 
					FROM tags
					WHERE viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'idtags' => $rs->getInt('idtags'),
				'name' => $rs->getString('name'),
				'textcount' => $rs->getInt('textcount'),
				'viewid' => $rs->getInt('viewid')
			);
		}
		return $Data;
	}

	public function getClientTags ()
	{
		$Data = Array();
		$sql = "SELECT T.name, T.textcount, T.idtags, clientid, T.viewid 
					FROM tags T
					LEFT JOIN producttags PT ON PT.tagsid = T.idtags
					WHERE clientid=:clientid AND T.viewid = :viewid
					GROUP BY name";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'idtags' => $rs->getInt('idtags'),
					'name' => $rs->getString('name'),
					'textcount' => $rs->getInt('textcount'),
					'viewid' => $rs->getInt('viewid')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function getProductTags ($id)
	{
		$Data = Array();
		$sql = "SELECT 
					P.idproduct, 
					PT.name as productname, 
					P.sellprice, 
					PT.shortdescription, 
					Photo.photoid, 
					T.name as tagname, 
					T.idtags,
					V.`value`,
					ROUND(P.sellprice * (1 + (V.`value` / 100)), 2) AS price
				FROM product P
				LEFT JOIN producttranslation PT ON PT.productid = P.idproduct AND PT.languageid=:languageid
				LEFT JOIN producttags PTS ON P.idproduct = PTS.productid
		        LEFT JOIN tags T ON PTS.tagsid = T.idtags
		        LEFT JOIN vat V ON V.idvat = P.vatid
		        LEFT JOIN productphoto Photo ON Photo.productid = P.idproduct
     		 	WHERE T.idtags=:id AND mainphoto=1 AND PTS.viewid = :viewid AND P.enable = 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('id', $id);
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$price = 0;
				$priceWithoutVat = 0;
				$idproduct = $rs->getInt('idproduct');
				
				$prices = App::getModel('product')->getProductPrices($idproduct);
				
				$Data[] = Array(
					'id' => $id,
					'pricewithoutvat' => $price['pricenetto'],
					'price' => $price['price'],
					'discountpricenetto' => $price['discountpricenetto'],
					'discountprice' => $price['discountprice'],
					'buypricenetto' => $price['buypricenetto'],
					'buyprice' => $price['buyprice'],
					'shortdescription' => $rs->getString('shortdescription'),
					'tagname' => $rs->getString('tagname'),
					'productname' => $rs->getString('productname'),
					'idproduct' => $rs->getInt('idproduct'),
					'mainphotoid' => $rs->getInt('photoid')
				);
				foreach ($Data as $key => $value){
					try{
						$Image = App::getModel('gallery')->getSmallImageById($value['mainphotoid']);
						$Data[$key]['photo'] = App::getModel('gallery')->getImagePath($Image);
					}
					catch (FrontendException $e){
						throw new FrontendException($e->getMessage());
					}
				}
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function getAllMostSearch ()
	{
		$Data = Array();
		$sql = "SELECT name, idmostsearch, textcount
					FROM mostsearch
					WHERE viewid=:viewid
					GROUP BY name ORDER BY textcount DESC LIMIT 10";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			
			while ($rs->next()){
				$Data[] = Array(
					'idmostsearch' => $rs->getString('name'),
					'name' => $rs->getString('name'),
					'textcount' => $rs->getString('textcount')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function getMostSearchbyId ($id)
	{
		$Data = Array();
		$sql = "SELECT idmostsearch as id, name  
					FROM mostsearch 
					WHERE idmostsearch=:id AND viewid=:viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'name' => $rs->getString('name'),
					'viewid' => $rs->getInt('viewid')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function addAJAXPhraseAboutMostSearch ($name)
	{
		$objResponseNewPhrase = new xajaxResponse();
		try{
			if ($name == NULL){
				$objResponseNewPhrase->script('alert("' . $this->registry->core->getMessage('ERR_INSERT_PHRASE') . '")');
			}
			else{
				$result = $this->checkInsertingMostSearch($name);
				if ($result == NULL){
					$this->addPhraseAboutMostSearch($name);
					$objResponseNewPhrase->script('window.location.reload(false)');
				}
				else{
					$this->updatePhraseAboutMostSearch($result['idmostsearch'], $result['textcount']);
					$objResponseNewPhrase->script('window.location.reload(false)');
				}
			}
		}
		catch (FrontendException $fe){
			$objResponseNewPhrase->script('alert("' . $this->registry->core->getMessage('ERR_PHRASE_SEARCHING') . '")');
		}
		return $objResponseNewPhrase;
	}

	public function checkInsertingMostSearch ($phrase)
	{
		$Data = Array();
		$sql = "SELECT MS.idmostsearch, MS.textcount 
					FROM mostsearch MS 
					WHERE MS.name= :phrase";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('phrase', $phrase);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'idmostsearch' => $rs->getInt('idmostsearch'),
					'textcount' => $rs->getInt('textcount')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function addPhraseAboutMostSearch ($name, $counter = 0)
	{
		$sql = 'INSERT INTO mostsearch (name, viewid)
					VALUES (:name, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $name);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function updatePhraseAboutMostSearch ($id, $counter = 0)
	{
		$counter = $counter + 1;
		$sql = 'UPDATE mostsearch MS SET MS.textcount = :counter
					WHERE MS.idmostsearch = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('counter', $counter);
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
	}
}
?>