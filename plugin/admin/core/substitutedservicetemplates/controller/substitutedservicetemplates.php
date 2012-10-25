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
 * $Id: substitutedservicetemplates.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SubstitutedservicetemplatesController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('substitutedservicetemplates');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteNotificationTempl',
			$this->model,
			'doAJAXDeleteNotificationTempl'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllNotificationTempl',
			$this->model,
			'getNotificationTemplForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_substitutedservicetemplates',
			'action' => '',
			'method' => 'post'
		));
		
		$mainData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_TEMPLATE_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'transmail', 'name')
			)
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'title',
			'label' => $this->registry->core->getMessage('TXT_MAIL_TITLE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TITLE'))
			)
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'filename',
			'label' => $this->registry->core->getMessage('TXT_FILE_NAME_TEMPLATE'),
			'comment' => 'Wprowadź nazwę pliku bez rozszerzenia. Dozwolone znaki alfanumeryczne. Niedozwolone: < > | { } [ ] % $ # @ & * / \ ? ; ,',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FILE_NAME')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT'), '/^([A-Za-z0-9_])*$/'),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_FILE_NAME_ALREADY_EXISTS'), 'transmail', 'filename')
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
			'name' => 'htmlform',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 8000
		)));
		
		$contentData->AddChild(new FE_StaticListing(Array(
			'name' => 'tags',
			'title' => $this->registry->core->getMessage('TXT_LIST_OF_TAGS_FOR_USE'),
			'values' => FE_ListItem::Make($this->model->GetAllTagsForThisNotificationAction())
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('substitutedservicetemplates')->addNewNotificationTemplTemplate($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/substitutedservicetemplates/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/substitutedservicetemplates');
			}
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$rawNotificationData = $this->model->getSubstitutedServiceTemplToEdit($this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_substitutedservicetemplates',
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
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'title',
			'label' => $this->registry->core->getMessage('TXT_TITLE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TITLE'))
			)
		)));
		
		if (isset($rawNotificationData['filename'])){
			$mainData->AddChild(new FE_StaticText(Array(
				'text' => '<p align="center"><strong>' . $this->registry->core->getMessage('TXT_FILE_NAME_TEMPLATE') . ':</strong> <i>' . $rawNotificationData['filename'] . '.tpl</i></p>'
			)));
		}
		else{
			$mainData->AddChild(new FE_TextField(Array(
				'name' => 'filename',
				'label' => $this->registry->core->getMessage('TXT_FILE_NAME_TEMPLATE'),
				'comment' => 'Wprowadź nazwę pliku bez rozszerzenia. Dozwolone znaki alfanumeryczne. Niedozwolone: < > | { } [ ] % $ # @ & * / \ ? ; ,',
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FILE_NAME')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT'), '/^([A-Za-z0-9_])*$/'),
					new FE_RuleUnique($this->registry->core->getMessage('ERR_FILE_NAME_ALREADY_EXISTS'), 'transmail', 'filename')
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
			'name' => 'contenthtml',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 8000
		)));
		
		$contentData->AddChild(new FE_StaticListing(Array(
			'name' => 'tags',
			'title' => $this->registry->core->getMessage('TXT_LIST_OF_TAGS_FOR_USE'),
			'values' => FE_ListItem::Make($this->model->GetAllTagsForThisNotificationAction())
		)));
		
		if (! empty($rawNotificationData)){
			$notificationData = Array(
				'main_data' => Array(
					'name' => $rawNotificationData['name'],
					'title' => $rawNotificationData['title']
				),
				'content_data' => Array(
					'contenthtml' => $rawNotificationData['contenthtml']
				)
			);
			if ($rawNotificationData['transmailheaderid'] > 0){
				$notificationData['content_data']['transmailheaderid'] = $rawNotificationData['transmailheaderid'];
			}
			else{
				$notificationData['content_data']['transmailheaderid'] = 0;
			}
			if ($rawNotificationData['transmailfooterid'] > 0){
				$notificationData['content_data']['transmailfooterid'] = $rawNotificationData['transmailfooterid'];
			}
			else{
				$notificationData['content_data']['transmailfooterid'] = 0;
			}
			$form->Populate($notificationData);
		}
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('substitutedservicetemplates')->editSubstitutedServiceTempl($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__ . '/substitutedservicetemplates');
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}