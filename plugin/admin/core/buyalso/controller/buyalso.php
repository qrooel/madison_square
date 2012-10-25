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
 * $Id: buyalso.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class buyalsoController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('buyalso');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllBuyalso',
			$this->model,
			'getBuyalsoForAjax'
		));
		
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->Render();
	}

	public function view ()
	{
		$form = new FE_Form(Array(
			'name' => 'view_buyalso',
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
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_BUY_ALSO_LIST'),
			'options' => FE_Option::Make($this->model->getProductAlsoToSelect())
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$buyalso = $this->model->getOrderProduct((int) $this->registry->core->getParam());
		$form->Populate(Array(
			'required_data' => Array(
				'name' => $buyalso['name'],
				'products' => $buyalso['products']
			)
		));
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}