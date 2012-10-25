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
 * $Id: attributeproduct.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class AttributeProductController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteAttributeProducts',
			App::getModel('attributeproduct'),
			'doAJAXDeleteAttributeProducts'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllAttributeProducts',
			App::getModel('attributeproduct'),
			'getAttributeProductsForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('attributeproduct'),
			'getNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_attributeproduct',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES_GROUP_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'attributeproductgroupname',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT_GROUP')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS'), 'attributeproduct', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'attributeproductvalues',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT'))
			),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('attributeproduct')->addAttributeGroup($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/attributeproduct/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/attributeproduct');
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
			'name' => 'edit_attributeproduct',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'attributeproductname',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT_GROUP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT_GROUP')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS'), 'attributeproduct', 'name', null, Array(
					'column' => 'idattributeproduct',
					'values' => $this->registry->core->getParam()
				))
			)
		)));
		
		$attributesData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'attributes_data',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTES_DATA')
		)));
		
		$attributesData->AddChild(new FE_TextField(Array(
			'name' => 'attributeproductvalues',
			'label' => $this->registry->core->getMessage('TXT_ATTRIBUTE_PRODUCT'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ATTRIBUTE_PRODUCT'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawAttributeproductData = App::getModel('attributeproduct')->getAttributeProductName($this->registry->core->getParam());
		$attributeproductData = Array(
			'required_data' => Array(
				'attributeproductname' => $rawAttributeproductData['attributeproductname']
			),
			'attributes_data' => Array(
				'attributeproductvalues' => $rawAttributeproductData['attributes']
			)
		);
		
		$form->Populate($attributeproductData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('attributeproduct')->updateAttribute($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/attributeproduct');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}