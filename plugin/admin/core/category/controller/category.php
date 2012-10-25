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
 * $Id: category.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class categoryController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('category');
		
		$this->registry->xajaxInterface->registerFunction(Array(
			'DeleteCategory',
			$this->model,
			'deleteCategory'
		));
		
		$this->registry->xajaxInterface->registerFunction(Array(
			'AddCategory',
			$this->model,
			'addEmptyCategory'
		));
		
		$this->registry->xajaxInterface->registerFunction(Array(
			'ChangeCategoryOrder',
			$this->model,
			'changeCategoryOrder'
		));
		
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeoCategory',
			App::getModel('seo'),
			'doAJAXCreateSeoCategory'
		));
		
		$this->registry->xajax->registerFunction(array(
			'doAJAXRefreshSeoCategory',
			App::getModel('seo'),
			'doAJAXRefreshSeoCategory'
		));
	}

	public function index ()
	{
		
		$tree = new FE_Form(Array(
			'name' => 'category_tree',
			'class' => 'category-select',
			'action' => '',
			'method' => 'post'
		));
		
		$tree->AddChild(new FE_Tree(Array(
			'name' => 'categories',
			'label' => $this->registry->core->getMessage('TXT_CATEGORIES'),
			'add_item_prompt' => $this->registry->core->getMessage('TXT_ENTER_NEW_CATEGORY_NAME'),
			'sortable' => true,
			'selectable' => false,
			'clickable' => true,
			'deletable' => true,
			'addable' => true,
			'prevent_duplicates' => true,
			'items' => $this->model->getChildCategories(),
			'load_children' => Array(
				$this->model,
				'getChildCategories'
			),
			'onClick' => 'openCategoryEditor',
			'onAdd' => 'xajax_AddCategory',
			'addLabel' => $this->registry->core->getMessage('TXT_ADD_CATEGORY'),
			'onAfterAdd' => 'openCategoryEditor',
			'onDelete' => 'xajax_DeleteCategory',
			'onAfterDelete' => 'openCategoryEditor',
			'onSaveOrder' => 'xajax_ChangeCategoryOrder'
		)));
		
		$tree->AddFilter(new FE_FilterTrim());
		$tree->AddFilter(new FE_FilterNoCode());
		
		$this->registry->template->assign('tree', $tree);
		$this->Render();
	}

	public function edit ()
	{
		
		$rawCategoryData = $this->model->getCategoryView($this->registry->core->getParam());
		
		$tree = new FE_Form(Array(
			'name' => 'category_tree',
			'class' => 'category-select',
			'action' => '',
			'method' => 'post'
		));
		
		$tree->AddChild(new FE_Tree(Array(
			'name' => 'categories',
			'label' => $this->registry->core->getMessage('TXT_CATEGORIES'),
			'add_item_prompt' => $this->registry->core->getMessage('TXT_ENTER_NEW_CATEGORY_NAME'),
			'sortable' => true,
			'selectable' => false,
			'clickable' => true,
			'deletable' => true,
			'addable' => true,
			'prevent_duplicates' => true,
			'items' => $this->model->getChildCategories(0, Array(
				$this->registry->core->getParam()
			)),
			'load_children' => Array(
				$this->model,
				'getChildCategories'
			),
			'onClick' => 'openCategoryEditor',
			'onDuplicate' => 'openCategoryEditorDuplicate',
			'onSaveOrder' => 'xajax_ChangeCategoryOrder',
			'onAdd' => 'xajax_AddCategory',
			'onAfterAdd' => 'openCategoryEditor',
			'onDelete' => 'xajax_DeleteCategory',
			'onAfterDelete' => 'openCategoryEditor',
			'active' => $this->registry->core->getParam()
		)));
		
		$tree->AddFilter(new FE_FilterTrim());
		$tree->AddFilter(new FE_FilterNoCode());
		
		$form = new FE_Form(Array(
			'name' => 'edit_category',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BASIC_INFORMATION')
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
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_SEO_URL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CATEGORY_SEO'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_CATEGORY'),
			'default' => '1'
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'distinction',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY_ORDER')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_PARENT_CATEGORY') . '</p>'
		)));
		
		if ($rawCategoryData['catid']){
			$active = $rawCategoryData['catid'];
		}
		else{
			$active = $this->registry->core->getParam();
		}
		
		$requiredData->AddChild(new FE_Tree(Array(
			'name' => 'categoryid',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'comment' => $this->registry->core->getMessage('TXT_PARENT_CATEGORY_EXAMPLE'),
			'choosable' => true,
			'selectable' => false,
			'sortable' => false,
			'clickable' => false,
			'items' => $this->model->getChildCategories(0, Array(
				$active
			)),
			'load_children' => Array(
				$this->model,
				'getChildCategories'
			),
			'rules' => Array(
				new FE_RuleCustom($this->registry->core->getMessage('ERR_BIND_SELF_PARENT_INVALID'), Array(
					App::getModel('category'),
					'checkParentValue'
				), Array(
					'categoryid' => (int) $this->registry->core->getParam()
				))
			)
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
			'name' => 'keywordtitle',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyworddescription',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$descriptionPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_pane',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$descriptionLanguageData = $descriptionPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'shortdescription',
			'label' => $this->registry->core->getMessage('TXT_SHORTDESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 3000,
			'rows' => 20
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 3000,
			'rows' => 30
		)));
		
		$products = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_products',
			'label' => $this->registry->core->getMessage('TXT_PRODUCTS')
		)));
		
		$products->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_PRODUCTS'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$integrationModels = App::getModel('integration')->getIntegrationModelAll();
		
		foreach ($integrationModels as $key => $model){
			
			if (method_exists(App::getModel('integration/' . $model['model']), 'getChildCategories')){
				
				$t[$model['model']] = $form->AddChild(new FE_Fieldset(Array(
					'name' => $model['model'] . '_data',
					'label' => $model['name']
				)));
				
				$t[$model['model']]->AddChild(new FE_Tree(Array(
					'name' => $model['model'] . 'category',
					'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
					'choosable' => true,
					'selectable' => false,
					'sortable' => false,
					'clickable' => false,
					'items' => App::getModel('integration/' . $model['model'])->getChildCategories(),
					'load_children' => Array(
						App::getModel('integration/' . $model['model']),
						'getChildCategories'
					)
				)));
			}
		
		}
		
		$categoryData = Array(
			'required_data' => Array(
				'language_data' => $rawCategoryData['language'],
				'categoryid' => $rawCategoryData['catid'],
				'distinction' => $rawCategoryData['distinction'],
				'enable' => $rawCategoryData['enable']
			),
			'meta_data' => Array(
				'language_data' => $rawCategoryData['language']
			),
			'description_pane' => Array(
				'language_data' => $rawCategoryData['language']
			),
			'photos_pane' => Array(
				'photo' => $rawCategoryData['photoid']
			),
			'category_products' => Array(
				'products' => App::getModel('category')->getProductsDataGrid((int) $this->registry->core->getParam())
			),
			'view_data' => Array(
				'view' => $rawCategoryData['view']
			)
		);
		
		foreach ($integrationModels as $key => $model){
			if (method_exists(App::getModel('integration/' . $model['model']), 'Populate')){
				$categoryData[$model['model'] . '_data'][$model['model'] . 'category'] = App::getModel('integration/' . $model['model'])->Populate($this->registry->core->getParam());
			}
		}
		
		$event = new sfEvent($this, 'admin.category.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.category.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$categoryData[$tab] = $values;
			}
		}
		
		$form->Populate($categoryData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				$this->model->editCategory($formData, $this->registry->core->getParam());
				
				foreach ($integrationModels as $key => $model){
					if (method_exists(App::getModel('integration/' . $model['model']), 'integrationUpdate')){
						App::getModel('integration/' . $model['model'])->integrationUpdate($formData, $this->registry->core->getParam());
					}
				}
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/category');
		}
		
		$this->registry->template->assign(Array(
			'tree' => $tree,
			'form' => $form,
			'categoryName',
			$rawCategoryData['language'][Helper::getLanguageId()]['name']
		));
		
		$this->Render();
	}

	public function duplicate ()
	{
		
		$rawCategoryData = $this->model->getCategoryView($this->registry->core->getParam());
		
		$tree = new FE_Form(Array(
			'name' => 'category_tree',
			'class' => 'category-select',
			'action' => '',
			'method' => 'post'
		));
		
		$tree->AddChild(new FE_Tree(Array(
			'name' => 'categories',
			'label' => $this->registry->core->getMessage('TXT_CATEGORIES'),
			'add_item_prompt' => $this->registry->core->getMessage('TXT_ENTER_NEW_CATEGORY_NAME'),
			'sortable' => true,
			'selectable' => false,
			'clickable' => true,
			'deletable' => true,
			'addable' => true,
			'prevent_duplicates' => true,
			'items' => $this->model->getChildCategories(0, Array(
				$this->registry->core->getParam()
			)),
			'load_children' => Array(
				$this->model,
				'getChildCategories'
			),
			'onClick' => 'openCategoryEditor',
			'onDuplicate' => 'openCategoryEditorDuplicate',
			'onSaveOrder' => 'xajax_ChangeCategoryOrder',
			'onAdd' => 'xajax_AddCategory',
			'onAfterAdd' => 'openCategoryEditor',
			'onDelete' => 'xajax_DeleteCategory',
			'onAfterDelete' => 'openCategoryEditor',
			'active' => $this->registry->core->getParam()
		)));
		
		$tree->AddFilter(new FE_FilterTrim());
		$tree->AddFilter(new FE_FilterNoCode());
		
		$form = new FE_Form(Array(
			'name' => 'duplicate_category',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BASIC_INFORMATION')
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
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_SEO_URL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CATEGORY_SEO'))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'keywordtitle',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyworddescription',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_CATEGORY'),
			'default' => '1'
		)));
		
		if ($rawCategoryData['catid']){
			$active = $rawCategoryData['catid'];
		}
		else{
			$active = $this->registry->core->getParam();
		}
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'distinction',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY_ORDER')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_PARENT_CATEGORY') . '</p>'
		)));
		
		$requiredData->AddChild(new FE_Tree(Array(
			'name' => 'categoryid',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'comment' => $this->registry->core->getMessage('TXT_PARENT_CATEGORY_EXAMPLE'),
			'choosable' => true,
			'selectable' => false,
			'sortable' => false,
			'clickable' => false,
			'items' => $this->model->getChildCategories(0, Array(
				$active
			)),
			'load_children' => Array(
				$this->model,
				'getChildCategories'
			),
			'rules' => Array(
				new FE_RuleCustom($this->registry->core->getMessage('ERR_BIND_SELF_PARENT_INVALID'), Array(
					App::getModel('category'),
					'checkParentValue'
				), Array(
					'categoryid' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$descriptionPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_pane',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$descriptionLanguageData = $descriptionPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'shortdescription',
			'label' => $this->registry->core->getMessage('TXT_SHORTDESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 3000,
			'rows' => 20
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 3000,
			'rows' => 30
		)));
		
		$products = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_products',
			'label' => $this->registry->core->getMessage('TXT_PRODUCTS')
		)));
		
		$products->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_PRODUCTS'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		foreach ($rawCategoryData['language'] as $languageid => $values){
			$rawCategoryData['language'][$languageid]['name'] = $values['name'] . ' - ' . $this->registry->core->getMessage('TXT_COPY');
			$rawCategoryData['language'][$languageid]['seo'] = $values['seo'] . '-' . strtolower($this->registry->core->getMessage('TXT_COPY'));
		}
		$categoryData = Array(
			'required_data' => Array(
				'language_data' => $rawCategoryData['language'],
				'categoryid' => $rawCategoryData['catid'],
				'distinction' => $rawCategoryData['distinction'] + 1,
				'enable' => $rawCategoryData['enable']
			),
			'description_pane' => Array(
				'language_data' => $rawCategoryData['language']
			),
			'photos_pane' => Array(
				'photo' => $rawCategoryData['photoid']
			),
			'category_products' => Array(
				'products' => App::getModel('category')->getProductsDataGrid((int) $this->registry->core->getParam())
			),
			'view_data' => Array(
				'view' => $rawCategoryData['view']
			)
		);
		
		$event = new sfEvent($this, 'admin.category.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.category.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$categoryData[$tab] = $values;
			}
		}
		
		$form->Populate($categoryData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				
				$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				$this->model->duplicateCategory($formData);
			
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/category');
		}
		
		$this->registry->template->assign(Array(
			'tree' => $tree,
			'form' => $form,
			'categoryName',
			$rawCategoryData['language'][Helper::getLanguageId()]['name']
		));
		
		$this->Render();
	}
}