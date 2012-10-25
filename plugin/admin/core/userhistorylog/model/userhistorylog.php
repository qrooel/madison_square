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
 * $Id: userhistorylog.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class userhistorylogModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('userhistorylog', Array(
			'iduserhistorylog' => Array(
				'source' => 'iduserhistorylog'
			),
			'firstname' => Array(
				'source' => 'UD.firstname',
				'prepareForAutosuggest' => true
			),
			'surname' => Array(
				'source' => 'UD.surname',
				'prepareForAutosuggest' => true
			),
			'sessionid' => Array(
				'source' => 'UHL.sessionid'
			),
			'address' => Array(
				'source' => 'UHL.URL',
				'prepareForSelect' => true
			),
			'adddate' => Array(
				'source' => 'UHL.adddate'
			)
		));
		$datagrid->setFrom('
			`userhistorylog` UHL
			LEFT JOIN `userdata` UD ON UD.userid = UHL.userid
		');
		
		if (Helper::getViewId() > 0){
			$datagrid->setAdditionalWhere('
				UHL.viewid IN (:viewids)
			');
		}
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

	public function getUserhistorylogForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function deleteUserHistoryLog ()
	{
		$sql = "TRUNCATE userhistorylog";
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
	}
}