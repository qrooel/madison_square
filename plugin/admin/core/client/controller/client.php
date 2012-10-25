<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 674 $
 * $Author: gekosale $
 * $Date: 2012-04-25 21:23:09 +0200 (Śr, 25 kwi 2012) $
 * $Id: client.php 674 2012-04-25 19:23:09Z gekosale $
 */

class ClientController extends Admin {

	public function __construct ($registry) {
		parent::__construct($registry);
		$this->model = App::getModel('client');
	}

	public function index () {
		$this->registry->xajax->registerFunction(array(
			'doDeleteClient',
			$this->model,
			'doAJAXDeleteClient'
		));
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllClient',
			$this->model,
			'getClientForAjax'
		));
		
		$this->registry->xajax->registerFunction(array(
			'GetFirstnameSuggestions',
			$this->model,
			'getFirstnameForAjax'
		));
		
		$this->registry->xajax->registerFunction(array(
			'GetSurnameSuggestions',
			$this->model,
			'getSurnameForAjax'
		));
		
		$this->registry->xajax->registerFunction(array(
			'disableClient',
			$this->model,
			'doAJAXDisableClient'
		));
		
		$this->registry->xajax->registerFunction(array(
			'enableClient',
			$this->model,
			'doAJAXEnableClient'
		));
		
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->Render();
	}

	public function add () {
		
		$form = new FE_Form(Array(
			'name' => 'add_client',
			'action' => '',
			'method' => 'post'
		));
		
		$personalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'personal_data',
			'label' => $this->registry->core->getMessage('TXT_PERSONAL_DATA')
		)));
		
		if (Helper::getViewId() == 0){
			$personalData->AddChild(new FE_Select(Array(
				'name' => 'viewid',
				'label' => $this->registry->core->getMessage('TXT_SHOP'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHOP'))
				),
				'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('view')->getViewAllSelect())
			)));
		}
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_Tip(Array(
			'tip' => '<p>Uwaga zmieniając adres Email zmieni sie również login do sklepu</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_EMAIL_ALREADY_EXISTS'), 'clientdata', 'email', null, Array(
					'column' => 'email',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$personalData->AddChild(new FE_Checkbox(Array(
			'name' => 'newsletter',
			'label' => $this->registry->core->getMessage('TXT_NEWSLETTER'),
			'default' => '1'
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PHONE'))
			)
		)));
		
		$personalData->AddChild(new FE_Select(Array(
			'name' => 'clientgroupid',
			'label' => $this->registry->core->getMessage('TXT_GROUPS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUPS'))
			),
			'options' => FE_Option::Make(App::getModel('clientgroup/clientgroup')->getClientGroupAllToSelect())
		)));
		
		$additionalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'additional_data',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_DATA')
		)));
		
		$additionalData->AddChild(new FE_Textarea(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$additionalData->AddChild(new FE_Checkbox(Array(
			'name' => 'disable',
			'label' => $this->registry->core->getMessage('TXT_DISABLE_CLIENT')
		)));
		
		$billingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'billing_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_BILLING_DATA')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			)
		)));
		
		$billingData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		$shippingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'shipping_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_SHIPPING_DATA')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			)
		)));
		
		$shippingData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$generatedPassword = Core::passwordGenerate();
			$clientId = $this->model->addNewClient($form->getSubmitValues(), $generatedPassword);
			$Data = $form->getSubmitValues();
			$Data['personal_data']['password'] = $generatedPassword;
			$this->registry->template->assign('personal_data', $Data['personal_data']);
			$this->registry->template->assign('address', $Data['address_book']['address_data']['new-0']);
			
			$mailer = new Mailer($this->registry);
			$mailer->loadContentToBody('addClientFromAdmin');
			$mailer->addAddress($form->getElementValue('email'));
			$mailer->addBCC($this->registry->session->getActiveShopEmail());
			$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
			$mailer->setSubject($this->registry->core->getMessage('TXT_REGISTRATION_NEW'));
			try{
				$mailer->Send();
			}
			catch (phpmailerException $e){
			
			}
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/client/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/client');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit () {
		
		$form = new FE_Form(Array(
			'name' => 'edit_client',
			'action' => '',
			'method' => 'post'
		));
		
		$personalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'personal_data',
			'label' => $this->registry->core->getMessage('TXT_PERSONAL_DATA')
		)));
		
		if (Helper::getViewId() == 0){
			$personalData->AddChild(new FE_Select(Array(
				'name' => 'viewid',
				'label' => $this->registry->core->getMessage('TXT_SHOP'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SHOP'))
				),
				'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('view')->getViewAllSelect())
			)));
		}
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$personalData->AddChild(new FE_Tip(Array(
			'tip' => '<p>Uwaga zmieniając adres Email zmieni sie również login do sklepu</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_EMAIL_ALREADY_EXISTS'), 'clientdata', 'email', null, Array(
					'column' => 'email',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$personalData->AddChild(new FE_Checkbox(Array(
			'name' => 'newsletter',
			'label' => $this->registry->core->getMessage('TXT_NEWSLETTER'),
			'default' => '1'
		)));
		
		$personalData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PHONE'))
			)
		)));
		
		$personalData->AddChild(new FE_Select(Array(
			'name' => 'clientgroupid',
			'label' => $this->registry->core->getMessage('TXT_GROUPS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_GROUPS'))
			),
			'options' => FE_Option::Make(App::getModel('clientgroup/clientgroup')->getClientGroupAllToSelect())
		)));
		
		$additionalData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'additional_data',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_DATA')
		)));
		
		$additionalData->AddChild(new FE_Textarea(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$additionalData->AddChild(new FE_Checkbox(Array(
			'name' => 'disable',
			'label' => $this->registry->core->getMessage('TXT_DISABLE_CLIENT')
		)));
		
		$billingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'billing_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_BILLING_DATA')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			)
		)));
		
		$billingData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP')
		)));
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		$shippingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'shipping_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_SHIPPING_DATA')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			)
		)));
		
		$shippingData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP')
		)));
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		$event = new sfEvent($this, 'admin.client.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawClientData = $this->model->getClientView($this->registry->core->getParam());
		
		$clientData = Array(
			'personal_data' => Array(
				'viewid' => $rawClientData['viewid'],
				'firstname' => $rawClientData['firstname'],
				'surname' => $rawClientData['surname'],
				'email' => $rawClientData['email'],
				'newsletter' => $rawClientData['newsletter'],
				'phone' => $rawClientData['phone'],
				'clientgroupid' => $rawClientData['clientgroupid']
			),
			'billing_data' => Array(
				'firstname' => $rawClientData['billing_address']['firstname'],
				'surname' => $rawClientData['billing_address']['surname'],
				'street' => $rawClientData['billing_address']['street'],
				'streetno' => $rawClientData['billing_address']['streetno'],
				'placeno' => $rawClientData['billing_address']['placeno'],
				'placename' => $rawClientData['billing_address']['placename'],
				'postcode' => $rawClientData['billing_address']['postcode'],
				'countryid' => $rawClientData['billing_address']['countryid'],
				'companyname' => $rawClientData['billing_address']['companyname'],
				'nip' => $rawClientData['billing_address']['nip']
			),
			'shipping_data' => Array(
				'firstname' => $rawClientData['delivery_address']['firstname'],
				'surname' => $rawClientData['delivery_address']['surname'],
				'street' => $rawClientData['delivery_address']['street'],
				'streetno' => $rawClientData['delivery_address']['streetno'],
				'placeno' => $rawClientData['delivery_address']['placeno'],
				'placename' => $rawClientData['delivery_address']['placename'],
				'postcode' => $rawClientData['delivery_address']['postcode'],
				'countryid' => $rawClientData['delivery_address']['countryid'],
				'companyname' => $rawClientData['delivery_address']['companyname'],
				'nip' => $rawClientData['delivery_address']['nip']
			)
		);
		
		$clientData['additional_data'] = Array(
			'description' => $rawClientData['description'],
			'disable' => $rawClientData['disable']
		);
		
		$form->Populate($clientData);
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editClient($form->getSubmitValues(), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/client');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

}