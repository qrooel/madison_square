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
 * $Id: store.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class storeController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteStore',
			App::getModel('store'),
			'doAJAXDeleteStore'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllStore',
			App::getModel('store'),
			'getStoreForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_store',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_SHOP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHOP_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_SHOP_ALREADY_EXISTS'), 'store', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'curriencies',
			'label' => $this->registry->core->getMessage('TXT_KIND_OF_CURRENCY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('currencieslist')->getCurrencyForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_KIND_OF_CURRENCY'))
			)
		)));
		
		$companyData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'company_data',
			'label' => $this->registry->core->getMessage('TXT_COMPANY_DATA')
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_COMPANYNAME'))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'shortcompanyname',
			'label' => $this->registry->core->getMessage('TXT_SHORT_COMPANY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHORT_COMPANY_NAME'))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NIP')),
				new FE_RuleCustom($this->registry->core->getMessage('ERR_WRONG_NIP'), Array(
					$this,
					'checkVAT'
				))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'krs',
			'label' => $this->registry->core->getMessage('TXT_KRS'),
		)));
		
		$addressData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'address_data',
			'label' => $this->registry->core->getMessage('TXT_ADDRESS_COMPANY_DATA')
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACENAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'province',
			'label' => $this->registry->core->getMessage('TXT_PROVINCE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PROVINCE'))
			)
		)));
		
		$addressData->AddChild(new FE_Select(Array(
			'name' => 'countries',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$bankData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'bank_data',
			'label' => $this->registry->core->getMessage('TXT_BANK_DATA')
		)));
		
		$bankData->AddChild(new FE_TextField(Array(
			'name' => 'bankname',
			'label' => $this->registry->core->getMessage('TXT_BANK_NAME'),
		)));
		
		$bankData->AddChild(new FE_TextField(Array(
			'name' => 'banknr',
			'label' => $this->registry->core->getMessage('TXT_BANK_NUMBER'),
			'comment' => $this->registry->core->getMessage('TXT_BANK_NUMBER_FORMAT'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT_BANK_NUMBER'), '/^([0-9\s]*)$/')
			)
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('INVOICE_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$invoicedata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'invoice_data',
			'label' => $this->registry->core->getMessage('TXT_INVOICE')
		)));
		
		$isinvoiceshopslogan = $invoicedata->AddChild(new FE_RadioValueGroup(Array(
			'name' => 'isinvoiceshopslogan',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME_AND_TAG'),
			'options' => FE_Option::Make(Array(
				'1' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME'),
				'2' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME_AND_TAG')
			)),
			'value' => '1'
		)));
		
		$invoicedata->AddChild(new FE_TextField(Array(
			'name' => 'invoiceshopslogan',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_INVOICE_TAG'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('TXT_EMPTY_NAME_OF_INVOICE_TAG'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $isinvoiceshopslogan, new FE_ConditionNot(new FE_ConditionEquals('2')))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('store')->addStore($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/store/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/store');
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
			'name' => 'edit_store',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_SHOP_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHOP_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_SHOP_ALREADY_EXISTS'), 'store', 'name', null, Array(
					'column' => 'idstore',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'curriencies',
			'label' => $this->registry->core->getMessage('TXT_KIND_OF_CURRENCY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('currencieslist')->getCurrencyForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_KIND_OF_CURRENCY'))
			)
		)));
		
		$companyData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'company_data',
			'label' => $this->registry->core->getMessage('TXT_COMPANY_DATA')
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_COMPANYNAME'))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'shortcompanyname',
			'label' => $this->registry->core->getMessage('TXT_SHORT_COMPANY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHORT_COMPANY_NAME'))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NIP')),
				new FE_RuleCustom($this->registry->core->getMessage('ERR_WRONG_NIP'), Array(
					$this,
					'checkVAT'
				))
			)
		)));
		
		$companyData->AddChild(new FE_TextField(Array(
			'name' => 'krs',
			'label' => $this->registry->core->getMessage('TXT_KRS'),
		)));
		
		$addressData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'address_data',
			'label' => $this->registry->core->getMessage('TXT_ADDRESS_COMPANY_DATA')
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACENAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$addressData->AddChild(new FE_TextField(Array(
			'name' => 'province',
			'label' => $this->registry->core->getMessage('TXT_PROVINCE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PROVINCE'))
			)
		)));
		
		$addressData->AddChild(new FE_Select(Array(
			'name' => 'countries',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('countrieslist')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$bankData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'bank_data',
			'label' => $this->registry->core->getMessage('TXT_BANK_DATA')
		)));
		
		$bankData->AddChild(new FE_TextField(Array(
			'name' => 'bankname',
			'label' => $this->registry->core->getMessage('TXT_BANK_NAME'),
		)));
		
		$bankData->AddChild(new FE_TextField(Array(
			'name' => 'banknr',
			'label' => $this->registry->core->getMessage('TXT_BANK_NUMBER'),
			'comment' => $this->registry->core->getMessage('TXT_BANK_NUMBER_FORMAT'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT_BANK_NUMBER'), '/^([0-9\s]*)$/')
			)
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('INVOICE_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$invoicedata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'invoice_data',
			'label' => $this->registry->core->getMessage('TXT_INVOICE')
		)));
		
		$isinvoiceshopslogan = $invoicedata->AddChild(new FE_RadioValueGroup(Array(
			'name' => 'isinvoiceshopslogan',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME_AND_TAG'),
			'options' => FE_Option::Make(Array(
				'1' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME'),
				'2' => $this->registry->core->getMessage('TXT_INVOICE_SHOW_SHOP_NAME_AND_TAG')
			)),
			'value' => '1'
		)));
		
		$invoicedata->AddChild(new FE_TextField(Array(
			'name' => 'invoiceshopslogan',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_INVOICE_TAG'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('TXT_EMPTY_NAME_OF_INVOICE_TAG'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $isinvoiceshopslogan, new FE_ConditionNot(new FE_ConditionEquals('2')))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawStoreData = App::getModel('store')->getStoreView($this->registry->core->getParam());
		
		$slogan = 0;
		if ($rawStoreData['isinvoiceshopname'] == 1){
			$slogan = 1;
		}
		if ($rawStoreData['isinvoiceshopslogan'] == 1){
			$slogan = 2;
		}
		
		$storeData = Array(
			'required_data' => Array(
				'name' => $rawStoreData['name'],
				'curriencies' => $rawStoreData['currencyid']
			),
			'address_data' => Array(
				'placename' => $rawStoreData['placename'],
				'postcode' => $rawStoreData['postcode'],
				'street' => $rawStoreData['street'],
				'streetno' => $rawStoreData['streetno'],
				'placeno' => $rawStoreData['placeno'],
				'province' => $rawStoreData['province'],
				'countries' => $rawStoreData['countryid']
			),
			'company_data' => Array(
				'companyname' => $rawStoreData['companyname'],
				'shortcompanyname' => $rawStoreData['shortcompanyname'],
				'nip' => $rawStoreData['nip'],
				'krs' => $rawStoreData['krs'],
			),
			'bank_data' => Array(
				'bankname' => $rawStoreData['bankname'],
				'banknr' => $rawStoreData['banknr']
			),
			'photos_pane' => Array(
				'photo' => $rawStoreData['photo']
			),
			'invoice_data' => Array(
				'isinvoiceshopslogan' => Array(
					'value' => $slogan
				),
				'invoiceshopslogan' => $rawStoreData['invoiceshopslogan']
			)
		);
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$form->Populate($storeData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('store')->editStore($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/store');
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function checkVAT ($value)
	{
		$value = trim($value);
		if (is_numeric(substr($value, 0, 1))){
			$vatNumber = $value;
		}
		else{
			if (substr($value, 0, 2) == 'PL'){
				$vatNumber = substr($value, 2);
			}
			else{
				return false;
			}
		}
		
		$vatNumber = str_replace(array(
			' ',
			'-'
		), '', $vatNumber);
		if (strlen($vatNumber) != 10){
			return false;
		}
		$steps = array(
			6,
			5,
			7,
			2,
			3,
			4,
			5,
			6,
			7
		);
		$sum = 0;
		for ($i = 0; $i < 9; $i ++){
			$sum += $steps[$i] * $vatNumber[$i];
		}
		$tmp = $sum % 11;
		
		$control = ($tmp == 10) ? 0 : $tmp;
		if ($control == $vatNumber[9]){
			return true;
		}
		return false;
	}
}