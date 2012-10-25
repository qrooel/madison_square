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
 * $Id: similarproduct.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class similarproductModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('similarproduct', Array(
			'idsimilarproduct' => Array(
				'source' => 'SP.productid'
			),
			'name' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'productcount' => Array(
				'source' => 'count(distinct SP.relatedproductid)'
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
			similarproduct SP
			LEFT JOIN producttranslation PT ON SP.productid = PT.productid AND PT.languageid = :languageid
			LEFT JOIN productcategory PC ON PC.productid = PT.productid
			LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
			LEFT JOIN category C ON C.idcategory = PC.categoryid
			LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
			LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
		');
		
		$datagrid->setGroupBy('
				SP.productid
		');
		
		if(Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
				VC.viewid = :viewid
			');
		}
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSimilarProductForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteSimilarProduct ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteSimilarProduct'
		), $this->getName());
	}

	public function deleteSimilarProduct ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'productid' => $id
			), $this->getName(), 'deleteSimilarProduct');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getProductsDataGrid ($id)
	{
		$sql = "SELECT 
					DISTINCT SP.relatedproductid as idproduct
 				FROM similarproduct SP
				WHERE SP.productid = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = $rs->getInt('idproduct');
		}
		return $Data;
	}

	public function getSimilarView ($id)
	{
		$sql = "SELECT 
					CS.productid AS id, 
					PT.name
				FROM similarproduct CS
				LEFT JOIN product P ON P.idproduct= CS.productid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				WHERE CS.productid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'relatedproduct' => $this->getSimilarProductView($id),
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_SIMILARPRODUCT_NO_EXIST'));
		}
		return $Data;
	}

	public function getSimilarProductView ($id)
	{
		$sql = "SELECT 
					PT.name AS relatedproduct
				FROM similarproduct CS
				LEFT JOIN product P ON P.idproduct= CS.relatedproductid
				LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
				WHERE CS.productid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'relatedproduct' => $rs->getString('relatedproduct'),
			);
		}
		return $Data;
	}

	public function addNewRelated ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->addSimilarProduct($Data['products'], $Data['productid']);
			foreach($Data['products'] as $key => $product){
				$this->addSimilarProduct(Array($Data['productid']), $product);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SIMILAR_PRODUCT_NEW_ADD'), 12, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addSimilarProduct ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO similarproduct SET
						productid = :productid, 
						relatedproductid = :relatedproductid, 
						addid = :addid
					ON DUPLICATE KEY UPDATE
						editid = :addid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('relatedproductid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_SIMILAR_PRODUCT_ADD'), 11, $e->getMessage());
			}
		}
	}

	public function editRelated ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->deleteSimilarProductById($id);
			$this->addSimilarProduct($Data['products'], $id);
			foreach($Data['products'] as $key => $product){
				$this->addSimilarProduct(Array($id), $product);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SIMILAR_PRODUCT_EDIT'), 10, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function deleteSimilarProductById ($id)
	{
		$sql = 'DELETE FROM similarproduct WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_SIMILAR_PRODUCT_DELETE'), 4, $e->getMessage());
		}
	}
}