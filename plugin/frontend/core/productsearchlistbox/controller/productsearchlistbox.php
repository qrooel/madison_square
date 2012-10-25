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
 * $Id: productsearchlistbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class ProductSearchListBoxController extends BoxController
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'addPhrase',
			App::getModel('productsearch'),
			'addAJAXPhraseAboutMostSearch'
		));
		
		$params = array_reverse($this->registry->core->getParams());
		$this->currentPage = (isset($params[0]) && (int) $params[0] > 0) ? (int) $params[0] : 1;
		$this->producers = $this->registry->core->getParamFromUrl('producer');
		$this->attributes = $this->registry->core->getParamFromUrl('attributes');
		$this->staticattributes = $this->registry->core->getParamFromUrl('staticattributes');
		$this->orderBy = isset($_GET['sort']) ? $_GET['sort'] : $this->_boxAttributes['orderBy'];
		$this->orderDir = isset($_GET['dir']) ? $_GET['dir'] : $this->_boxAttributes['orderDir'];
		
		if (isset($_GET['view'])){
			$this->view = (int) $_GET['view'];
			$this->registry->session->setActiveProductListView($this->view);
		}
		else{
			if ($this->registry->session->getActiveProductListView() == NULL){
				$this->view = (int) $this->_boxAttributes['view'];
			}
			else{
				$this->view = $this->registry->session->getActiveProductListView();
			}
		}
		
		$params = $this->registry->core->getParams();
		
		$searchPhrase = '';
		$searchCategoryName = '';
		$searchCategoryId = 0;
		$searchType = 0;
		$sellPriceMin = 0;
		$sellPriceMax = 0;
		$searchProducerName = '';
		$searchProducerId = 0;
		
		isset($params[0]) && $searchPhrase = urldecode((string) $params[0]);
		isset($params[1]) && $searchCategoryName = urldecode((string) $params[1]);
		isset($params[2]) && $searchCategoryId = (int) $params[2];
		isset($params[3]) && $searchType = (int) $params[3];
		isset($params[4]) && $sellPriceMin = (int) $params[4];
		isset($params[5]) && $sellPriceMax = (int) $params[5];
		isset($params[6]) && $searchProducerName = (string) $params[6];
		isset($params[7]) && $searchProducerId = (int) $params[7];
		
		$this->registry->template->assign('searchCategoryId', $searchCategoryId);
		$this->registry->template->assign('searchCategoryName', $searchCategoryName);
		$this->registry->template->assign('searchType', $searchType);
		$this->registry->template->assign('sellPriceMin', $sellPriceMin);
		$this->registry->template->assign('sellPriceMax', $sellPriceMax);
		$this->registry->template->assign('searchProducerId', $searchProducerId);
		$this->searchPhrase = App::getModel('formprotection')->cropDangerousCode(base64_decode($searchPhrase));
		$this->registry->template->assign('searchPhrase', $searchPhrase);
		$this->registry->template->assign('showpagination', $this->_boxAttributes['pagination']);
		$this->registry->template->assign('sorting', $this->createSorting());
		$this->registry->template->assign('view', $this->view);
		$this->registry->template->assign('orderBy', $this->orderBy);
		$this->registry->template->assign('orderDir', $this->orderDir);
		$this->registry->template->assign('productList', App::getModel('productsearch/productsearch')->search($searchPhrase, $searchCategoryId, $searchType, $sellPriceMin, $sellPriceMax, $searchProducerId));
		$this->registry->template->assign('producers', App::getModel('product')->getProducerAll());
		$this->registry->template->assign('products', $this->getProductsTemplate($this->registry->core->getParamsForBox($this->_boxId)));
		$this->registry->template->assign('pagination', $this->getPaginationTemplate());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxTypeClassname ()
	{
		return 'layout-box-type-product-list';
	}

	protected function getProductsTemplate ($request)
	{
		$params = Array();
		if (! empty($request['params'])){
			$params = $this->parseParams($request['params']);
		}
		
		$category = (isset($request['params']['category']) && ($request['params']['category'] > 0)) ? $request['params']['category'] : 0;
		$producer = (! empty($this->producers)) ? array_keys($this->producers) : Array();
		
		$prices = $this->registry->core->getPriceRangeFromUrl();
		$pricefrom = $prices['priceFrom'];
		$priceto = $prices['priceTo'];
		
		$name = (isset($request['params']['name']) && ($request['params']['name'] != '')) ? $request['params']['name'] : $this->searchPhrase;
		
		$attributes = Array();
		foreach ($this->attributes as $attribute){
			$attribute = substr($attribute, 1);
			$group = explode('-', $attribute);
			$attributes[$group[0]][] = $group[1];
		}
		$staticattributes = Array();
		if (! empty($this->staticattributes)){
			foreach ($this->staticattributes as $staticattribute){
				$staticattribute = substr($staticattribute, 1);
				$group = explode('-', $staticattribute);
				$staticattributes[$group[0]][] = $group[1];
			}
		}
		
		$Products = App::getModel('layerednavigationbox')->getProductsForAttributes(0, $attributes, $staticattributes);
		
		if (is_numeric($this->currentPage)){
			$dataset = App::getModel('productsearch')->getDataset();
			if ($this->_boxAttributes['productsCount'] > 0){
				$dataset->setPagination($this->_boxAttributes['productsCount']);
			}
			$dataset->setCurrentPage($this->currentPage);
			$dataset->setOrderBy('name', $this->orderBy);
			$dataset->setOrderDir('asc', $this->orderDir);
			$dataset->setSQLParams(Array(
				'categoryid' => (int) $category,
				'clientid' => $this->registry->session->getActiveClientid(),
				'producer' => $producer,
				'filterbyproducer' => (! empty($producer)) ? 1 : 0,
				'pricefrom' => (float) $pricefrom,
				'priceto' => (float) $priceto,
				'name' => '%' . strtolower($name) . '%',
				'enablelayer' => (! empty($Products) && (count($attributes) > 0 || count($staticattributes) > 0)) ? 1 : 0,
				'products' => $Products
			));
			$products = App::getModel('productsearch')->getProductDataset();
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

	public function getBoxHeading ()
	{
		return $this->searchPhrase;
	}

	protected function getPaginationTemplate ()
	{
		
		$this->registry->template->saveState();
		$result = Array();
		$this->registry->template->assign('dataset', $this->dataset);
		$this->registry->template->assign('controller', $this->registry->router->getCurrentController());
		$this->registry->template->assign('priceRange', $this->registry->core->getParamFromUrl('price'));
		$this->registry->template->assign('currentProducers', $this->producers);
		$this->registry->template->assign('currentAttributes', $this->attributes);
		$result = $this->registry->template->fetch($this->loadTemplate('pagination.tpl'));
		
		$this->registry->template->reloadState();
		return $result;
	}

	public function createSorting ()
	{
		
		$Sorting = Array(
			'name' => $this->registry->core->getMessage('TXT_NAME'),
			'price' => $this->registry->core->getMessage('TXT_PRICE'),
			'rating' => $this->registry->core->getMessage('TXT_AVERAGE_OPINION'),
			'opinions' => $this->registry->core->getMessage('TRANS_OPINIONS_QTY'),
			'adddate' => $this->registry->core->getMessage('TXT_ADDDATE')
		);
		return $Sorting;
	}
}
