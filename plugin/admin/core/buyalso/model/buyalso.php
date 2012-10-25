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
 * $Id: buyalso.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class buyalsoModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('orderproduct', Array(
			'productid' => Array(
				'source' => 'OP.productid'
			),
			'name' => Array(
				'source' => 'OP.name',
				'prepareForAutosuggest' => true
			)
		));
		$datagrid->setFrom('
			orderproduct OP
			LEFT JOIN `order` O ON OP.orderid = O.idorder
			LEFT JOIN product P ON OP.productid = P.idproduct
		');
		
		$datagrid->setGroupBy('
			OP.productid
		');
		
		$datagrid->setAdditionalWhere('
			P.sellprice IS NOT NULL AND O.viewid IN (:viewids)
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

	public function getBuyalsoForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getOrderProduct ($id)
	{
		$sql = "SELECT name FROM orderproduct WHERE productid=:id";
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

	public function getAlsoProduct ($id)
	{
		$sql = "SELECT orderid FROM orderproduct WHERE productid=:id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'orderid' => $rs->getInt('orderid')
			);
		}
		$Products = Array();
		foreach ($Data as $key => $table){
			$sql = "SELECT productid as idproduct FROM orderproduct WHERE orderid=:orderId AND productid!=:id";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('orderId', $table['orderid']);
			$stmt->setInt('id', $id);
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Products[] = $rs->getInt('idproduct');
			}
		}
		return $Products;
	}

	public function getProductAlsoAll ()
	{
		$sql = 'SELECT productid as id, name FROM orderproduct';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getProductAlsoToSelect ()
	{
		$Data = $this->getProductAlsoAll();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$key['id']] = $key['name'];
		}
		return $tmp;
	}
}