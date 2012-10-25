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
 * $Id: crosssell.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class crosssellModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('crosssell', Array(
			'idcrosssell' => Array(
				'source' => 'CS.productid'
			),
			'name' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'productcount' => Array(
				'source' => 'count(distinct CS.relatedproductid)'
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
			crosssell CS
			LEFT JOIN producttranslation PT ON CS.productid = PT.productid AND PT.languageid = :languageid
			LEFT JOIN productcategory PC ON PC.productid = PT.productid
			LEFT JOIN viewcategory VC ON PC.categoryid = VC.categoryid
			LEFT JOIN category C ON C.idcategory = PC.categoryid
			LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
			LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
		');
		$datagrid->setGroupBy('
			CS.productid
		');
		
		if (Helper::getViewId() > 0){
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

	public function getCrosssellForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteCrosssell ($datagrid, $id)
	{
		$this->deleteCrosssell($id);
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteCrosssell ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'productid' => $id
			), $this->getName(), 'deleteCrosssell');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function getCrossSellView ($id)
	{
		$sql = "SELECT CS.productid AS id, PT.name
					FROM crosssell CS
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
				'relatedproduct' => $this->getCrossSellProductView($id)
			);
		}
		else{
			throw new CoreException($this->registry->core->getMessage('ERR_CROSSSELL_NO_EXIST'));
		}
		return $Data;
	}

	public function getCrossSellProductView ($id)
	{
		$sql = "SELECT PT.name AS relatedproduct, APV.name AS attribute
					FROM crosssell CS
					LEFT JOIN product P ON P.idproduct= CS.relatedproductid
					LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
					LEFT JOIN productattributevalueset PAVS ON PAVS.productattributesetid= CS.productattributesetid 
					LEFT JOIN attributeproductvalue APV ON APV.idattributeproductvalue = PAVS.attributeproductvalueid
					WHERE CS.productid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'relatedproduct' => $rs->getString('relatedproduct'),
				'attribute' => $rs->getString('attribute')
			);
		}
		return $Data;
	}

	public function getProductsDataGrid ($id)
	{
		$sql = "SELECT CS.productid AS id, CS.relatedproductid as idproduct, PT.name
 					FROM crosssell CS
 					LEFT JOIN product P ON P.idproduct = CS.relatedproductid
 					LEFT JOIN producttranslation PT ON P.idproduct = PT.productid AND PT.languageid = :languageid
					WHERE CS.productid =:id";
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

	public function addNewRelated ($Data)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->addCrossSell($Data['products'], $Data['productid']);
			foreach ($Data['products'] as $key => $product){
				$this->addCrossSell(Array(
					$Data['productid']
				), $product);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CROSS_SELL_NEW_ADD'), 12, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function addCrossSell ($Data, $id)
	{
		foreach ($Data as $value){
			$sql = 'INSERT INTO crosssell SET 
						productid = :productid, 
						relatedproductid = :relatedproductid, 
						addid = :addid
					ON DUPLICATE KEY UPDATE
						editid = :addid
					';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('productid', $id);
			$stmt->setInt('relatedproductid', $value);
			$stmt->setInt('addid', $this->registry->session->getActiveUserid());
			try{
				$stmt->executeUpdate();
			}
			catch (Exception $e){
				throw new CoreException($this->registry->core->getMessage('ERR_CROSS_SELL_ADD'), 11, $e->getMessage());
			}
		}
	}

	public function editRelated ($Data, $id)
	{
		$this->registry->db->setAutoCommit(false);
		try{
			$this->deleteCrossSellById($id);
			$this->addCrossSell($Data['products'], $id);
			foreach ($Data['products'] as $key => $product){
				$this->addCrossSell(Array(
					$id
				), $product);
			}
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CROSS_SELL_EDIT'), 10, $e->getMessage());
		}
		$this->registry->db->commit();
		$this->registry->db->setAutoCommit(true);
		return true;
	}

	public function deleteCrossSellById ($id)
	{
		
		$sql = 'DELETE FROM crosssell WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new CoreException($this->registry->core->getMessage('ERR_CROSS_SELL_DELETE'), 4, $e->getMessage());
		}
	}

}