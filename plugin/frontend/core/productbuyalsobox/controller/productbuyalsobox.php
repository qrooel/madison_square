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
 * $Id: productbuyalsobox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ProductBuyAlsoBoxController extends BoxController
{

	public function index ()
	{
		$this->_boxAttributes['productsCount'] = 10;
		$this->_boxAttributes['pagination'] = 1;
		$this->_boxAttributes['view'] = 'list';
		$this->_boxAttributes['orderBy'] = 'name';
		$this->_boxAttributes['orderDir'] = 'asc';
		$this->registry->xajaxInterface->registerFunction(array(
			'GetProductsForList_' . $this->_boxId,
			$this,
			'ajax_getProducts'
		));
		$this->registry->template->assign('view', $this->_boxAttributes['view']);
		$this->registry->template->assign('pagination', $this->_boxAttributes['pagination']);
		$this->registry->template->assign('products', $this->getProductsTemplate($this->registry->core->getParamsForBox($this->_boxId)));
		$this->registry->template->assign('dataset', $this->dataset);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function boxVisible ()
	{
		return ($this->dataset['total'] > 0) ? true : false;
	}

	public function getBoxTypeClassname ()
	{
		if ($this->dataset['total'] > 0){
			return 'layout-box-type-product-list';
		}
	}

	protected function getProductsTemplate ($request)
	{
		
		$page = (isset($request['page'])) ? $request['page'] : 1;
		if (is_numeric($page)){
			$dataset = App::getModel('productbuyalsobox')->getDataset();
			if ($this->_boxAttributes['productsCount'] > 0){
				$dataset->setPagination($this->_boxAttributes['productsCount']);
			}
			$dataset->setOrderBy($this->_boxAttributes['orderBy'], $this->_boxAttributes['orderBy']);
			$dataset->setOrderDir($this->_boxAttributes['orderDir'], $this->_boxAttributes['orderDir']);
			$dataset->setCurrentPage($page);
			$products = App::getModel('productbuyalsobox')->getProductDataset();
		}
		else{
			$products = $page;
		}
		
		$this->dataset = $products;
		$this->registry->template->saveState();
		$this->registry->template->assign('items', $products['rows']);
		$result = $this->registry->template->fetch($this->loadTemplate('items.tpl', true));
		$this->registry->template->reloadState();
		return $result;
	}

	public function ajax_getProducts ($request)
	{
		return Array(
			'products' => $this->getProductsTemplate($request)
		);
	}

}