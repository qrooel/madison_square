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
 * $Id: mostsearch.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class mostSearchModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('mostsearch', Array(
			'idmostsearch' => Array(
				'source' => 'idmostsearch'
			),
			'name' => Array(
				'source' => 'name',
				'prepareForAutosuggest' => true
			),
			'textcount' => Array(
				'source' => 'textcount'
			)
		));
		$datagrid->setFrom('
				mostsearch
			');
		$datagrid->setGroupBy('
				name
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL, 1, viewid = :viewid)
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getMostSearchForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function getNameForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getFilterSuggestions('name', $request, $processFunction);
	}

	public function doAJAXDeleteMostSearch ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteMostSearch'
		), $this->getName());
	}

	public function deleteMostSearch ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idmostsearch' => $id
			), $this->getName(), 'deleteMostSearch');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}