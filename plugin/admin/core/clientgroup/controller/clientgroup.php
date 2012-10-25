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
 * $Id: clientgroup.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class ClientGroupController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('clientGroup');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteClientGroup',
			$this->model,
			'doAJAXDeleteClientGroup'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllClientGroup',
			$this->model,
			'getClientGroupForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		
		$this->Render();
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_clientgroup',
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
			'label' => $this->registry->core->getMessage('TXT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_GROUP_NAME_ALREADY_EXISTS'), 'clientgrouptranslation', 'name')
			)
		)));
		
		$clientsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clients_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENTS_SELECTION')
		)));
		
		$clientsData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clients',
			'label' => $this->registry->core->getMessage('TXT_SELECT_CLIENTS'),
			'key' => 'idclient',
			'datagrid_init_function' => Array(
				App::getModel('client/client'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addClientGroup($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/clientgroup/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/clientgroup');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		$form = new FE_Form(Array(
			'name' => 'edit_clientgroup',
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
			'label' => $this->registry->core->getMessage('TXT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_GROUP_NAME_ALREADY_EXISTS'), 'clientgrouptranslation', 'name', null, Array(
					'column' => 'clientgroupid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$clientsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clients_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENTS_SELECTION')
		)));
		
		$clientsData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clients',
			'label' => $this->registry->core->getMessage('TXT_SELECT_CLIENTS'),
			'key' => 'idclient',
			'datagrid_init_function' => Array(
				App::getModel('client/client'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawClientgroupData = $this->model->getClientGroupById($this->registry->core->getParam());
		$clientgroupData = Array(
			'required_data' => Array(
				'language_data' => $rawClientgroupData['language']
			),
			'clients_data' => Array(
				'clients' => Array(
					'11',
					'13'
				)
			)
		);
		
		$form->Populate($clientgroupData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editClientGroup($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/clientgroup');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	protected function getClientDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idclient',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_BETWEEN
				)
			),
			Array(
				'id' => 'clientorder',
				'caption' => $this->registry->core->getMessage('TXT_CLIENTORDER_VALUE'),
				'appearance' => Array(
					'width' => 60,
					'visible' => false,
					'align' => FE_DatagridSelect::ALIGN_RIGHT
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_BETWEEN
				)
			),
			Array(
				'id' => 'firstname',
				'caption' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
				'appearance' => Array(
					'width' => 160,
					'align' => FE_DatagridSelect::ALIGN_LEFT
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_INPUT
				)
			),
			Array(
				'id' => 'surname',
				'caption' => $this->registry->core->getMessage('TXT_SURNAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO,
					'align' => FE_DatagridSelect::ALIGN_LEFT
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_INPUT
				)
			),
			Array(
				'id' => 'email',
				'caption' => $this->registry->core->getMessage('TXT_EMAIL'),
				'appearance' => Array(
					'width' => 140,
					'align' => FE_DatagridSelect::ALIGN_LEFT,
					'visible' => false
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_INPUT
				)
			),
			Array(
				'id' => 'phone',
				'caption' => $this->registry->core->getMessage('TXT_PHONE'),
				'appearance' => Array(
					'width' => 110
				),
				'filter' => Array(
					'type' => FE_DatagridSelect::FILTER_INPUT
				)
			)
		);
	}
}