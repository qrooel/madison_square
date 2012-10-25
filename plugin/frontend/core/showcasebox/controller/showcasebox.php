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
 * $Revision: 692 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:10:30 +0200 (Cz, 06 wrz 2012) $
 * $Id: showcasebox.php 692 2012-09-06 21:10:30Z gekosale $
 */

class ShowcaseBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->dataset = App::getModel('ShowcaseBox')->getDataset('ShowcaseBox');
	}

	public function index ()
	{
		$this->registry->template->assign('showcasecategories', App::getModel('ShowcaseBox')->getCategories($this->_boxAttributes));
		$this->registry->template->assign('products', $this->getProductsTemplate());
		$this->registry->template->assign('boxId',  $this->_boxId);
		$this->registry->xajaxInterface->registerFunction(Array(
			'GetProductsForSchowcase_' . $this->_boxId,
			$this,
			'ajax_getProducts'
		));
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	protected function getProductsTemplate ($categoryId = 0)
	{
		$params = $this->_boxAttributes;
		if ($params['productsCount'] > 0){
			$this->dataset->setPagination($params['productsCount']);
		}
		$this->dataset->setOrderBy($this->_boxAttributes['orderBy'], $this->_boxAttributes['orderBy']);
		$this->dataset->setOrderDir($this->_boxAttributes['orderDir'], $this->_boxAttributes['orderDir']);
		$this->dataset->setSQLParams(Array(
			'clientid' => $this->registry->session->getActiveClientid(),
			'statusid' => $params['statusId'],
			'category' => $categoryId
		));
		$products = App::getModel('ShowcaseBox')->getProductDataset();
		$this->registry->template->saveState();
		$this->registry->template->assign('items', $products['rows']);
		$result = $this->registry->template->fetch($this->loadTemplate('item.tpl'));
		$this->registry->template->reloadState();
		return $result;
	}

	public function ajax_getProducts ($request)
	{
		return Array(
			'products' => $this->getProductsTemplate($request['category'])
		);
	}

}