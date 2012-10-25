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
 * $Id: transmailtemplates.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class transmailtemplatesController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteTransmail',
			App::getModel('transmailtemplates'),
			'doAJAXDeleteTransmail'
		));
		$this->registry->xajax->registerFunction(array(
			'doRefreshTransmail',
			App::getModel('transmailtemplates'),
			'doAJAXRefreshTransmail'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllTransmail',
			App::getModel('transmailtemplates'),
			'getTransmailForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'setDefaultTransMailTemplate',
			App::getModel('transmailtemplates'),
			'setAJAXDefaultTransMailTemplate'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('transmailtemplates')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		//FIXME: rozwiązać problem sztywnego ustawienia identyfikatora akcji
		$form = new FE_Form(Array(
			'name' => 'add_transmailtemplates',
			'action' => '',
			'method' => 'post'
		));
		
		$mainData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'transmail', 'name')
			)
		)));
		
		$action = $mainData->AddChild(new FE_Select(Array(
			'name' => 'action',
			'label' => $this->registry->core->getMessage('TXT_ACTION'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailActionAllToSelect())
		)));
		
		$mainData->AddChild(new FE_Checkbox(Array(
			'name' => 'active',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('19')))
			)
		)));
		
		$mainData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center"><strong>Wpisz nazwę pliku szablonu</strong> <br />
						 Wprowadź nazwę pliku szablonu bez kropki i rozszerzenia. <br /> 
						 W nazwie nie mogą występować znaki <strong> spacji tabulacji . , \ / : ; ? * < > { } ( ) & ^ % $ # @ ! </strong><br />
						 Możliwe do użycia są wyłącznie znaki alfanumeryczne </p>',
			'direction' => FE_Tip::DOWN,
			'short_tip' => '<p align="center"><strong>Wpisz nazwę pliku szablonu</strong></p>',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $action, new FE_ConditionNot(new FE_ConditionEquals('19')))
			)
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'filename',
			'label' => $this->registry->core->getMessage('TXT_FILE_NAME'),
			'comment' => 'Wpisz nazwę pliku. Niedozwolone znaki spacji / \ . * : ; , " < > ',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_FILE_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_FILE_NAME_ALREADY_EXISTS'), 'transmail', 'filename'),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT'), '/^([A-Za-z0-9_-])*$/')
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $action, new FE_ConditionNot(new FE_ConditionEquals('19')))
			)
		)));
		
		$contentData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'content_data',
			'label' => $this->registry->core->getMessage('TXT_CONTENT')
		)));
		
		$transmailheader = $contentData->AddChild(new FE_Select(Array(
			'name' => 'transmailheaderid',
			'label' => $this->registry->core->getMessage('TXT_HEADERS_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailHeaderAllToSelect())
		)));
		
		$transmailfooter = $contentData->AddChild(new FE_Select(Array(
			'name' => 'transmailfooterid',
			'label' => $this->registry->core->getMessage('TXT_FOOTERS_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailFooterAllToSelect())
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'textform',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_TEXT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEXT'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('0')))
			)
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'htmlform',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 80,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('0')))
			)
		)));
		
		$tags = $contentData->AddChild(new FE_StaticListing(Array(
			'name' => 'tags',
			'title' => 'Lista tagów możliwych do użycia',
			'values' => Array(
				new FE_ListItem('{$clientdata.firstname}', $this->registry->core->getMessage('TXT_FIRSTNAME'))
			)
		)));
		App::getRegistry()->xajaxInterface->registerFunction(array(
			'GetAllTagsForThisAction',
			App::getModel('transmailtemplates'),
			'GetAllTagsForThisAction'
		));
		$tags->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $action, 'ChangeTagsForThisAction'));
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('transmailtemplates')->addNewTransMailTemplate($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/transmailtemplates/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/transmailtemplates');
			}
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		//FIXME: rozwiązać problem sztywnego ustawienia identyfikatora akcji
		

		$rawTransMailData = App::getModel('transmailtemplates')->getTransMailToEdit($this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_transmailtemplates',
			'action' => '',
			'method' => 'post'
		));
		
		$mainData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'transmail', 'name', null, Array(
					'column' => 'idtransmail',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$action = $mainData->AddChild(new FE_Select(Array(
			'name' => 'action',
			'label' => $this->registry->core->getMessage('TXT_ACTION'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailActionAllToSelect())
		)));
		
		$mainData->AddChild(new FE_Checkbox(Array(
			'name' => 'active',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('19')))
			)
		)));
		
		if (isset($rawTransMailData['filename']) && ! empty($rawTransMailData['filename'])){
			$mainData->AddChild(new FE_StaticText(Array(
				'text' => '<p align="center">' . $this->registry->core->getMessage('TXT_FILE_NAME') . ': <strong>' . $rawTransMailData['filename'] . '.tpl</strong></p>',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionEquals('19'))
				)
			)));
		}
		
		$contentData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'content_data',
			'label' => $this->registry->core->getMessage('TXT_CONTENT')
		)));
		
		$transmailheader = $contentData->AddChild(new FE_Select(Array(
			'name' => 'transmailheaderid',
			'label' => $this->registry->core->getMessage('TXT_HEADERS_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailHeaderAllToSelect())
		)));
		
		$transmailfooter = $contentData->AddChild(new FE_Select(Array(
			'name' => 'transmailfooterid',
			'label' => $this->registry->core->getMessage('TXT_FOOTERS_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('transmailtemplates')->getTransMailFooterAllToSelect())
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenttxt',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_TEXT'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('0')))
			)
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenthtml',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 80,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $action, new FE_ConditionNot(new FE_ConditionEquals('0')))
			)
		)));
		
		$tags = $contentData->AddChild(new FE_StaticListing(Array(
			'name' => 'tags',
			'title' => 'Lista tagów możliwych do użycia',
			'values' => Array()//	new FE_ListItem('{$clientdata.firstname}', $this->registry->core->getMessage('TXT_FIRSTNAME'))
			
		)));
		App::getRegistry()->xajaxInterface->registerFunction(array(
			'GetAllTagsForThisAction',
			App::getModel('transmailtemplates'),
			'GetAllTagsForThisAction'
		));
		$tags->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $action, 'ChangeTagsForThisAction'));
		
		if (! empty($rawTransMailData)){
			$transMailData = Array(
				'main_data' => Array(
					'name' => $rawTransMailData['name'],
					'action' => $rawTransMailData['transmailactionid']
				),
				'content_data' => Array(
					'contenttxt' => $rawTransMailData['contenttxt'],
					'contenthtml' => $rawTransMailData['contenthtml']
				)
			);
			if ($rawTransMailData['transmailheaderid'] > 0){
				$transMailData['content_data']['transmailheaderid'] = $rawTransMailData['transmailheaderid'];
			}
			else{
				$transMailData['content_data']['transmailheaderid'] = 0;
			}
			if ($rawTransMailData['transmailfooterid'] > 0){
				$transMailData['content_data']['transmailfooterid'] = $rawTransMailData['transmailfooterid'];
			}
			else{
				$transMailData['content_data']['transmailfooterid'] = 0;
			}
			if ($rawTransMailData['active'] == 0 && ! empty($rawTransMailData['filename'])){
				$transMailData['main_data']['filename'] = $rawTransMailData['filename'];
			}
			else{
				$transMailData['main_data']['active'] = $rawTransMailData['active'];
			}
			$form->Populate($transMailData);
		}
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('transmailtemplates')->editTransMailTemplate($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__ . '/transmailtemplates');
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}