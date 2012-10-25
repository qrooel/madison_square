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
 * $Id: paymentmethod.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class PaymentmethodController extends Controller
{
	protected $_excludeDirectories = Array(
		'.settings',
		'.svn'
	);

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('paymentmethod');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeletePaymentMethod',
			$this->model,
			'doAJAXDeletePaymentmethod'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllPaymentMethod',
			$this->model,
			'getPaymentmethodForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'disablePaymentmethod',
			$this->model,
			'doAJAXDisablePaymentmethod'
		));
		$this->registry->xajax->registerFunction(array(
			'enablePaymentmethod',
			$this->model,
			'doAJAXEnablePaymentmethod'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetControllerSuggestions',
			$this->model,
			'getControllerForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doAJAXUpdateMethod',
			$this->model,
			'doAJAXUpdateMethod'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('paymentmethod', $this->model->getPaymentmethodAll());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_paymentmethod',
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
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'paymentmethod', 'name')
			)
		)));
		$path = ROOTPATH . 'plugin' . DS . 'admin' . DS . 'core' . DS . 'paymentmethod' . DS . 'model';
		$filesObj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		foreach ($filesObj as $file){
			if ($this->checkIfExclude($file->getPathName()) == FALSE){
				$fileName = substr($file->getFileName(), 0, - 4);
				if ($fileName != 'paymentmethod'){
					$Data[$fileName] = $fileName;
				}
			}
		}
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'controller',
			'label' => $this->registry->core->getMessage('TXT_PAYMENT_CONTROLLER'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PAYMENT_CONTROLLER'))
			),
			'options' => FE_Option::Make($Data)
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'dispatchmethod',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD'),
			'options' => FE_Option::Make(App::getModel('dispatchmethod')->getDispatchmethodToSelect())
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewPaymentmethod($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/paymentmethod/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/paymentmethod');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_paymentmethod',
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
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'paymentmethod', 'name', null, Array(
					'column' => 'idpaymentmethod',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'dispatchmethod',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD'),
			'options' => FE_Option::Make(App::getModel('dispatchmethod')->getDispatchmethodToSelect())
		)));
		
		$paymentMethodModel = $this->model->getPaymentmethodModelById($this->registry->core->getParam());
		if (file_exists(ROOTPATH . 'plugin' . DS . 'admin' . DS . 'core' . DS . $this->getName() . DS . 'model' . DS . $paymentMethodModel . '.php')){
			$paymentMethodConfigurationFields = App::getModel('paymentmethod/' . $paymentMethodModel)->getConfigurationFields();
			$configurationValues = array();
			
			if (is_array($paymentMethodConfigurationFields) && ! empty($paymentMethodConfigurationFields)){
				$configurationData = $form->AddChild(new FE_Fieldset(Array(
					'name' => 'configuration_data',
					'label' => $this->registry->core->getMessage('TXT_CONFIGURATION_DATA')
				)));
				
				if (Helper::getViewId() > 0){
					foreach ($paymentMethodConfigurationFields as $field){
						if (isset($field['name']) && isset($field['value'])){
							$configurationValues[$field['name']] = $field['value'];
						}
						if ($field['fe_element'] == 'FE_TextField'){
							$configurationData->AddChild(new FE_TextField(Array(
								'name' => $field['name'],
								'label' => $field['label'],
								'comment' => $field['comment'],
								'rules' => Array(
									new FE_RuleRequired($field['help'])
								)
							)));
						}
						if ($field['fe_element'] == 'FE_Select'){
							$configurationData->AddChild(new FE_Select(Array(
								'name' => $field['name'],
								'label' => $field['label'],
								'comment' => $field['comment'],
								'options' => FE_Option::Make($field['options'])
							)));
						}
						
						if ($field['fe_element'] == 'FE_StaticText'){
							$configurationData->AddChild(new FE_StaticText(Array(
								'text' => $field['text']
							)));
						}
					}
				}
				else{
					$configurationData->AddChild(new FE_StaticText(Array(
						'text' => $this->registry->core->getMessage('TXT_PAYMENT_CONFIGURATION_VIEW')
					)));
				}
			}
		}
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if (isset($configurationValues) && $configurationValues != NULL){
			$rawPaymentmethodData = $this->model->getPaymentmethodView($this->registry->core->getParam());
			$paymentmethodData = Array(
				'required_data' => Array(
					'name' => $rawPaymentmethodData['name'],
					'dispatchmethod' => $rawPaymentmethodData['dispatchmethod']
				),
				'configuration_data' => $configurationValues,
				'view_data' => Array(
					'view' => $rawPaymentmethodData['view']
				)
			);
		}
		else{
			$rawPaymentmethodData = $this->model->getPaymentmethodView($this->registry->core->getParam());
			$paymentmethodData = Array(
				'required_data' => Array(
					'name' => $rawPaymentmethodData['name'],
					'dispatchmethod' => $rawPaymentmethodData['dispatchmethod']
				),
				'view_data' => Array(
					'view' => $rawPaymentmethodData['view']
				)
			);
		}
		
		$form->Populate($paymentmethodData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editPaymentmethod($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
				
				if (file_exists(ROOTPATH . 'plugin' . DS . 'admin' . DS . $this->model->getNamespace() . DS . $this->getName() . DS . 'model' . DS . $paymentMethodModel . '.php')){
					App::getModel('paymentmethod/' . $paymentMethodModel)->editPaymentmethodConfiguration($form->getSubmitValues(FE_Form::FORMAT_FLAT));
				}
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/paymentmethod');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	protected function checkIfExclude ($fileName)
	{
		foreach ($this->_excludeDirectories as $dir){
			if (strpos($fileName, DS . $dir) > 0){
				return true;
			}
		}
		return false;
	}
}