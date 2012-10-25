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
 * $Id: contentcategory.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class contentcategoryController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('contentcategory');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllContentCategory',
			$this->model,
			'getContentCategoryForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteContentCategory',
			$this->model,
			'doAJAXDeleteContentCategory'
		));
		$this->registry->xajax->registerFunction(array(
			'doAJAXUpdateContentCategory',
			$this->model,
			'doAJAXUpdateContentCategory'
		));
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->Render();
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_contentcategory',
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
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'header',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_IN_HEADER'),
			'default' => '1'
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'footer',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_IN_FOOTER'),
			'default' => '1'
		)));
		
		$categoriesArray = Array();
		$categoriesRaw = $this->model->getContentCategoryALL();
		foreach ($categoriesRaw as $categoryRaw){
			$categoriesArray[$categoryRaw['id']]['name'] = $categoryRaw['contentcategory'];
			$categoriesArray[$categoryRaw['id']]['parent'] = $categoryRaw['parent'];
			$categoriesArray[$categoryRaw['id']]['weight'] = $categoryRaw['hierarchy'];
		}
		
		$requiredData->AddChild(new FE_Tip(Array(
			'direction' => FE_Tip::DOWN,
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_PARENT_CATEGORY_EXAMPLE') . '</p>'
		)));
		
		$requiredData->AddChild(new FE_Tree(Array(
			'name' => 'contentcategoryid',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => true,
			'selectable' => false,
			'sortable' => false,
			'clickable' => false,
			'items' => $categoriesArray
		)));
		
		$metaData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'meta_data',
			'label' => $this->registry->core->getMessage('TXT_META_INFORMATION')
		)));
		
		$languageData = $metaData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'keyword_title',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword_description',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS')
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$event = new sfEvent($this, 'admin.contentcategory.renderForm', Array(
			'form' => &$form
		));
		
		$this->registry->dispatcher->notify($event);
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewContentCategory($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/contentcategory/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/contentcategory');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_contentcategory',
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
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'header',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_IN_HEADER'),
			'default' => '1'
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'footer',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_IN_FOOTER'),
			'default' => '1'
		)));
		
		$categoriesArray = Array();
		$categoriesRaw = $this->model->getContentCategoryALL($this->registry->core->getParam());
		foreach ($categoriesRaw as $categoryRaw){
			$categoriesArray[$categoryRaw['id']]['name'] = $categoryRaw['contentcategory'];
			$categoriesArray[$categoryRaw['id']]['parent'] = $categoryRaw['parent'];
			$categoriesArray[$categoryRaw['id']]['weight'] = $categoryRaw['hierarchy'];
		}
		
		$requiredData->AddChild(new FE_Tip(Array(
			'direction' => FE_Tip::DOWN,
			'tip' => '<p>' . $this->registry->core->getMessage('TXT_PARENT_CATEGORY_EXAMPLE') . '</p>'
		)));
		
		$requiredData->AddChild(new FE_Tree(Array(
			'name' => 'contentcategoryid',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => true,
			'selectable' => false,
			'sortable' => false,
			'clickable' => false,
			'items' => $categoriesArray
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<a href="#" id="reset-parent">' . $this->registry->core->getMessage('TXT_CLEAR_PARENT_CATEGORY') . '</a>'
		)));
		
		$metaData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'meta_data',
			'label' => $this->registry->core->getMessage('TXT_META_INFORMATION')
		)));
		
		$languageData = $metaData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'keyword_title',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword_description',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS')
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$rawContentcategoryData = $this->model->getContentCategoryView($this->registry->core->getParam());
		$contentcategoryData = Array(
			'required_data' => Array(
				'footer' => $rawContentcategoryData['footer'],
				'header' => $rawContentcategoryData['header'],
				'contentcategoryid' => $rawContentcategoryData['contentcategory'],
				'language_data' => $rawContentcategoryData['language']
			),
			'meta_data' => Array(
				'language_data' => $rawContentcategoryData['language']
			),
			'view_data' => Array(
				'view' => $rawContentcategoryData['view']
			)
		);
		
		$event = new sfEvent($this, 'admin.contentcategory.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.contentcategory.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$contentcategoryData[$tab] = $values;
			}
		}
		
		$form->Populate($contentcategoryData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editContentCategory($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/contentcategory');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}