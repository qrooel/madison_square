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
 * $Id: sendnewsletter.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class sendnewsletterModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('sendnewsletter', Array(
			'idsendnewsletter' => Array(
				'source' => 'idsendnewsletter'
			),
			'name' => Array(
				'source' => 'name'
			)
		));
		$datagrid->setFrom('
				sendnewsletter 
			');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSendNewsletterForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function doAJAXDeleteSendNewsletter ($id, $datagrid)
	{
		return $this->getDatagrid()->deleteRow($id, $datagrid, Array(
			$this,
			'deleteSendNewsletter'
		), $this->getName());
	}

	public function deleteSendNewsletter ($id)
	{
		try{
			$dbtracker = new DBTracker($this->registry);
			$dbtracker->load($this->getDirPath());
			return $dbtracker->run(Array(
				'idsendnewsletter' => $id
			), $this->getName(), 'deleteSendNewsletter');
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	public function addNewRecipientList ($Data)
	{
		$result = Array();
		$vars = preg_split('/(([a-zA-Z0-9_.\-])+(@)+([a-zA-Z0-9.\-])+)?(;)|(,)
		  	|([\0\n\r])/', $Data['emaillist']);
		
		$test = Array();
		foreach ($vars as $var){
			$test[] = trim($var);
			echo "var:" . $var . "<br>";
		}
		
	}
}