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
 * $Id: recipientlist.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class recipientlistController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteRecipientList',
			App::getModel('recipientlist'),
			'doAJAXDeleteRecipientList'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllRecipientList',
			App::getModel('recipientlist'),
			'getRecipientListForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('recipientlist')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_recipientlist',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'recipientlist', 'name')
			)
		)));
		
		$clientgroupData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clientgroup_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_GROUPS_LIST')
		)));
		
		$clientgroupData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clientgroup',
			'label' => $this->registry->core->getMessage('TXT_CLIENTGROUPS'),
			'key' => 'idclientgroup',
			'datagrid_init_function' => Array(
				App::getModel('clientgroup'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientGroupDatagridColumns()
		)));
		
		$clientData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'client_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENTS_LIST')
		)));
		
		$clientData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clients',
			'label' => $this->registry->core->getMessage('TXT_CLIENT'),
			'key' => 'idclient',
			'datagrid_init_function' => Array(
				App::getModel('client'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientDatagridColumns()
		)));
		
		$clientnewsletterData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clientnewsletter_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_NEWSLETTER_LIST')
		)));
		
		$clientnewsletterData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clientnewsletter',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_NEWSLETTER'),
			'key' => 'idclientnewsletter',
			'datagrid_init_function' => Array(
				App::getModel('clientnewsletter'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientNewsletterDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('recipientlist')->addNewRecipient($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/recipientlist/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/recipientlist');
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
			'name' => 'edit_recipientlist',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'recipientlist', 'name', null, Array(
					'column' => 'idrecipientlist',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$clientgroupData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clientgroup_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_GROUPS_LIST')
		)));
		
		$clientgroupData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clientgroup',
			'label' => $this->registry->core->getMessage('TXT_CLIENTGROUPS'),
			'key' => 'idclientgroup',
			'datagrid_init_function' => Array(
				App::getModel('clientgroup'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientGroupDatagridColumns()
		)));
		
		$clientData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'client_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENTS_LIST')
		)));
		
		$clientData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clients',
			'label' => $this->registry->core->getMessage('TXT_CLIENT'),
			'key' => 'idclient',
			'datagrid_init_function' => Array(
				App::getModel('client'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientDatagridColumns()
		)));
		
		$clientnewsletterData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'clientnewsletter_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_NEWSLETTER_LIST')
		)));
		
		$clientnewsletterData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clientnewsletter',
			'label' => $this->registry->core->getMessage('TXT_CLIENT_NEWSLETTER'),
			'key' => 'idclientnewsletter',
			'datagrid_init_function' => Array(
				App::getModel('clientnewsletter'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientNewsletterDatagridColumns()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawRecipientListData = App::getModel('recipientlist')->getRecipientListView($this->registry->core->getParam());
		
		$groups = Array();
		foreach ($rawRecipientListData['clientgrouplist'] as $groupKey => $groupValue){
			array_push($groups, $groupValue['clientgroupid']);
		}
		
		$clients = Array();
		foreach ($rawRecipientListData['clientlist'] as $clientKey => $clientValue){
			array_push($clients, $clientValue['clientid']);
		}
		
		$clientnewsletter = Array();
		foreach ($rawRecipientListData['clientnewsletterlist'] as $clientnewsletterKey => $clientnewsletterValue){
			array_push($clientnewsletter, $clientnewsletterValue['clientnewsletterid']);
		}
		$RecipientListData = Array(
			'required_data' => Array(
				'name' => $rawRecipientListData['name']
			),
			'clientgroup_data' => Array(
				'clientgroup' => $groups
			),
			'client_data' => Array(
				'clients' => $clients
			),
			'clientnewsletter_data' => Array(
				'clientnewsletter' => $clientnewsletter
			)
		);
		
		$form->Populate($RecipientListData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('recipientlist')->editRecipientList($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/recipientlist');
		}
		
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	protected function getClientGroupDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idclientgroup',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				)
			),
			Array(
				'id' => 'name',
				'caption' => $this->registry->core->getMessage('TXT_NAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'adddate',
				'caption' => $this->registry->core->getMessage('TXT_DATE'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			)
		);
	}

	protected function getClientDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idclient',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				)
			),
			Array(
				'id' => 'firstname',
				'caption' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'surname',
				'caption' => $this->registry->core->getMessage('TXT_SURNAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'groupname',
				'caption' => $this->registry->core->getMessage('TXT_GROUPS_CLIENT'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'adddate',
				'caption' => $this->registry->core->getMessage('TXT_DATE'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			)
		);
	}

	protected function getClientNewsletterDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idclientnewsletter',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				)
			),
			Array(
				'id' => 'email',
				'caption' => $this->registry->core->getMessage('TXT_EMAIL'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'active',
				'caption' => $this->registry->core->getMessage('TXT_ACTIVE'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			)
		);
	}
}