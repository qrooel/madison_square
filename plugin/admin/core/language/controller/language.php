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
 * $Id: language.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class LanguageController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteLanguage',
			App::getModel('language'),
			'doAJAXDeleteLanguage'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllLanguage',
			App::getModel('language'),
			'getLanguageForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('language')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_language',
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
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': en_EN',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'language', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': TXT_ENGLISH',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_TRANSLATION_ALREADY_EXISTS'), 'language', 'translation')
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'copylanguage',
			'label' => $this->registry->core->getMessage('TXT_COPY_FROM_LANGUAGE'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('language')->getLanguageALLToSelect()),
			'default' => 0
		)));
		
		$requiredData->AddChild(new FE_LocalFile(Array(
			'name' => 'translations',
			'label' => 'Plik z tłumaczeniem',
			'file_source' => 'upload/',
			'file_types' => Array(
				'xml'
			)
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$currencyData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'currency_data',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DATA')
		)));
		
		$currencyData->AddChild(new FE_Select(Array(
			'name' => 'currencyid',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_LANGUAGE_CURRENCY'),
			'options' => FE_Option::Make($currencies)
		)));
		
		$flagPane = $form->addChild(new FE_Fieldset(Array(
			'name' => 'flag_pane',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_FLAG')
		)));
		
		$flagPane->AddChild(new FE_LocalFile(Array(
			'name' => 'flag',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_FLAG'),
			'file_source' => 'design/_images_common/icons/languages/',
			'file_types' => Array(
				'png'
			)
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('language')->addNewLanguage($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/language/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/language');
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
			'name' => 'edit_language',
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
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': en_EN',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'language', 'name', null, Array(
					'column' => 'idlanguage',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': TXT_ENGLISH',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION'))
			)
		)));
		
		$requiredData->AddChild(new FE_LocalFile(Array(
			'name' => 'translations',
			'label' => 'Plik z tłumaczeniem',
			'file_source' => 'upload/',
			'file_types' => Array(
				'xml'
			)
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$currencyData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'currency_data',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DATA')
		)));
		
		$currencyData->AddChild(new FE_Select(Array(
			'name' => 'currencyid',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_LANGUAGE_CURRENCY'),
			'options' => FE_Option::Make($currencies)
		)));
		
		$flagPane = $form->addChild(new FE_Fieldset(Array(
			'name' => 'flag_pane',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_FLAG')
		)));
		
		$flagPane->AddChild(new FE_LocalFile(Array(
			'name' => 'flag',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_FLAG'),
			'file_source' => 'design/_images_common/icons/languages/',
			'file_types' => Array(
				'png'
			)
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawLanguageData = App::getModel('language')->getLanguageView($this->registry->core->getParam());
		$languageData = Array(
			'required_data' => Array(
				'name' => $rawLanguageData['name'],
				'translation' => $rawLanguageData['translation']
			),
			'currency_data' => Array(
				'currencyid' => $rawLanguageData['currencyid']
			),
			'flag_pane' => Array(
				'flag' => $rawLanguageData['flag']
			),
			'view_data' => Array(
				'view' => $rawLanguageData['view']
			)
		);
		
		$form->Populate($languageData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('language')->editLanguage($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/language');
		
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}