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

class LayeredNavigationBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
	}

	public function index ()
	{
		$this->params = explode(',', $this->registry->router->getParams());
		$this->attributes = $this->registry->core->getParamFromUrl('attributes');
		$this->staticattributes = $this->registry->core->getParamFromUrl('staticattributes');
		$params = array_reverse($this->params);
		$this->currentPage = (isset($params[0]) && (int) $params[0] > 0) ? (int) $params[0] : 1;
		
		switch ($this->registry->router->getCurrentController()) {
			case 'categorylist':
				
				if (! is_numeric($this->params[0])){
					$this->category = App::getModel('categorylist')->getCategoryIdBySeo($this->params[0]);
				}
				else{
					$this->category = (int) $this->registry->core->getParam();
				}
				
				$Data = App::getModel('layerednavigationbox')->getLayeredAttributesForCategory($this->category['id']);
				$this->total = count($Data);
				$producers = App::getModel('product')->getProducerAll(Array(
					$this->category['id']
				));
				
				$staticattributes = App::getModel('layerednavigationbox')->getStaticAttributesForCategory($this->category['id']);
				$this->registry->template->assign('producers', $producers);
				$ranges = App::getModel('layerednavigationbox')->getPriceRangeForCategory($this->category['id']);
				$this->registry->template->assign('currentCategory', $this->category);
				$this->registry->template->assign('currentSeo', $this->category['seo']);
				$this->registry->template->assign('ranges', $ranges);
				$this->registry->template->assign('groups', $Data);
				$this->registry->template->assign('staticattributes', $staticattributes);
				$this->registry->template->assign('current', (int) $this->registry->core->getParam());
				$this->registry->template->assign('params', $this->registry->router->getParams());
				
				break;
			case 'productsearch':
				$param = App::getModel('formprotection')->cropDangerousCode(base64_decode($this->registry->core->getParam()));
				$dataset = App::getModel('searchresults')->getDataset();
				$dataset->setPagination(5);
				$dataset->setCurrentPage(1);
				$dataset->setOrderBy('name', 'name');
				$dataset->setOrderDir('asc', 'asc');
				$dataset->setSQLParams(Array(
					'name' => '*' . $param . '*',
					'symbol' => '%' . $param . '%'
				));
				$products = App::getModel('searchresults')->getProductDataset();
				$productIds = Array(
					0
				);
				foreach ($products['rows'] as $key => $product){
					$productIds[] = $product['id'];
				}
				$Data = App::getModel('layerednavigationbox')->getLayeredAttributesByProductIds($productIds);
				$this->total = count($Data);
				$producers = App::getModel('product')->getProducerAllByProducts($productIds);
				$this->registry->template->assign('groups', $Data);
				$this->registry->template->assign('currentSeo', $this->registry->core->getParam());
				$this->registry->template->assign('ranges', App::getModel('layerednavigationbox')->getPriceRangeForProducts($productIds));
				$this->registry->template->assign('producers', $producers);
				$this->registry->template->assign('params', $this->registry->router->getParams());
				break;
		}
		$this->registry->template->assign('currentController', $this->registry->router->getCurrentController());
		$this->registry->template->assign('currentPage', $this->currentPage);
		$this->registry->template->assign('currentPrice', $this->registry->core->getParamFromUrl('price'));
		$this->registry->template->assign('currentProducers', $this->registry->core->getParamFromUrl('producer'));
		$this->registry->template->assign('currentAttributes', $this->attributes);
		$this->registry->template->assign('currentStaticAttributes', $this->staticattributes);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		return 'layout-box-type-layered-navigation';
	}

}