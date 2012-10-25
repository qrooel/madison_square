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
 * $Revision: 689 $
 * $Author: gekosale $
 * $Date: 2012-09-01 19:55:28 +0200 (So, 01 wrz 2012) $
 * $Id: dispatchmethod.php 689 2012-09-01 17:55:28Z gekosale $ 
 */

class DispatchmethodController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteDispatchMethod',
			App::getModel('dispatchmethod'),
			'doAJAXDeleteDispatchmethod'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllDispatchMethod',
			App::getModel('dispatchmethod'),
			'getDispatchmethodForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('dispatchmethod'),
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doAJAXUpdateMethod',
			App::getModel('dispatchmethod'),
			'doAJAXUpdateMethod'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('dispatchmethod', App::getModel('dispatchmethod')->getDispatchmethodAll());
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('dispatchmethod')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_dispatchmethod',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'dispatchmethod', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'paymentmethodname',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD'),
			'options' => FE_Option::Make(App::getModel('paymentmethod')->getPaymentmethodToSelect())
		)));
		
		$type = $requiredData->AddChild(new FE_Select(Array(
			'name' => 'type',
			'label' => 'Obliczanie kosztów',
			'options' => Array(
				new FE_Option('', $this->registry->core->getMessage('TXT_CHOOSE_SELECT')),
				new FE_Option('1', 'Koszt zależny od sumy zamówienia'),
				new FE_Option('2', 'Koszt zależny od wagi')
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_DELIVERY_COST_TYPE'))
			),
			'default' => ''
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		$currency = $requiredData->AddChild(new FE_Select(Array(
			'name' => 'currencyid',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DATA'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveCurrencyId()
		)));
		
		$dispatchmethodprice = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethod_data',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_PRICE')
		)));
		
		$dispatchmethodprice->AddChild(new FE_RangeEditor(Array(
			'name' => 'table',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_CURRENCY')
		)));
		
		$dispatchmethodprice->AddChild(new FE_TextField(Array(
			'name' => 'maximumweight',
			'label' => $this->registry->core->getMessage('TXT_MAXIMUM_WEIGHT'),
			'comment' => $this->registry->core->getMessage('TXT_MAXIMUM_WEIGHT_HELP'),
			'suffix' => $this->registry->core->getMessage('TXT_KG')
		)));
		
		$dispatchmethodprice->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $type, new FE_ConditionEquals(1)));
		
		$dispatchmethodweight = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethodweight_data',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_WEIGHT_PRICE')
		)));
		
		$dispatchmethodweight->AddChild(new FE_RangeEditor(Array(
			'name' => 'tableweight',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_WEIGHT_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_KG')
		)));
		
		$dispatchmethodweight->AddChild(new FE_TextField(Array(
			'name' => 'freedelivery',
			'label' => $this->registry->core->getMessage('TXT_FREE_DELIVERY'),
			'comment' => $this->registry->core->getMessage('TXT_FREE_DELIVERY_HELP')
		)));
		
		$dispatchmethodweight->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $type, new FE_ConditionEquals(2)));
		
		$countryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'country_pane',
			'label' => $this->registry->core->getMessage('TXT_COUNTRY')
		)));
		
		$countryPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'countryids',
			'label' => $this->registry->core->getMessage('TXT_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect())
		)));
		
		$descriptionData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_data',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION_COURIER')
		)));
		
		$descriptionData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_LOGO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
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
		
		$form->AddFilter(new FE_FilterTrim());
		
		$form->Populate(Array(
			'required_data' => Array(
				'currencyid' => $this->registry->session->getActiveCurrencyId()
			),
			'dispatchmethod_data' => Array(
				'table' => Array(
					'vat' => '1',
					'ranges' => Array(
						Array(
							'min' => '0.00',
							'max' => '0.00',
							'price' => '0.00'
						)
					)
				),
				'tableweight' => Array(
					'ranges' => Array(
						Array(
							'min' => '0.00',
							'max' => '0.00',
							'price' => '0.00'
						)
					)
				)
			)
		));
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('dispatchmethod')->addNewDispatchmethod($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/dispatchmethod/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/dispatchmethod');
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
			'name' => 'edit_dispatchmethod',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'dispatchmethod', 'name', null, Array(
					'column' => 'iddispatchmethod',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'paymentmethodname',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD'),
			'options' => FE_Option::Make(App::getModel('paymentmethod')->getPaymentmethodToSelect())
		)));
		
		$type = $requiredData->AddChild(new FE_Select(Array(
			'name' => 'type',
			'label' => 'Obliczanie kosztów',
			'options' => Array(
				new FE_Option('', $this->registry->core->getMessage('TXT_CHOOSE_SELECT')),
				new FE_Option('1', 'Koszt zależny od sumy zamówienia'),
				new FE_Option('2', 'Koszt zależny od wagi')
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_DELIVERY_COST_TYPE'))
			),
			'default' => ''
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$currency = $requiredData->AddChild(new FE_Select(Array(
			'name' => 'currencyid',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DATA'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveCurrencyId()
		)));
		
		$dispatchmethodprice = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethod_data',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_PRICE')
		)));
		
		$dispatchmethodprice->AddChild(new FE_RangeEditor(Array(
			'name' => 'table',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_precision' => 2,
			'allow_vat' => true
		)));
		
		$dispatchmethodprice->AddChild(new FE_TextField(Array(
			'name' => 'maximumweight',
			'label' => $this->registry->core->getMessage('TXT_MAXIMUM_WEIGHT'),
			'comment' => $this->registry->core->getMessage('TXT_MAXIMUM_WEIGHT_HELP'),
			'suffix' => $this->registry->core->getMessage('TXT_KG')
		)));
		
		$dispatchmethodprice->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $type, new FE_ConditionEquals(1)));
		
		$dispatchmethodweight = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethodweight_data',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_WEIGHT_PRICE')
		)));
		
		$dispatchmethodweight->AddChild(new FE_RangeEditor(Array(
			'name' => 'tableweight',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_WEIGHT_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_KG'),
			'allow_vat' => true
		)));
		
		$dispatchmethodweight->AddChild(new FE_TextField(Array(
			'name' => 'freedelivery',
			'label' => $this->registry->core->getMessage('TXT_FREE_DELIVERY'),
			'comment' => $this->registry->core->getMessage('TXT_FREE_DELIVERY_HELP')
		)));
		
		$dispatchmethodweight->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $type, new FE_ConditionEquals(2)));
		
		$countryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'country_pane',
			'label' => $this->registry->core->getMessage('TXT_COUNTRY')
		)));
		
		$countryPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'countryids',
			'label' => $this->registry->core->getMessage('TXT_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect())
		)));
		
		$descriptionData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_data',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION_COURIER')
		)));
		
		$descriptionData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
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
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawDispatchmethodData = App::getModel('dispatchmethod')->getDispatchmethodView($this->registry->core->getParam());
		
		$dispatchmethodData = Array(
			'required_data' => Array(
				'name' => $rawDispatchmethodData['name'],
				'paymentmethodname' => $rawDispatchmethodData['paymentmethods'],
				'type' => $rawDispatchmethodData['type'],
				'currencyid' => $rawDispatchmethodData['currencyid']
			),
			'dispatchmethod_data' => Array(
				'table' => App::getModel('dispatchmethod')->getDispatchmethodPrice((int) $this->registry->core->getParam()),
				'maximumweight' => $rawDispatchmethodData['maximumweight']
			),
			'dispatchmethodweight_data' => Array(
				'tableweight' => App::getModel('dispatchmethod')->getDispatchmethodWeight((int) $this->registry->core->getParam()),
				'freedelivery' => $rawDispatchmethodData['freedelivery']
			),
			'description_data' => Array(
				'description' => $rawDispatchmethodData['description']
			),
			'photos_pane' => Array(
				'photo' => $rawDispatchmethodData['photo']
			),
			'view_data' => Array(
				'view' => $rawDispatchmethodData['view']
			),
			'country_pane' => Array(
				'countryids' => $rawDispatchmethodData['countryids']
			)
		);
		$form->Populate($dispatchmethodData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('dispatchmethod')->editDispatchmethod($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/dispatchmethod');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}