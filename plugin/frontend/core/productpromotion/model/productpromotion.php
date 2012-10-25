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
 * $Id: productpromotion.php 655 2012-04-24 08:51:44Z gekosale $
 */

class productpromotionModel extends ModelWithDataset
{

	public function initDataset ($dataset)
	{
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		
		if (! empty($clientGroupId)){
			
			$dataset->setTableData(Array(
				'id' => Array(
					'source' => 'P.idproduct'
				),
				'adddate' => Array(
					'source' => 'P.adddate'
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
				'seo' => Array(
					'source' => 'PT.seo'
				),
				'categoryname' => Array(
					'source' => 'CT.name'
				),
				'categoryseo' => Array(
					'source' => 'CT.seo'
				),
				'stock' => Array(
					'source' => 'P.stock'
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
				'datefrom' => Array(
					'source' => 'IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart, IF(P.promotionstart IS NOT NULL, P.promotionstart, NULL))'
				),
				'dateto' => Array(
					'source' => 'IF(PGP.promotionend IS NOT NULL, PGP.promotionend, IF(P.promotionend IS NOT NULL, P.promotionend, NULL))'
				)
			));
			
			$dataset->setFrom('
				productcategory PC
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
				LEFT JOIN categorytranslation CT ON PC.categoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN product P ON PC.productid = P.idproduct
				LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid = :clientgroupid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
			
			if ($this->registry->router->getCurrentController() == 'categorylist'){
				
				$params = explode(',', $this->registry->router->getParams());
				
				$dataset->setAdditionalWhere('
					IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
				 	PGP.discountprice IS NOT NULL,
				 	IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice IS NOT NULL, NULL)
					) AND
					VC.viewid = :viewid AND
					P.enable = 1 AND
					CT.seo = :seo AND
					IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
				');
				
				$dataset->setSQLParams(Array(
					'seo' => $params[0]
				));
			
			}
			elseif ($this->registry->router->getCurrentController() == 'producerlist'){
				
				$params = explode(',', $this->registry->router->getParams());
				$producer = App::getModel('producerlistbox')->getProducerBySeo($params[0]);
				
				$dataset->setAdditionalWhere('
					IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
				 	PGP.discountprice IS NOT NULL,
				 	IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice IS NOT NULL, NULL)
					) AND
					VC.viewid = :viewid AND
					P.enable = 1 AND
					P.producerid = :id AND PV.viewid = :viewid
				');
				
				$dataset->setSQLParams(Array(
					'id' => $producer['id']
				));
			
			}
			else{
				$dataset->setAdditionalWhere('
				IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
				 	PGP.discountprice IS NOT NULL,
				 	IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice IS NOT NULL, NULL)
				) AND
				VC.viewid = :viewid AND
				P.enable = 1 AND
				IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
			');
			}
		
		}
		else{
			
			$dataset->setTableData(Array(
				'id' => Array(
					'source' => 'P.idproduct'
				),
				'adddate' => Array(
					'source' => 'P.adddate'
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
				'seo' => Array(
					'source' => 'PT.seo'
				),
				'categoryname' => Array(
					'source' => 'CT.name'
				),
				'categoryseo' => Array(
					'source' => 'CT.seo'
				),
				'stock' => Array(
					'source' => 'P.stock'
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
				'datefrom' => Array(
					'source' => 'IF(P.promotionstart IS NOT NULL, P.promotionstart, NULL)'
				),
				'dateto' => Array(
					'source' => 'IF(P.promotionend IS NOT NULL, P.promotionend, NULL)'
				)
			));
			
			$dataset->setFrom('
				product P
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				INNER JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN categorytranslation CT ON PC.categoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
			
			if ($this->registry->router->getCurrentController() == 'categorylist'){
				
				$params = explode(',', $this->registry->router->getParams());
				
				$dataset->setAdditionalWhere('
					P.promotion = 1 AND 
					IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND 
					IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1) AND 
					P.enable = 1 AND
					CT.seo = :seo AND
					IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
				');
				
				$dataset->setSQLParams(Array(
					'seo' => $params[0]
				));
			
			}
			elseif ($this->registry->router->getCurrentController() == 'producerlist'){
				
				$params = explode(',', $this->registry->router->getParams());
				$producer = App::getModel('producerlistbox')->getProducerBySeo($params[0]);
				
				$dataset->setAdditionalWhere('
					P.promotion = 1 AND 
					IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND 
					IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1) AND 
					P.enable = 1 AND
					P.producerid = :id AND PV.viewid = :viewid
				');
				
				$dataset->setSQLParams(Array(
					'id' => $producer['id']
				));
			
			}
			else{
				$dataset->setAdditionalWhere('
					P.promotion = 1 AND 
					IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND 
					IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1) AND 
					P.enable = 1 AND
					IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
				');
			}
		
		}
		
		$dataset->setGroupBy('
			P.idproduct
		');
	
	}

	public function getProductDataset ()
	{
		return $this->getDataset('productpromotion')->getDatasetRecords();
	}

}