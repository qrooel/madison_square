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
 * $Id: statsclients.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class StatsclientsModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function clientsGroupsChart ($request)
	{
		$Data = array();
		$sql = 'SELECT count(CD.clientid) as clients,CGT.name as groupsname  
					FROM clientdata CD
					LEFT JOIN clientgrouptranslation CGT ON CD.clientgroupid = CGT.clientgroupid AND CGT.languageid = :languageid 
					GROUP BY CGT.clientgroupid;';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['values'][] = array(
				"value" => $rs->getInt('clients'),
				"label" => $rs->getString('groupsname')
			);
		}
		$Data['colours'] = array(
			'#d01f3c',
			'#356aa0',
			'#C79810'
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
			'values' => $Data['values']
		);
		$Data['oChartData']['title'] = array(
			'text' => ''
		);
		return $Data;
	}

	public function bestClientsChart ($request)
	{
		$Data = array();
		$Data['values'][] = array(
			'right' => 10
		);
		$Data['values'][] = array(
			'right' => 5
		);
		$Data['values'][] = array(
			'right' => 15
		);
		$Data['values'][] = array(
			'right' => 12
		);
		$Data['values'][] = array(
			'right' => 11
		);
		$Data['values'][] = array(
			'right' => 11
		);
		$Data['values'][] = array(
			'right' => 11
		);
		$Data['values'][] = array(
			'right' => 11
		);
		$Data['values'][] = array(
			'right' => 11
		);
		$Data['x_labels']['labels'] = array(
			"a",
			"b",
			"c",
			"d",
			"e",
			"f",
			"g",
			"h",
			"i",
			"j",
			"k",
			"l",
			"m",
			"n",
			"o",
			"p",
			"q",
			"r",
			"s",
			"t",
			"u",
			"v"
		);
		$Data['y_labels'] = array(
			"slashdot.org",
			"digg.com",
			"reddit.com",
			"reddit.com",
			"reddit.com",
			"reddit.com",
			"reddit.com",
			"reddit.com",
			"reddit.com"
		);
		$Data['oChartData']['title'] = array(
			'text' => 'Najlepsi klienci'
		);
		$Data['oChartData']['elements'][] = array(
			'type' => 'hbar',
			'tip' => '#val#<br>L:#left#, R:#right#',
			'text' => 'Klient',
			'colour' => '#000000',
			'values' => $Data['values']
		);
		$Data['oChartData']['x_axis'] = array(
			'min' => 0,
			'max' => 20,
			'offset' => false,
			'labels' => $Data['x_labels']
		);
		$Data['oChartData']['y_axis'] = array(
			'offset' => true,
			'labels' => $Data['y_labels']
		);
		$Data['oChartData']['tooltip'] = array(
			'mouse' => 1
		);
		return $Data;
	}

	public function getSummaryStats ()
	{
		$Data = Array();
		$period = date("Ym");
		$sql = 'SELECT COUNT(idclient) as clients
					FROM `client`
					WHERE IF(:viewid =0,1,viewid = :viewid) AND DATE_FORMAT(adddate,\'%Y-%m-%d\') = CURDATE()';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['day'] = Array(
				'dayclients' => (int) $rs->getInt('clients')
			);
		}
		$sql = 'SELECT COUNT(idclient) as clients
				FROM `client`
				WHERE IF(:viewid =0,1,viewid = :viewid) AND DATE_FORMAT(adddate,\'%Y%m\') = :period';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('period', $period);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['month'] = Array(
				'monthclients' => (int) $rs->getInt('clients')
			);
		}
		$sql = 'SELECT COUNT(idclient) as clients
					FROM `client`
					WHERE IF(:viewid =0,1,viewid = :viewid) AND year(adddate) = :period';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('period', date("Y"));
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['year'] = Array(
				'yearclients' => (int) $rs->getInt('clients')
			);
		}
		$sql = 'SELECT COUNT(idclient) as totalclients FROM `client`';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data['total'] = Array(
				'totalclients' => (int) $rs->getInt('totalclients')
			);
		}
		return $Data;
	}
}