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
 * $Id: statsproducts.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class StatsproductsModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getLimits ()
	{
		$Limits = range(10, 50, 10);
		return $Limits;
	}

	public function bestsellersChart ($request)
	{
		$Data = array();
		$sql = "SELECT OP.productid, OP.price as productprice, OP.name as productname, SUM(OP.qty) as bestorder 
					FROM orderproduct OP
					LEFT JOIN `order` O ON O.idorder = OP.orderid
					WHERE IF(:viewid =0,1,O.viewid = :viewid)
 					GROUP BY OP.name ORDER BY bestorder DESC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setOffset(0);
		$stmt->setLimit($request['limit']);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['values'][] = array(
				"value" => $rs->getInt('bestorder'),
				"label" => $rs->getString('productname') . ':' . $rs->getInt('bestorder') . ' szt.'
			);
		}
		$Data['colours'] = array(
			"0x336699",
			"0x88AACC",
			"0x999933",
			"0x666699",
			"0xCC9933",
			"0x006666",
			"0x3399FF",
			"0x993300",
			"0xAAAA77",
			"0x666666",
			"0xFFCC66",
			"0x6699CC",
			"0x663366",
			"0x9999CC",
			"0xAAAAAA",
			"0x669999",
			"0xBBBB55",
			"0xCC6600",
			"0x9999FF",
			"0x0066CC",
			"0x99CCCC",
			"0x999999",
			"0xFFCC00",
			"0x009999",
			"0x99CC33",
			"0xFF9900",
			"0x999966",
			"0x66CCCC",
			"0x339966",
			"0xCCCC33"
		);
		$Data['animate'][] = array(
			'type' => 'fade',
			'type' => 'bounce',
			'distance' => 4
		);
		$Data['oChartData']['bg_colour'] = "#ffffff";
		$Data['oChartData']['elements'][] = array(
			'type' => 'pie',
			'tip' => '#label#<br>#val# (#percent#)',
			'colours' => $Data['colours'],
			'gradient-fill' => true,
			'alpha' => 0.6,
			'border' => 2,
			'animate' => false,
			'start-angle' => 65,
			'radius' => 190,
			'values' => $Data['values']
		);
		$Data['oChartData']['title'] = array(
			'text' => ''
		);
		return $Data;
	}

	public function viewedChart ($request)
	{
		$Data = array();
		$sql = "SELECT OP.productid, OP.price as productprice, OP.name as productname, SUM(OP.qty) as bestorder 
					FROM orderproduct OP  
					LEFT JOIN `order` O ON O.idorder = OP.orderid
					WHERE IF(:viewid =0,1,O.viewid = :viewid) 
 					GROUP BY OP.name ORDER BY bestorder DESC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setOffset(0);
		$stmt->setLimit($request['limit']);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['values'][] = array(
				"value" => $rs->getInt('bestorder'),
				"label" => $rs->getString('productname') . ':' . $rs->getInt('bestorder') . ' szt.'
			);
		}
		$Data['colours'] = array(
			"0x336699",
			"0x88AACC",
			"0x999933",
			"0x666699",
			"0xCC9933",
			"0x006666",
			"0x3399FF",
			"0x993300",
			"0xAAAA77",
			"0x666666",
			"0xFFCC66",
			"0x6699CC",
			"0x663366",
			"0x9999CC",
			"0xAAAAAA",
			"0x669999",
			"0xBBBB55",
			"0xCC6600",
			"0x9999FF",
			"0x0066CC",
			"0x99CCCC",
			"0x999999",
			"0xFFCC00",
			"0x009999",
			"0x99CC33",
			"0xFF9900",
			"0x999966",
			"0x66CCCC",
			"0x339966",
			"0xCCCC33"
		);
		$Data['animate'][] = array(
			'type' => 'fade',
			'type' => 'bounce',
			'distance' => 4
		);
		$Data['oChartData']['bg_colour'] = "#ffffff";
		$Data['oChartData']['elements'][] = array(
			'type' => 'pie',
			'tip' => '#label#<br>#val# (#percent#)',
			'colours' => $Data['colours'],
			'gradient-fill' => true,
			'alpha' => 0.6,
			'border' => 2,
			'animate' => false,
			'start-angle' => 65,
			'radius' => 190,
			'values' => $Data['values']
		);
		$Data['oChartData']['title'] = array(
			'text' => ''
		);
		return $Data;
	}

	public function taggedChart ($request)
	{
		$Data = array();
		$sql = "SELECT OP.productid, OP.price as productprice, OP.name as productname, SUM(OP.qty) as bestorder 
					FROM orderproduct OP  
					LEFT JOIN `order` O ON O.idorder = OP.orderid
					WHERE IF(:viewid =0,1,O.viewid = :viewid)
 					GROUP BY OP.name ORDER BY bestorder DESC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setOffset(0);
		$stmt->setLimit($request['limit']);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['values'][] = array(
				"value" => $rs->getInt('bestorder'),
				"label" => $rs->getString('productname') . ':' . $rs->getInt('bestorder') . ' szt.'
			);
		}
		$Data['colours'] = array(
			"0x336699",
			"0x88AACC",
			"0x999933",
			"0x666699",
			"0xCC9933",
			"0x006666",
			"0x3399FF",
			"0x993300",
			"0xAAAA77",
			"0x666666",
			"0xFFCC66",
			"0x6699CC",
			"0x663366",
			"0x9999CC",
			"0xAAAAAA",
			"0x669999",
			"0xBBBB55",
			"0xCC6600",
			"0x9999FF",
			"0x0066CC",
			"0x99CCCC",
			"0x999999",
			"0xFFCC00",
			"0x009999",
			"0x99CC33",
			"0xFF9900",
			"0x999966",
			"0x66CCCC",
			"0x339966",
			"0xCCCC33"
		);
		$Data['animate'][] = array(
			'type' => 'fade',
			'type' => 'bounce',
			'distance' => 4
		);
		$Data['oChartData']['bg_colour'] = "#ffffff";
		$Data['oChartData']['elements'][] = array(
			'type' => 'pie',
			'tip' => '#label#<br>#val# (#percent#)',
			'colours' => $Data['colours'],
			'gradient-fill' => true,
			'alpha' => 0.6,
			'border' => 2,
			'animate' => false,
			'start-angle' => 65,
			'radius' => 190,
			'values' => $Data['values']
		);
		$Data['oChartData']['title'] = array(
			'text' => ''
		);
		return $Data;
	}

	public function getSummaryStats ()
	{
		$sql = 'SELECT count(idproduct) as products 
					FROM product
					';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['summary'] = Array(
				'products' => $rs->getInt('products')
			);
		}
		return $Data;
	}
}