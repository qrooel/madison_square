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
 * $Id: sitemaps.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SitemapsController extends Controller
{

	public function index ()
	{
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllSitemaps',
			App::getModel('sitemaps'),
			'getSitemapsForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteSitemaps',
			App::getModel('sitemaps'),
			'doAJAXDeleteSitemaps'
		));
		$this->registry->xajax->registerFunction(array(
			'refreshSitemaps',
			APP::getModel('sitemaps'),
			'doAJAXRefreshSitemaps'
		));
		
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('datagrid_filter', App::getModel('sitemaps')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_sitemaps',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_SITEMAPS_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SITEMAPS_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'pingserver',
			'label' => $this->registry->core->getMessage('TXT_SITEMAPS_PINGSERVER'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SITEMAPS_PINGSERVER'))
			)
		)));
		
		$settingsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'settings_data',
			'label' => $this->registry->core->getMessage('TXT_SETTINGS'),
		)));
		
		$publishforcategories = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforcategories',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_CATEGORIES'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforcategories',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_CATEGORIES'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TXT_PRIORITY_FOR_CATEGORIES'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforcategories, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforproducts = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforproducts',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PRODUCTS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforproducts',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PRODUCTS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PRODUCTS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforproducts, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforproducers = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforproducers',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PRODUCERS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforproducers',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PRODUCERS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PRODUCERS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforproducers, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishfornews = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishfornews',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_NEWS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityfornews',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_NEWS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_NEWS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishfornews, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforpages = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforpages',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PAGES'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforpages',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PAGES'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PAGES'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforpages, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('sitemaps')->addSitemaps($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/sitemaps/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/sitemaps');
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
			'name' => 'edit_sitemaps',
			'action' => '',
			'method' => 'post',
			'store_selector' => true
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_SITEMAPS_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SITEMAPS_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'pingserver',
			'label' => $this->registry->core->getMessage('TXT_SITEMAPS_PINGSERVER'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SITEMAPS_PINGSERVER'))
			)
		)));
		
		$settingsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'settings_data',
			'label' => $this->registry->core->getMessage('TXT_SETTINGS'),
		)));
		
		$publishforcategories = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforcategories',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_CATEGORIES'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforcategories',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_CATEGORIES'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TXT_PRIORITY_FOR_CATEGORIES'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforcategories, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforproducts = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforproducts',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PRODUCTS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforproducts',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PRODUCTS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PRODUCTS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforproducts, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforproducers = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforproducers',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PRODUCERS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforproducers',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PRODUCERS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PRODUCERS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforproducers, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishfornews = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishfornews',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_NEWS'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityfornews',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_NEWS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_NEWS'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishfornews, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$publishforpages = $settingsData->AddChild(new FE_Checkbox(Array(
			'name' => 'publishforpages',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH_FOR_PAGES'),
			'default' => '1'
		)));
		
		$settingsData->AddChild(new FE_TextField(Array(
			'name' => 'priorityforpages',
			'label' => $this->registry->core->getMessage('TXT_PRIORITY_FOR_PAGES'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/'),
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRIORITY_FOR_PAGES'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.5',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $publishforpages, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawSitemapsData = App::getModel('sitemaps')->getSitemapsView($this->registry->core->getParam());
		
		$sitemapsData = Array(
			'required_data' => Array(
				'name' => $rawSitemapsData['name'],
				'pingserver' => $rawSitemapsData['pingserver']
			),
			'settings_data' => Array(
				'publishforcategories' => $rawSitemapsData['publishforcategories'],
				'priorityforcategories' => $rawSitemapsData['priorityforcategories'],
				'publishforproducts' => $rawSitemapsData['publishforproducts'],
				'priorityforproducts' => $rawSitemapsData['priorityforproducts'],
				'publishforproducers' => $rawSitemapsData['publishforproducers'],
				'priorityforproducers' => $rawSitemapsData['priorityforproducers'],
				'publishfornews' => $rawSitemapsData['publishfornews'],
				'priorityfornews' => $rawSitemapsData['priorityfornews'],
				'publishforpages' => $rawSitemapsData['publishforpages'],
				'priorityforpages' => $rawSitemapsData['priorityforpages']
			)
		);
		
		$form->Populate($sitemapsData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('sitemaps')->editSitemaps($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/sitemaps');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}