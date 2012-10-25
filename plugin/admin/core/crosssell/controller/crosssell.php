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
 * $Id: crosssell.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class crosssellController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('crosssell');
	}

	public function index ()
	{
		$this->registry->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren',
			App::getModel('product'),
			'loadCategoryChildren'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllCrosssell',
			$this->model,
			'getCrosssellForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteCrosssell',
			$this->model,
			'doAJAXDeleteCrosssell'
		));
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->Render();
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_crosssell',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BASE_PRODUCT')
		)));
		
		$productid = $requiredData->AddChild(new FE_ProductSelect(Array(
			'name' => 'productid',
			'label' => $this->registry->core->getMessage('TXT_BASE_PRODUCT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BASE_PRODUCT'))
			)
		)));
		
		$relatedProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'related_products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL_PRODUCTS')
		)));
		
		$relatedProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL_PRODUCTS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CROSSSELL_PRODUCTS'))
			),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'exclude_from' => $productid
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewRelated($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/crosssell/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/crosssell');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		$form = new FE_Form(Array(
			'name' => 'edit_crosssell',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BASE_PRODUCT')
		)));
		
		$requiredData->AddChild(new FE_Constant(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_BASE_PRODUCT')
		)));
		
		$relatedProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'related_products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL_PRODUCTS')
		)));
		
		$relatedProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL_PRODUCTS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CROSSSELL_PRODUCTS'))
			),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'exclude' => (int) $this->registry->core->getParam()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$crosssell = $this->model->getCrossSellView((int) $this->registry->core->getParam());
		$form->Populate(Array(
			'required_data' => Array(
				'name' => $crosssell['name']
			),
			'related_products' => Array(
				'products' => $this->model->getProductsDataGrid((int) $this->registry->core->getParam())
			),
		));
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->editRelated($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__ . '/crosssell');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}