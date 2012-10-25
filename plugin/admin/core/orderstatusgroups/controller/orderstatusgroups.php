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
 * $Id: orderstatusgroups.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class orderstatusgroupsController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('orderstatusgroups');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteOrderStatusGroups',
			$this->model,
			'doAJAXDeleteOrderStatusGroups'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllOrderStatusGroups',
			$this->model,
			'getOrderStatusGroupsForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_orderstatusgroups',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'orderstatusgroupstranslation', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'orderstatus',
			'label' => $this->registry->core->getMessage('TXT_ORDERSTATUS'),
			'options' => FE_Option::Make(App::getModel('orderstatus')->getOrderStatusToSelect())
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewOrderStatusGroups($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/orderstatusgroups/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/orderstatusgroups');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('orderstatusgroupsadd', $this->registry->core->getMessage('TXT_ORDER_STATUS_GROUPS_ADD'));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_orderstatusgroups',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_TOPIC_ALREADY_EXISTS'), 'orderstatusgroupstranslation', 'name', null, Array(
					'column' => 'orderstatusgroupsid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'orderstatus',
			'label' => $this->registry->core->getMessage('TXT_ORDERSTATUS'),
			'options' => FE_Option::Make(App::getModel('orderstatus')->getOrderStatusToSelect())
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawOrderStatusGroupsData = $this->model->getOrderStatusGroupsView($this->registry->core->getParam());
		$orderStatusGroupsData = Array(
			'required_data' => Array(
				'language_data' => $rawOrderStatusGroupsData['language'],
				'orderstatus' => $rawOrderStatusGroupsData['orderstatus']
			),
		);
		
		$form->Populate($orderStatusGroupsData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editOrderStatusGroups($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/orderstatusgroups');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('orderstatusgroupsedit', $this->registry->core->getMessage('TXT_ORDER_STATUS_GROUPS_EDIT'));
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}