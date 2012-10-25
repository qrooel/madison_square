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
 * $Id: cmsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class CmsBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('staticcontent');
	
	}

	public function index ()
	{
		$event2 = new sfEvent($this, 'frontend.cmsbox.assign');
		$this->registry->dispatcher->filter($event2, (int) $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$this->registry->template->assign($tab, $values);
			}
		}
		
		$cms = $this->model->getStaticContent((int) $this->registry->core->getParam());
		$this->registry->template->assign('cms', $cms);
		$this->registry->template->assign('cmscategories', $this->model->getUnderCategoryBox((int) $this->registry->core->getParam()));
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxHeading ()
	{
		$cms = $this->model->getBoxHeadingName((int) $this->registry->core->getParam());
		if (! isset($cms['seo'])){
			App::redirectSeo(App::getURLAdress());
		}
		$params = explode('/', $this->registry->router->getParams());
		if (count($params) == 1 || (isset($params[1]) && $params[1] != $cms['seo'])){
			$url = App::getURLAdress() . $this->registry->core->getControllerNameForSeo('staticcontent') . '/' . (int) $this->registry->core->getParam() . '/' . $cms['seo'];
			App::redirectSeo($url);
		}
		
		if (isset($cms['name'])){
			return $cms['name'];
		}
		else{
			return $this->registry->core->getMessage('ERR_CMS_NO_EXIST');
		}
	}
}