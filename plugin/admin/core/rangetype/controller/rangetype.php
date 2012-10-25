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
 * $Id: rangetype.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class rangetypeController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllRangeType',
			App::getModel('rangetype'),
			'getRangeTypeForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteRangeType',
			App::getModel('rangetype'),
			'doAJAXDeleteRangeType'
		));
		$this->registry->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren',
			App::getModel('product'),
			'loadCategoryChildren'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('rangetype')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_rangetype',
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
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'rangetypetranslation', 'name')
			)
		)));
		
		$categoryData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_data',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY_DATA')
		)));
		
		$categoryData->AddChild(new FE_Tree(Array(
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
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('rangetype')->addNewRangeType($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/rangetype/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/rangetype');
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
			'name' => 'edit_rangetype',
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
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'rangetypetranslation', 'name', null, Array(
					'column' => 'rangetypeid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$categoryData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_data',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY_DATA')
		)));
		
		$rawRangetypeData = App::getModel('rangetype')->getRangeTypeView($this->registry->core->getParam());
		
		$categoryData->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => false,
			'selectable' => true,
			'sortable' => false,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(0, $rawRangetypeData['rangetypecategorys']),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rangetypeData = Array(
			'required_data' => Array(
				'language_data' => $rawRangetypeData['language']
			),
			'category_data' => Array(
				'category' => $rawRangetypeData['rangetypecategorys']
			)
		);
		
		$form->Populate($rangetypeData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('rangetype')->editRangeType($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/rangetype');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}