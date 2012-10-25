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
 * $Revision: 661 $
 * $Author: gekosale $
 * $Date: 2012-04-25 17:54:37 +0200 (Śr, 25 kwi 2012) $
 * $Id: orderstatus.php 661 2012-04-25 15:54:37Z gekosale $ 
 */

class orderstatusController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('orderstatus');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteOrderstatus',
			$this->model,
			'doAJAXDeleteOrderstatus'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllOrderstatus',
			$this->model,
			'getOrderstatusForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'setDefault',
			$this->model,
			'doAJAXDefault'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_orderstatus',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'orderstatustranslation', 'name')
			)
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'comment',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_ORDER_COMMENT')
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'orderstatusgroupsid',
			'label' => $this->registry->core->getMessage('TXT_ORDER_STATUS_GROUPS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ORDER_STATUS_GROUPS'))
			),
			'options' => FE_Option::Make(App::getModel('orderstatusgroups')->getOrderStatusGroupsAllToSelect(), $this->registry->core->getDefaultValueToSelect())
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewOrderstatus($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/orderstatus/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/orderstatus');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('orderstatusadd', $this->registry->core->getMessage('TXT_ORDERSTATUS_ADD'));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_orderstatus',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'orderstatustranslation', 'name', null, Array(
					'column' => 'orderstatusid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'comment',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_ORDER_COMMENT')
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'orderstatusgroupsid',
			'label' => $this->registry->core->getMessage('TXT_ORDER_STATUS_GROUPS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ORDER_STATUS_GROUPS'))
			),
			'options' => FE_Option::Make(App::getModel('orderstatusgroups/orderstatusgroups')->getOrderStatusGroupsAllToSelect(), $this->registry->core->getDefaultValueToSelect())
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawOrderstatusData = $this->model->getOrderstatusView($this->registry->core->getParam());
		$orderstatusData = Array(
			'required_data' => Array(
				'language_data' => $rawOrderstatusData['language'],
				'orderstatusgroupsid' => $rawOrderstatusData['orderstatusgroupsid']
			)
		);
		
		$form->Populate($orderstatusData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editOrderstatus($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/orderstatus');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('orderstatusedit', $this->registry->core->getMessage('TXT_ORDERSTATUS_EDIT'));
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}