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
 * $Id: tags.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class tagsModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('tags', Array(
			'idtags' => Array(
				'source' => 'T.idtags'
			),
			'textcount' => Array(
				'source' => 'T.textcount'
			),
			'name' => Array(
				'source' => 'T.name',
				'prepareForSelect' => true
			),
			'clientid' => Array(
				'source' => 'PT.clientid'
			),
			'view' => Array(
				'source' => 'V.name',
				'prepareForSelect' => true
			)
		));
		$datagrid->setFrom('
				tags T
				LEFT JOIN producttags PT ON PT.tagsid = T.idtags
				LEFT JOIN producttranslation PTR ON PTR.productid = PT.productid AND languageid=:languageid
				LEFT JOIN view V ON PT.viewid = V.idview
			');
		$datagrid->setAdditionalWhere('
				IF(:viewid IS NULL,1,T.viewid = :viewid)
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getTagsForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteTags ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteTags'
		), $this->getName());
	}

	public function deleteTags ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idtags' => $id
			), $this->getName(), 'deleteTags');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}