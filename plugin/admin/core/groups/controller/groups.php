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
 * $Id: groups.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class GroupsController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteGroup',
			App::getModel('groups'),
			'doAJAXDeleteGroups'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllGroups',
			App::getModel('groups'),
			'getGroupsForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('groups'),
			'getNameForAjax'
		));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('datagrid_filter', App::getModel('groups')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$controllers = Array();
		$controllersRaw = App::getModel('groups')->getFullPermission();
		foreach ($controllersRaw as $controller){
			$controllers[] = Array(
				'name' => $controller['name'],
				'id' => $controller['id']
			);
		}
		
		$actions = Array();
		$actionsRaw = $this->registry->right->getRightsToSmarty();
		foreach ($actionsRaw as $right){
			$actions[] = Array(
				'name' => $right['name'],
				'id' => $right['value']
			);
		}
		
		$form = new FE_Form(Array(
			'name' => 'add_group',
			'action' => '',
			'method' => 'post'
		));
		
		$basicData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'basic_data',
			'label' => $this->registry->core->getMessage('TXT_BASIC_GROUP_DATA')
		)));
		
		$basicData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_DUPLICATE_GROUP_NAME'), '`group`', 'name')
			)
		)));
		
		$rightsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'rights_data',
			'label' => $this->registry->core->getMessage('TXT_RIGHTS')
		)));
		
		$rightsData->AddChild(new FE_RightsTable(Array(
			'name' => 'rights',
			'label' => $this->registry->core->getMessage('TXT_GROUP_RIGHTS'),
			'controllers' => $controllers,
			'actions' => $actions
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('groups')->add($form->getSubmitValues());
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/groups/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/groups');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$controllers = Array();
		$controllersRaw = App::getModel('groups')->getFullPermission();
		
		foreach ($controllersRaw as $controller){
			$controllers[] = Array(
				'name' => $controller['name'],
				'id' => $controller['id']
			);
		}
		
		$actions = Array();
		$actionsRaw = $this->registry->right->getRightsToSmarty();
		foreach ($actionsRaw as $right){
			$actions[] = Array(
				'name' => $right['name'],
				'id' => $right['value']
			);
		}
		
		$form = new FE_Form(Array(
			'name' => 'edit_group',
			'action' => '',
			'method' => 'post'
		));
		
		$basicData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'basic_data',
			'label' => $this->registry->core->getMessage('TXT_BASIC_GROUP_DATA')
		)));
		
		$basicData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUP_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_DUPLICATE_GROUP_NAME'), '`group`', 'name', null, Array(
					'column' => 'idgroup',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$rightsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'rights_data',
			'label' => $this->registry->core->getMessage('TXT_RIGHTS')
		)));
		
		$rightsData->AddChild(new FE_RightsTable(Array(
			'name' => 'rights',
			'label' => $this->registry->core->getMessage('TXT_GROUP_RIGHTS'),
			'controllers' => $controllers,
			'actions' => $actions
		)));
		
		$rawGroupData = App::getModel('groups')->getGroupsView((int) $this->registry->core->getParam());
		$rightsData = Array();
		foreach ($controllersRaw as $controller){
			$mask = 1;
			$rights = Array();
			for ($i = 0; $i < count($actions); $i ++){
				$rights[$actions[$i]['id']] = ($controller['permission'] & $mask) ? 1 : 0;
				$mask = $mask << 1;
			}
			$rightsData[$controller['id']] = $rights;
		}
		
		$groupData = Array(
			'basic_data' => Array(
				'name' => $rawGroupData['name']
			),
			'rights_data' => Array(
				'rights' => $rightsData
			)
		);
		
		$rightsData = Array();
		foreach ($controllersRaw as $controller){
			$mask = 1;
			$rights = Array();
			for ($i = 0; $i < count($actions); $i ++){
				$rights[$actions[$i]['id']] = ($controller['permission'] & $mask) ? 1 : 0;
				$mask = $mask << 1;
			}
			$rightsData[$controller['id']] = $rights;
		}
		
		$form->Populate($groupData);
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('groups')->editPermission($form->getSubmitValues(), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__ . '/groups');
		
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	
	}
}