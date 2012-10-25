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
 * $Revision: 263 $
 * $Author: gekosale $
 * $Date: 2011-07-24 16:23:40 +0200 (N, 24 lip 2011) $
 * $Id: productnews.php 263 2011-07-24 14:23:40Z gekosale $ 
 */

class productpromotionModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('productnew', Array(
			'idproduct' => Array(
				'source' => 'P.idproduct'
			),
			'name' => Array(
				'source' => 'PT.name'
			),
			'enddate' => Array(
				'source' => 'IF(PGP.promotionend IS NOT NULL, PGP.promotionend, IF(P.promotionend IS NOT NULL, P.promotionend, NULL))'
			),
			'categoryname' => Array(
				'source' => 'CT.name'
			),
			'categoryid' => Array(
				'source' => 'PC.categoryid',
				'prepareForTree' => true,
				'first_level' => App::getModel('product')->getCategories()
			),
			'ancestorcategoryid' => Array(
				'source' => 'CP.ancestorcategoryid'
			),
			'categoriesname' => Array(
				'source' => 'GROUP_CONCAT(DISTINCT SUBSTRING(CONCAT(\' \', CT.name), 1))',
				'filter' => 'having'
			)
		));
		$datagrid->setFrom('
			product P
			LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
			LEFT JOIN productcategory PC ON PC.productid = P.idproduct
			LEFT JOIN productgroupprice PGP ON PGP.productid = P.idproduct AND PGP.clientgroupid
			LEFT JOIN category C ON C.idcategory = PC.categoryid
			LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
			LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
		');
		
		$datagrid->setAdditionalWhere('
			PGP.promotion = 1 OR P.promotion = 1
		');
		
		$datagrid->setGroupBy('
			P.idproduct
		');
	
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getProductPromotionForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteProductPromotion ($datagrid, $id)
	{
		if (is_array($id)){
			$sql = 'UPDATE product SET 
					promotion = :promotion,
					discountprice = IF(:discount > 0, sellprice - (sellprice * :discount), 0),
					promotionstart = :promotionstart,
					promotionend = :promotionend
				WHERE idproduct IN (:ids)';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setINInt('ids', $id);
			$stmt->setInt('promotion', 0);
			$stmt->setNull('discount', 0);
			$stmt->setNull('promotionstart');
			$stmt->setNull('promotionend');
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
			foreach ($id as $product){
				$this->deleteProductPromotion($product);
			}
		}
		else{
			$sql = 'UPDATE product SET 
					promotion = :promotion,
					discountprice = IF(:discount > 0, sellprice - (sellprice * :discount), 0),
					promotionstart = :promotionstart,
					promotionend = :promotionend
				WHERE idproduct = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $id);
			$stmt->setInt('promotion', 0);
			$stmt->setNull('discount', 0);
			$stmt->setNull('promotionstart');
			$stmt->setNull('promotionend');
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
			
			$this->deleteProductPromotion($id);
		}
		
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteProductPromotion ($id)
	{
		
		$productGroupPrice = App::getModel('product')->getProductGroupPrice($id);
		if (! empty($productGroupPrice)){
			try{
				$dbtracker = new DBTracker($this->registry);
				$dbtracker->load($this->getDirPath());
				return $dbtracker->run(Array(
					'productid' => $id
				), $this->getName(), 'deleteProductPromotion');
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}

	public function addPromotion ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		
		$sql = 'DELETE FROM productgroupprice 
				WHERE productid IN(:productid) AND
				groupprice = 0 AND promotion = 0';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setINInt('productid', $Data['productid']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
		}
		
		$sql = 'UPDATE productgroupprice SET 
					promotion = :promotion,
					discountprice = :discountprice,
					promotionstart = :promotionstart,
					promotionend = :promotionend
				WHERE productid IN(:productid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('promotion', 0);
		$stmt->setFloat('discountprice', 0);
		$stmt->setNull('promotionstart');
		$stmt->setNull('promotionend');
		$stmt->setINInt('productid', $Data['productid']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
		}
		
		foreach ($Data['productid'] as $key => $idproduct){
			
			$sql = 'UPDATE product SET 
					promotion = :promotion,
					discountprice = IF(:discount > 0, sellprice - (sellprice * :discount), 0),
					promotionstart = :promotionstart,
					promotionend = :promotionend
				WHERE idproduct = :id';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', $idproduct);
			if (isset($Data['promotion']) && $Data['promotion'] == 1){
				$stmt->setInt('promotion', $Data['promotion']);
				$stmt->setFloat('discount', $Data['discount'] / 100);
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
				$stmt->setNull('discount', 0);
				$stmt->setNull('promotionstart');
				$stmt->setNull('promotionend');
			}
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_UPDATE'), 112, $e->getMessage());
			}
			
			$productGroupPrice = App::getModel('product')->getProductGroupPrice($idproduct);
			$clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
			foreach ($clientGroups as $key => $group){
				$clientgroupid = $group['id'];
				if (isset($Data['promotion_' . $clientgroupid]) && $Data['promotion_' . $clientgroupid] == 1){
					if (isset($productGroupPrice['groupid_' . $clientgroupid])){
						$sellprice = $productGroupPrice['sellprice_' . $clientgroupid];
					}
					else{
						$product = App::getModel('product')->getProductView($idproduct);
						$sellprice = $product['sellprice'];
					}
					$sql = 'INSERT INTO productgroupprice SET
								productid = :productid,
								clientgroupid = :clientgroupid,
								promotion = :promotion,
								discountprice = :discountprice,
								promotionstart = :promotionstart,
								promotionend = :promotionend
							ON DUPLICATE KEY UPDATE
								promotion = :promotion,
								discountprice = :discountprice,
								promotionstart = :promotionstart,
								promotionend = :promotionend
					';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('productid', $idproduct);
					$stmt->setInt('clientgroupid', $clientgroupid);
					$stmt->setInt('promotion', 1);
					$stmt->setFloat('discountprice', $sellprice * (1 - ($Data['discount_' . $clientgroupid] / 100)));
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
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new CoreException($this->registry->core->getMessage('ERR_PRODUCT_GROUP_PRICE_ADD'), 112, $e->getMessage());
					}
				}
			}
		
		}
		
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
	}
}