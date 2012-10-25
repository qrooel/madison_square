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
 * $Id: exchange.php 309 2011-08-01 19:10:16Z gekosale $ 
 */

class MigrationModel extends Model
{
	
	protected $url;
	protected $key;

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function doLoadQueque ()
	{
		$params = json_decode(base64_decode($this->registry->core->getParam()), true);
		$this->url = $params['apiurl'];
		$this->key = $params['apikey'];
		
		switch ($params['entity']) {
			case 1:
				$iTotal = $this->getProductTotal();
				break;
			case 2:
				$iTotal = $this->getCategoryTotal();
				break;
			case 3:
				$iTotal = $this->getProducerTotal();
				break;
			case 4:
				$iTotal = $this->getPhotosTotal();
				break;
		}
		
		return Array(
			'iTotal' => $iTotal['total'],
			'iCompleted' => 0
		);
	
	}

	public function doProcessQueque ($request)
	{
		$params = json_decode(base64_decode($this->registry->core->getParam()), true);
		$this->url = $params['apiurl'];
		$this->key = $params['apikey'];
		
		$steps = $this->registry->session->getActiveMigrationSteps();
		$startFrom = intval($request['iStartFrom']);
		
		$offset = Array(
			'offset' => $startFrom
		);
		
		switch ($params['entity']) {
			case 1:
				$response = $this->getProduct($offset);
				if (isset($response['product'])){
					$this->addUpdateProduct($response['product']);
				}
				break;
			case 2:
				$response = $this->getCategory($offset);
				if (isset($response['category'])){
					$this->registry->db->setAutoCommit(false);
					$this->addUpdateCategory($response['category']);
					$this->registry->db->commit();
					$this->registry->db->setAutoCommit(true);
				}
				break;
			case 3:
				$response = $this->getProducer($offset);
				if (isset($response['producer'])){
					$this->registry->db->setAutoCommit(false);
					$this->addUpdateProducer($response['producer']);
					$this->registry->db->commit();
					$this->registry->db->setAutoCommit(true);
				}
				break;
			case 4:
				$response = $this->getPhoto($offset);
				if (isset($response['photo'])){
					$sql = 'SELECT idfile FROM file WHERE name = :name';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('name', Core::clearUTF($response['photo']['name']));
					$rs = $stmt->executeQuery();
					if ($rs->first()){
					}
					else{
						App::getModel('gallery')->getRemoteImage($response['photo']['url'], $response['photo']['name']);
					}
				}
				break;
		}
		
		if ($startFrom + 1 <= intval($request['iTotal'])){
			return Array(
				'iStartFrom' => $startFrom + 1
			);
		}
		else{
			return Array(
				'iStartFrom' => $startFrom,
				'bFinished' => true
			);
		}
	
	}

	public function doSuccessQueque ($request)
	{
		$params = json_decode(base64_decode($this->registry->core->getParam()), true);
		if ($params['entity'] == 2){
			$this->updateParentCategories();
			App::getModel('category')->getCategoriesPathById();
		}
		
		if ($request['bFinished']){
			return Array(
				'bCompleted' => true
			);
		}
	}

	public function __call ($method, $params)
	{
		
		if (is_array($params)){
			$params = array_values($params);
		}
		else{
			throw new Exception('Params must be given as array');
		}
		
		$request = array(
			'method' => $method,
			'params' => $params,
			'key' => $this->key
		);
		$request = json_encode($request);
		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json'
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		if (isset($response['error']) && ! is_null($response['error'])){
			return Array();
		}
		return $response['result'];
	
	}

	protected function addUpdateProducer ($Data)
	{
		$viewid = (Helper::getViewId() > 0) ? Helper::getViewId() : $this->registry->loader->getLayerViewId();
		
		$sql = 'SELECT idproducer FROM producer WHERE migrationid = :migrationid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('migrationid', $Data['id']);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$producerid = $rs->getInt('idproducer');
		}
		else{
			$sql = 'SELECT producerid FROM producertranslation WHERE name = :name';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('name', $Data['name']);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$producerid = $rs->getInt('producerid');
			}
			else{
				$sql = 'INSERT INTO producer (addid, photoid, migrationid) VALUES (:addid, :photoid, :migrationid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				$stmt->setNull('photoid');
				$stmt->setInt('migrationid', $Data['id']);
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new Exception(nl2br($e->getMessage()) . '<br />' . "\n");
				}
				
				$producerid = $stmt->getConnection()->getIdGenerator()->getId();
				
				$sql = 'INSERT INTO producertranslation SET
						producerid = :producerid,
						name = :name,
						seo = :seo,
						languageid = :languageid
					';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('producerid', $producerid);
				$stmt->setString('name', $Data['name']);
				$stmt->setString('seo', strtolower(App::getModel('seo')->clearSeoUTF($Data['name'])));
				$stmt->setInt('languageid', Helper::getLanguageId());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception(nl2br($e->getMessage()) . '<br />' . "\n");
				}
				
				$sql = 'INSERT INTO producerview (producerid,viewid,addid)
						VALUES (:producerid, :viewid,:addid)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('producerid', $producerid);
				$stmt->setInt('viewid', $viewid);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeQuery();
				}
				catch (Exception $e){
					throw new Exception(nl2br($e->getMessage()) . '<br />' . "\n");
				}
			}
		}
	
	}

	protected function addUpdateProduct ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		
		$viewid = (Helper::getViewId() > 0) ? Helper::getViewId() : $this->registry->loader->getLayerViewId();
		$vatValues = array_flip(App::getModel('vat')->getVATValuesAll());
		$sql = 'SELECT idproduct FROM product WHERE migrationid = :migrationid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('migrationid', $Data['id']);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$idproduct = $rs->getInt('idproduct');
		}
		else{
			$sql = 'INSERT INTO product SET 
	    				ean				=	:ean,
	    				delivelercode	=	:ean,
	    				barcode			=	:ean,
						buyprice		=	:buyprice,
						sellprice		=	:sellprice, 
						buycurrencyid   =	:buycurrencyid,
						sellcurrencyid  =	:sellcurrencyid,
						weight			=	:weight, 
						vatid			=	:vatid, 
						addid			=	:addid,
						producerid		=	(SELECT idproducer FROM producer WHERE migrationid = :producerid),
						enable			= 	:enable,
						stock			= 	:stock,
						migrationid		=   :migrationid
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('ean', $Data['ean']);
			$stmt->setFloat('buyprice', $Data['buyprice']);
			$stmt->setFloat('sellprice', $Data['sellprice']);
			$stmt->setInt('buycurrencyid', $this->registry->session->getActiveShopCurrencyId());
			$stmt->setInt('sellcurrencyid', $this->registry->session->getActiveShopCurrencyId());
			$stmt->setInt('stock', $Data['stock']);
			$stmt->setFloat('weight', $Data['weight']);
			if (isset($vatValues[number_format($Data['vatvalue'], 2)])){
				$stmt->setInt('vatid', $vatValues[number_format($Data['vatvalue'], 2)]);
			}
			else{
				$stmt->setInt('vatid', 2);
			}
			$stmt->setInt('migrationid', $Data['id']);
			$stmt->setInt('producerid', $Data['producerid']);
			$stmt->setInt('enable', $Data['enable']);
			$stmt->setInt('stock', $Data['stock']);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->executeUpdate();
			
			$idproduct = $stmt->getConnection()->getIdGenerator()->getId();
			
			$sql = 'INSERT INTO producttranslation SET
						productid = :productid,
						name = :name,
						description = :description,
						shortdescription = :shortdescription,
						seo = :seo,
						languageid = :languageid
					';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $idproduct);
			$stmt->setString('name', $Data['name']);
			$stmt->setString('description', $Data['description']);
			$stmt->setString('shortdescription', $Data['shortdescription']);
			$stmt->setString('seo', str_replace('/', '', strtolower(App::getModel('seo')->clearSeoUTF($Data['name']))));
			$stmt->setInt('languageid', Helper::getLanguageId());
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
			
			if ($Data['categoryid'] != NULL && $Data['categoryid'] > 0){
				$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
						SELECT :productid, idcategory, :addid FROM category WHERE migrationid = :categoryid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('productid', $idproduct);
				$stmt->setInt('categoryid', $Data['categoryid']);
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_UPDATE'), 112, $e->getMessage());
				}
			}
			
			$this->registry->db->commit();
			$this->registry->db->setAutoCommit(true);
			
			if ($Data['photo'] != ''){
				$sql = 'SELECT idfile FROM file WHERE name = :name';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', Core::clearUTF($Data['photo']));
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = 'INSERT INTO productphoto (productid, mainphoto, photoid, addid)
							VALUES (:productid, :mainphoto, :photoid,  :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $idproduct);
					$stmt->setInt('mainphoto', 1);
					$stmt->setInt('photoid', $rs->getInt('idfile'));
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_PHOTO_UPDATE'), 112, $e->getMessage());
					}
				}
			
			}
			//			
		//			$sql = 'INSERT INTO productsearch SET
		//							productid = :productid, 
		//							languageid = :languageid, 
		//							name = :name, 
		//							shortdescription = :shortdescription, 
		//							description = :description, 
		//							producername = :producername, 
		//							attributes = :attributes
		//				';
		//			$stmt = $this->registry->db->prepareStatement($sql);
		//			$stmt->setInt('productid', $idproduct);
		//			$stmt->setInt('languageid', Helper::getLanguageId());
		//			$stmt->setString('name', $Data['name']);
		//			$stmt->setString('shortdescription', '');
		//			$stmt->setString('description', '');
		//			$stmt->setString('producername', $product[12]);
		//			$stmt->setNull('attributes');
		//			try{
		//				$stmt->executeQuery();
		//			}
		//			catch (Exception $e){
		//				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_SEARCH_INSERT'), 112, $e->getMessage());
		//			}
		}
	}

	protected function addUpdateCategory ($Data)
	{
		$viewid = (Helper::getViewId() > 0) ? Helper::getViewId() : $this->registry->loader->getLayerViewId();
		
		$sql = 'SELECT idcategory FROM category WHERE migrationid = :migrationid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('migrationid', $Data['id']);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$idcategory = $rs->getInt('idcategory');
		}
		else{
			$sql = 'INSERT INTO category SET
						photoid = :photoid, 
						distinction = :distinction,
						addid = :addid,
						migrationid = :migrationid,
						migrationparentid = :migrationparentid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setNull('categoryid');
			$stmt->setNull('photoid');
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('migrationid', $Data['id']);
			if ($Data['categoryid'] == 0){
				$stmt->setNull('migrationparentid');
			}
			else{
				$stmt->setInt('migrationparentid', $Data['categoryid']);
			}
			$stmt->setInt('distinction', $Data['distinction']);
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_ADD'), 3003, $e->getMessage());
			}
			
			$idcategory = $stmt->getConnection()->getIdGenerator()->getId();
			
			$sql = 'INSERT INTO categorytranslation (
						categoryid,
						name,
						shortdescription,
						description,
						keyword_title,
						keyword,
						keyword_description,
						seo, 
						languageid
					)VALUES(
						:categoryid,
						:name,
						:shortdescription,
						:description,
						:keyword_title,
						:keyword,
						:keyword_description,
						:seo, 
						:languageid
				)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('categoryid', $idcategory);
			$stmt->setString('name', $Data['name']);
			$stmt->setString('shortdescription', $Data['shortdescription']);
			$stmt->setString('description', $Data['description']);
			$stmt->setString('keyword_title', $Data['keyword_title']);
			$stmt->setString('keyword', $Data['keyword']);
			$stmt->setString('keyword_description', $Data['keyword_description']);
			$stmt->setString('seo', strtolower(App::getModel('seo')->clearSeoUTF($Data['name'])));
			$stmt->setInt('languageid', Helper::getLanguageId());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_TRANSLATION_ADD'), 4, $e->getMessage());
			}
			$sql = 'INSERT INTO viewcategory (categoryid,viewid, addid) VALUES (:categoryid, :viewid, :addid)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			$stmt->setInt('categoryid', $idcategory);
			$stmt->setInt('viewid', $viewid);
			try{
				$stmt->executeQuery();
			
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
			}
		}
	}

	public function updateParentCategories ()
	{
		$this->registry->db->setAutoCommit(false);
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		
		$sql = 'SELECT idcategory, migrationid FROM category WHERE migrationid IN (SELECT DISTINCT migrationparentid FROM category)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$sql2 = 'UPDATE category SET categoryid = :idcategory WHERE migrationparentid = :migrationid';
			$stmt2 = $this->registry->db->prepareStatement($sql2);
			$stmt2->setInt('idcategory', $rs->getInt('idcategory'));
			$stmt2->setInt('migrationid', $rs->getInt('migrationid'));
			$stmt2->executeQuery();
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}

}