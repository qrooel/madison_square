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
 * $Id: wishlist.php 655 2012-04-24 08:51:44Z gekosale $
 */

class wishlistModel extends ModelWithDataset
{

	public function initDataset ($dataset)
	{
		
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		
		if (! empty($clientGroupId)){
			
			$dataset->setTableData(Array(
				'id' => Array(
					'source' => 'WL.productid'
				),
				'name' => Array(
					'source' => 'PT.name',
					'sortable' => true
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
					'source' => 'IF(Photo.photoid IS NULL, 1, Photo.photoid)',
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
				wishlist WL
				LEFT JOIN productcategory PC ON PC.productid = WL.productid
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
				LEFT JOIN product P ON PC.productid = P.idproduct
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
					'source' => 'WL.productid'
				),
				'name' => Array(
					'source' => 'PT.name',
					'sortable' => true
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
					'source' => 'IF(Photo.photoid IS NULL, 1, Photo.photoid)',
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
				wishlist WL
				LEFT JOIN productcategory PC ON PC.productid = WL.productid
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
				LEFT JOIN product P ON PC.productid= P.idproduct
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
		
		}
		
		$dataset->setAdditionalWhere('
			WL.clientid = :clientid AND 
			VC.viewid = :viewid
		');
		
		$dataset->setGroupBy('
			P.idproduct
		');
		
		$dataset->setSQLParams(Array(
			'clientid' => $this->registry->session->getActiveClientid()
		));
	
	}

	public function getProductDataset ()
	{
		return $this->getDataset()->getDatasetRecords();
	}

	public function getClientWishList ()
	{
		$sql = "SELECT 
					WL.idwishlist, 
					WL.productid, 
					WL.productattributesetid, 
					PT.name, 
					PT.shortdescription, 
					WL.viewid,
					PHOTO.photoid, 
					P.sellprice, 
					V.`value`, 
					CG.idclientgroup,
							IF (WL.productattributesetid>0,
		  						(SELECT GROUP_CONCAT(APV.name ORDER BY APV.name DESC SEPARATOR '; ')
		  							FROM attributeproductvalue APV
		   							JOIN productattributevalueset PAVS ON PAVS.attributeproductvalueid = APV.idattributeproductvalue
		    						JOIN productattributeset PAS ON  PAS.idproductattributeset=PAVS.productattributesetid
		    					WHERE PAS.idproductattributeset=WL.productattributesetid)
							, NULL) as productattributes,
							IF (WL.productattributesetid>0, PAS.stock, P.stock) as stock,
							IF (WL.productattributesetid>0, PAS.attributeprice, P.sellprice) as pricewithoutvat,
  							IF (WL.productattributesetid>0, 
  								ROUND(PAS.attributeprice+(PAS.attributeprice*V.`value`)/100, 4),
  								ROUND((P.sellprice*V.`value`/100)+P.sellprice, 4)
  							) AS price
				    FROM wishlist WL
				         LEFT JOIN productattributeset AS PAS ON WL.productattributesetid = PAS.idproductattributeset
				         LEFT JOIN product AS P ON WL.productid= P.idproduct
				         LEFT JOIN producttranslation  PT ON PT.productid = P.idproduct AND PT.languageid = :languageid
				         LEFT JOIN `vat` V ON V.idvat = P.vatid
				         LEFT JOIN clientdata AS CD ON CD.clientid = :clientid
				         LEFT JOIN productphoto PHOTO ON PHOTO.productid=P.idproduct
				         LEFT JOIN clientgroup AS CG ON CG.idclientgroup = CD.clientgroupid
				    WHERE WL.clientid = :clientid 
				    	AND PHOTO.mainphoto = 1 
				    	AND WL.viewid= :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('viewid', Helper::getViewId());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$price = 0;
				$priceWithoutVat = 0;
				$productid = $rs->getInt('productid');
				$clientGroupid = $this->registry->session->getActiveClientGroupid();
				$attrId = $rs->getInt('productattributesetid');
				//price for clientgorup
				if ($clientGroupid > 0){
					//price for product with attribute
					if ($attrId > 0){
						$priceWithoutVat = App::getModel('product')->getProductAttributePromotionPriceForClients($productid, $attrId, $clientGroupid);
						if ($priceWithoutVat > 0){
							$price = sprintf('%01.2f', ($priceWithoutVat + ($priceWithoutVat * $rs->getFloat('value') / 100)));
						}
						//price for standadrd product
					}
					else{
						$priceWithoutVat = App::getModel('product')->getProductPromotionPriceForClients($productid, $clientGroupid);
						if ($priceWithoutVat > 0){
							$price = sprintf('%01.2f', ($priceWithoutVat + ($priceWithoutVat * $rs->getFloat('value') / 100)));
						}
					}
					//price for all clients
				}
				else{
					//price for product with attribute
					if ($attrId > 0){
						$priceWithoutVat = App::getModel('product')->getProductAttributePromotionPrice($productid, $attrId);
						if ($priceWithoutVat > 0){
							$price = sprintf('%01.2f', ($priceWithoutVat + ($priceWithoutVat * $rs->getFloat('value') / 100)));
						}
						//price for standadrd product
					}
					else{
						$priceWithoutVat = App::getModel('product')->getProductPromotionPrice($productid);
						if ($priceWithoutVat > 0){
							$price = sprintf('%01.2f', ($priceWithoutVat + ($priceWithoutVat * $rs->getFloat('value') / 100)));
						}
					}
				}
				//there isn't price promotion for product
				if ($price == 0){
					//price for product with attribute
					if ($attrId > 0){
						$priceWithoutVat = sprintf('%01.2f', $rs->getFloat('pricewithoutvat'));
						$price = sprintf('%01.2f', $rs->getFloat('price'));
						//price for standadrd product
					}
					else{
						$priceWithoutVat = sprintf('%01.2f', $rs->getFloat('pricewithoutvat'));
						$price = sprintf('%01.2f', $rs->getFloat('price'));
					}
				}
				$Data[] = Array(
					'idwishlist' => $rs->getInt('idwishlist'),
					'productattributesetid' => $rs->getInt('productattributesetid'),
					'idproduct' => $rs->getInt('productid'),
					'name' => $rs->getString('name'),
					'photoid' => $rs->getInt('photoid'),
					'price' => $price,
					'stock' => $rs->getInt('stock'),
					'productattributes' => $rs->getString('productattributes'),
					'pricewithoutvat' => $priceWithoutVat
				);
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage('ERR_QUERY_WISHLIST'));
		}
		return $Data;
	}

	public function deleteAJAXProductFromWishList ($idproduct, $attribute = 0)
	{
		$objResponseDeleteProd = new xajaxResponse();
		try{
			if ($attribute == 0){
				$this->deleteProductFromWishList($idproduct, 0);
				$objResponseDeleteProd->script('alert("' . $this->registry->core->getMessage('TXT_WISHLIST_PRODUCT_WAS_DELETED') . '")');
				$objResponseDeleteProd->script('window.location.reload( false )');
			}
			else{
				$this->deleteProductFromWishList($idproduct, $attribute);
				$objResponseDeleteProd->script('alert("' . $this->registry->core->getMessage('TXT_WISHLIST_PRODUCT_WAS_DELETED') . '")');
				$objResponseDeleteProd->script('window.location.reload( false )');
			}
		}
		catch (FrontendException $fe){
			$objResponseDeleteProd->script('window.location.reload( false )');
			$objResponseDeleteProd->script('alert("' . $this->registry->core->getMessage('ERR_ADD_OPINION') . '")');
		}
		return $objResponseDeleteProd;
	}

	public function deleteProductFromWishList ($idproduct, $idattribute = 0)
	{
		//usunięcie produktu bez cech
		if ($idattribute == 0){
			$sql = 'DELETE
						FROM `wishlist` 
							WHERE clientid = :clientid
								AND productid = :idproduct
								AND productattributesetid = 0';
			//usunięcie produktu z atrybutami
		}
		else{
			$sql = 'DELETE
						FROM `wishlist` 
							WHERE clientid= :clientid
								AND productid= :idproduct
								AND productattributesetid= :productattributesetid';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productattributesetid', $idattribute);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('idproduct', $idproduct);
		try{
			$stmt->executeQuery();
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage(''));
		}
	}

}
