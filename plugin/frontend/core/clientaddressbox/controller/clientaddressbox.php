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
 * $Id: clientaddressbox.php 689 2012-09-01 17:55:28Z gekosale $
 */

class ClientAddressBoxController extends BoxController
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		if ($this->registry->session->getActiveClientid() == NULL){
			App::redirect('mainside');
		}
		$this->model = App::getModel('client');
	}

	public function index ()
	{
		
		$formBilling = new FE_Form(Array(
			'name' => 'billingForm',
			'action' => '',
			'method' => 'post'
		));
		
		$billingData = $formBilling->AddChild(new FE_Fieldset(Array(
			'name' => 'billing_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_BILLING_DATA')
		)));
		
		$billingAddressColumns = $billingData->AddChild(new FE_Columns(Array(
			'name' => 'billing_address_columns'
		)));
		
		$leftBilling = $billingAddressColumns->AddChild(new FE_Fieldset(Array(
			'name' => 'left_billing',
			'class' => 'inner-fieldset'
		)));
		
		$rightBilling = $billingAddressColumns->AddChild(new FE_Fieldset(Array(
			'name' => 'right_billing',
			'class' => 'inner-fieldset'
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME'),
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$rightBilling->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('lists')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$billingData->AddChild(new FE_Submit(Array(
			'name' => 'submit',
			'label' => $this->registry->core->getMessage('TXT_EDIT_SETTINGS')
		)));
		
		$clientBillingAddress = $this->model->getClientAddress(1);
		$billingAddress = Array(
			'billing_data' => Array(
				'billing_address_columns' => Array(
					'left_billing' => Array(
						'firstname' => $clientBillingAddress['firstname'],
						'surname' => $clientBillingAddress['surname'],
						'companyname' => $clientBillingAddress['companyname'],
						'nip' => $clientBillingAddress['nip']
					),
					'right_billing' => Array(
						'street' => $clientBillingAddress['street'],
						'streetno' => $clientBillingAddress['streetno'],
						'postcode' => $clientBillingAddress['postcode'],
						'placename' => $clientBillingAddress['placename'],
						'placeno' => $clientBillingAddress['placeno'],
						'countryid' => $clientBillingAddress['countryid']
					)
				)
			)
		);
		$formBilling->Populate($billingAddress);
		$formBilling->AddFilter(new FE_FilterTrim());
		$formBilling->AddFilter(new FE_FilterNoCode());
		
		if ($formBilling->Validate(FE::SubmittedData())){
			$formData = $formBilling->getSubmitValues(FE_Form::FORMAT_FLAT);
			$checkBillingAddressForm = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($checkBillingAddressForm == true){
				$this->model->updateClientAddress($formData, 1);
				App::redirect('clientaddress/index');
			}
			else{
				$this->registry->session->setVolatileForbiddenCodeAddressForm(1, false);
			}
		}
		
		$formShipping = new FE_Form(Array(
			'name' => 'shippingForm',
			'action' => '',
			'method' => 'post'
		));
		
		$shippingData = $formShipping->AddChild(new FE_Fieldset(Array(
			'name' => 'shipping_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_SHIPPING_DATA')
		)));
		
		$shippingAddressColumns = $shippingData->AddChild(new FE_Columns(Array(
			'name' => 'shipping_address_columns'
		)));
		
		$leftShipping = $shippingAddressColumns->AddChild(new FE_Fieldset(Array(
			'name' => 'left_shipping',
			'class' => 'inner-fieldset'
		)));
		
		$rightShipping = $shippingAddressColumns->AddChild(new FE_Fieldset(Array(
			'name' => 'right_shipping',
			'class' => 'inner-fieldset'
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'firstname',
			'label' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_FIRSTNAME'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME'),
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$rightShipping->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('lists')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$shippingData->AddChild(new FE_Submit(Array(
			'name' => 'submit',
			'label' => $this->registry->core->getMessage('TXT_EDIT_SETTINGS')
		)));
		
		$clientShippingAddress = $this->model->getClientAddress(0);
		
		$shippingAddress = Array(
			'shipping_data' => Array(
				'shipping_address_columns' => Array(
					'left_shipping' => Array(
						'firstname' => $clientShippingAddress['firstname'],
						'surname' => $clientShippingAddress['surname'],
						'companyname' => $clientShippingAddress['companyname'],
						'nip' => $clientShippingAddress['nip']
					),
					'right_shipping' => Array(
						'street' => $clientShippingAddress['street'],
						'streetno' => $clientShippingAddress['streetno'],
						'postcode' => $clientShippingAddress['postcode'],
						'placename' => $clientShippingAddress['placename'],
						'placeno' => $clientShippingAddress['placeno'],
						'countryid' => $clientShippingAddress['countryid']
					)
				)
			)
		);
		$formShipping->Populate($shippingAddress);
		
		$formShipping->AddFilter(new FE_FilterTrim());
		$formShipping->AddFilter(new FE_FilterNoCode());
		
		if ($formShipping->Validate(FE::SubmittedData())){
			$formData = $formShipping->getSubmitValues(FE_Form::FORMAT_FLAT);
			$checkShippingAddressForm = App::getModel('formprotection')->cropDangerousCodeSubmitedValues($formData);
			if ($checkShippingAddressForm == true){
				$this->model->updateClientAddress($formData, 0);
				App::redirect('clientaddress/index');
			}
			else{
				$this->registry->session->setVolatileForbiddenCodeAddressForm(1, false);
			}
		}
		$this->registry->template->assign('formBilling', $formBilling);
		$this->registry->template->assign('formShipping', $formShipping);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}
}