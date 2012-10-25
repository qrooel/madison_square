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
 * $Id: transmailfooter.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class transmailfooterController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteTransmailfooter',
			App::getModel('transmailfooter'),
			'doAJAXDeleteTransmailfooter'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllTransmailfooter',
			App::getModel('transmailfooter'),
			'getTransmailfooterForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('transmailfooter')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_transmailfooter',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'transmailfooter', 'name')
			)
		)));
		
		$contentData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'content_data',
			'label' => $this->registry->core->getMessage('TXT_CONTENT')
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenttxt',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_TEXT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEXT'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000 znaków.',
			'max_length' => 5000,
			'rows' => 10
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenthtml',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000 znaków.' . 'Tutaj umieść informacje o stylach i nagłówkach HTML.',
			'max_length' => 5000,
			'rows' => 80
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('transmailfooter')->addNewTransMailfooter($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/transmailfooter/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/transmailfooter');
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
			'name' => 'edit_transmailfooter',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'transmailfooter', 'name', null, Array(
					'column' => 'idtransmailfooter',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$contentData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'content_data',
			'label' => $this->registry->core->getMessage('TXT_CONTENT')
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenttxt',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_TEXT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEXT'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 10
		)));
		
		$contentData->AddChild(new FE_Textarea(Array(
			'name' => 'contenthtml',
			'label' => $this->registry->core->getMessage('TXT_CONTENT') . ' ' . $this->registry->core->getMessage('TXT_HTML'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_HTML'))
			),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000 znaków.' . 'Tutaj umieść informacje o stylach i nagłówkach HTML.',
			'max_length' => 5000,
			'rows' => 80
		)));
		
		$rawTransMailfooterData = App::getModel('transmailfooter')->getTransMailfooterToEdit($this->registry->core->getParam());
		if (! empty($rawTransMailfooterData)){
			$transMailfooterData = Array(
				'main_data' => Array(
					'name' => $rawTransMailfooterData['name']
				),
				'content_data' => Array(
					'contenttxt' => $rawTransMailfooterData['contenttxt'],
					'contenthtml' => $rawTransMailfooterData['contenthtml']
				)
			);
		}
		$form->Populate($transMailfooterData);
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('transmailfooter')->editTransMailfooter($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__ . '/transmailfooter');
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}