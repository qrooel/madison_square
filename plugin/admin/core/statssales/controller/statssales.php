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
 * $Id: statssales.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class StatssalesController extends Controller
{

	public function index ()
	{
		$mainsideModel = App::getModel('mainside');
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('from', date('Y/m/1'));
		$this->registry->template->assign('to', date('Y/m/d'));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('summaryStats', $mainsideModel->getSummaryStats());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}
