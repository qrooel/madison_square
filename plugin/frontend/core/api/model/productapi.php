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
 * $Revision: 309 $
 * $Author: gekosale $
 * $Date: 2011-08-01 21:10:16 +0200 (Pn, 01 sie 2011) $
 * $Id: product.php 309 2011-08-01 19:10:16Z gekosale $
 */

class ProductApiModel extends ModelWithDataset
{

	public function initDataset ($dataset)
	{
		
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
			'producername' => Array(
				'source' => 'PRT.name'
			),
			'producerid' => Array(
				'source' => 'PRT.producerid'
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
			'description' => Array(
				'source' => 'PT.description'
			),
			'seo' => Array(
				'source' => 'PT.seo'
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
				'source' => 'IF(PN.enddate IS NOT NULL AND PN.enddate >= CURDATE(), 1, 0)'
			),
			'dateto' => Array(
				'source' => 'IF(P.promotionend IS NOT NULL, P.promotionend, NULL)'
			),
			'attributes' => Array(
				'source' => 'P.idproduct',
				'processFunction' => Array(
					App::getModel('product'),
					'getAttributesForProductById'
				)
			)
		));
		
		$dataset->setFrom('
				productcategory PC
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN product P ON PC.productid= P.idproduct
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PRT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
		
		$dataset->setAdditionalWhere('
			PC.categoryid = :categoryid AND 
			P.enable = 1
		');
		
		$dataset->setGroupBy('
			P.idproduct
		');
		
		$dataset->setSQLParams(Array(
			'categoryid' => (int) $this->registry->core->getParam(),
			'producer' => 0,
		));
	
	}

	public function getProductDataset ()
	{
		return $this->getDataset()->getDatasetRecords();
	}
}