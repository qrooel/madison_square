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
 * $Id: productpromotionsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ProductPromotionsBoxController extends BoxController
{

	public function __construct($registry){
		parent::__construct($registry);
		$this->model = App::getModel('productpromotion');
	}
	public function index ()
	{
		$params = array_reverse($this->registry->core->getParams());
		$this->currentPage = (isset($params[0]) && $params[0] > 0) ? (int) $params[0] : 1;
		$this->registry->template->assign('view', $this->_boxAttributes['view']);
		$this->registry->template->assign('pagination', $this->_boxAttributes['pagination']);
		$this->registry->template->assign('products', $this->getProductsTemplate($this->registry->core->getParamsForBox($this->_boxId)));
		$this->registry->template->assign('dataset', $this->dataset);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		if ($this->dataset['total'] > 0){
			return 'layout-box-type-product-list';
		}
	
	}
	
	public function boxVisible ()
	{
		return ($this->dataset['total'] > 0) ? true : false;
	}

	protected function getProductsTemplate ($request)
	{
		if (is_numeric($this->currentPage)){
			$dataset = $this->model->getDataset();
			if ($this->_boxAttributes['productsCount'] > 0){
				$dataset->setPagination($this->_boxAttributes['productsCount']);
			}
			$dataset->setOrderBy($this->_boxAttributes['orderBy'], $this->_boxAttributes['orderBy']);
			$dataset->setOrderDir($this->_boxAttributes['orderDir'], $this->_boxAttributes['orderDir']);
			$dataset->setCurrentPage($this->currentPage);
			$products = $this->model->getProductDataset();
		}
		$this->dataset = $products;
		foreach($products['rows'] as $key => $val){
			if($val['discountpricenetto'] > 0 && $val['pricenetto'] > 0){
				$products['rows'][$key]['discount'] = abs(ceil((1 - ($val['discountpricenetto'] / $val['pricenetto'])) * 100));
			}else{
				$products['rows'][$key]['discount'] = 1;
			}
		}
		$this->registry->template->saveState();
		$this->registry->template->assign('items', $products['rows']);
		$result = $this->registry->template->fetch($this->loadTemplate('items.tpl', true));
		$this->registry->template->reloadState();
		return $result;
	}
}