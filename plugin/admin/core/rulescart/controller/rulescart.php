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
 * $Id: rulescart.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class RulesCartController extends Controller
{

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////          INDEX          ////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	public function index ()
	{
		
		$rulescartArray = Array();
		$rulescartRaw = App::getModel('rulescart')->getRulesCartAll();
		foreach ($rulescartRaw as $rulescartruleRaw){
			$rulescartArray[$rulescartruleRaw['id']]['name'] = $rulescartruleRaw['name'];
			$rulescartArray[$rulescartruleRaw['id']]['parent'] = $rulescartruleRaw['parent'];
			$rulescartArray[$rulescartruleRaw['id']]['weight'] = $rulescartruleRaw['distinction'];
		}
		
		$tree = new FE_Form(Array(
			'name' => 'rulescart_tree',
			'class' => 'rulescart-select',
			'action' => '',
			'method' => 'post'
		));
		
		$tree->AddChild(new FE_SortableList(Array(
			'name' => 'rulescart',
			'label' => $this->registry->core->getMessage('TXT_RULES_CART'),
			'add_item_prompt' => $this->registry->core->getMessage('TXT_ENTER_NEW_CART_RULE_NAME'),
			'sortable' => true,
			'selectable' => false,
			'clickable' => true,
			'deletable' => true,
			'addable' => true,
			'items' => $rulescartArray,
			'onClick' => 'openRulesCartEditor',
			'onAdd' => 'xajax_AddRulesCart',
			'onAfterAdd' => 'openRulesCartEditor',
			'onDelete' => 'xajax_DeleteRulesCart',
			'onAfterDelete' => 'openRulesCartEditor',
			'onSaveOrder' => 'xajax_ChangeRulesCartOrder'
		)));
		
		$tree->AddFilter(new FE_FilterTrim());
		$tree->AddFilter(new FE_FilterNoCode());
		
		$this->registry->template->assign('tree', $tree);
		$this->registry->xajaxInterface->registerFunction(Array(
			'DeleteRulesCart',
			App::getModel('rulescart'),
			'deleteRulesCart'
		));
		$this->registry->xajaxInterface->registerFunction(Array(
			'AddRulesCart',
			App::getModel('rulescart'),
			'addEmptyRulesCart'
		));
		$this->registry->xajaxInterface->registerFunction(Array(
			'ChangeRulesCartOrder',
			App::getModel('rulescart'),
			'changeRulesCartOrder'
		));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////          VIEW           ////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	public function view ()
	{
		try{
			$rulescartrule = App::getModel('rulescart')->getSimpleRulesCart((int) $this->registry->core->getParam());
			$this->registry->template->assign('rulescartrule', $rulescartrule);
		}
		catch (Exception $e){
			$this->registry->template->assign('error', $e->getMessage());
		}
		$this->registry->template->display($this->loadTemplate('view.tpl'));
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////          EDIT           ////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit ()
	{
		
		$rulescartArray = Array();
		$rulescartRaw = App::getModel('rulescart')->getRulesCartAll();
		foreach ($rulescartRaw as $rulescartruleRaw){
			$rulescartArray[$rulescartruleRaw['id']]['name'] = $rulescartruleRaw['name'];
			$rulescartArray[$rulescartruleRaw['id']]['parent'] = $rulescartruleRaw['parent'];
			$rulescartArray[$rulescartruleRaw['id']]['weight'] = $rulescartruleRaw['distinction'];
		}
		
		////////////////////////////////////     LIST   /////////////////////////////////////////////////////////
		$tree = new FE_Form(Array(
			'name' => 'rulescart_tree',
			'class' => 'rulescart-select',
			'action' => '',
			'method' => 'post'
		));
		$tree->AddChild(new FE_SortableList(Array(
			'name' => 'rulescart',
			'label' => $this->registry->core->getMessage('TXT_RULES_CART'),
			'add_item_prompt' => $this->registry->core->getMessage('TXT_ENTER_NEW_CART_RULE_NAME'),
			'sortable' => true,
			'selectable' => false,
			'clickable' => true,
			'deletable' => true,
			'addable' => true,
			'items' => $rulescartArray,
			'onClick' => 'openRulesCartEditor',
			'onSaveOrder' => 'xajax_ChangeRulesCartOrder',
			'onAdd' => 'xajax_AddRulesCart',
			'onAfterAdd' => 'openRulesCartEditor',
			'onDelete' => 'xajax_DeleteRulesCart',
			'onAfterDelete' => 'openRulesCartEditor',
			'active' => $this->registry->core->getParam()
		)));
		$tree->AddFilter(new FE_FilterTrim());
		$tree->AddFilter(new FE_FilterNoCode());
		$this->registry->template->assign('tree', $tree);
		
		//////////////////////////////////////   EDIT RULE CART    ///////////////////////////////////////////////
		$form = new FE_Form(Array(
			'name' => 'edit_rulescart',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$name = $requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">Jeśli chcesz, by reguła obowiązywała zawsze, zostaw puste pola z datą</strong></p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$requiredData->AddChild(new FE_Date(Array(
			'name' => 'datefrom',
			'label' => $this->registry->core->getMessage('TXT_START_DATE'),
		)));
		
		$requiredData->AddChild(new FE_Date(Array(
			'name' => 'dateto',
			'label' => $this->registry->core->getMessage('TXT_END_DATE'),
		)));
		
		//////////////////////////////////    CLIENT GROUPS     //////////////////////////////////////////	
		$additionalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'additional_data',
			'label' => $this->registry->core->getMessage('TXT_PROMOTIONRULE_DISCOUNT_DATA')
		)));
		
		$additionalData->AddChild(new FE_Tip(Array(
			'tip' => '
					<div>
						<p align="center"><strong>Ustal wielkość modyfikatora dla grupy/grup klientów</strong></p>
						<p align="center">Każdej grupie z osobna możesz zdefiniwać wielkość modyfikatora. <br>
										Jeśli chcesz, by wielkość modyfikatora była taka sama we wszystkich grupach, 
										zaznacz pole <b>"Jednakowy modyfikatora dla wszystkich grup"</b>.</p>
					</div>',
			'direction' => FE_Tip::DOWN,
			'short_tip' => '<p align="center">Wybierz grupę/grupy klientów</p>'
		)));
		
		$discountForAll = $additionalData->AddChild(new FE_Checkbox(Array(
			'name' => 'discountforall',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNT_FOR_ALL_GROUP'),
		)));
		
		$suffixtypeid = $additionalData->AddChild(new FE_Select(Array(
			'name' => 'suffixtypeid',
			'label' => $this->registry->core->getMessage('TXT_SUFFIXTYPE'),
			'options' => FE_Option::Make(App::getModel('suffix/suffix')->getRulesSuffixTypesForSelect()),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$additionalData->AddChild(new FE_TextField(Array(
			'name' => 'discount',
			'label' => $this->registry->core->getMessage('TXT_VALUE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^([0-9]*\.*([0-9]{1,2})|(\0)?)$/')
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$clientGroups = App::getModel('clientgroup/clientgroup')->getClientGroupAll();
		
		foreach ($clientGroups as $clientGroup){
			$additionalData->AddChild(new FE_Fieldset(Array(
				'name' => 'field_' . $clientGroup['id'],
				'label' => 'Rabat dla ' . $clientGroup['name'],
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
				)
			)));
			
			$groups[$clientGroup['id']] = $additionalData->AddChild(new FE_Checkbox(Array(
				'name' => 'groupid_' . $clientGroup['id'],
				'label' => $clientGroup['name'],
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
				)
			)));
			
			$suffix = $additionalData->AddChild(new FE_Select(Array(
				'name' => 'suffixtypeid_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_SUFFIXTYPE'),
				'options' => FE_Option::Make(App::getModel('suffix/suffix')->getRulesSuffixTypesForSelect()),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $groups[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1))),
					new FE_Dependency(FE_Dependency::SHOW, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
				)
			)
			));
			
			$additionalData->AddChild(new FE_TextField(Array(
				'name' => 'discount_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_DISCOUNT'),
				'default' => '0.00',
				'rules' => Array(
					new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^([0-9]*\.*([0-9]{1,2})|(\0)?)$/'),
					new FE_RuleCustom($this->registry->core->getMessage('ERR_VALUE_INVALID'), Array(
						$this,
						'checkDiscountValue'
					), Array(
						'suffixType' => $suffix
					))
				),
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $groups[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1))),
					new FE_Dependency(FE_Dependency::SHOW, $discountForAll, new FE_ConditionNot(new FE_ConditionEquals('1')))
				),
				'filters' => Array(
					new FE_FilterCommaToDotChanger()
				)
			)));
		}
		
		////////////////////////////////////       DISPATCHMETHOD    /////////////////////////////////////////////////
		$deliverers = App::getModel('dispatchmethod')->getDispatchmethodToSelect();
		
		$deliverersData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'deliverers_data',
			'label' => $this->registry->core->getMessage('TXT_CONDITIONS') . '- ' . $this->registry->core->getMessage('TXT_DELIVERERS')
		)));
		if (count($deliverers)){
			$deliverersData->AddChild(new FE_MultiSelect(Array(
				'name' => 'deliverers',
				'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD'),
				'options' => FE_Option::Make($deliverers)
			)));
		}
		else{
			$deliverersData->AddChild(new FE_StaticText(Array(
				'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_EMPTY_DISPATCHMETHODS') . '</strong><br/>
						<a href="/admin/dispatchmethod/add" target="_blank">' . $this->registry->core->getMessage('TXT_ADD_DISPATCHMETHOD') . '</a></p>'
			)));
		}
		
		///////////////////////////////////      PAYMENT METHODS      /////////////////////////////////////////////
		$payments = App::getModel('paymentmethod')->getPaymentmethodToSelect();
		
		$paymentsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'payments_data',
			'label' => $this->registry->core->getMessage('TXT_CONDITIONS') . '- ' . $this->registry->core->getMessage('TXT_PAYMENTMETHODS')
		)));
		if (count($payments)){
			$paymentsData->AddChild(new FE_MultiSelect(Array(
				'name' => 'payments',
				'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHODS'),
				'options' => FE_Option::Make($payments)
			)));
		}
		else{
			$paymentsData->AddChild(new FE_StaticText(Array(
				'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_EMPTY_PAYMENTMETHODS') . '</strong><br/>
						<a href="/admin/paymentmethod/add" target="_blank">' . $this->registry->core->getMessage('TXT_ADD_PAYMENTMETHOD') . '</a></p>'
			)));
		}
		
		/////////////////////////////////////     CART PRICE     ////////////////////////////////////////////////
		///////////////////////////    CART PRICE  WITH DISPATCHMETHOD   ////////////////////////////////////////
		$pricePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'price_pane',
			'label' => $this->registry->core->getMessage('TXT_CONDITIONS') . '- ' . $this->registry->core->getMessage('TXT_SUM_PRICE')
		)));
		
		$pricePane->AddChild(new FE_StaticText(Array(
			'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_FINAL_CART_PRICE') . '</strong></p>'
		)));
		
		$pricePane->AddChild(new FE_TextField(Array(
			'name' => 'cart_price_from',
			'label' => $this->registry->core->getMessage('TXT_PRICE_FROM'),
			'suffix' => 'brutto',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/^(([0-9]{1,})|(\0)?)$/')
			)
		)));
		
		$pricePane->AddChild(new FE_TextField(Array(
			'name' => 'cart_price_to',
			'label' => $this->registry->core->getMessage('TXT_PRICE_TO'),
			'suffix' => 'brutto',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/^(([0-9]{1,})|(\0)?)$/')
			)
		)));
		
		$pricePane->AddChild(new FE_StaticText(Array(
			'text' => '<p><strong>' . $this->registry->core->getMessage('TXT_TOTAL_COST') . '</strong></p>'
		)));
		
		$pricePane->AddChild(new FE_TextField(Array(
			'name' => 'dispatch_price_from',
			'label' => $this->registry->core->getMessage('TXT_PRICE_FROM'),
			'suffix' => 'brutto',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/^(([0-9]{1,})|(\0)?)$/')
			)
		)));
		
		$pricePane->AddChild(new FE_TextField(Array(
			'name' => 'dispatch_price_to',
			'label' => $this->registry->core->getMessage('TXT_PRICE_TO'),
			'suffix' => 'brutto',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/^(([0-9]{1,})|(\0)?)$/')
			)
		)));
		
		/////////////////////////////////////        VIEW DATA        /////////////////////////////////////////////
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layers = $layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		//////////////////////////////////////       POPULATE    //////////////////////////////////////////////////
		////////////////////////////////////      REQUIRED_DATA    ////////////////////////////////////////////////
		$rawRulesCartData = App::getModel('rulescart')->getRulesCartView($this->registry->core->getParam());
		$rulesCartRuleData['required_data'] = Array(
			'name' => $rawRulesCartData['name'],
			'suffixtypeid' => $rawRulesCartData['suffixtypeid'],
			'discount' => $rawRulesCartData['discount'],
			'datefrom' => $rawRulesCartData['datefrom'],
			'dateto' => $rawRulesCartData['dateto']
		);
		
		////////////////////////////////////     ADDITIONAL_DATA    ////////////////////////////////////////////////
		$rawRulesCartClientGroupData = App::getModel('rulescart')->getRulesCartClientGroupView($this->registry->core->getParam());
		if (isset($rawRulesCartData['discountforall']) && $rawRulesCartData['discountforall'] == 1){
			$rulesCartRuleData['additional_data']['discountforall'] = $rawRulesCartData['discountforall'];
			$rulesCartRuleData['additional_data']['suffixtypeid'] = $rawRulesCartData['suffixtypeid'];
			$rulesCartRuleData['additional_data']['discount'] = $rawRulesCartData['discount'];
		}
		else{
			$rawRulesCartClientGroupData = App::getModel('rulescart')->getRulesCartClientGroupView($this->registry->core->getParam());
			if (count($rawRulesCartClientGroupData) > 0){
				foreach ($rawRulesCartClientGroupData as $clientGroupKey => $clientGroupValue){
					$rulesCartRuleData['additional_data']['groupid_' . $clientGroupValue['clientgroupid']] = 1;
					$rulesCartRuleData['additional_data']['discount_' . $clientGroupValue['clientgroupid']] = $clientGroupValue['discount'];
					$rulesCartRuleData['additional_data']['suffixtypeid_' . $clientGroupValue['clientgroupid']] = $clientGroupValue['suffixtypeid'];
				}
			}
		}
		////////////////////////////////////     DELIVERER_DATA    ////////////////////////////////////////////////
		$rawRulesCartDeliverersData = App::getModel('rulescart')->getRulesCartDeliverersView($this->registry->core->getParam());
		if (count($rawRulesCartDeliverersData) > 0){
			foreach ($rawRulesCartDeliverersData as $delivererId){
				$rulesCartRuleData['deliverers_data']['deliverers'][] = $delivererId;
			}
		}
		///////////////////////////////////      PAYMENT METHODS      /////////////////////////////////////////////
		$rawRulesCartPaymentData = App::getModel('rulescart')->getRulesCartPaymentsView($this->registry->core->getParam());
		if (count($rawRulesCartPaymentData) > 0){
			foreach ($rawRulesCartPaymentData as $paymentId){
				$rulesCartRuleData['payments_data']['payments'][] = $paymentId;
			}
		}
		/////////////////////////////////////     CART PRICE     ////////////////////////////////////////////////
		$rawRulesCartDynamicData = App::getModel('rulescart')->getRulesCartOtherDinamicDataConditionsView($this->registry->core->getParam());
		if (count($rawRulesCartDynamicData) > 0){
			foreach ($rawRulesCartDynamicData as $dynamicData){
				if ($dynamicData['ruleid'] == 11 && $dynamicData['field'] == 'globalpricefrom'){
					$rulesCartRuleData['price_pane']['cart_price_from'] = $dynamicData['pricefrom'];
				}
				if ($dynamicData['ruleid'] == 12 && $dynamicData['field'] == 'globalpriceto'){
					$rulesCartRuleData['price_pane']['cart_price_to'] = $dynamicData['priceto'];
				}
				if ($dynamicData['ruleid'] == 13 && $dynamicData['field'] == 'globalpricewithdispatchmethodfrom'){
					$rulesCartRuleData['price_pane']['dispatch_price_from'] = $dynamicData['pricefrom'];
				}
				if ($dynamicData['ruleid'] == 14 && $dynamicData['field'] == 'globalpricewithdispatchmethodto'){
					$rulesCartRuleData['price_pane']['dispatch_price_to'] = $dynamicData['priceto'];
				}
			}
		}
		/////////////////////////////////////        VIEW DATA        /////////////////////////////////////////////
		$rawRulesCartViewData = App::getModel('rulescart')->getRulesCartViews($this->registry->core->getParam());
		if (count($rawRulesCartViewData) > 0){
			foreach ($rawRulesCartViewData as $viewKey => $viewValue){
				$rulesCartRuleData['view_data']['view'][] = $viewValue;
			}
		}
		$form->Populate($rulesCartRuleData);
		
		///////////////////////////////////////     SUBMIT     /////////////////////////////////////////////////////
		if ($form->Validate(FE::SubmittedData())){
			try{
				$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				App::getModel('rulescart')->editRulesCart($formData, $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/rulescart');
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->registry->template->assign('form', $form);
		$this->registry->xajaxInterface->registerFunction(Array(
			'DeleteRulesCart',
			App::getModel('rulescart'),
			'deleteRulesCart'
		));
		$this->registry->xajaxInterface->registerFunction(Array(
			'AddRulesCart',
			App::getModel('rulescart'),
			'addEmptyRulesCart'
		));
		$this->registry->xajaxInterface->registerFunction(Array(
			'ChangeRulesCartOrder',
			App::getModel('rulescart'),
			'changeRulesCartOrder'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function checkDiscountValue ($value, $params)
	{
		if (isset($params['suffixType']) && ($params['suffixType'] == '1')){
			if (intval($value) >= 100){
				return false;
			}
		}
		return true;
	}

	public function checkDiscountValueSuffix ($value, $params)
	{
		if (isset($params['discountValue']) && ($params['discountValue'] > 0)){
			if (intval($value) == '1' && (int) $params['discountValue'] >= 100){
				return false;
			}
		}
		return true;
	}
}