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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: attributeproduct.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class StaticAttributeController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteStaticAttributes',
			App::getModel('staticattribute'),
			'doAJAXDeleteStaticAttributes'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllStaticAttributes',
			App::getModel('staticattribute'),
			'getStaticAttributesForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('staticattribute'),
			'getNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_staticattribute',
			'action' => '',
			'method' => 'post'
		));
		
	$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES_GROUP_DATA')
		)));
		
		$basicLanguageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'staticattributegroupname',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT_GROUP')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS'), 'staticgrouptranslation', 'name', null, Array(
					'column' => 'staticgroupid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$attributesData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'attributes_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES')
		)));
		
		$languageData = $attributesData->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'language_data',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$languages = App::getModel('language')->getLanguageALL();
		foreach ($languages as $language){
			$languageData->AddChild(new FE_TextField(Array(
				'name' => 'name_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_VALUE') . ' (' . $language['translation'] . ')',
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ANSWERS'))
				)
			)));
			
			$languageData->AddChild(new FE_RichTextEditor(Array(
				'name' => 'description_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_DESCRIPTION') . ' (' . $language['translation'] . ')'
			)));
			
			$files = $languageData->AddChild(new FE_LocalFile(Array(
				'name' => 'files_' . $language['id'],
				'label' => 'Plik'. ' (' . $language['translation'] . ')',
				'file_source' => 'design/_images_frontend/staticlogos/',
				'file_types' => Array(
					'jpg',
					'png',
					'gif',
				)
			)));
		}
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('staticattribute')->addAttributeGroup($form->getSubmitValues());
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/staticattribute/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/staticattribute');
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
			'name' => 'edit_staticattribute',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES_GROUP_DATA')
		)));
		
		$basicLanguageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'staticattributegroupname',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT_GROUP')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS'), 'staticgrouptranslation', 'name', null, Array(
					'column' => 'staticgroupid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$attributesData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'attributes_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES')
		)));
		
		$languageData = $attributesData->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'language_data',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$languages = App::getModel('language')->getLanguageALL();
		foreach ($languages as $language){
			$languageData->AddChild(new FE_TextField(Array(
				'name' => 'name_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_VALUE') . ' (' . $language['translation'] . ')',
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ANSWERS'))
				)
			)));
			
			$languageData->AddChild(new FE_RichTextEditor(Array(
				'name' => 'description_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_DESCRIPTION') . ' (' . $language['translation'] . ')'
			)));
			
			$files = $languageData->AddChild(new FE_LocalFile(Array(
				'name' => 'files_' . $language['id'],
				'label' => 'Plik'. ' (' . $language['translation'] . ')',
				'file_source' => 'design/_images_frontend/staticlogos/',
				'file_types' => Array(
					'jpg',
					'png',
					'gif',
				)
			)));
		}
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawAttributeproductData = App::getModel('staticattribute')->getStaticGroupView($this->registry->core->getParam());
		$staticattributeData = Array(
			'required_data' => Array(
				'language_data' => $rawAttributeproductData['language']
			),
			'attributes_data' => Array(
				'language_data' => $rawAttributeproductData['attributes']
			)
		);
		$form->Populate($staticattributeData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('staticattribute')->updateAttribute($form->getSubmitValues(), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/staticattribute');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}