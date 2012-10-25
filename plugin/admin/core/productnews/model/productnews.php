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
 * $Id: productnews.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class productnewsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('productnew', Array(
			'idproductnew' => Array(
				'source' => 'PN.productid'
			),
			'name' => Array(
				'source' => 'PT.name'
			),
			'active' => Array(
				'source' => 'PN.active'
			),
			'enddate' => Array(
				'source' => 'PN.enddate'
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
				productnew PN
				LEFT JOIN producttranslation PT ON PN.productid = PT.productid AND PT.languageid = :languageid
				LEFT JOIN productcategory PC ON PC.productid = PN.productid
				LEFT JOIN category C ON C.idcategory = PC.categoryid
				LEFT JOIN categorypath CP ON C.idcategory = CP.categoryid
				LEFT JOIN categorytranslation CT ON C.idcategory = CT.categoryid AND CT.languageid = :languageid
			');
		$datagrid->setGroupBy('
				PN.productid
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

	public function getProductNewsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteProductNews ($datagrid, $id)
	{
		$this->deleteProductNews($id);
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteProductNews ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'productid' => $id
			), $this->getName(), 'deleteProductNews');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function doAJAXEnableProductNews ($datagridId, $id)
	{
		try{
			$this->enableProductNews($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_ENABLE_PRODUCT_NEWS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function doAJAXDisableProductNews ($datagridId, $id)
	{
		try{
			$this->disableProductNews($id);
			return $this->getDatagrid()->refresh($datagridId);
		}
		catch (Exception $e){
			$objResponse = new xajaxResponse();
			$objResponse->script("GF_Alert('{$this->registry->core->getMessage('ERR_UNABLE_TO_DISABLE_PRODUCT_NEWS')}', '{$e->getMessage()}');");
			return $objResponse;
		}
	}

	public function disableProductNews ($id)
	{
		$sql = 'UPDATE productnew SET active = 0 WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function enableProductNews ($id)
	{
		$sql = 'UPDATE productnew SET active = 1 WHERE productid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}