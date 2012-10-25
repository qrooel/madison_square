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
 * $Id: mostviewed.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class mostviewedController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('mostviewed');
	}

	public function index ()
	{
		$this->registry->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren',
			App::getModel('product'),
			'loadCategoryChildren'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllMostViewed',
			$this->model,
			'getMostViewedForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function delete ()
	{
		$this->disableLayout();
		$this->model->deleteMostViewed();
		App::redirect(__ADMINPANE__ . '/mostviewed');
	}
}