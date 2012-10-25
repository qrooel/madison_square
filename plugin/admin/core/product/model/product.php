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
class ProductModel extends ModelWithDatagrid {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	public function getNamesForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getAttributeCombinationsForProduct ($productId, $clientGroupId = 0, $currencyid = 0) {
		if ($currencyid == 0){
			$currencyid = $this->registry->session->getActiveCurrencyId();
		}
		$Data = Array();
		if ($clientGroupId > 0){
			$sql = '
				SELECT
					A.idproductattributeset AS id,
					A.`value`,
					A.stock AS qty,
					A.symbol,
					A.status,
					A.weight,
					A.suffixtypeid AS prefix_id,
					GROUP_CONCAT(SUBSTRING(CONCAT(\' \', C.name), 1)) AS name,
					IF(PGP.promotion = 1 AND IF(PGP.promotionstart IS NOT NULL, PGP.promotionstart <= CURDATE(), 1) AND IF(PGP.promotionend IS NOT NULL, PGP.promotionend >= CURDATE(), 1),
						CASE A.suffixtypeid
                           	WHEN 1 THEN PGP.discountprice * (A.value / 100)
                            WHEN 2 THEN PGP.discountprice + A.value
                            WHEN 3 THEN PGP.discountprice - A.value
                          	WHEN 4 THEN A.`value`
                        END,
						IF(PGP.groupprice IS NULL AND D.promotion = 1 AND IF(D.promotionstart IS NOT NULL, D.promotionstart <= CURDATE(), 1) AND IF(D.promotionend IS NOT NULL, D.promotionend >= CURDATE(), 1), 
							A.discountprice, 
							IF(PGP.sellprice IS NOT NULL, 
								CASE A.suffixtypeid
		                           	WHEN 1 THEN PGP.sellprice * (A.value / 100)
		                            WHEN 2 THEN PGP.sellprice + A.value
		                            WHEN 3 THEN PGP.sellprice - A.value
		                          	WHEN 4 THEN A.`value`
	                           	END,
								A.attributeprice
							)
						)
					) * CR.exchangerate AS price,
					(
						SELECT (
							ROUND(price + (price * V.`value` / 100), 2)
						)
					) AS price_gross
				FROM
					productattributeset A
					LEFT JOIN productattributevalueset B ON A.idproductattributeset = B.productattributesetid
					LEFT JOIN attributeproductvalue C ON B.attributeproductvalueid = C.idattributeproductvalue
					LEFT JOIN product D ON A.productid = D.idproduct
					LEFT JOIN productgroupprice PGP ON PGP.productid = D.idproduct AND PGP.clientgroupid = :clientgroupid
					LEFT JOIN currencyrates CR ON CR.currencyfrom = D.sellcurrencyid AND CR.currencyto = :currencyto
					LEFT JOIN suffixtype E ON A.suffixtypeid = E.idsuffixtype
					LEFT JOIN vat V ON V.idvat = D.vatid
				WHERE
					A.productid = :productid
				GROUP BY
					A.idproductattributeset
			';
		}
		else{
			$sql = '
				SELECT
					A.idproductattributeset AS id,
					A.`value`,
					A.stock AS qty,
					A.symbol,
					A.status,
					A.weight,
					A.suffixtypeid AS prefix_id,
					GROUP_CONCAT(SUBSTRING(CONCAT(\' \', C.name), 1)) AS name,
					IF(D.promotion = 1 AND IF(D.promotionstart IS NOT NULL, D.promotionstart <= CURDATE(), 1) AND IF(D.promotionend IS NOT NULL, D.promotionend >= CURDATE(), 1), A.discountprice, A.attributeprice) * CR.exchangerate AS price, 
					(
						SELECT (
							ROUND(price + (price * V.`value` / 100), 2)
						)
					) AS price_gross
				FROM
					productattributeset A
					LEFT JOIN productattributevalueset B ON A.idproductattributeset = B.productattributesetid
					LEFT JOIN attributeproductvalue C ON B.attributeproductvalueid = C.idattributeproductvalue
					LEFT JOIN product D ON A.productid = D.idproduct
					LEFT JOIN productgroupprice PGP ON PGP.productid = D.idproduct AND PGP.clientgroupid = :clientgroupid
					LEFT JOIN currencyrates CR ON CR.currencyfrom = D.sellcurrencyid AND CR.currencyto = :currencyto
					LEFT JOIN suffixtype E ON A.suffixtypeid = E.idsuffixtype
					LEFT JOIN vat V ON V.idvat = D.vatid
				WHERE
					A.productid = :productid
				GROUP BY
					A.idproductattributeset
			';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productId);
		$stmt->setInt('clientgroupid', $clientGroupId);
		$stmt->setInt('currencyto', $currencyid);
		$rs = $stmt->executeQuery();
		$Data = $rs->getAllRows();
		foreach ($Data as $key => $value){
			$sql = '
					SELECT
						B.attributeproductid AS attribute,
						A.attributeproductvalueid AS value,
						B.name AS name
					FROM
						productattributevalueset A
						LEFT JOIN attributeproductvalue B ON A.attributeproductvalueid = B.idattributeproductvalue
					WHERE
						A.productattributesetid = :productattributesetid
				';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productattributesetid', $value['id']);
			$rs = $stmt->executeQuery();
			$Data[$key]['attributes'] = Array();
			while ($rs->next()){
				$Data[$key]['attributes'][] = Array(
					'id' => $rs->getInt('attribute'),
					'value_id' => $rs->getInt('value'),
					'name' => $rs->getString('name')
				);
			}
		}
		return $Data;
	}

	public function initDatagrid ($datagrid) {
		$datagrid->setTableData('product', Array(
			'idproduct' => Array(
				'source' => 'P.idproduct'
			),
			'name' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'seo' => Array(
				'source' => 'PT.seo',
				'processFunction' => Array(
					$this,
					'getProductSeo'
				)
			),
			'delivelercode' => Array(
				'source' => 'P.delivelercode'
			),
			'ean' => Array(
				'source' => 'P.ean'
			),
			'categoryname' => Array(
				'source' => 'CT.name'
			),
			'categoryid' => Array(
				'source' => 'PC.categoryid',
				'prepareForTree' => true,
				'first_level' => $this->getCategories()
			),
			'ancestorcategoryid' => Array(
				'source' => 'CP.ancestorcategoryid'
			),
			'categoriesname' => Array(
				'source' => 'GROUP_CONCAT(DISTINCT SUBSTRING(CONCAT(\' \', CT.name), 1))',
				'filter' => 'having'
			),
			'sellprice' => Array(
				'source' => 'P.sellprice'
			),
			'sellprice_gross' => Array(
				'source' => 'ROUND(P.sellprice * (1 + V.value / 100), 2)'
			),
			'barcode' => Array(
				'source' => 'P.barcode',
				'prepareForAutosuggest' => true
			),
			'buyprice' => Array(
				'source' => 'P.buyprice'
			),
			'buyprice_gross' => Array(
				'source' => 'ROUND(P.buyprice * (1 + V.value / 100), 2)'
			),
			'producer' => Array(
				'source' => 'PRT.name',
				'prepareForSelect' => true
			),
			'deliverer' => Array(
				'source' => 'DT.name',
				'prepareForSelect' => true
			),
			'status' => Array(
				'source' => 'PS.name',
				'prepareForSelect' => true
			),
			'vat' => Array(
				'source' => 'CONCAT(V.value, \'%\')',
				'prepareForSelect' => true
			),
			'stock' => Array(
				'source' => 'P.stock'
			),
			'enable' => Array(
				'source' => 'P.enable'
			),
			'weight' => Array(
				'source' => 'P.weight'
			),
			'adddate' => Array(
				'source' => 'P.adddate'
			),
			'thumb' => Array(
				'source' => 'PP.photoid',
				'processFunction' => Array(
					$this,
					'getThumbPathForId'
				)
			)
		));
		$datagrid->setFrom('
				product P
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN productphoto PP ON PP.productid = P.idproduct AND PP.mainphoto = 1
				LEFT JOIN productstatus PS ON PS.idproductstatus = P.status
				LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
				LEFT JOIN category C ON C.idcategory = PC.categoryid
				LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
				LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PRT.languageid = :languageid
				LEFT JOIN productdeliverer PD ON PD.productid = P.idproduct
				LEFT JOIN deliverertranslation DT ON PD.delivererid = DT.delivererid AND DT.languageid = :languageid
				LEFT JOIN vat V ON P.vatid = V.idvat
			');
		$datagrid->setGroupBy('
				P.idproduct
		');
		
		if (Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
				VC.viewid IN(:viewids)
			');
		}
	}

	public function getProductSeo ($seo) {
		return App::getURLAdress() . $this->registry->core->getFrontendControllerNameForSeo('productcart') . '/' . $seo;
	}

	public function getThumbPathForId ($id) {
		if ($id > 1){
			try{
				$image = App::getModel('gallery')->getSmallImageById($id);
			}
			catch (Exception $e){
				$image = Array(
					'path' => ''
				);
			}
			return $image['path'];
		}
		else{
			return '';
		}
	}

	public function getDatagridFilterData () {
		return $this->getDatagrid()->getFilterData();
	}

	public function getProductForAjax ($request, $processFunction) {
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteProduct ($id, $datagrid) {
		return $this->getDatagrid()->deleteRow($datagrid, $id, Array(
			$this,
			'deleteProduct'
		), $this->getName());
	}

	public function doAJAXChangeProductStatus ($id, $datagrid, $status) {
		$sql = "UPDATE product SET status = :status 
					WHERE idproduct IN (:ids)";
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($status > 0){
			$stmt->setInt('status', $status);
		}
		else{
			$stmt->setNull('status');
		}
		if (is_array($id)){
			$stmt->setINInt('ids', $id);
		}
		else{
			$stmt->setInt('ids', $id);
		}
		$rs = $stmt->executeQuery();
		App::getModel('dataset')->flushCache();
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function setProductEnable ($datagrid, $id, $enable) {
		if (! is_array($id)){
			$id = Array(
				$id
			);
		}
		$sql = "UPDATE product SET enable = :enable 
				WHERE idproduct IN (:id)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('enable', $enable);
		$stmt->setINInt('id', $id);
		$rs = $stmt->executeQuery();
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteProduct ($id) {
		try{
			$this->deleteRelated($id);
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idproduct' => $id
			), $this->getName(), 'deleteProduct');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function deleteRelated ($id) {
		$this->registry->db->executeQuery('SET FOREIGN_KEY_CHECKS=0');
		
		$sql = 'DELETE from upsell WHERE relatedproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$sql = 'DELETE from upsell WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$sql = 'DELETE from crosssell WHERE relatedproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$sql = 'DELETE from crosssell WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$sql = 'DELETE from similarproduct WHERE relatedproductid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$sql = 'DELETE from similarproduct WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->executeQuery();
		
		$this->registry->db->executeQuery('SET FOREIGN_KEY_CHECKS=1');
	}

	public function getProductAndAttributesById ($id, $duplicate = false) {
		try{
			$Data = $this->getProductView($id, $duplicate);
			if (empty($Data)){
				App::redirect(__ADMINPANE__ . '/product');
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	public function getProductView ($id, $duplicate) {
		$sql = "SELECT
  					P.idproduct AS id,
  					P.stock AS standardstock,
  					P.trackstock,
  					P.shippingcost,
  					P.enable,
  					P.ean,
  					P.delivelercode,
  					P.weight,
  					P.width,
  					P.height,
  					P.deepth,
  					P.unit,
  					P.buyprice,
  					P.sellprice,
  					P.buycurrencyid,
  					P.sellcurrencyid,
  					Photo.photoid AS mainphotoid,
  					V.`value` AS vatvalue,
  					P.vatid,
  					ROUND((P.sellprice*V.`value`/100)+P.sellprice, 2) AS sellpricewithvatvalue,
  					ROUND((P.sellprice*V.`value`/100), 2) AS vatvalueofsellprice,
  					IF(P.producerid IS NOT NULL,P.producerid,0) as producerid,
  					PT.seo,
  					P.technicaldatasetid,
  					P.promotion,
  					P.discountprice,
  					P.promotionstart,
  					P.promotionend
  				FROM product P
  				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
    			LEFT JOIN `vat` V ON V.idvat = P.vatid
    			LEFT JOIN productphoto Photo ON Photo.productid = P.idproduct AND Photo.mainphoto = 1
  				WHERE P.idproduct = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$Data = Array();
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = Array(
					'standardstock' => $rs->getString('standardstock'),
					'trackstock' => $rs->getInt('trackstock'),
					'shippingcost' => $rs->getFloat('shippingcost'),
					'enable' => $rs->getInt('enable'),
					'ean' => $rs->getString('ean'),
					'delivelercode' => $rs->getString('delivelercode'),
					'weight' => $rs->getString('weight'),
					'width' => $rs->getString('width'),
					'height' => $rs->getString('height'),
					'deepth' => $rs->getString('deepth'),
					'unit' => $rs->getString('unit'),
					'buyprice' => $rs->getString('buyprice'),
					'sellprice' => $rs->getString('sellprice'),
					'sellpricewithvatvalue' => $rs->getString('sellpricewithvatvalue'),
					'vatvalueofsellprice' => $rs->getString('vatvalueofsellprice'),
					'vatvalue' => $rs->getString('vatvalue'),
					'id' => $rs->getInt('id'),
					'buycurrencyid' => $rs->getInt('buycurrencyid'),
					'sellcurrencyid' => $rs->getInt('sellcurrencyid'),
					'mainphotoid' => $rs->getInt('mainphotoid'),
					'vatid' => $rs->getInt('vatid'),
					'producerid' => $rs->getInt('producerid'),
					'delivererid' => $this->getProductDeliverer($rs->getInt('id')),
					'category' => $this->productCategoryIds($rs->getInt('id')),
					'variants' => $this->getSuffixForProductById($rs->getInt('id'), $duplicate),
					'photo' => $this->productPhotoIds($rs->getInt('id')),
					'file' => $this->productFileIds($rs->getInt('id')),
					'technicaldatasetid' => $rs->getInt('technicaldatasetid'),
					'language' => $this->getProductTranslation($rs->getInt('id')),
					'productnew' => $this->getProductNew($rs->getInt('id')),
					'promotion' => $rs->getInt('promotion'),
					'discountprice' => $rs->getString('discountprice'),
					'promotionstart' => $rs->getString('promotionstart'),
					'promotionend' => $rs->getString('promotionend'),
					'staticattributes' => $this->getStaticAttributes($rs->getInt('id'))
				);
			}
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		return $Data;
	}

	public function getStaticAttributes ($id) {
		$sql = 'SELECT staticgroupid, staticattributeid FROM productstaticattribute WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data['static_group_' . $rs->getInt('staticgroupid')][] = $rs->getInt('staticattributeid');
		}
		return $Data;
	}

	public function getProductTranslation ($id) {
		$sql = "SELECT name,description,longdescription,shortdescription,languageid, seo, keyword_title, keyword_description, keyword
					FROM producttranslation
					WHERE productid =:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('languageid')] = Array(
				'name' => $rs->getString('name'),
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'longdescription' => $rs->getString('longdescription'),
				'seo' => $rs->getString('seo'),
				'keywordtitle' => $rs->getString('keyword_title'),
				'keyworddescription' => $rs->getString('keyword_description'),
				'keyword' => $rs->getString('keyword')
			);
		}
		return $Data;
	}

	public function getSuffixForProductById ($id, $duplicate) {
		$sql = 'SELECT    
					PAS.suffixtypeid as suffix, 
					PAS.`value` as modifier, 
					PAS.stock, 
					PAS.symbol,
					PAS.weight,
					PAS.idproductattributeset,
					PAS.status,
					COUNT(orderid) AS total,
					PAS.photoid AS photoid
			    FROM productattributeset AS PAS
			    LEFT JOIN orderproduct OP ON OP.productattributesetid = PAS.idproductattributeset
			    WHERE PAS.productid=:id
			    GROUP BY PAS.idproductattributeset';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		$i = 0;
		while ($rs->next()){
			if ($duplicate == true){
				$deletable = ((int) $rs->getInt('total') > 0) ? 0 : 1;
			}
			else{
				$deletable = 1;
			}
			$Data[] = Array(
				'idvariant' => ($duplicate == false) ? $rs->getInt('idproductattributeset') : 'new-' . $i,
				'suffix' => $rs->getInt('suffix'),
				'modifier' => $rs->getString('modifier'),
				'stock' => $rs->getInt('stock'),
				'symbol' => $rs->getString('symbol'),
				'weight' => $rs->getString('weight'),
				'attributes' => $this->getAttributesForProductById($rs->getInt('idproductattributeset')),
				'deletable' => $deletable,
				'status' => $rs->getInt('status'),
				'photo' => $rs->getInt('photoid')
			);
			$i ++;
		}
		return $Data;
	}

	public function getAttributesForProductById ($attrId) {
		$sql = "SELECT idattributeproduct as idattributegroup,
				      idattributeproductvalue as  idattribut
				    FROM productattributeset AS PAS
				      LEFT JOIN productattributevalueset AS PAVS ON PAVS.productattributesetid = PAS.idproductattributeset
				      LEFT JOIN attributeproductvalue AS APV ON APV.idattributeproductvalue = PAVS.attributeproductvalueid
				      LEFT JOIN attributeproduct AS AP ON AP.idattributeproduct = APV.attributeproductid
				    WHERE productattributesetid=:attrId";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('attrId', $attrId);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('idattributegroup')] = $rs->getInt('idattribut');
		}
		return $Data;
	}

	public function productPhoto ($id) {
		$sql = 'SELECT photoid AS id FROM productphoto WHERE productid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function productFile ($id) {
		$sql = 'SELECT fileid AS id FROM productfile WHERE productid=:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function productPhotoIds ($id) {
		$Data = $this->productPhoto($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function productFileIds ($id) {
		$Data = $this->productFile($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function getProductNew ($id) {
		$sql = "SELECT 
					enddate as endnew, active as newactive 
				FROM productnew
				WHERE productid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'endnew' => $rs->getString('endnew'),
				'newactive' => $rs->getInt('newactive')
			);
		}
		
		if (empty($Data)){
			$Data = Array(
				'endnew' => '',
				'newactive' => '0'
			);
		}
		return $Data;
	}

	public function getProductDeliverer ($id) {
		$sql = "SELECT delivererid
					FROM productdeliverer
					WHERE productid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			return $rs->getInt('delivererid');
		}
		return 0;
	}

	public function productProducer ($id) {
		$sql = 'SELECT producerid AS id FROM product WHERE idproduct = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function productProducerIds ($id) {
		$Data = $this->productProducer($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function productCategory ($id) {
		if (Helper::getViewId() > 0){
			$sql = 'SELECT VC.categoryid as id
						FROM viewcategory VC,
						productcategory PC
						WHERE VC.viewid = :viewid AND PC.productid = :id AND VC.categoryid = PC.categoryid';
		}
		else{
			$sql = 'SELECT categoryid AS id
						FROM productcategory
						WHERE productid=:id';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		return $rs->getAllRows();
	}

	public function productCategoryIds ($id) {
		$Data = $this->productCategory($id);
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[] = $key['id'];
		}
		return $tmp;
	}

	public function productUpdateAll ($Data, $id) {
		$this->registry->db->setAutoCommit(false);
		try{
			$this->productUpdate($Data, $id);
			$this->productPhotoUpdate($Data, $id);
			$this->productFileUpdate($Data, $id);
			App::getModel('technicaldata')->SaveValuesForProduct($id, $Data['technical_data']);
			if (isset($Data['variants']['set']) && $Data['variants']['set'] > 0){
				$this->updateAttributesProduct($Data, $id);
			}
			$this->updateProductNew($Data, $id);
			$this->updateProductGroupPrices($Data, $id);
			$this->updateProductAttributesetPricesAll();
			$this->deleteRelated($id);
			if (isset($Data['upsell'])){
				$upsell['products'] = $Data['upsell'];
				App::getModel('upsell')->editRelated($upsell, $id);
			}
			if (isset($Data['similar'])){
				$similar['products'] = $Data['similar'];
				App::getModel('similarproduct')->editRelated($similar, $id);
			}
			if (isset($Data['crosssell'])){
				$crosssell['products'] = $Data['crosssell'];
				App::getModel('crosssell')->editRelated($crosssell, $id);
			}
			
			$this->addProductStaticAttributes($Data, $id);
			
			$event = new sfEvent($this, 'admin.product.edit', Array(
				'id' => $id,
				'data' => $Data
			));
			App::getModel('dataset')->flushCache();
			$this->registry->dispatcher->notify($event);
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_EDIT'), 3002, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		App::getModel('category')->flushCache();
	}

	protected function productFileUpdate ($Data, $idproduct) {
		if (isset($Data['file']['unmodified']) && $Data['file']['unmodified']){
			return;
		}
		$sql = 'DELETE FROM productfile WHERE productid = :idproduct';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idproduct', $idproduct);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		try{
			$this->addFileProduct($Data['file'], $idproduct);
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function productPhotoUpdate ($Data, $id) {
		if (isset($Data['photo']['unmodified']) && $Data['photo']['unmodified']){
			return;
		}
		$sql = 'DELETE FROM productphoto WHERE productid =:productid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (isset($Data['photo']['main'])){
			$mainphoto = $Data['photo']['main'];
			foreach ($Data['photo'] as $key => $photo){
				if (! is_array($photo) && is_int($key) && ($photo > 0)){
					$sql = 'INSERT INTO productphoto (productid, mainphoto, photoid, addid)
								VALUES (:productid, :mainphoto, :photoid,  :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $id);
					$stmt->setInt('mainphoto', ($photo == $mainphoto) ? 1 : 0);
					$stmt->setInt('photoid', $photo);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_PHOTO_UPDATE'), 112, $e->getMessage());
					}
				}
			}
		}
	}

	protected function updateProductNew ($Data, $id) {
		$sqlDelete = 'DELETE FROM productnew WHERE productid=:id';
		$stmt = $this->registry->db->prepareStatement($sqlDelete);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if ($Data['newactive'] == 1){
			$sql = 'INSERT INTO productnew (productid, enddate, active, addid)
						VALUES (:productid, :enddate, :active, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			if ($Data['endnew'] == '' || $Data['endnew'] == '0000-00-00 00:00:00'){
				$stmt->setNull('enddate');
			}
			else{
				$stmt->setString('enddate', $Data['endnew']);
			}
			$stmt->setInt('active', $Data['newactive']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_NEW_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function productUpdate ($Data, $id) {
		$this->updateProductcategory($Data['category'], $id);
		$sql = 'UPDATE product SET 
					producerid=:producerid, 
					stock=:stock, 
					enable=:enable, 
					trackstock=:trackstock, 
					shippingcost=:shippingcost, 
					weight=:weight,
					width=:width,
					height=:height,
					deepth=:deepth,
					unit=:unit,
					ean=:ean,
					delivelercode=:delivelercode,
					buyprice=:buyprice,
					sellprice=:sellprice, 
					buycurrencyid=:buycurrencyid,
					sellcurrencyid=:sellcurrencyid,
					vatid=:vatid, 
					editid=:editid,
					editdate= NOW(),
					technicaldatasetid=:setid,
					promotion = :promotion,
					discountprice = :discountprice,
					promotionstart = :promotionstart,
					promotionend = :promotionend
				WHERE idproduct = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('stock', $Data['stock']);
		$stmt->setInt('trackstock', $Data['trackstock']);
		$stmt->setFloat('shippingcost', $Data['shippingcost']);
		$stmt->setFloat('weight', $Data['weight']);
		if ($Data['width'] != ''){
			$stmt->setFloat('width', $Data['width']);
		}
		else{
			$stmt->setNull('width');
		}
		if ($Data['height'] != ''){
			$stmt->setFloat('height', $Data['height']);
		}
		else{
			$stmt->setNull('height');
		}
		if ($Data['deepth'] != ''){
			$stmt->setFloat('deepth', $Data['deepth']);
		}
		else{
			$stmt->setNull('deepth');
		}
		if ($Data['unit'] != ''){
			$stmt->setInt('unit', $Data['unit']);
		}
		else{
			$stmt->setInt('unit', 1);
		}
		$stmt->setString('ean', $Data['ean']);
		$stmt->setString('delivelercode', $Data['delivelercode']);
		if ($Data['producerid'] > 0){
			$stmt->setInt('producerid', $Data['producerid']);
		}
		else{
			$stmt->setInt('producerid', NULL);
		}
		if (isset($Data['enable']) && $Data['enable'] == 1){
			$stmt->setInt('enable', $Data['enable']);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		if (isset($Data['promotion']) && $Data['promotion'] == 1){
			$stmt->setInt('promotion', $Data['promotion']);
			$stmt->setFloat('discountprice', $Data['discountprice']);
			if ($Data['promotionstart'] != ''){
				$stmt->setString('promotionstart', $Data['promotionstart']);
			}
			else{
				$stmt->setNull('promotionstart');
			}
			if ($Data['promotionend'] != ''){
				$stmt->setString('promotionend', $Data['promotionend']);
			}
			else{
				$stmt->setNull('promotionend');
			}
		}
		else{
			$stmt->setInt('promotion', 0);
			$stmt->setFloat('discountprice', 0);
			$stmt->setNull('promotionstart');
			$stmt->setNull('promotionend');
		}
		$stmt->setInt('vatid', $Data['vatid']);
		$stmt->setFloat('buyprice', $Data['buyprice']);
		$stmt->setFloat('sellprice', $Data['sellprice']);
		$stmt->setInt('buycurrencyid', $Data['buycurrencyid']);
		$stmt->setInt('sellcurrencyid', $Data['sellcurrencyid']);
		$stmt->setInt('editid', $this->registry->session->getActiveUserid());
		$stmt->setInt('setid', ($Data['technical_data']['set'] ? $Data['technical_data']['set'] : NULL));
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
		}
		
		$sql = 'DELETE FROM producttranslation WHERE productid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO producttranslation (productid, name, shortdescription,longdescription, description, languageid, seo, keyword_title, keyword, keyword_description)
						VALUES (:productid, :name, :shortdescription,:longdescription, :description, :languageid, :seo, :keyword_title, :keyword, :keyword_description)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('longdescription', $Data['longdescription'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setString('seo', App::getModel('seo')->clearSeoUTF($Data['seo'][$key]));
			$stmt->setString('keyword_title', $Data['keywordtitle'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyworddescription'][$key]);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
		}
		
		$sql = 'DELETE FROM productdeliverer WHERE productid =:id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeQuery();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if ($Data['delivererid'] > 0){
			$sql = 'INSERT INTO productdeliverer (productid, delivererid, addid)
						VALUES (:productid, :delivererid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('delivererid', $Data['delivererid']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_PRODUCT_ADD'), 11, $e->getMessage());
			}
		}
		
		return true;
	}

	public function updateProductcategory ($array, $id) {
		$sql = 'DELETE FROM productcategory WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		
		if (! is_array($array))
			return;
		foreach ($array as $value){
			$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
						VALUES (:productid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('categoryid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_UPDATE'), 112, $e->getMessage());
			}
		}
	}

	public function updateAttributesProduct ($Data, $productid) {
		$this->deleteAttributesProductValueSet($productid);
		if (isset($Data['variants']) && is_array($Data['variants'])){
			$this->deleteAttributesProductSet($productid, array_keys($Data['variants']));
			$this->addAttributesProducts($Data['variants'], $productid);
		}
		$this->updateProductAttributesetPricesAll();
	}

	public function addAttributesProducts ($variant, $newProductId) {
		
		if (empty($variant)){
			return;
		}
		foreach ($variant as $key => $attributegroup){
			if (is_array($attributegroup)){
				if (substr($key, 0, 3) == 'new'){
					$sql = 'INSERT INTO productattributeset (
							productid, 
							stock,
							symbol, 
							status, 
							photoid, 
							weight, 
							suffixtypeid, 
							value, 
							attributegroupnameid, 
							addid
						)
						VALUES 
						(
							:productid, 
							:stock,
							:symbol, 
							:status, 
							:photoid, 
							:weight,
							:suffixtypeid, 
							:value, 
							:attributegroupnameid, 
							:addid
						)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $newProductId);
					$stmt->setInt('stock', $attributegroup['stock']);
					$stmt->setInt('suffixtypeid', $attributegroup['suffix']);
					$stmt->setString('value', $attributegroup['modifier']);
					$stmt->setString('symbol', $attributegroup['symbol']);
					$stmt->setString('status', $attributegroup['status']);
					$stmt->setString('photoid', ((int) $attributegroup['photo'] > 0) ? $attributegroup['photo'] : NULL);
					$stmt->setString('weight', $attributegroup['weight']);
					$stmt->setInt('attributegroupnameid', $variant['set']);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ATTRIBUTES_ADD'), 112, $e->getMessage());
					}
					$contener = $stmt->getConnection()->getIdGenerator()->getId();
					if (is_array($attributegroup['attributes'])){
						$this->getProductVariant($attributegroup['attributes'], $contener);
					}
				}
				else{
					$sql = 'UPDATE productattributeset SET
								stock = :stock,
								symbol = :symbol, 
								status = :status, 
								photoid = :photoid, 
								weight = :weight, 
								suffixtypeid = :suffixtypeid, 
								value = :value, 
								attributegroupnameid = :attributegroupnameid
							WHERE productid = :productid AND idproductattributeset = :id';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $newProductId);
					$stmt->setInt('id', $key);
					$stmt->setInt('stock', $attributegroup['stock']);
					$stmt->setInt('suffixtypeid', $attributegroup['suffix']);
					$stmt->setString('value', $attributegroup['modifier']);
					$stmt->setString('symbol', $attributegroup['symbol']);
					$stmt->setString('status', $attributegroup['status']);
					$stmt->setString('photoid', ((int) $attributegroup['photo'] > 0) ? $attributegroup['photo'] : NULL);
					$stmt->setString('weight', $attributegroup['weight']);
					$stmt->setInt('attributegroupnameid', $variant['set']);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ATTRIBUTES_ADD'), 112, $e->getMessage());
					}
					if (is_array($attributegroup['attributes'])){
						$this->getProductVariant($attributegroup['attributes'], $key);
					}
				}
			}
		}
	}

	protected function deleteAttributesProductSet ($productid, $variants) {
		$sql = 'DELETE FROM productattributeset WHERE productid = :productid AND idproductattributeset NOT IN(:variants)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$stmt->setINInt('variants', $variants);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ATTRIBUTE_DELETE'), 112, $e->getMessage());
		}
	}

	protected function deleteAttributesProductValueSet ($productid) {
		$sql = 'SELECT idproductattributeset FROM productattributeset WHERE productid = :productid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $productid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('idproductattributeset');
		}
		foreach ($Data as $attributesetid){
			$sql = 'DELETE FROM productattributevalueset WHERE productattributesetid IN (:attributesetid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('attributesetid', $attributesetid);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ATTRIBUTESSET_DELETE'), 112, $e->getMessage());
			}
		}
	}

	public function addNewProduct ($Data) {
		$this->registry->db->setAutoCommit(false);
		try{
			$newProductId = $this->newProduct($Data);
			$this->addProductTranslation($Data, $newProductId);
			$this->addPhotoProduct($Data['photo'], $newProductId);
			$this->addFileProduct($Data['file'], $newProductId);
			if ($Data['category'] > 0){
				$this->addProductToCategoryGroup($Data['category'], $newProductId);
			}
			if (is_array($Data['variants'])){
				$this->addAttributesProducts($Data['variants'], $newProductId);
			}
			if (isset($Data['upsell'])){
				$upsell['products'] = $Data['upsell'];
				App::getModel('upsell')->editRelated($upsell, $newProductId);
			}
			if (isset($Data['similar'])){
				$similar['products'] = $Data['similar'];
				App::getModel('similarproduct')->editRelated($similar, $newProductId);
			}
			if (isset($Data['crosssell'])){
				$crosssell['products'] = $Data['crosssell'];
				App::getModel('crosssell')->editRelated($crosssell, $newProductId);
			}
			$this->updateProductNew($Data, $newProductId);
			$this->updateProductGroupPrices($Data, $newProductId);
			App::getModel('technicaldata')->SaveValuesForProduct($newProductId, $Data['technical_data']);
			
			$this->addProductStaticAttributes($Data, $newProductId);
			
			$event = new sfEvent($this, 'admin.product.add', Array(
				'id' => $newProductId,
				'data' => $Data
			));
			$this->registry->dispatcher->notify($event);
			App::getModel('dataset')->flushCache();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ADD'), 112, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		App::getModel('category')->flushCache();
	}

	public function addProductStaticAttributes ($Data, $id) {
		$deleteAttributes = Array(
			0
		);
		foreach ($Data as $key => $val){
			if (substr($key, 0, 13) == 'static_group_'){
				foreach ($val as $k => $attributeid){
					$deleteAttributes[] = $attributeid;
				}
			}
		}
		
		if (count($deleteAttributes) > 0){
			$sql = 'DELETE FROM productstaticattribute WHERE productid = :productid AND staticattributeid NOT IN (:attributes)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setINInt('attributes', $deleteAttributes);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_ADD'), 112, $e->getMessage());
			}
		}
		
		foreach ($Data as $key => $val){
			if (substr($key, 0, 13) == 'static_group_'){
				$groupid = substr($key, 13);
				foreach ($val as $k => $attributeid){
					if (! in_array($attributeid, $deleteAttributes)){
						$sql = 'INSERT INTO productstaticattribute (productid, staticgroupid, staticattributeid)
							VALUES (:productid, :staticgroupid, :staticattributeid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('productid', $id);
						$stmt->setInt('staticgroupid', $groupid);
						$stmt->setInt('staticattributeid', $attributeid);
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_ADD'), 112, $e->getMessage());
						}
					}
				}
			}
		}
	}

	public function newProduct ($Data) {
		$sql = 'INSERT INTO product SET
					producerid =:producerid,
					stock=:stock, 
					trackstock=:trackstock, 
					shippingcost=:shippingcost, 
					enable=:enable, 
					weight=:weight,
					width=:width,
					height=:height,
					deepth=:deepth,
					unit=:unit,
					vatid=:vatid, 
					ean=:ean, 
					delivelercode=:delivelercode, 
					buyprice=:buyprice,
					sellprice=:sellprice, 
					buycurrencyid = :buycurrencyid,
					sellcurrencyid = :sellcurrencyid,
					addid=:addid,
					technicaldatasetid=:setid,
					promotion = :promotion,
					discountprice = :discountprice,
					promotionstart = :promotionstart,
					promotionend = :promotionend';
		$stmt = $this->registry->db->prepareStatement($sql);
		if ($Data['producerid'] > 0){
			$stmt->setInt('producerid', $Data['producerid']);
		}
		else{
			$stmt->setNull('producerid');
		}
		if (isset($Data['enable']) && $Data['enable'] == 1){
			$stmt->setInt('enable', $Data['enable']);
		}
		else{
			$stmt->setInt('enable', 0);
		}
		$stmt->setInt('stock', $Data['stock']);
		$stmt->setInt('trackstock', $Data['trackstock']);
		$stmt->setFloat('shippingcost', $Data['shippingcost']);
		$stmt->setFloat('weight', $Data['weight']);
		if ($Data['width'] != ''){
			$stmt->setFloat('width', $Data['width']);
		}
		else{
			$stmt->setNull('width');
		}
		if ($Data['height'] != ''){
			$stmt->setFloat('height', $Data['height']);
		}
		else{
			$stmt->setNull('height');
		}
		if ($Data['deepth'] != ''){
			$stmt->setFloat('deepth', $Data['deepth']);
		}
		else{
			$stmt->setNull('deepth');
		}
		if ($Data['unit'] != ''){
			$stmt->setInt('unit', $Data['unit']);
		}
		else{
			$stmt->setInt('unit', 1);
		}
		$stmt->setString('ean', $Data['ean']);
		$stmt->setString('delivelercode', $Data['delivelercode']);
		$stmt->setFloat('buyprice', $Data['buyprice']);
		$stmt->setInt('vatid', $Data['vatid']);
		$stmt->setFloat('sellprice', $Data['sellprice']);
		$stmt->setInt('buycurrencyid', $Data['buycurrencyid']);
		$stmt->setInt('sellcurrencyid', $Data['sellcurrencyid']);
		$stmt->setInt('addid', $this->registry->session->getActiveUserid());
		$stmt->setInt('setid', ($Data['technical_data']['set'] ? $Data['technical_data']['set'] : NULL));
		if (isset($Data['promotion']) && $Data['promotion'] == 1){
			$stmt->setInt('promotion', $Data['promotion']);
			$stmt->setFloat('discountprice', $Data['discountprice']);
			if ($Data['promotionstart'] != ''){
				$stmt->setString('promotionstart', $Data['promotionstart']);
			}
			else{
				$stmt->setNull('promotionstart');
			}
			if ($Data['promotionend'] != ''){
				$stmt->setString('promotionend', $Data['promotionend']);
			}
			else{
				$stmt->setNull('promotionend');
			}
		}
		else{
			$stmt->setInt('promotion', 0);
			$stmt->setFloat('discountprice', 0);
			$stmt->setNull('promotionstart');
			$stmt->setNull('promotionend');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_ADD'), 112, $e->getMessage());
		}
		$id = $stmt->getConnection()->getIdGenerator()->getId();
		
		if ($Data['delivererid'] > 0){
			$sql = 'INSERT INTO productdeliverer (productid, delivererid, addid)
						VALUES (:productid, :delivererid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('delivererid', $Data['delivererid']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_DELIVERER_PRODUCT_ADD'), 11, $e->getMessage());
			}
		}
		
		return $id;
	}

	public function addProductTranslation ($Data, $productid) {
		foreach ($Data['name'] as $key => $val){
			$sql = 'INSERT INTO producttranslation (productid, name, shortdescription,longdescription, description, languageid, seo, keyword_title, keyword, keyword_description)
						VALUES (:productid, :name, :shortdescription,:longdescription, :description, :languageid, :seo, :keyword_title, :keyword, :keyword_description)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $productid);
			$stmt->setString('name', $Data['name'][$key]);
			$stmt->setString('shortdescription', $Data['shortdescription'][$key]);
			$stmt->setString('description', $Data['description'][$key]);
			$stmt->setString('longdescription', $Data['longdescription'][$key]);
			$stmt->setInt('languageid', $key);
			$stmt->setString('seo', App::getModel('seo')->clearSeoUTF($Data['seo'][$key]));
			$stmt->setString('keyword_title', $Data['keywordtitle'][$key]);
			$stmt->setString('keyword', $Data['keyword'][$key]);
			$stmt->setString('keyword_description', $Data['keyworddescription'][$key]);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_TRANSLATION_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addPhotoProduct ($array, $productId) {
		if ($array['unmodified'] == 0 && isset($array['main'])){
			$mainphoto = $array['main'];
			foreach ($array as $key => $photo){
				if (! is_array($photo) && is_int($key)){
					$sql = 'INSERT INTO productphoto (productid, mainphoto, photoid, addid)
								VALUES (:productid, :mainphoto, :photoid,  :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $productId);
					$stmt->setInt('mainphoto', ($photo == $mainphoto) ? 1 : 0);
					$stmt->setInt('photoid', $photo);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_PHOTO_ADD'), 112, $e->getMessage());
					}
				}
			}
		}
		else{
			$photos = $this->productPhotoIds($this->registry->core->getParam());
			foreach ($photos as $key => $val){
				$sql = 'INSERT INTO productphoto (productid, mainphoto, photoid, addid)
							VALUES (:productid, :mainphoto, :photoid,  :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $productId);
				$stmt->setInt('mainphoto', 1);
				$stmt->setInt('photoid', $val);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_PHOTO_ADD'), 112, $e->getMessage());
				}
			}
		}
	}

	public function addFileProduct ($Data, $productId) {
		foreach ($Data as $key => $file){
			if (is_int($key) && $file != 0){
				$sql = 'INSERT INTO productfile (productid, fileid, addid) 
							VALUES (:productid, :fileid, :addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $productId);
				$stmt->setInt('fileid', $file);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_FILE_ADD'), 112, $e->getMessage());
				}
			}
		}
	}

	public function addProductToCategoryGroup ($array, $ProductId) {
		foreach ($array as $value){
			$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
						VALUES (:productid, :categoryid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $ProductId);
			$stmt->setInt('categoryid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function addProductNew ($Data, $productId) {
		if ($Data['newactive'] == 1){
			$sql = 'INSERT INTO productnew (productid, enddate, addid)
						VALUES (:productid, :enddate, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $productId);
			$stmt->setString('enddate', $Data['endnew']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_NEW_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function getProductVariant ($attributes, $contener) {
		foreach ($attributes as $key => $variant){
			$sql = 'INSERT INTO productattributevalueset (attributeproductvalueid, productattributesetid, addid)
						VALUES (:attributeproductvalueid, :productattributesetid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('attributeproductvalueid', $variant);
			$stmt->setInt('productattributesetid', $contener);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_VARIANT_ADD'), 112, $e->getMessage());
			}
		}
	}

	public function getProductVariantDetails ($product) {
		$id = $product['id'];
		if (($product['variant']) > 0){
			$sql = "SELECT 
						PAS.suffixtypeid, 
						PAS.value, 
						PAS.status,
						P.sellprice, 
						V.value as vatvalue, 
						PT.name,
						IF (PAS.`value` > 0,
						    (CASE ST.symbol
								 WHEN '+' THEN
						  			ROUND(P.sellprice + PAS.`value`,2)
								WHEN '-' THEN
									ROUND(P.sellprice - PAS.`value`,2)
						  		WHEN '%' THEN
						        		ROUND(P.sellprice - (P.sellprice*(PAS.`value`/100)), 2)
						     	WHEN '=' THEN
						       		ROUND(PAS.`value`, 2)
						       	ELSE ROUND(P.sellprice, 2)
						    END), 
							ROUND(P.sellprice, 2)) AS variantprice,
							(SELECT (ROUND(variantprice +(variantprice*V.value/100), 2)) ) as variantpricevat
						FROM product P
						LEFT JOIN productattributeset PAS ON PAS.productid = P.idproduct
						LEFT JOIN producttranslation PT ON PT.productid = P.idproduct
						LEFT JOIN vat V ON V.idvat = P.vatid
						LEFT JOIN suffixtype AS ST ON ST.idsuffixtype = PAS.suffixtypeid
						WHERE idproduct=:id and idproductattributeset=:attributes";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$stmt->setInt('attributes', $product['variant']);
			$rs = $stmt->executeQuery();
			$Data = Array();
			if ($rs->first()){
				$Data = Array(
					'id' => $product['id'],
					'quantity' => $product['quantity'],
					'variant' => $product['variant'],
					'name' => $rs->getString('name'),
					'sellprice' => ($rs->getString('variantprice') * $product['quantity']),
					'sellprice_gross' => ($rs->getString('variantpricevat') * $product['quantity'])
				);
			}
			return $Data;
		}
		else{
			$sql = "SELECT PT.name, P.sellprice,
						ROUND(P.sellprice +(P.sellprice * V.value/100), 2)  as variantpricevat
						FROM product P
						LEFT JOIN producttranslation PT ON PT.productid = P.idproduct
						LEFT JOIN vat V ON V.idvat = P.vatid
						WHERE P.idproduct=:id";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$rs = $stmt->executeQuery();
			$Data = Array();
			if ($rs->first()){
				$Data = Array(
					'id' => $product['id'],
					'quantity' => $product['quantity'],
					'variant' => $product['variant'],
					'name' => $rs->getString('name'),
					'sellprice' => ($rs->getString('sellprice') * $product['quantity']),
					'sellprice_gross' => ($rs->getString('variantpricevat') * $product['quantity'])
				);
			}
			return $Data;
		}
	}

	public function loadCategoryChildren ($request) {
		return Array(
			'aoItems' => $this->getCategories($request['parentId'])
		);
	}

	public function getCategories ($parent = 0) {
		$categories = App::getModel('category')->getChildCategories($parent);
		usort($categories, Array(
			$this,
			'sortCategories'
		));
		return $categories;
	}

	protected function sortCategories ($a, $b) {
		return $a['weight'] - $b['weight'];
	}

	public function doAJAXUpdateProduct ($id, $stock, $sellpriceGross, $sellpriceNet, $buypriceGross, $buypriceNet) {
		$product = $this->getProductView($id, false);
		$vatValue = $product['vatvalue'];
		
		$recalculatedNet = $sellpriceGross / (1 + ($vatValue / 100));
		$recalculatedBuyNet = $buypriceGross / (1 + ($vatValue / 100));
		
		$finalSell = ($product['sellprice'] != $sellpriceNet) ? $sellpriceNet : $recalculatedNet;
		$finalBuy = ($product['buyprice'] != $buypriceNet) ? $buypriceNet : $recalculatedBuyNet;
		
		$sql = 'UPDATE product SET 
					stock = :stock,
					sellprice = :price,
					buyprice = :buyprice
				WHERE idproduct = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('stock', $stock);
		$stmt->setFloat('price', $finalSell);
		$stmt->setFloat('buyprice', $finalBuy);
		$stmt->setFloat('vat', $vatValue);
		$stmt->executeUpdate();
		
		$this->updateProductAttributesetPricesAll();
		
		return 1;
	}

	public function doCartesianProduct ($array) {
		$current = array_shift($array);
		if (count($array) > 0){
			$results = array();
			$temp = $this->doCartesianProduct($array);
			foreach ($current as $word){
				foreach ($temp as $value){
					$raw = Array();
					if (is_array($value)){
						$raw[] = $word;
						foreach ($value as $key => $val){
							$raw[] = $val;
						}
						$results[] = $raw;
					}
					else{
						$results[] = array(
							$word,
							$value
						);
					}
				}
			}
			return $results;
		}
		else{
			return $current;
		}
	}

	public function doAJAXCreateCartesianVariants ($request) {
		$sql = 'SELECT
				   	APV.idattributeproductvalue,
					APV.attributeproductid,
					APV.name
				FROM attributeproductvalue APV
				INNER JOIN attributegroup AG ON AG.attributeproductid = APV.attributeproductid
				WHERE AG.attributegroupnameid = :set AND APV.idattributeproductvalue IN(:ids)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('set', $request['setid']);
		$stmt->setINInt('ids', $request['ids']);
		$rs = $stmt->executeQuery();
		$Attributes = Array();
		while ($rs->next()){
			$Attributes[$rs->getInt('attributeproductid')][] = $rs->getInt('idattributeproductvalue');
			$Values[$rs->getInt('idattributeproductvalue')] = Array(
				'sAttributeId' => $rs->getInt('attributeproductid'),
				'sValueName' => $rs->getString('name'),
				'sValueId' => $rs->getInt('idattributeproductvalue')
			);
		}
		if (count($Attributes) > 1){
			$Cartesian = $this->doCartesianProduct($Attributes);
			foreach ($Cartesian as $k => $combination){
				foreach ($combination as $key => $variant){
					$CombinedAttributes[$k][$key] = $Values[$variant];
				}
			}
		}
		else{
			foreach ($Values as $key => $variant){
				$CombinedAttributes[$key][0] = $variant;
			}
		}
		return $CombinedAttributes;
	}

	public function updateProductAttributesetPricesAll () {
		$sql = 'UPDATE productattributeset, product SET 
					productattributeset.attributeprice = 
					CASE
						WHEN (productattributeset.suffixtypeid = 1) THEN ROUND(product.sellprice * (productattributeset.value / 100), 4)
						WHEN (productattributeset.suffixtypeid = 2) THEN ROUND(product.sellprice + productattributeset.value,4)
						WHEN (productattributeset.suffixtypeid = 3) THEN ROUND(product.sellprice - productattributeset.value,4)
						WHEN (productattributeset.suffixtypeid = 4) THEN ROUND(productattributeset.value,4)
					END,
					productattributeset.discountprice = 
					IF(product.promotion = 1, 
					CASE
						WHEN (productattributeset.suffixtypeid = 1) THEN ROUND(product.discountprice * (productattributeset.value / 100), 4)
						WHEN (productattributeset.suffixtypeid = 2) THEN ROUND(product.discountprice + productattributeset.value,4)
						WHEN (productattributeset.suffixtypeid = 3) THEN ROUND(product.discountprice - productattributeset.value,4)
						WHEN (productattributeset.suffixtypeid = 4) THEN ROUND(productattributeset.value,4)
					END, NULL)
				WHERE productattributeset.productid = product.idproduct';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
	}

	public function updateProductGroupPrices ($Data, $id) {
		$clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
		
		$sql = 'DELETE FROM productgroupprice WHERE productid = :productid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('productid', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
		}
		
		$sql = 'INSERT INTO productgroupprice SET
					productid = :productid,
					clientgroupid = :clientgroupid,
					groupprice = :groupprice,
					sellprice = :sellprice,
					promotion = :promotion,
					discountprice = :discountprice,
					promotionstart = :promotionstart,
					promotionend = :promotionend
		';
		foreach ($clientGroups as $group){
			$clientgroupid = $group['id'];
			
			if ((isset($Data['groupid_' . $clientgroupid]) && $Data['groupid_' . $clientgroupid] == 1) || (isset($Data['promotion_' . $clientgroupid]) && $Data['promotion_' . $clientgroupid] == 1)){
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $id);
				$stmt->setInt('clientgroupid', $clientgroupid);
				if (isset($Data['groupid_' . $clientgroupid]) && $Data['groupid_' . $clientgroupid] == 1){
					$stmt->setInt('groupprice', 1);
					$stmt->setFloat('sellprice', $Data['sellprice_' . $clientgroupid]);
				}
				else{
					$stmt->setInt('groupprice', 0);
					$stmt->setFloat('sellprice', 0);
				}
				if (isset($Data['promotion_' . $clientgroupid]) && $Data['promotion_' . $clientgroupid] == 1){
					$stmt->setInt('promotion', 1);
					$stmt->setFloat('discountprice', $Data['discountprice_' . $clientgroupid]);
					if ($Data['promotionstart_' . $clientgroupid] != ''){
						$stmt->setString('promotionstart', $Data['promotionstart_' . $clientgroupid]);
					}
					else{
						$stmt->setNull('promotionstart');
					}
					if ($Data['promotionend_' . $clientgroupid] != ''){
						$stmt->setString('promotionend', $Data['promotionend_' . $clientgroupid]);
					}
					else{
						$stmt->setNull('promotionend');
					}
				}
				else{
					$stmt->setInt('promotion', 0);
					$stmt->setFloat('discountprice', 0);
					$stmt->setNull('promotionstart');
					$stmt->setNull('promotionend');
				}
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
				}
			}
		}
	}

	public function getProductGroupPrice ($id) {
		$sql = 'SELECT
				   *
				FROM productgroupprice
				WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$clientgroupid = $rs->getInt('clientgroupid');
			$Data['groupid_' . $clientgroupid] = $rs->getInt('groupprice');
			$Data['sellprice_' . $clientgroupid] = $rs->getFloat('sellprice');
			$Data['promotion_' . $clientgroupid] = $rs->getInt('promotion');
			$Data['discountprice_' . $clientgroupid] = $rs->getFloat('discountprice');
			$Data['promotionstart_' . $clientgroupid] = $rs->getString('promotionstart');
			$Data['promotionend_' . $clientgroupid] = $rs->getString('promotionend');
		}
		return $Data;
	}
}