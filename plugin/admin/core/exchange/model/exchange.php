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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: exchange.php 687 2012-09-01 12:02:47Z gekosale $
 */
class exchangeModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function importFromFile ($file, $entity)
	{
		switch ($entity) {
			case 1:
				$this->importProducts($file);
				break;
			case 2:
				$this->importCategories($file);
				break;
		}
	}

	public function getCategoryViewsByNames ($views)
	{
		$sql = 'SELECT idview FROM view
				WHERE name IN (:views) GROUP BY idview';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINString('views', explode(';', $views));
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = $rs->getInt('idview');
		}
		return $Data;
	}

	public function getProductProducerByName ($producer)
	{
		$sql = 'SELECT producerid FROM producertranslation 
				WHERE name = :producer GROUP BY producerid';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('producer', $producer);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			return $rs->getInt('producerid');
		}
		else{
			return null;
		}
	}

	public function updateParentCategories ($ParentCategories)
	{
		foreach ($ParentCategories as $key => $val){
			$sql = 'UPDATE category SET categoryid = :categoryid WHERE idcategory = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $val['idcategory']);
			if (! is_null($val['categoryid'])){
				$stmt->setInt('categoryid', $val['categoryid']);
			}
			else{
				$stmt->setNull('categoryid');
			}
			$rs = $stmt->executeQuery();
		}
	}

	public function importProducts ($file)
	{
		if (($handle = fopen(ROOTPATH . 'upload' . DS . $file, "r")) === FALSE)
			return;
		while (($cols = fgetcsv($handle, 1000, ";")) !== FALSE){
			if ($cols[0] != 'name'){
				$Data[] = $cols;
			}
		}
		$categories = array_flip($this->getCategoryPath());
		$vatValues = array_flip(App::getModel('vat')->getVATValuesAll());
		$currencies = App::getModel('currencieslist')->getCurrencyIds();
		
		foreach ($Data as $key => $product){
			if (count($product) == 21){
				$name = $product[0];
				$ean = $product[1];
				$delivelercode = $product[2];
				$barcode = $product[3];
				$buyprice = $product[4];
				$buycurrency = (isset($currencies[$product[5]])) ? $currencies[$product[5]] : NULL;
				$sellprice = $product[6];
				$sellcurrency = (isset($currencies[$product[7]])) ? $currencies[$product[7]] : NULL;
				$stock = $product[8];
				$weight = $product[9];
				$vat = (isset($vatValues[$product[10]])) ? $vatValues[$product[10]] : 2;
				$photo = $this->getPhotoByName($product[11]);
				$producer = $this->getProductProducerByName($product[12]);
				$category = (isset($categories[$product[13]])) ? $categories[$product[13]] : NULL;
				$shortdescription = $product[14];
				$description = $product[15];
				$seo = ($product[16] != '') ? $product[16] : strtolower(App::getModel('seo')->clearSeoUTF($name));
				$keyword_title = $product[17];
				$keyword = $product[18];
				$keyword_description = $product[19];
				$trackstock = $product[20];
				
				$sql = 'SELECT 
							productid 
						FROM producttranslation	 
						WHERE name = :name AND languageid = :languageid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', $name);
				$stmt->setInt('languageid', Helper::getLanguageId());
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = 'UPDATE product SET 
		    					ean				=	:ean,
		    					delivelercode	=	:delivelercode,
		    					barcode			=	:barcode,
								buyprice		=	:buyprice,
								buycurrencyid	=	:buycurrencyid,
								sellcurrencyid  =	:sellcurrencyid,
								sellprice		=	:sellprice, 
								stock			=	:stock, 
								weight			=	:weight, 
								vatid			=	:vatid, 
								editid			=	:editid,
								producerid		=	:producerid,
								trackstock		=	:trackstock
							WHERE idproduct = :id';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('id', $rs->getInt('productid'));
					$stmt->setString('ean', $ean);
					$stmt->setString('delivelercode', $delivelercode);
					$stmt->setString('barcode', $barcode);
					$stmt->setFloat('buyprice', $buyprice);
					$stmt->setFloat('sellprice', $sellprice);
					$stmt->setInt('buycurrencyid', $buycurrency);
					$stmt->setInt('sellcurrencyid', $sellcurrency);
					$stmt->setInt('stock', $stock);
					$stmt->setFloat('weight', $weight);
					$stmt->setInt('vatid', $vat);
					$stmt->setInt('trackstock', $trackstock);
					if (! is_null($producer)){
						$stmt->setInt('producerid', $producer);
					}
					else{
						$stmt->setInt('producerid', NULL);
					}
					$stmt->setInt('editid', $this->registry->session->getActiveUserid());
					$stmt->executeUpdate();
					
					$sql = 'UPDATE producttranslation SET
								name = :name,
								seo = :seo,
								shortdescription = :shortdescription,
								description = :description,
								keyword_title = :keyword_title,
								keyword = :keyword,
								keyword_description = :keyword_description
							WHERE productid = :productid AND languageid = :languageid
					';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $rs->getInt('productid'));
					$stmt->setString('name', $name);
					$stmt->setString('seo', $seo);
					$stmt->setString('shortdescription', $shortdescription);
					$stmt->setString('description', $description);
					$stmt->setString('keyword_title', $keyword_title);
					$stmt->setString('keyword', $keyword);
					$stmt->setString('keyword_description', $keyword_description);
					$stmt->setInt('languageid', Helper::getLanguageId());
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
					}
					
					$sql = 'DELETE FROM productcategory WHERE productid = :id';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('id', $rs->getInt('productid'));
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
					
					if ($category != NULL){
						$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
								VALUES (:productid, :categoryid, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('productid', $rs->getInt('productid'));
						$stmt->setInt('categoryid', $category);
						$stmt->setInt('addid', $this->registry->session->getActiveUserid());
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_UPDATE'), 112, $e->getMessage());
						}
					}
					
					$sql = 'DELETE FROM productsearch WHERE productid =:id';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('id', $rs->getInt('productid'));
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($e->getMessage());
					}
				}
				else{
					
					$sql = 'INSERT INTO product SET 
		    					ean				=	:ean,
		    					delivelercode	=	:delivelercode,
		    					barcode			=	:barcode,
								buyprice		=	:buyprice,
								sellprice		=	:sellprice, 
								buycurrencyid   =	:buycurrencyid,
								sellcurrencyid  =	:sellcurrencyid,
								stock			=	:stock, 
								weight			=	:weight, 
								vatid			=	:vatid, 
								addid			=	:addid,
								producerid		=	:producerid,
								trackstock		=	:trackstock
						';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('ean', $ean);
					$stmt->setString('delivelercode', $delivelercode);
					$stmt->setString('barcode', $barcode);
					$stmt->setFloat('buyprice', $buyprice);
					$stmt->setFloat('sellprice', $sellprice);
					$stmt->setInt('buycurrencyid', $buycurrency);
					$stmt->setInt('sellcurrencyid', $sellcurrency);
					$stmt->setInt('stock', $stock);
					$stmt->setFloat('weight', $weight);
					$stmt->setInt('vatid', $vat);
					$stmt->setInt('trackstock', $trackstock);
					if (! is_null($producer)){
						$stmt->setInt('producerid', $producer);
					}
					else{
						$stmt->setInt('producerid', NULL);
					}
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					$stmt->executeUpdate();
					
					$idproduct = $stmt->getConnection()->getIdGenerator()->getId();
					
					$sql = 'INSERT INTO producttranslation SET
								productid = :productid,
								name = :name,
								seo = :seo,
								shortdescription = :shortdescription,
								description = :description,
								keyword_title = :keyword_title,
								keyword = :keyword,
								keyword_description = :keyword_description,
								languageid = :languageid
					';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $idproduct);
					$stmt->setString('name', $name);
					$stmt->setString('seo', $seo);
					$stmt->setString('shortdescription', $shortdescription);
					$stmt->setString('description', $description);
					$stmt->setString('keyword_title', $keyword_title);
					$stmt->setString('keyword', $keyword);
					$stmt->setString('keyword_description', $keyword_description);
					$stmt->setInt('languageid', Helper::getLanguageId());
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
					}
					
					if ($category != NULL){
						$sql = 'INSERT INTO productcategory (productid, categoryid, addid)
								VALUES (:productid, :categoryid, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setInt('productid', $idproduct);
						$stmt->setInt('categoryid', $category);
						$stmt->setInt('addid', $this->registry->session->getActiveUserid());
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_CATEGORY_UPDATE'), 112, $e->getMessage());
						}
					}
				}
			}
		}
	}

	public function importCategories ($file)
	{
		$categories = array_flip($this->getCategoryPath());
		
		if (($handle = fopen(ROOTPATH . 'upload' . DS . $file, "r")) === FALSE)
			return;
		while (($cols = fgetcsv($handle, 1000, ";")) !== FALSE){
			if ($cols[0] != 'name'){
				$Data[] = $cols;
			}
		}
		$this->registry->db->setAutoCommit(false);
		
		foreach ($Data as $key => $category){
			
			$name = $category[0];
			$photo = $category[1];
			$parent = $category[2];
			$views = $this->getCategoryViewsByNames($category[3]);
			
			if ($parent != ''){
				$fullPath = implode('/', array_merge(explode('/', $parent), Array(
					$name
				)));
			}
			else{
				$fullPath = $name;
			}
			
			$categoryid = (isset($categories[$parent])) ? $categories[$parent] : NULL;
			
			if (isset($categories[$fullPath]) && $idcategory = $categories[$fullPath]){
				
				$sql = 'UPDATE categorytranslation SET 
	    					seo	 		= :seo
	    				WHERE 
	    					categoryid = :categoryid 
	    				AND 
	    					languageid = :languageid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('name', $name);
				$stmt->setString('seo', strtolower(App::getModel('seo')->clearSeoUTF($fullPath)));
				$stmt->setInt('categoryid', $idcategory);
				$stmt->setInt('languageid', Helper::getLanguageId());
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				}
				
				$sql = 'UPDATE category SET 
			    			photoid 	= :photoid,
			    			categoryid  = :categoryid
			    		WHERE 
			    			idcategory = :idcategory';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('idcategory', $idcategory);
				if ($categoryid == NULL){
					$stmt->setNull('categoryid');
				}
				else{
					$stmt->setInt('categoryid', $categoryid);
				}
				$stmt->setInt('photoid', $this->getPhotoByName($photo));
				try{
					$stmt->executeUpdate();
				}
				catch (Exception $e){
					throw new CoreException($this->registry->core->getMessage('ERR_CATEGORY_UPDATE'), 1, $e->getMessage());
				}
				
				$sql = 'DELETE FROM viewcategory WHERE categoryid =:id';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('id', $idcategory);
				$stmt->executeUpdate();
				
				foreach ($views as $key => $val){
					$sql = 'INSERT INTO viewcategory (categoryid,viewid, addid)
										VALUES (:categoryid, :viewid, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					$stmt->setInt('categoryid', $idcategory);
					$stmt->setInt('viewid', $val);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
					}
				}
			}
			else{
				$sql = 'INSERT INTO category SET
							photoid = :photoid, 
							categoryid  = :categoryid,
							addid = :addid';
				$stmt = $this->registry->db->prepareStatement($sql);
				if ($categoryid == NULL){
					$stmt->setNull('categoryid');
				}
				else{
					$stmt->setInt('categoryid', $categoryid);
				}
				$stmt->setInt('photoid', $this->getPhotoByName($photo));
				$stmt->setInt('addid', $this->registry->session->getActiveUserid());
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
							seo, 
							languageid
						)
						VALUES 
						(
							:categoryid,
							:name,
							:seo, 
							:languageid
						)';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setInt('categoryid', $idcategory);
				$stmt->setString('name', $name);
				$stmt->setString('seo', strtolower(App::getModel('seo')->clearSeoUTF($fullPath)));
				$stmt->setInt('languageid', Helper::getLanguageId());
				$stmt->executeUpdate();
				
				foreach ($views as $key => $val){
					$sql = 'INSERT INTO viewcategory (categoryid,viewid, addid)
							VALUES (:categoryid, :viewid, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('addid', $this->registry->session->getActiveUserid());
					$stmt->setInt('categoryid', $idcategory);
					$stmt->setInt('viewid', $val);
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_NEWS_ADD'), 4, $e->getMessage());
					}
				}
			}
		}
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		$this->updateParentCategories($ParentCategories);
		App::getModel('category')->getCategoriesPathById();
		App::getModel('seo')->doRefreshSeoCategory();
	}

	public function exportFile ($entity)
	{
		switch ($entity) {
			case 1:
				$this->exportProducts();
				break;
			case 2:
				$this->exportCategories();
				break;
			case 3:
				$this->exportClients();
				break;
			case 4:
				$this->exportOrders();
				break;
		}
	}

	public function getPhotoByName ($name)
	{
		$sql = 'SELECT idfile FROM file WHERE name = :name';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('name', $name);
		$rs = $stmt->executeQuery();
		$Data = 1;
		if ($rs->first()){
			$Data = $rs->getInt('idfile');
		}
		return $Data;
	}

	public function exportCategories ()
	{
		$categories = $this->getCategoryPath();
		$columns = Array();
		
		$sql = "SELECT
    			CT.name,
    			C.categoryid as parent,
    			F.name AS photo,
    			GROUP_CONCAT(DISTINCT V.name ORDER BY V.name ASC SEPARATOR ';') as view
				FROM categorytranslation CT
				LEFT JOIN category C ON C.idcategory = CT.categoryid
				LEFT JOIN viewcategory VC ON VC.categoryid = C.idcategory
				LEFT JOIN view V ON VC.viewid = V.idview
				LEFT JOIN file F ON F.idfile = C.photoid
				WHERE CT.languageid = :languageid
				GROUP BY
				CT.categoryid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'photo' => $rs->getString('photo'),
				'parent' => (isset($categories[$rs->getInt('parent')])) ? $categories[$rs->getInt('parent')] : '',
				'view' => $rs->getString('view')
			);
		}
		$filename = 'categories_' . date('Y_m_d_H_i_s') . '.csv';
		if (isset($Data[0])){
			$header = array_keys($Data[0]);
		}
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$fp = fopen("php://output", 'w');
		fputcsv($fp, $header, ";");
		foreach ($Data as $key => $values){
			fputcsv($fp, $values, ";");
		}
		fclose($fp);
		exit();
	}

	public function exportClients ()
	{
		$sql = "SELECT 
					AES_DECRYPT(CD.firstname, :encryptionkey) AS firstname, 
					AES_DECRYPT(CD.surname, :encryptionkey) AS surname, 
					AES_DECRYPT(CD.email, :encryptionkey) AS email, 
					CGT.name AS groupname, 
					AES_DECRYPT(CD.phone, :encryptionkey) AS phone, 
					CD.adddate AS adddate, 
					SUM(O.globalprice) AS ordertotal, 
					V.name AS shop  
				FROM 
				client C
				LEFT JOIN clientdata CD ON CD.clientid = C.idclient
				LEFT JOIN clientgrouptranslation CGT ON CGT.clientgroupid = CD.clientgroupid AND CGT.languageid=1
				LEFT JOIN orderclientdata OCD ON OCD.clientid = CD.clientid
				LEFT JOIN `order` O ON O.idorder = OCD.orderid
				LEFT JOIN view V ON C.viewid = V.idview
				WHERE C.viewid IN (:viewids)
				GROUP BY C.idclient ORDER BY idclient ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'email' => $rs->getString('email'),
				'phone' => $rs->getString('phone'),
				'groupname' => $rs->getString('groupname'),
				'adddate' => date('Y-m-d', strtotime($rs->getString('adddate'))),
				'ordertotal' => $rs->getString('ordertotal'),
				'shop' => $rs->getString('shop')
			);
		}
		$filename = 'clients_' . date('Y_m_d_H_i_s') . '.csv';
		if (isset($Data[0])){
			$header = Array(
				$this->registry->core->getMessage('TXT_FIRSTNAME'),
				$this->registry->core->getMessage('TXT_SURNAME'),
				$this->registry->core->getMessage('TXT_EMAIL'),
				$this->registry->core->getMessage('TXT_PHONE'),
				$this->registry->core->getMessage('TXT_VIEW_ORDER_CLIENT_GROUP'),
				$this->registry->core->getMessage('TXT_REGISTRATION'),
				$this->registry->core->getMessage('TXT_SUM_ALL_ORDER'),
				$this->registry->core->getMessage('TXT_SHOP')
			);
		}
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$fp = fopen("php://output", 'w');
		fputcsv($fp, $header, ";");
		foreach ($Data as $key => $values){
			fputcsv($fp, $values, ";");
		}
		fclose($fp);
		exit();
	}

	public function exportOrders ()
	{
		$sql = "SELECT 
					O.idorder AS idorder, 
					CONCAT(AES_DECRYPT(OC.surname,:encryptionkey),' ',AES_DECRYPT(OC.firstname,:encryptionkey)) AS client, 
					CONCAT(O.globalprice,' ',O.currencysymbol) AS globalprice, 
					O.dispatchmethodprice AS dispatchmethodprice, 
					OST.name AS orderstatusname, 
					O.dispatchmethodname AS dispatchmethodname, 
					O.paymentmethodname AS paymentmethodname, 
					O.adddate AS adddate,
					V.name AS shop 
				FROM `order` O
				LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
				LEFT JOIN orderstatustranslation OST ON OS.idorderstatus = OST.orderstatusid AND OST.languageid = 1
				LEFT JOIN orderclientdata OC ON OC.orderid=O.idorder
				LEFT JOIN view V ON O.viewid = V.idview
				WHERE O.viewid IN (:viewids)
				ORDER BY idorder DESC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('viewids', Helper::getViewIds());
		$stmt->setString('encryptionkey', $this->registry->session->getActiveEncryptionKeyValue());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idorder' => $rs->getInt('idorder'),
				'client' => $rs->getString('client'),
				'globalprice' => $rs->getFloat('globalprice'),
				'dispatchmethodprice' => $rs->getFloat('dispatchmethodprice'),
				'orderstatusname' => $rs->getString('orderstatusname'),
				'dispatchmethodname' => $rs->getString('dispatchmethodname'),
				'paymentmethodname' => $rs->getString('paymentmethodname'),
				'adddate' => date('Y-m-d', strtotime($rs->getString('adddate'))),
				'shop' => $rs->getString('shop')
			);
		}
		$filename = 'orders_' . date('Y_m_d_H_i_s') . '.csv';
		if (isset($Data[0])){
			$header = Array(
				$this->registry->core->getMessage('TXT_ORDER_NUMER'),
				$this->registry->core->getMessage('TXT_CLIENT'),
				$this->registry->core->getMessage('TXT_VIEW_ORDER_TOTAL'),
				$this->registry->core->getMessage('TXT_DELIVERERPRICE'),
				$this->registry->core->getMessage('TXT_ORDER_STATUS'),
				$this->registry->core->getMessage('TXT_VIEW_ORDER_DELIVERY_METHOD'),
				$this->registry->core->getMessage('TXT_VIEW_ORDER_PAYMENT_METHOD'),
				$this->registry->core->getMessage('TXT_VIEW_ORDER_ORDER_DATE'),
				$this->registry->core->getMessage('TXT_SHOP')
			);
		}
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$fp = fopen("php://output", 'w');
		fputcsv($fp, $header, ";");
		foreach ($Data as $key => $values){
			fputcsv($fp, $values, ";");
		}
		fclose($fp);
		exit();
	}

	public function getCategoryPath ()
	{
		$sql = 'SELECT
					C.categoryid,
					GROUP_CONCAT(SUBSTRING(CT.name, 1) ORDER BY C.order DESC SEPARATOR \'/\') AS path
				FROM categorytranslation CT
				LEFT JOIN categorypath C ON C.ancestorcategoryid = CT.categoryid
				WHERE CT.languageid = :languageid
				GROUP BY C.categoryid
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getInt('categoryid')] = $rs->getString('path');
		}
		return $Data;
	}

	public function exportProducts ()
	{
		$categories = $this->getCategoryPath();
		
		$sql = "SELECT
	    			PT.name AS name,
	    			P.ean as ean,
	    			P.delivelercode as delivelercode,
	    			P.barcode as barcode,
	    			ROUND(P.buyprice,2) as buyprice,
	    			BUYCUR.currencysymbol AS buycurrency,
	    			ROUND(P.sellprice,2) as sellprice,
	    			SELLCUR.currencysymbol AS sellcurrency,
	    			P.stock as stock,
	    			ROUND(P.weight,2) as weight,
	    			F.name as photo,
	    			ROUND(V.value,2) as vat,
	    			PRT.name as producer,
	    			PC.categoryid,
					PT.shortdescription,
					PT.description,
					PT.seo,
					PT.keyword_title,
					PT.keyword,
					PT.keyword_description,
					P.trackstock AS trackstock
				FROM producttranslation PT
				LEFT JOIN product P ON P.idproduct = PT.productid
				LEFT JOIN productcategory PC ON PC.productid = P.idproduct
				LEFT JOIN categorytranslation CT ON PC.categoryid = CT.categoryid AND CT.languageid = :languageid
				LEFT JOIN categorypath C ON C.ancestorcategoryid = CT.categoryid
				LEFT JOIN producertranslation PRT ON P.producerid = PRT.producerid AND PRT.languageid = :languageid
				LEFT JOIN productphoto PP ON PP.productid = P.idproduct AND PP.mainphoto = 1
				LEFT JOIN file F ON F.idfile = PP.photoid
				LEFT JOIN vat V ON P.vatid = V.idvat
				LEFT JOIN currency BUYCUR ON P.buycurrencyid = BUYCUR.idcurrency
				LEFT JOIN currency SELLCUR ON P.sellcurrencyid = SELLCUR.idcurrency
				WHERE PT.languageid = :languageid AND C.categoryid = PC.categoryid
				GROUP BY P.idproduct
				";
		
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'name' => $rs->getString('name'),
				'ean' => $rs->getString('ean'),
				'delivelercode' => $rs->getString('delivelercode'),
				'barcode' => $rs->getString('barcode'),
				'buyprice' => $rs->getString('buyprice'),
				'buycurrency' => $rs->getString('buycurrency'),
				'sellprice' => $rs->getString('sellprice'),
				'sellcurrency' => $rs->getString('sellcurrency'),
				'stock' => $rs->getString('stock'),
				'weight' => $rs->getString('weight'),
				'vat' => $rs->getString('vat'),
				'photo' => $rs->getString('photo'),
				'producer' => $rs->getString('producer'),
				'category' => (isset($categories[$rs->getInt('categoryid')])) ? $categories[$rs->getInt('categoryid')] : '',
				'shortdescription' => $rs->getString('shortdescription'),
				'description' => $rs->getString('description'),
				'seo' => $rs->getString('seo'),
				'keyword_title' => $rs->getString('keyword_title'),
				'keyword' => $rs->getString('keyword'),
				'keyword_description' => $rs->getString('keyword_description'),
				'trackstock' => (int) $rs->getInt('trackstock')
			);
		}
		
		$filename = 'products_' . date('Y_m_d_H_i_s') . '.csv';
		if (isset($Data[0])){
			$header = array_keys($Data[0]);
		}
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$fp = fopen("php://output", 'w');
		fputcsv($fp, $header, ";");
		foreach ($Data as $key => $values){
			fputcsv($fp, $values, ";");
		}
		fclose($fp);
		exit();
	}
}