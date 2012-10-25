<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
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
 * $Id: product.php 692 2012-09-06 21:10:30Z gekosale $
 */
class productModel extends ModelWithDataset {

	public function initDataset ($dataset) {
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
				'producername' => Array(
					'source' => 'PRT.name'
				),
				'producerseo' => Array(
					'source' => 'PRT.seo'
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
				'finalprice' => Array(
					'source' => 'IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, P.sellprice)',
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
				productcategory PC
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN categorytranslation CT ON PC.categoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN product P ON PC.productid = P.idproduct
				LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid = :clientgroupid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PT.languageid = :languageid
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
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
				'producername' => Array(
					'source' => 'PRT.name'
				),
				'producerseo' => Array(
					'source' => 'PRT.seo'
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
				'finalprice' => Array(
					'source' => 'IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, P.sellprice)',
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
				productcategory PC
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid AND VC.viewid = :viewid
				LEFT JOIN categorytranslation CT ON PC.categoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN product P ON PC.productid= P.idproduct
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PT.languageid = :languageid
				LEFT JOIN producerview PV ON PV.producerid = P.producerid
				LEFT JOIN productphoto Photo ON P.idproduct= Photo.productid AND Photo.mainphoto = 1
				LEFT JOIN productnew PN ON P.idproduct = PN.productid
				LEFT JOIN vat V ON P.vatid= V.idvat
				LEFT JOIN productreview PREV ON PREV.productid = P.idproduct
				LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
				LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
			');
		}
		
		$dataset->setAdditionalWhere('
			PC.categoryid = :categoryid AND 
			IF(:filterbyproducer > 0, P.producerid IN (:producer), 1) AND
			ROUND((P.sellprice + (P.sellprice * V.`value`)/100), 2) BETWEEN IF(:pricefrom > 0, :pricefrom, 0) AND IF( :priceto > 0, :priceto, 999999) AND
			IF (:name <> \'\', PT.name LIKE :name, 1) AND 
			P.enable = 1 AND
			IF(:enablelayer > 0, P.idproduct IN (:products), 1) AND
			IF(P.producerid IS NOT NULL, PV.viewid = :viewid, 1)
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
			'filterbyproducer' => 0,
			'enablelayer' => 0,
			'products' => Array()
		));
	}

	public function getProductDataset () {
		return $this->getDataset()->getDatasetRecords();
	}

	public function addAJAXOpinionAboutProduct ($productid, $params) {
		$objResponseNewOpinion = new xajaxResponse();
		$review = $params['htmlopinion'];
		try{
			if ($review == NULL){
				$objResponseNewOpinion->script('alert("' . $this->registry->core->getMessage('ERR_FILL_AN_OPINION') . '")');
			}
			else{
				$review = App::getModel('formprotection')->cropDangerousCode($review);
				if ($review == NULL){
					$objResponseNewOpinion->script('alert("' . $this->registry->core->getMessage('ERR_ADD_DENGEROUS_OPINION') . '")');
					$objResponseNewOpinion->clear('htmlopinion', 'reset');
				}
				else{
					$this->addOpinionAboutProduct($productid, $params);
					$objResponseNewOpinion->script('window.location.reload(false)');
				}
			}
		}
		catch (FrontendException $fe){
			$objResponseNewOpinion->script('alert("' . $this->registry->core->getMessage('ERR_ADD_OPINION') . '")');
		}
		return $objResponseNewOpinion;
	}

	/**
	 * Adding product to client's wishlist
	 * Xajax method
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	integer idattribute (0 by default)
	 * @return object response
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function addAJAXProductToWishList ($idproduct, $idattribute = 0) {
		$objResponseAddToWishList = new xajaxResponse();
		try{
			if ($idproduct == NULL){
				$objResponseAddToWishList->script('alert("' . $this->registry->core->getMessage('ERR_WISHLIST_NO_PRODUCT_SELECTED') . '")');
			}
			else{
				if ($idattribute == 0){
					$check = $this->addProductToClientWishList($idproduct, 0);
					$objResponseAddToWishList->script('window.location.reload( false )');
				}
				else{
					$check = $this->addProductToClientWishList($idproduct, $idattribute);
				}
				if ($check == true){
					$objResponseAddToWishList->script('alert("' . $this->registry->core->getMessage('TXT_WISHLIST_PRODUCT_WAS_ADDED') . '")');
					$objResponseAddToWishList->script('window.location.reload( false )');
				}
				else{
					$objResponseAddToWishList->script('alert("' . $this->registry->core->getMessage('ERR_WISHLIST_HAS_THIS_PRODUCT') . '")');
				}
			}
		}
		catch (FrontendException $fe){
			$objResponseAddToWishList->script('alert("' . $this->registry->core->getMessage('ERR_ADD_PRODUCT_TO_WISH_LIST') . '")');
		}
		return $objResponseAddToWishList;
	}

	/**
	 * Adding product to client's wishlist
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	integer idattribute (0 by default)
	 * @return bool TRUE if adding was successful or FALSE otherwise
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function addProductToClientWishList ($idproduct, $idattribute = 0) {
		$sql = "SELECT COUNT(WL.idwishlist) as counter
					FROM wishlist WL
					WHERE WL.clientid = :clientid 
						AND WL.productid = :productid
						AND WL.productattributesetid = :productattributesetid
						AND WL.viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('productattributesetid', $idattribute);
		$stmt->setInt('productid', $idproduct);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			$rs->getAllRows();
			if ($rs->first()){
				$counter = $rs->getInt('counter');
				if ($counter == 0){
					$sql = "INSERT INTO wishlist (productid, productattributesetid, clientid, wishprice, viewid)
								VALUES (:productid, :productattributesetid, :clientid, 0, :viewid)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $idproduct);
					$stmt->setInt('productattributesetid', $idattribute);
					$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
					$stmt->setInt('viewid', Helper::getViewId());
					try{
						$stmt->executeUpdate();
					}
					catch (FrontendException $e){
						throw new FrontendException($e->getMessage());
					}
					return true;
				}
				else{
					return false;
				}
			}
		}
		catch (FrontendException $fe){
			throw new FrontendException($fe->getMessage());
		}
	}

	public function getProductPrices ($idproduct) {
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		if (! empty($clientGroupId)){
			$sql = 'SELECT
						C.currencysymbol,
						V.value AS vatvalue,
						IF(PGP.groupprice = 1, PGP.sellprice, P.sellprice) * CR.exchangerate AS pricenetto,
						IF(PGP.groupprice = 1, PGP.sellprice, P.sellprice) * (1 + (V.value / 100)) * CR.exchangerate AS price,
						P.buyprice * CR.exchangerate AS buypricenetto,
						P.buyprice * (1 + (V.value / 100)) * CR.exchangerate AS buyprice,
						P.shippingcost * CR.exchangerate AS shippingcost,
						IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
							PGP.discountprice,
							IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, NULL)
						) * CR.exchangerate AS discountpricenetto,
						IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
						 	PGP.discountprice,
							IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice, NULL)
						) * (1 + (V.value / 100)) * CR.exchangerate AS discountprice
					FROM product P
					LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid = :group
					LEFT JOIN vat V ON P.vatid= V.idvat
					LEFT JOIN currency C ON C.idcurrency = P.sellcurrencyid
					LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
					WHERE P.idproduct = :id';
		}
		else{
			$sql = 'SELECT
						C.currencysymbol,
						V.value AS vatvalue,
						P.sellprice * CR.exchangerate AS pricenetto,
						P.sellprice * (1 + (V.value / 100)) * CR.exchangerate AS price,
						P.buyprice * CR.exchangerate AS buypricenetto,
						P.buyprice * (1 + (V.value / 100)) * CR.exchangerate AS buyprice,
						P.shippingcost * CR.exchangerate AS shippingcost,
						IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * CR.exchangerate, NULL) AS discountpricenetto,
						IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), P.discountprice * (1 + (V.value / 100)) * CR.exchangerate, NULL) AS discountprice
					FROM product P
					LEFT JOIN vat V ON P.vatid= V.idvat
					LEFT JOIN currency C ON C.idcurrency = P.sellcurrencyid
					LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
					WHERE P.idproduct = :id';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('group', $clientGroupId);
		$stmt->setInt('id', $idproduct);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'pricenetto' => $rs->getFloat('pricenetto'),
					'price' => $rs->getFloat('price'),
					'buypricenetto' => $rs->getFloat('buypricenetto'),
					'buyprice' => $rs->getFloat('buyprice'),
					'shippingcost' => $rs->getFloat('shippingcost'),
					'discountpricenetto' => $rs->getFloat('discountpricenetto'),
					'discountprice' => $rs->getFloat('discountprice'),
					'vatvalue' => $rs->getFloat('vatvalue'),
					'currencysymbol' => $rs->getString('currencysymbol')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Get information for chosen product
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	integer idclient (0 by default)
	 * @param
	 *        	idcategory (0 by default)
	 * @return array with product informations
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function getProductById ($id) {
		$sql = "SELECT 
					P.`status`, 
					P.ean,
					P.delivelercode,
					P.stock,
					IF(P.trackstock IS NULL, 0, P.trackstock) AS trackstock,
					PT.name as productname,
					PT.shortdescription, 
					PT.description, 
					PT.longdescription, 
					PT.seo, 
					PRODT.name AS producername,
					PRODT.seo AS producerurl,
					PROD.photoid AS producerphoto,
					IF(PHOTO.photoid IS NOT NULL, IF(PHOTO.mainphoto= 1, PHOTO.photoid, 0),0) as mainphotoid,
					PT.keyword_title AS keyword_title, 
					IF(PT.keyword = '', VT.keyword, PT.keyword) AS keyword, 
					IF(PT.keyword_description = '',VT.keyword_description,PT.keyword_description) AS keyword_description,
					P.weight,
					IF(PN.active = 1 AND (PN.enddate IS NULL OR PN.enddate >= CURDATE()), 1, 0) AS new,
					P.unit,
					IF(CEILING(AVG(PRANGE.value)) IS NULL, 0, CEILING(AVG(PRANGE.value))) AS rating,
					C.photoid AS categoryphoto,
					CT.name AS categoryname,
					CT.seo AS categoryseo
				FROM product P
					LEFT JOIN producttranslation PT ON P.idproduct= PT.productid AND PT.languageid= :languageid
					LEFT JOIN productcategory PROCAT ON P.idproduct = PROCAT.productid
					LEFT JOIN categorytranslation CT ON PROCAT.categoryid = CT.categoryid AND CT.languageid = :languageid
					LEFT JOIN category C ON PROCAT.categoryid = C.idcategory
					LEFT JOIN viewcategory VC ON PROCAT.categoryid = VC.categoryid
					LEFT JOIN viewtranslation VT ON VT.viewid = VC.viewid
					LEFT JOIN producer AS PROD ON P.producerid= PROD.idproducer
					LEFT JOIN producertranslation PRODT ON PROD.idproducer= PRODT.producerid AND PRODT.languageid= :languageid
					LEFT JOIN productphoto PHOTO ON P.idproduct= PHOTO.productid AND PHOTO.mainphoto = 1
					LEFT JOIN productnew PN ON P.idproduct = PN.productid
					LEFT JOIN productrange PRANGE ON PRANGE.productid = P.idproduct
					WHERE P.idproduct= :productid AND P.enable = 1 AND VC.viewid = :viewid
					GROUP BY P.idproduct";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('productid', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				
				$price = $this->getProductPrices($id);
				$Data = Array(
					'idproduct' => $id,
					'seo' => $rs->getString('seo'),
					'ean' => $rs->getString('ean'),
					'delivelercode' => $rs->getString('delivelercode'),
					'producername' => $rs->getString('producername'),
					'producerurl' => urlencode($rs->getString('producerurl')),
					'producerphotoid' => $rs->getInt('producerphoto'),
					'producerphoto' => App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($rs->getString('producerphoto'), 0)),
					'stock' => $rs->getInt('stock'),
					'trackstock' => $rs->getInt('trackstock'),
					'new' => $rs->getInt('new'),
					'pricewithoutvat' => $price['pricenetto'],
					'price' => $price['price'],
					'shippingcost' => $price['shippingcost'],
					'discountpricenetto' => $price['discountpricenetto'],
					'discountprice' => $price['discountprice'],
					'buypricenetto' => $price['buypricenetto'],
					'buyprice' => $price['buyprice'],
					'vatvalue' => $price['vatvalue'],
					'currencysymbol' => $price['currencysymbol'],
					'mainphotoid' => ($rs->getInt('mainphotoid') > 0) ? $rs->getInt('mainphotoid') : $this->layer['defaultphotoid'],
					'description' => $rs->getString('description'),
					'longdescription' => $rs->getString('longdescription'),
					'productname' => $rs->getString('productname'),
					'shortdescription' => $rs->getString('shortdescription'),
					'keyword_title' => ($rs->getString('keyword_title') == NULL || $rs->getString('keyword_title') == '') ? $rs->getString('productname') : $rs->getString('keyword_title'),
					'keyword_description' => $rs->getString('keyword_description'),
					'keyword' => $rs->getString('keyword'),
					'weight' => $rs->getFloat('weight'),
					'unit' => $rs->getInt('unit'),
					'categoryphoto' => App::getModel('gallery')->getImagePath(App::getModel('gallery')->getSmallImageById($rs->getString('categoryphoto'), 0)),
					'categoryname' => $rs->getString('categoryname'),
					'categoryseo' => $rs->getString('categoryseo'),
					'rating' => $rs->getFloat('rating'),
					'staticattributes' => $this->getStaticAttributes($id),
					'tierpricing' => App::getModel('tierpricing')->getTierPricingById($id)
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	public function getStaticAttributes ($id) {
		$sql = 'SELECT 
					SAT.name AS attributename,
					SGT.name AS groupname,
					SAT.description,
					SAT.file AS file,
					PSA.staticgroupid
				FROM productstaticattribute PSA 
				LEFT JOIN staticattributetranslation SAT ON SAT.staticattributeid = PSA.staticattributeid AND SAT.languageid = :languageid
				LEFT JOIN staticgrouptranslation SGT ON SGT.staticgroupid = PSA.staticgroupid AND SGT.languageid = :languageid
				WHERE PSA.productid = :id
				GROUP BY PSA.staticattributeid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('staticgroupid')]['name'] = $rs->getString('groupname');
			$Data[$rs->getInt('staticgroupid')]['attributes'][] = Array(
				'name' => $rs->getString('attributename'),
				'description' => $rs->getString('description'),
				'file' => $rs->getString('file')
			);
		}
		return $Data;
	}

	public function getMetadataForProduct () {
		$params = $this->registry->router->getParams();
		if (! is_numeric($params)){
			$this->productid = App::getModel('product')->getProductIdBySeo($params);
		}
		else{
			$this->productid = (int) $this->registry->core->getParam();
		}
		if ($this->productid === NULL)
			return '';
		$Data = $this->getProductById($this->productid);
		if (! empty($Data)){
			$KeywordData = Array(
				'keyword_title' => $Data['keyword_title'],
				'keyword' => $Data['keyword'],
				'keyword_description' => $Data['keyword_description']
			);
			return $KeywordData;
		}
	}

	/**
	 * Get all attributes for selected product.
	 *
	 * Each attribute has a new price.
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	integer idclient (0 by default)
	 * @return array with attributes' product informations
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function getAttributesForProductById ($id) {
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		
		if (! empty($clientGroupId)){
			$sql = "SELECT 
						P.idproduct as id, 
						PAS.stock, 
						PAS.idproductattributeset, 
						PAS.`value`,
						PAS.symbol,
						PAS.photoid,
						IF(PAS.weight IS NULL, P.weight, PAS.weight) AS weight,
						PAVS.idproductattributevalueset, 
						PAVS.productattributesetid AS attributesgroup,
						APV.name AS attributename, 
						APV.idattributeproductvalue AS attributeid,
						AP.name AS attributegroupname, 
						AP.idattributeproduct AS attributegroupid, 
						IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
							CASE PAS.suffixtypeid
                            	WHEN 1 THEN PGP.discountprice * (PAS.value / 100)
                                WHEN 2 THEN PGP.discountprice + PAS.value
                                WHEN 3 THEN PGP.discountprice - PAS.value
                            	WHEN 4 THEN PAS.`value`
                            END,
							IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), 
								PAS.discountprice, 
								IF(PGP.sellprice IS NOT NULL, 
									CASE PAS.suffixtypeid
		                            	WHEN 1 THEN PGP.sellprice * (PAS.value / 100)
		                                WHEN 2 THEN PGP.sellprice + PAS.value
		                                WHEN 3 THEN PGP.sellprice - PAS.value
		                            	WHEN 4 THEN PAS.`value`
	                            	END,
									PAS.attributeprice
								)
							)
						) * CR.exchangerate AS attributeprice,
						(PAS.attributeprice * CR.exchangerate) AS attributepricenettobeforepromotion,
						(PAS.attributeprice * (1 + (V.value / 100)) * CR.exchangerate) AS attributepricegrossbeforepromotion,
						IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
							CASE PAS.suffixtypeid
                            	WHEN 1 THEN PGP.discountprice * (PAS.value / 100)
                                WHEN 2 THEN PGP.discountprice + PAS.value
                                WHEN 3 THEN PGP.discountprice - PAS.value
                            	WHEN 4 THEN PAS.`value`
                            END,
							IF(PGP.groupprice IS NULL AND P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), 
								PAS.discountprice, 
								IF(PGP.sellprice IS NOT NULL, 
									CASE PAS.suffixtypeid
		                            	WHEN 1 THEN PGP.sellprice * (PAS.value / 100)
		                                WHEN 2 THEN PGP.sellprice + PAS.value
		                                WHEN 3 THEN PGP.sellprice - PAS.value
		                            	WHEN 4 THEN PAS.`value`
	                            	END,
									PAS.attributeprice
								)
							)
						) * (1 + (V.value / 100)) * CR.exchangerate AS price
	                FROM productattributeset AS PAS
				    LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid = PAS.idproductattributeset
				    LEFT JOIN attributeproductvalue APV ON PAVS.attributeproductvalueid = APV.idattributeproductvalue
				    LEFT JOIN attributeproduct AS AP ON APV.attributeproductid = AP.idattributeproduct 
				    LEFT JOIN product AS P ON PAS.productid = P.idproduct
				    LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid = :clientgroupid
				    LEFT JOIN `vat` V ON P.vatid = V.idvat 
				    LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				    WHERE PAS.productid = :id AND PAS.status = 1";
		}
		else{
			$sql = "SELECT 
						P.idproduct as id, 
						PAS.stock, 
						PAS.idproductattributeset, 
						PAS.`value`,
						PAS.symbol,
						PAS.photoid,
						IF(PAS.weight IS NULL, P.weight, PAS.weight) AS weight,
						PAVS.idproductattributevalueset, 
						PAVS.productattributesetid AS attributesgroup,
						APV.name AS attributename, 
						APV.idattributeproductvalue AS attributeid,
						AP.name AS attributegroupname, 
						AP.idattributeproduct AS attributegroupid, 
						IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), PAS.discountprice, PAS.attributeprice) * CR.exchangerate AS attributeprice, 
						IF(P.promotion = 1 AND IF(P.promotionstart IS NOT NULL, P.promotionstart <= CURDATE(), 1) AND IF(P.promotionend IS NOT NULL, P.promotionend >= CURDATE(), 1), PAS.discountprice, PAS.attributeprice) * (1 + (V.value / 100)) * CR.exchangerate AS price,
						(PAS.attributeprice * CR.exchangerate) AS attributepricenettobeforepromotion,
						(PAS.attributeprice * (1 + (V.value / 100)) * CR.exchangerate) AS attributepricegrossbeforepromotion 
	                FROM productattributeset AS PAS
				    LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid = PAS.idproductattributeset
				    LEFT JOIN attributeproductvalue APV ON PAVS.attributeproductvalueid = APV.idattributeproductvalue
				    LEFT JOIN attributeproduct AS AP ON APV.attributeproductid = AP.idattributeproduct 
				    LEFT JOIN product AS P ON PAS.productid = P.idproduct
				    LEFT JOIN `vat` V ON P.vatid = V.idvat 
				    LEFT JOIN currencyrates CR ON CR.currencyfrom = P.sellcurrencyid AND CR.currencyto = :currencyto
				    WHERE PAS.productid = :id AND PAS.status = 1";
		}
		
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$stmt->setInt('clientgroupid', $clientGroupId);
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$price = 0;
				$priceWithoutVat = 0;
				$attrId = $rs->getInt('idproductattributeset');
				
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'stock' => $rs->getInt('stock'),
					'symbol' => $rs->getString('symbol'),
					'photo' => $rs->getString('photoid'),
					'weight' => $rs->getString('weight'),
					'idproductattributeset' => $rs->getInt('idproductattributeset'),
					'idproductattributevalueset' => $rs->getInt('idproductattributevalueset'),
					'attributesgroup' => $rs->getInt('attributesgroup'),
					'attributename' => $rs->getString('attributename'),
					'attributeid' => $rs->getString('attributeid'),
					'attributegroupname' => $rs->getString('attributegroupname'),
					'attributegroupid' => $rs->getInt('attributegroupid'),
					'attributeprice' => $rs->getFloat('attributeprice'),
					'value' => $rs->getFloat('value'),
					'price' => $rs->getFloat('price'),
					'attributepricenettobeforepromotion' => $rs->getFloat('attributepricenettobeforepromotion'),
					'attributepricegrossbeforepromotion' => $rs->getFloat('attributepricegrossbeforepromotion')
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Get product's photos
	 *
	 * @param
	 *        	integer idproduct
	 * @return array with ids' of product's photos
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function getPhotosByProductId ($id) {
		$sql = "SELECT photoid
					FROM productphoto
					WHERE productid= :id 
					AND mainphoto= 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
		return $rs->getAllRows();
	}

	public function getOtherPhotosByProductId ($id) {
		$sql = "SELECT photoid
					FROM productphoto
					WHERE productid= :id 
					AND mainphoto = 0";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
		return $rs->getAllRows();
	}

	/**
	 * Get data of product and product's attributes
	 *
	 * @param
	 *        	integer idproduct
	 * @return array with attributes and photos
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function getFilesByProductId ($id) {
		$sql = "SELECT 
					F.name, 
					F.idfile,
					FE.name AS filextensioname
				FROM productfile PF 
				LEFT JOIN file F ON PF.fileid = F.idfile 
				LEFT JOIN fileextension FE ON FE.idfileextension = F.fileextensionid
				WHERE PF.productid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException('Error while doing sql query.', 11, $e->getMessage());
		}
		return $rs->getAllRows();
	}

	public function updateViewedCount ($id) {
		$sql = "UPDATE product SET viewed = viewed+1 WHERE idproduct = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$rs = $stmt->executeQuery();
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
	}

	public function getProductAndAttributesById ($id) {
		try{
			$Data = $this->getProductById($id);
			if ($Data != NULL){
				$Data['attributes'] = $this->getAttributesForProductById($id);
				$Data['photo'] = $this->getPhotosByProductId($id);
				$Data['otherphoto'] = $this->getOtherPhotosByProductId($id);
			}
		}
		catch (Exception $e){
			throw new FrontendException($e->getMessage());
		}
		return $Data;
	}

	/**
	 * Get product's photos
	 *
	 * @param
	 *        	pointer to member product array
	 * @return array product with photos (small, norma, and original)
	 * @access public
	 */
	public function getPhotos (&$product) {
		$gallery = App::getModel('gallery');
		
		if (is_array($product['photo'])){
			if (isset($product['mainphotoid']) && $product['mainphotoid'] > 0){
				$product['mainphoto']['small'] = $gallery->getImagePath($gallery->getSmallImageById($product['mainphotoid']));
				$product['mainphoto']['normal'] = $gallery->getImagePath($gallery->getNormalImageById($product['mainphotoid']));
				$product['mainphoto']['orginal'] = $gallery->getImagePath($gallery->getOrginalImageById($product['mainphotoid']));
			}
			foreach ($product['photo'] as $photo){
				$product['photo']['small'][] = $gallery->getImagePath($gallery->getSmallImageById($photo['photoid']));
				$product['photo']['normal'][] = $gallery->getImagePath($gallery->getNormalImageById($photo['photoid']));
				$product['photo']['orginal'][] = $gallery->getImagePath($gallery->getOrginalImageById($photo['photoid']));
			}
			if (isset($product['producerphotoid']) && $product['producerphotoid'] > 0){
				$product['producerphoto']['small'] = $gallery->getImagePath($gallery->getSmallImageById($product['producerphotoid']));
				$product['producerphoto']['normal'] = $gallery->getImagePath($gallery->getNormalImageById($product['producerphotoid']));
				$product['producerphoto']['orginal'] = $gallery->getImagePath($gallery->getOrginalImageById($product['producerphotoid']));
			}
		}
	}

	public function getOtherPhotos (&$product) {
		$gallery = App::getModel('gallery');
		
		if (is_array($product['otherphoto'])){
			if (isset($product['mainphotoid']) && $product['mainphotoid'] = 0){
				$product['mainphoto']['small'] = $gallery->getImagePath($gallery->getSmallImageById($product['mainphotoid']));
				$product['mainphoto']['normal'] = $gallery->getImagePath($gallery->getNormalImageById($product['mainphotoid']));
				$product['mainphoto']['orginal'] = $gallery->getImagePath($gallery->getOrginalImageById($product['mainphotoid']));
			}
			foreach ($product['otherphoto'] as $photo){
				$product['otherphoto']['small'][] = $gallery->getImagePath($gallery->getSmallImageById($photo['photoid']));
				$product['otherphoto']['normal'][] = $gallery->getImagePath($gallery->getNormalImageById($photo['photoid']));
				$product['otherphoto']['orginal'][] = $gallery->getImagePath($gallery->getOrginalImageById($photo['photoid']));
			}
		}
	}

	public function getProductAttributes (&$product, $groupid = NULL, $groupname = NULL) {
		$Data = Array();
		if (isset($product['attributes'])){
			if (count($product['attributes']) == 0)
				return $Data;
			foreach ($product['attributes'] as $attribute){
				if ($groupname !== NULL && $attribute['attributegroupname'] == $groupname && $groupid == NULL){
					$Data[$attribute['attributeid']] = $attribute['attributename'];
				}
				if ($attribute['attributegroupid'] == $groupid){
					$Data[$attribute['attributeid']] = $attribute['attributename'];
				}
			}
		}
		return $Data;
	}

	public function getProductAttributeGroups (&$product, $withAttr = true) {
		$Data = Array();
		if (isset($product['attributes'])){
			if (count($product['attributes']) == 0)
				return $Data;
			foreach ($product['attributes'] as $attribute){
				$Data[$attribute['attributegroupid']]['name'] = $attribute['attributegroupname'];
				if ($withAttr == true){
					$Data[$attribute['attributegroupid']]['attributes'] = $this->getProductAttributes($product, $attribute['attributegroupid']);
				}
			}
		}
		return $Data;
	}

	public function getProductVariant (&$product, $withVariants = true) {
		$Data = Array();
		if (isset($product['attributes'])){
			if (count($product['attributes']) == 0){
				return $Data;
			}
			else{
				$gallery = App::getModel('gallery');
				foreach ($product['attributes'] as $variant){
					$Data[$variant['attributesgroup']]['grid'] = $variant['attributesgroup'];
					$Data[$variant['attributesgroup']]['value'] = $variant['value'];
					if ($withVariants == true){
						$Data[$variant['attributesgroup']]['variant'] = $this->getVariants($product, $variant['attributesgroup']);
						$Data[$variant['attributesgroup']]['stock'] = $variant['stock'];
						$Data[$variant['attributesgroup']]['sellprice'] = $variant['price'];
						$Data[$variant['attributesgroup']]['price'] = $variant['price'];
						$Data[$variant['attributesgroup']]['attributepricenettobeforepromotion'] = $variant['attributepricenettobeforepromotion'];
						$Data[$variant['attributesgroup']]['attributepricegrossbeforepromotion'] = $variant['attributepricegrossbeforepromotion'];
						$Data[$variant['attributesgroup']]['symbol'] = $variant['symbol'];
						if (isset($variant['photo']) && $variant['photo'] > 1){
							$Data[$variant['attributesgroup']]['photonormal']= $gallery->getImagePath($gallery->getNormalImageById($variant['photo']));
							$Data[$variant['attributesgroup']]['photoorginal'] = $gallery->getImagePath($gallery->getOrginalImageById($variant['photo']));
						}
						else{
							$Data[$variant['attributesgroup']]['photonormal'] = '';
							$Data[$variant['attributesgroup']]['photoorginal'] = '';
						}
						$Data[$variant['attributesgroup']]['sellpricenetto'] = $variant['attributeprice'];
					}
				}
			}
			return $Data;
		}
	}

	public function getVariants (&$product, $attributesgroup) {
		$Data = Array();
		if (isset($product['attributes'])){
			if (count($product['attributes']) == 0)
				return $Data;
			foreach ($product['attributes'] as $variant){
				if ($attributesgroup !== NULL && $variant['attributesgroup'] == $attributesgroup){
					$Data[$variant['attributeid']] = $variant['attributename'];
				}
				if ($variant['attributesgroup'] == $attributesgroup){
					$Data[$variant['attributeid']] = $variant['attributename'];
				}
			}
		}
		return $Data;
	}

	/**
	 * Adding an opinion
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	string review
	 * @return id from generator
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function addOpinionAboutProduct ($productid, $params) {
		$review = App::getModel('formprotection')->cropDangerousCode($params['htmlopinion']);
		$sql = 'INSERT INTO productreview (productid, clientid, review)
					VALUES (:productid, :clientid, :review)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setString('review', $review);
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
		
		$reviewid = $stmt->getConnection()->getIdGenerator()->getId();
		
		foreach ($params as $rangetypeid => $value){
			if (is_numeric($rangetypeid) && ($value > 0)){
				$sql = 'INSERT INTO productrange SET
								productid = :productid,
								rangetypeid = :rangetypeid,
								productreviewid = :productreviewid,
								value = :value';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $productid);
				$stmt->setInt('rangetypeid', $rangetypeid);
				$stmt->setInt('productreviewid', $reviewid);
				$stmt->setInt('value', $value);
				try{
					$stmt->executeUpdate();
				}
				catch (FrontendException $e){
					throw new FrontendException($e->getMessage());
				}
			}
		}
		return $reviewid;
	}

	/**
	 * Get product's range
	 *
	 * @param
	 *        	integer idproduct
	 * @return array wiht range's information
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function getProductRange ($productid) {
		$sql = "SELECT rangetypeid,
						COUNT(PR.`rangetypeid`) as qty, 
						SUM(PR.`value`) as sum, 
						ROUND(SUM(PR.`value`)/COUNT(PR.`rangetypeid`) , 0) as pkt
					FROM productrange PR
					WHERE productid=:productid
					GROUP BY rangetypeid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		try{
			while ($rs->next()){
				$Data[] = Array(
					'rangeid' => $rs->getInt('rangeid'),
					'pkt' => $rs->getInt('pkt')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('error product range');
		}
		return $Data;
		;
	}

	public function getRangeType ($productId) {
		$sql = "SELECT 
						RT.idrangetype as id, 
						RTT.name,
						CEILING(AVG(PR.value)) AS mean
					FROM rangetype RT
					LEFT JOIN rangetypecategory RTC ON RTC.rangetypeid = RT.idrangetype
					LEFT JOIN rangetypetranslation RTT ON RTT.rangetypeid = RT.idrangetype AND RTT.languageid = :languageid
					LEFT JOIN productrange PR ON PR.rangetypeid = RT.idrangetype AND PR.productid = :productid
					LEFT JOIN viewcategory VC ON VC.categoryid = RTC.categoryid
					WHERE VC.viewid=:viewid
					GROUP BY idrangetype";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('productid', $productId);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		try{
			while ($rs->next()){
				$Data[] = Array(
					'id' => $rs->getInt('id'),
					'name' => $rs->getString('name'),
					'values' => $this->getRangeValues(),
					'mean' => $rs->getInt('mean')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error RangeType Or RangeTypeCategory', 11, $e->getMessage());
		}
		return $Data;
	}

	public function getProductRangeValues ($rangetypeid, $productid) {
		$sql = "SELECT AVG(value) FROM productrange WHERE productid = 5011 AND rangetypeid = 21";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
	}

	public function getRangeValues () {
		return range(1, 5, 1);
	}

	/**
	 * Adding product's tag
	 * Xajax method
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	string tag (NULL by default)
	 * @return obejct response
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function addAJAXTagsForProduct ($idproduct, $tag = NULL) {
		$objResponseAddTags = new xajaxResponse();
		try{
			if ($tag == NULL){
				$objResponseAddTags->script('GError("' . $this->registry->core->getMessage('ERR_FILL_A_TAG') . '")');
			}
			else 
				if ($idproduct === NULL){
					$objResponseAddTags->script('GError("' . $this->registry->core->getMessage('ERR_EMPTY_PRODUCT') . '")');
				}
				else{
					$tag = App::getModel('formprotection')->cropDangerousCode($tag);
					$result = $this->checkInsertingTags($tag);
					// result: 0-idtags, 1-textcount
					// if returned rows havn't selected tag- insert new one
					// inser new tag for product
					if ($result == NULL){
						$this->addTagsForProduct($idproduct, $tag, 0, 0);
						$objResponseAddTags->script('GMessage("' . $this->registry->core->getMessage('TXT_ADD_NEW_TAG') . '")');
						$objResponseAddTags->script('xajax_refreshTags()');
						// increase couter of this tag and insert row about user
					}
					else{
						foreach ($result as $res){
							$checkclient = $this->checkClientTags($idproduct, $res['idtags']);
							if ($checkclient == NULL || $checkclient == 0){
								$this->addTagsForProduct($idproduct, $tag, $res['idtags'], $res['textcount']);
								$objResponseAddTags->assign("htmltag", "value", "");
								$objResponseAddTags->script('GMessage("' . $this->registry->core->getMessage('TXT_ADD_NEW_TAG') . '")');
								$objResponseAddTags->script('xajax_refreshTags()');
							}
							else{
								$objResponseAddTags->assign("htmltag", "value", "");
								$objResponseAddTags->script('GMessage("' . $this->registry->core->getMessage('ERR_DUPLICATED_TAG') . '")');
							}
						}
					}
				}
		}
		catch (FrontendException $fe){
			$objResponseAddTags->alert($fe->getMessage());
			return $objResponseAddTags;
		}
		return $objResponseAddTags;
	}

	/**
	 * Checking tag for client
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	integer idtag
	 * @return idclient
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function checkClientTags ($idproduct, $idtags) {
		$sql = "SELECT PT.clientid
					FROM tags T
					LEFT JOIN producttags PT ON T.idtags= PT.tagsid
					WHERE productid= :idproduct
						AND T.idtags= :idtags
						AND clientid= :clientid 
						AND T.viewid= :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idproduct', $idproduct);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('idtags', $idtags);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$result = Array();
		try{
			$res = $stmt->executeQuery();
			$result = $res->getAllRows();
		}
		catch (FrontendException $fe){
			throw new FrontendException('Error while selecting tags for product- checkInsertingTags- product model', 11, $fe->getMessage());
		}
		return $result;
	}

	/**
	 * Checking if tag exist
	 *
	 * @param
	 *        	string tag
	 * @return array with idtag and textcount if tag exist, or empty array
	 *         otherwise
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function checkInsertingTags ($tags) {
		$sql = "SELECT idtags, textcount
					FROM tags
					WHERE name LIKE :tags";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('tags', $tags);
		$result = Array();
		try{
			$res = $stmt->executeQuery();
			$result = $res->getAllRows();
		}
		catch (FrontendException $fe){
			$result = null;
			throw new FrontendException('Error while selecting tags for product- checkInsertingTags- product model', 11, $fe->getMessage());
		}
		return $result;
	}

	/**
	 * Adding product's tag
	 *
	 * @param
	 *        	integer idproduct
	 * @param
	 *        	string tag (can be null)
	 * @param
	 *        	integer idtag (0 by default)
	 * @param
	 *        	counter (0 by default)
	 * @return array with idtag and textcount if tag exist, or empty array
	 *         otherwise
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function addTagsForProduct ($idproduct, $tag = null, $tagid = 0, $counter = 0) {
		// add new product's tag
		if ($tagid == 0 && $counter == 0){
			$this->registry->db->setAutoCommit(false);
			try{
				$newTagId = $this->insertNewTag($tag);
				$this->registry->db->commit();
				$this->registry->db->setAutoCommit(true);
				$this->addTagsForProduct($idproduct, $tag, $newTagId, 0);
			}
			catch (Exception $e){
				throw new FrontendException($this->registry->core->getMessage('ERR_NEWCLIENT_ADD'), 125, $e->getMessage());
			}
			// add row to producttags or increase textcount for tags product
		}
		else{
			// adding records about adding tag to producttags table
			$sql = "INSERT INTO producttags(clientid, tagsid, productid, viewid)
							VALUES (:clientid, :tagsid, :productid, :viewid)";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
			$stmt->setInt('tagsid', $tagid);
			$stmt->setInt('productid', $idproduct);
			$stmt->setInt('viewid', Helper::getViewId());
			try{
				$stmt->executeQuery();
			}
			catch (FrontendException $fe){
				throw new FrontendException($this->registry->core->getMessage('ERR_TAGS_ADDING'), 11, $fe->getMessage());
			}
			if ($counter != 0){
				// update (increase) textcount in tags table and add new row to
				// producttags
				$sql = "UPDATE tags T SET T.textcount = :counter
								WHERE T.idtags = :idtags 
								AND viewid = :viewid";
				$counter = $counter + 1;
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('counter', $counter);
				$stmt->setInt('idtags', $tagid);
				$stmt->setInt('viewid', Helper::getViewId());
				try{
					$stmt->executeQuery();
				}
				catch (FrontendException $fe){
					throw new FrontendException($this->registry->core->getMessage('ERR_TAGS_ADDING'), 11, $fe->getMessage());
				}
			}
		}
	}

	/**
	 * Inserting new tag
	 *
	 * @param
	 *        	string tag
	 * @return idtag from generator
	 * @throws on error FrontendException object will be returned
	 * @access public
	 */
	public function insertNewTag ($tag) {
		$sql = 'INSERT INTO tags(name, textcount, viewid)
					VALUES (:name, 1, :viewid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $tag);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$stmt->executeQuery();
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_TAGS_ADDING'), 11, $fe->getMessage());
		}
		return $stmt->getConnection()->getIdGenerator()->getId();
	}

	public function getProductTagsById ($id) {
		$sql = "SELECT DISTINCT(T.name) as tagsname, T.idtags, PT.productid as id, T.textcount
					FROM tags T
					LEFT JOIN producttags PT ON T.idtags= PT.tagsid
					WHERE productid = :id AND T.viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idtags' => $rs->getInt('idtags'),
				'tagsname' => $rs->getString('tagsname'),
				'textcount' => $rs->getInt('textcount'),
				'percentage' => 0
			);
		}
		foreach ($Data as $key => $tag){
			$max[] = $tag['textcount'];
		}
		foreach ($Data as $key => $tag){
			$Data[$key]['percentage'] = ceil(($tag['textcount'] / max($max)) * 10);
		}
		return $Data;
	}

	public function getBuyAlsoProduct ($id) {
		$sql = "SELECT name FROM orderproduct WHERE productid= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'name' => $rs->getString('name'),
				'products' => $this->getAlsoProduct($id)
			);
		}
		return $Data;
	}

	public function getAlsoProduct ($id) {
		$sql = "SELECT OP.orderid 
				FROM orderproduct OP
				LEFT JOIN `order` O ON O.idorder = OP.orderid
				WHERE OP.productid= :id and O.viewid= :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('orderid');
		}
		return $Data;
	}

	public function GetTechnicalDataForProduct ($productId) {
		$languageId = Helper::getLanguageId();
		$sql = '
				SELECT
					TG.idtechnicaldatagroup AS id,
					TGT.name AS name
				FROM
					technicaldatagroup TG
					LEFT JOIN technicaldatagrouptranslation TGT ON TGT.technicaldatagroupid = TG.idtechnicaldatagroup AND TGT.languageid = :languageId
					LEFT JOIN producttechnicaldatagroup TSG ON TG.idtechnicaldatagroup = TSG.technicaldatagroupid
				WHERE
					TSG.productid = :productId
				GROUP BY
					TG.idtechnicaldatagroup
				ORDER BY
					TSG.order ASC
			';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productId', $productId);
		$stmt->setInt('languageId', $languageId);
		$rs = $stmt->executeQuery();
		$groups = Array();
		$groupIndices = Array();
		while ($rs->next()){
			$groupIndices[] = $rs->getInt('id');
			$groups[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'attributes' => Array()
			);
		}
		if (count($groups)){
			$sql = '
					SELECT
						TA.idtechnicaldataattribute AS id,
						TA.type AS type,
						IF (TA.type = 1, TAV.value, TGA.value) AS value,
						TSG.technicaldatagroupid AS group_id,
						TAT.name AS name
					FROM
						technicaldataattribute TA
						LEFT JOIN technicaldataattributetranslation TAT ON TAT.technicaldataattributeid = TA.idtechnicaldataattribute
						LEFT JOIN producttechnicaldatagroupattribute TGA ON TA.idtechnicaldataattribute = TGA.technicaldataattributeid
						LEFT JOIN producttechnicaldatagroupattributetranslation TAV ON TAV.producttechnicaldatagroupattributeid = TGA.idproducttechnicaldatagroupattribute
						LEFT JOIN producttechnicaldatagroup TSG ON TGA.producttechnicaldatagroupid = TSG.idproducttechnicaldatagroup
					WHERE
						TSG.productid = :productId
						AND TAT.languageId = :languageId
						AND ((TA.type <> 1) OR (TAV.languageid = :languageId))
					GROUP BY
						TA.idtechnicaldataattribute,
						TGA.idproducttechnicaldatagroupattribute
					ORDER BY
						TSG.order ASC,
						TGA.order ASC
				';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productId', $productId);
			$stmt->setInt('languageId', $languageId);
			$rs = $stmt->executeQuery();
			$groupIndex = 0;
			while ($rs->next()){
				$currentGroupIndex = $rs->getInt('group_id');
				if ($currentGroupIndex != $groups[$groupIndex]['id']){
					if ($currentGroupIndex != $groups[++ $groupIndex]['id']){
						throw new CoreException('Something\'s wrong with the technical data indices...');
					}
				}
				$groups[$groupIndex]['attributes'][] = Array(
					'id' => $rs->getInt('id'),
					'type' => $rs->getInt('type'),
					'value' => $rs->getString('value'),
					'name' => $rs->getString('name')
				);
			}
		}
		return $groups;
	}

	public function getImagePath ($id) {
		if ((int) $id == 0)
			$id = $this->layer['defaultphotoid'];
		$Image = App::getModel('gallery')->getSmallImageById($id);
		return App::getModel('gallery')->getImagePath($Image);
	}

	public function getNormalImagePath ($id) {
		if ((int) $id == 0)
			$id = $this->layer['defaultphotoid'];
		$Image = App::getModel('gallery')->getNormalImageById($id);
		return App::getModel('gallery')->getImagePath($Image);
	}

	public function getProducerAll ($Categories = Array()) {
		if (! empty($Categories)){
			$sql = 'SELECT 
						P.idproducer AS id,
						PT.name,
						PT.seo
					FROM producer P
					LEFT JOIN producerview PV ON PV.producerid = P.idproducer
					INNER JOIN product PR ON PR.producerid = P.idproducer
					LEFT JOIN productcategory PC ON PC.productid = PR.idproduct
					LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :language
					WHERE PC.categoryid IN (:categoryid) AND IF(PR.producerid IS NOT NULL, PV.viewid = :viewid, 1)
					GROUP BY P.idproducer
					ORDER BY PT.name ASC';
		}
		else{
			$sql = 'SELECT 
						P.idproducer AS id,
						PT.name,
						PT.seo
					FROM producer P
					LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :language
					GROUP BY P.idproducer
					ORDER BY PT.name ASC';
		}
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setINInt('categoryid', $Categories);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo')
			);
		}
		return $Data;
	}

	public function getProducerAllByProducts ($ids) {
		$sql = 'SELECT 
					P.idproducer AS id,
					PT.name,
					PT.seo
				FROM producer P
				INNER JOIN product PR ON PR.producerid = P.idproducer
				LEFT JOIN producertranslation PT ON PT.producerid = P.idproducer AND PT.languageid = :language
				WHERE PR.idproduct IN (:ids)
				GROUP BY P.idproducer';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('language', Helper::getLanguageId());
		$stmt->setINInt('ids', $ids);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'seo' => $rs->getString('seo')
			);
		}
		return $Data;
	}

	public function checkEraty () {
		$sql = "SELECT 
						ES.wariantsklepu, 
						ES.numersklepu, 
						ES.`char`
					FROM paymentmethod PM
					LEFT JOIN eratysettings ES ON ES.paymentmethodid  = PM.idpaymentmethod
					INNER JOIN paymentmethodview PV ON PM.idpaymentmethod  = PV.paymentmethodid
					WHERE PV.viewid = :viewid AND PM.controller = :controller AND PM.active = 1";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setString('controller', 'eraty');
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'wariantsklepu' => $rs->getInt('wariantsklepu'),
				'numersklepu' => $rs->getString('numersklepu'),
				'char' => $rs->getString('char')
			);
			return $Data;
		}
		return 0;
	}

	public function getProductIdBySeo ($seo) {
		$sql = "SELECT
							productid
						FROM producttranslation
						WHERE seo =:seo AND languageid = :languageid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$stmt->setString('seo', $seo);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				return $rs->getInt('productid');
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($e->getMessage());
		}
	}
}

?>