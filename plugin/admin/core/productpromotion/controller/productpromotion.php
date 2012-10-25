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
 * $Revision: 263 $
 * $Author: gekosale $
 * $Date: 2011-07-24 16:23:40 +0200 (N, 24 lip 2011) $
 * $Id: productpromotion.php 263 2011-07-24 14:23:40Z gekosale $ 
 */

class productpromotionController extends Controller
{

	public function index ()
	{
		$this->registry->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren',
			App::getModel('product'),
			'loadCategoryChildren'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllProductPromotion',
			App::getModel('productpromotion'),
			'getProductPromotionForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('productpromotion'),
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteProductPromotion',
			App::getModel('productpromotion'),
			'doAJAXDeleteProductPromotion'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('productpromotion')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_promotion',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS')
		)));
		
		$productid = $requiredData->AddChild(new FE_ProductSelect(Array(
			'name' => 'productid',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('TXT_SELECT_PRODUCTS'))
			),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$pricePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'discount_pane',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNT')
		)));
		
		$standardPrice = $pricePane->AddChild(new FE_Fieldset(Array(
			'name' => 'standard_price',
			'label' => $this->registry->core->getMessage('TXT_STANDARD_SELLPRICE')
		)));
		
		$enablePromotion = $standardPrice->AddChild(new FE_Checkbox(Array(
			'name' => 'promotion',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PROMOTION'),
			'default' => '0'
		)));
		
		$standardPrice->AddChild(new FE_TextField(Array(
			'name' => 'discount',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNT'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^([0-9]*\.*([0-9]{1,2})|(\0)?)$/')
			),
			'default' => '0.00',
			'suffix' => '%',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $enablePromotion, new FE_ConditionEquals(1))
			)
		)));
		
		$standardPrice->AddChild(new FE_Date(Array(
			'name' => 'promotionstart',
			'label' => $this->registry->core->getMessage('TXT_START_DATE'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $enablePromotion, new FE_ConditionEquals(1))
			)
		)));
		
		$standardPrice->AddChild(new FE_Date(Array(
			'name' => 'promotionend',
			'label' => $this->registry->core->getMessage('TXT_END_DATE'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $enablePromotion, new FE_ConditionEquals(1))
			)
		)));
		
		$clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
		
		foreach ($clientGroups as $clientGroup){
			
			$pricePane->AddChild(new FE_Fieldset(Array(
				'name' => 'field_' . $clientGroup['id'],
				'label' => $clientGroup['name']
			)));
			
			$promotion[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'promotion_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_CLIENTGROUP_PROMOTION'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_TextField(Array(
				'name' => 'discount_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_DISCOUNT'),
				'rules' => Array(
					new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^([0-9]*\.*([0-9]{1,2})|(\0)?)$/')
				),
				'default' => '0.00',
				'suffix' => '%',
				'filters' => Array(
					new FE_FilterCommaToDotChanger()
				),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $promotion[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1)))
				)
			)));
			
			$pricePane->AddChild(new FE_Date(Array(
				'name' => 'promotionstart_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_START_DATE'),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $promotion[$clientGroup['id']], new FE_ConditionEquals(1))
				)
			)));
			
			$pricePane->AddChild(new FE_Date(Array(
				'name' => 'promotionend_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_END_DATE'),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $promotion[$clientGroup['id']], new FE_ConditionEquals(1))
				)
			)));
		
		}
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			App::getModel('productpromotion')->addPromotion($Data);
			App::getModel('product')->updateProductAttributesetPricesAll();
			App::redirect(__ADMINPANE__ . '/productpromotion');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}
}