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
 * $Id: productrange.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class productrangeModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('productrange', Array(
			'idproductreview' => Array(
				'source' => 'PR.idproductreview'
			),
			'productname' => Array(
				'source' => 'PT.name',
				'prepareForAutosuggest' => true
			),
			'firstname' => Array(
				'source' => 'CD.firstname',
				'prepareForAutosuggest' => true,
				'encrypted' => true
			),
			'surname' => Array(
				'source' => 'CD.surname',
				'prepareForAutosuggest' => true,
				'encrypted' => true
			),
			'rating' => Array(
				'source' => 'IF(AVG(PRR.value) IS NULL, 0, AVG(PRR.value))'
			)
		));
		$datagrid->setFrom('
				productreview PR
				LEFT JOIN productrange PRR ON PRR.productreviewid = PR.idproductreview
				LEFT JOIN clientdata CD ON CD.clientid = PR.clientid
				LEFT JOIN producttranslation PT ON PR.productid = PT.productid AND PT.languageid = :languageid
			');
		
		$datagrid->setGroupBy('
				PR.idproductreview
			');
	
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('productname', $request, $processFunction);
	}

	public function getFirstnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('firstname', $request, $processFunction);
	}

	public function getSurnameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('surname', $request, $processFunction);
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getProductRangeForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteProductRange ($datagrid, $id)
	{
		$this->deleteProductRange($id);
		return $this->getDatagrid()->refresh($datagrid);
	}

	public function deleteProductRange ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idproductreview' => $id
			), $this->getName(), 'deleteProductRange');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}