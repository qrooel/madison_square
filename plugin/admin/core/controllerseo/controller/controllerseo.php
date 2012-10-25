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
 * $Id: controllerseo.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class ControllerSeoController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('controllerseo');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doUpdateControllerSeo',
			$this->model,
			'doAJAXUpdateControllerSeo'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllControllerSeo',
			$this->model,
			'getControllerSeoForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetTranslationSuggestions',
			$this->model,
			'getTranslationNameForAjax'
		));
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->Render();
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_controllerseo',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'controller',
			'label' => $this->registry->core->getMessage('TXT_CONTROLLER'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTROLLER')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'controller', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'side',
			'label' => $this->registry->core->getMessage('TXT_SIDE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SIDE'))
			),
			'options' => Array(
				new FE_Option('', 'Wybierz'),
				new FE_Option('1', 'Admin'),
				new FE_Option('2', 'Sklep')
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->addNewControllerSeo($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/controllerseo');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		$form = new FE_Form(Array(
			'name' => 'edit_controllerseo',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'controller',
			'label' => $this->registry->core->getMessage('TXT_CONTROLLER'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTROLLER')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'controller', 'name', null, Array(
					'column' => 'idcontroller',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'side',
			'label' => $this->registry->core->getMessage('TXT_SIDE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SIDE'))
			),
			'options' => Array(
				new FE_Option('', 'Wybierz'),
				new FE_Option('1', 'Admin'),
				new FE_Option('2', 'Sklep')
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawControllerSeoData = $this->model->getControllerSeoView($this->registry->core->getParam());
		$controllerseoData = Array(
			'required_data' => Array(
				'controller' => $rawControllerSeoData['name'],
				'enable' => $rawControllerSeoData['enable'],
				'side' => $rawControllerSeoData['mode'],
				'language_data' => $rawControllerSeoData['translation']
			)
		);
		
		$form->Populate($controllerseoData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->updateControllerSeo($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/controllerseo');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}