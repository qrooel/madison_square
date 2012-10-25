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
 * $Id: clienthistorylog.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class clienthistorylogModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('clienthistorylog', Array(
			'idclienthistorylog' => Array(
				'source' => 'CHL.idclienthistorylog'
			),
			'clientid' => Array(
				'source' => 'CHL.clientid'
			),
			'url' => Array(
				'source' => 'CHL.URL'
			),
			'sessionid' => Array(
				'source' => 'CHL.sessionid'
			),
			'adddate' => Array(
				'source' => 'CHL.adddate'
			)
		));
		$datagrid->setFrom('
			clienthistorylog CHL
		');
		
		$datagrid->setAdditionalWhere('
			CHL.viewid IN (:viewids)
		');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getClienthistorylogForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function deleteClientHistoryLog ()
	{
		$sql = "TRUNCATE clienthistorylog";
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
	}
}