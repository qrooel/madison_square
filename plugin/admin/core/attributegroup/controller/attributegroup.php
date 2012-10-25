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
 * $Id: attributegroup.php 655 2012-04-24 08:51:44Z gekosale $
 */

class attributegroupController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('attributegroup');
		$this->registry->xajaxInterface->registerFunction(Array(
			'AddGroup',
			$this->model,
			'addEmptyGroup'
		));
	}

	public function index ()
	{
		$this->registry->template->assign('existingGroups', App::getModel('attributegroup')->getAllAttributeGroupName());
		$this->Render();
	}

	public function edit ()
	{
		
		$this->registry->xajaxInterface->registerFunction(Array(
			'DeleteGroup',
			$this->model,
			'deleteGroup'
		));
		
		$form = new FE_Form(Array(
			'name' => 'edit_attributegroup',
			'action' => '',
			'method' => 'post',
			'class' => 'attributeGroupEditor'
		));
		
		$groupData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'group_data',
			'class' => 'group-data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_GROUP_DATA')
		)));
		
		$groupData->AddChild(new FE_TextField(Array(
			'name' => 'attributegroupname',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_GROUP_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS'), 'attributegroupname', 'name', null, Array(
					'column' => 'idattributegroupname',
					'values' => $this->registry->core->getParam()
				))
			)
		)));
		
		$category = $groupData->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => false,
			'selectable' => true,
			'sortable' => false,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$attributeData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'attribute_data',
			'class' => 'attribute-data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES')
		)));
		
		$attributeData->AddChild(new FE_AttributeEditor(Array(
			'name' => 'attributes',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES'),
			'set' => $this->registry->core->getParam()
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawAttributeGroupData = $this->model->getGroup($this->registry->core->getParam());
		
		$attributeGroupData = Array(
			'group_data' => Array(
				'attributegroupname' => $rawAttributeGroupData['name'],
				'category' => $rawAttributeGroupData['category']
			),
			'attribute_data' => Array(
				'attributes' => $rawAttributeGroupData['attributes']
			)
		);
		
		$form->Populate($attributeGroupData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editAttributeGroup($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/attributegroup/edit/' . $this->registry->core->getParam());
		}
		
		$this->registry->template->assign(Array(
			'currentGroup' => $rawAttributeGroupData,
			'existingGroups' => $this->model->getAllAttributeGroupName(),
			'form' => $form
		));
		
		$this->Render();
	}
}