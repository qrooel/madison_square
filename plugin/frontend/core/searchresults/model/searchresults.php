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
 * $Revision: 688 $
 * $Author: gekosale $
 * $Date: 2012-09-01 19:07:52 +0200 (So, 01 wrz 2012) $
 * $Id: searchresults.php 688 2012-09-01 17:07:52Z gekosale $
 */

class searchresultsModel extends ModelWithDataset
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
					'source' => 'IF(PN.enddate IS NOT NULL AND PN.enddate >= CURDATE(), 1, 0)'
				),
				'dateto' => Array(
					'source' => 'IF(PGP.promotionend IS NOT NULL, PGP.promotionend, IF(P.promotionend IS NOT NULL, P.promotionend, NULL))'
				),
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
					'source' => 'IF(PN.enddate IS NOT NULL AND PN.enddate >= CURDATE(), 1, 0)'
				),
				'dateto' => Array(
					'source' => 'IF(P.promotionend IS NOT NULL, P.promotionend, NULL)'
				),
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
			(P.ean LIKE :symbol OR P.delivelercode LIKE :symbol OR PT.name LIKE :symbol) AND
			P.enable = 1
		');
		
		$dataset->setGroupBy('
			P.idproduct
		');
		
		$dataset->setSQLParams(Array(
			'name' => '',
			'symbol' => ''
		));
	
	}

	public function getProductDataset ()
	{
		return $this->getDataset()->getDatasetRecords();
	}

	public function addPhrase ($name)
	{
		try{
			if ($name != NULL){
				$result = $this->checkInsertingMostSearch($name);
				if ($result == NULL){
					$this->addPhraseAboutMostSearch($name);
				}
				else{
					$this->updatePhraseAboutMostSearch($result['idmostsearch'], $result['textcount']);
				}
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($e->getMessage());
		}
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