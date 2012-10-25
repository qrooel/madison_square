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
 * $Id: productstatus.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class productstatusController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('productstatus');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteProductStatus',
			$this->model,
			'doAJAXDeleteProductStatus'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllProductstatus',
			$this->model,
			'getProductstatusForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_productstatus',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewProductStatus($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/productstatus/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/productstatus');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('productstatusadd', $this->registry->core->getMessage('TXT_ORDERSTATUS_ADD'));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_productstatus',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawProductStatusData = $this->model->getProductStatusView($this->registry->core->getParam());
		$productstatusData = Array(
			'required_data' => Array(
				'name' => $rawProductStatusData['name']
			)
		);
		
		$form->Populate($productstatusData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editProductStatus($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/productstatus');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('productstatusedit', $this->registry->core->getMessage('TXT_ORDERSTATUS_EDIT'));
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}