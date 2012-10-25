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
 */

class CategoriesBoxController extends BoxController
{

	public function index ()
	{
		$include = '';
		if (! isset($this->_boxAttributes['showall'])){
			$showall = 1;
		}
		else{
			$showall = $this->_boxAttributes['showall'];
			$include = isset($this->_boxAttributes['categoryIds']) ? explode(',',$this->_boxAttributes['categoryIds']) : Array();
		}
		
		if (isset($this->_boxAttributes['showcount']) && $this->_boxAttributes['showcount'] == 1){
			$showcount = 1;
		}
		else{
			$showcount = 0;
		}
		if (isset($this->_boxAttributes['hideempty']) && $this->_boxAttributes['hideempty'] == 1){
			$hideempty = 1;
		}
		else{
			$hideempty = 0;
		}
		if (($categories = Cache::loadObject('categories')) === FALSE){
			$categories = App::getModel('CategoriesBox')->getCategoriesTree();
			Cache::saveObject('categories', $categories, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		$params = $this->registry->router->getParams();
		$categoryPath = explode(',',$params);
		$path = App::getModel('categoriesbox')->getCurrentCategoryPath($categoryPath[0]);
		
		if ($this->registry->router->getCurrentController() == 'productcart'){
			$path = App::getModel('categoriesbox')->getCategoryPathForProductById($this->registry->core->getParam());
			foreach ($categories as $key => $category){
				if (in_array($category['id'], $path)){
					$categories[$key]['current'] = 1;
				}
				foreach ($category['children'] as $k => $subcategory){
					if (in_array($subcategory['id'], $path)){
						$categories[$key]['children'][$k]['current'] = 1;
					}
				}
			}
		}
		if ($this->registry->session->getActiveForceLogin() == 1 && $this->registry->session->getActiveClientid() == 0){
			$categories = Array();
		}
		
		$this->total = count($categories);
		$this->registry->template->assign('categories', $categories);
		$this->registry->template->assign('showcount', $showcount);
		$this->registry->template->assign('path', $path);
		$this->registry->template->assign('showall', $showall);
		$this->registry->template->assign('include', $include);
		$this->registry->template->assign('hideempty', $hideempty);
		$this->registry->template->assign('current', (int) $this->registry->core->getParam());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		if ($this->total > 0){
			return 'layout-box-type-categorymenu';
		}
	}

}