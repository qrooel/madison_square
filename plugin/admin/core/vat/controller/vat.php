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
 * $Id: vat.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class vatController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('vat');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteVAT',
			$this->model,
			'doAJAXDeleteVAT'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllVAT',
			$this->model,
			'getVATForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetValueSuggestions',
			$this->model,
			'getValueForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_vat',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'vattranslation', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'value',
			'label' => $this->registry->core->getMessage('TXT_VALUE'),
			'comment' => $this->registry->core->getMessage('TXT_VALUE_IN_PERCENT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_VALUE')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_VALUE_ALREADY_EXISTS'), 'vat', 'value')
			),
			'suffix' => '%',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('vat')->addNewVAT($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/vat/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/vat');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('vatadd', $this->registry->core->getMessage('TXT_VAT_ADD'));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_vat',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_VAT_ALREADY_EXISTS'), 'vattranslation', 'name', null, Array(
					'column' => 'vatid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'value',
			'label' => $this->registry->core->getMessage('TXT_VALUE'),
			'comment' => $this->registry->core->getMessage('TXT_VALUE_IN_PERCENT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_VALUE')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_VALUE_ALREADY_EXISTS'), 'vat', 'value', null, Array(
					'column' => 'idvat',
					'values' => (int) $this->registry->core->getParam()
				))
			),
			'suffix' => '%',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawVatData = App::getModel('vat')->getVATView($this->registry->core->getParam());
		$vatData = Array(
			'required_data' => Array(
				'value' => $rawVatData['value'],
				'language_data' => $rawVatData['language']
			)
		);
		
		$form->Populate($vatData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('vat')->editVAT($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/vat');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('vatedit', $this->registry->core->getMessage('TXT_VAT_EDIT'));
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}