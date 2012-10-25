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
 * $Revision: 250 $
 * $Author: gekosale $
 * $Date: 2011-07-07 20:59:21 +0200 (Cz, 07 lip 2011) $
 * $Id: productsincategorybox.php 250 2011-07-07 18:59:21Z gekosale $
 */

class ProducerListBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->params = explode(',', $this->registry->router->getParams());
		if (! is_numeric($this->params[0])){
			$this->producer = App::getModel('producerlistbox')->getProducerBySeo($this->params[0]);
		}
		if (! isset($this->producer['id'])){
			App::redirect('mainside');
		}
		$this->dataset = Array();
	}

	public function index ()
	{
		
		$params = array_reverse($this->registry->core->getParams());
		$this->currentPage = (isset($params[0]) && (int) $params[0] > 0) ? (int) $params[0] : 1;
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
		
		$defaultFilter = $this->_boxAttributes['orderBy'] . ',' . $this->_boxAttributes['orderDir'];
		$this->registry->template->assign('showpagination', $this->_boxAttributes['pagination']);
		$this->registry->template->assign('view', (int) $this->view);
		$this->registry->template->assign('currentPage', $this->currentPage);
		$this->registry->template->assign('orderBy', $this->orderBy);
		$this->registry->template->assign('orderDir', $this->orderDir);
		$this->registry->template->assign('showphoto', isset($this->_boxAttributes['showphoto']) ? $this->_boxAttributes['showphoto'] : 1);
		$this->registry->template->assign('showdescription', isset($this->_boxAttributes['showdescription']) ? $this->_boxAttributes['showdescription'] : 1);
		$this->registry->template->assign('sorting', $this->createSorting());
		$this->registry->template->assign('producer', $this->producer);
		$this->registry->template->assign('products', $this->getProductsTemplate($this->registry->core->getParamsForBox($this->_boxId)));
		$this->registry->template->assign('pagination', $this->getPaginationTemplate());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function getBoxHeading ()
	{
		return $this->producer['name'];
	}

	public function getBoxTypeClassname ()
	{
		return 'layout-box-type-product-list';
	}

	protected function getProductsTemplate ($request)
	{
		$prices = $this->registry->core->getPriceRangeFromUrl();
		$pricefrom = $prices['priceFrom'];
		$priceto = $prices['priceTo'];
		$name = (isset($request['params']['name']) && ($request['params']['name'] != '')) ? '%' . $request['params']['name'] . '%' : '';
		
		$dataset = App::getModel('producerlistbox')->getDataset();
		if ($this->_boxAttributes['productsCount'] > 0){
			$dataset->setPagination($this->_boxAttributes['productsCount']);
		}
		$dataset->setCurrentPage($this->currentPage);
		$dataset->setOrderBy('name', $this->orderBy);
		$dataset->setOrderDir('asc', $this->orderDir);
		$dataset->setSQLParams(Array(
			'clientid' => $this->registry->session->getActiveClientid(),
			'producer' => $this->producer['id'],
			'pricefrom' => (float) $pricefrom,
			'priceto' => (float) $priceto,
			'name' => $name
		));
		$products = App::getModel('producerlistbox')->getProductDataset();
		$this->dataset = $products;
		
		$this->registry->template->saveState();
		$this->registry->template->assign('items', $products['rows']);
		$result = $this->registry->template->fetch($this->loadTemplate('items.tpl', true));
		$this->registry->template->reloadState();
		return $result;
	}

	protected function getPaginationTemplate ()
	{
		
		$this->registry->template->saveState();
		$result = Array();
		$this->registry->template->assign('dataset', $this->dataset);
		$this->registry->template->assign('currentCategory', $this->producer);
		$this->registry->template->assign('controller', $this->registry->router->getCurrentController());
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