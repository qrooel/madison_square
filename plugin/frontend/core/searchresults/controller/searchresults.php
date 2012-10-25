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
 * $Id: searchresults.php 655 2012-04-24 08:51:44Z gekosale $
 */

class searchresultsController extends Controller
{

	public function index ()
	{
	}

	public function view ()
	{
		$this->disableLayout();
		
		$param = App::getModel('formprotection')->cropDangerousCode(base64_decode($this->registry->core->getParam()));
		if (strlen($param) > 2){
			$dataset = App::getModel('searchresults')->getDataset();
			$dataset->setPagination(5);
			$dataset->setCurrentPage(1);
			$dataset->setOrderBy('name', 'name');
			$dataset->setOrderDir('asc', 'asc');
			$dataset->setSQLParams(Array(
				'symbol' => '%' . $param . '%'
			));
			$products = App::getModel('searchresults')->getProductDataset();
			$this->registry->template->saveState();
			$this->registry->template->assign('items', $products['rows']);
			$this->registry->template->assign('phrase', base64_encode($param));
			$result = $this->registry->template->fetch($this->loadTemplate('items.tpl'));
			$this->registry->template->reloadState();
			App::getModel('searchresults')->addPhrase($param);
			echo $result;
			die();
		}
	}
}