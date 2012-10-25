<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o
 * licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 686 $
 * $Author: gekosale $
 * $Date: 2012-04-26 12:28:07 +0200 (Cz, 26 kwi 2012) $
 * $Id: cartbox.php 686 2012-04-26 10:28:07Z gekosale $
 */

class CartBoxController extends BoxController {

	public function __construct ($registry) {
		parent::__construct($registry);
		$this->clientModel = App::getModel('client');
		$this->cartModel = App::getModel('cart');
		$this->paymentModel = App::getModel('payment');
		$this->deliveryModel = App::getModel('delivery');
		if ($this->layer['catalogmode'] == 1){
			App::redirect('');
		}
	}

	public function index () {
		
		$this->registry->xajax->registerFunction(array(
			'setAjaxShippingCountryId',
			$this->deliveryModel,
			'setAjaxShippingCountryId'
		));
		$this->registry->xajax->registerFunction(array(
			'setDispatchmethodChecked',
			$this->deliveryModel,
			'setAJAXDispatchmethodChecked'
		));
		$this->registry->xajax->registerFunction(array(
			'setPeymentChecked',
			$this->paymentModel,
			'setAJAXPaymentMethodChecked'
		));
		$this->registry->xajax->registerFunction(array(
			'deleteProductFromCart',
			$this->cartModel,
			'deleteAJAXProductFromCart'
		));
		$this->registry->xajax->registerFunction(array(
			'checkQuantityInc',
			$this->cartModel,
			'increaseAJAXQuantityProduct'
		));
		$this->registry->xajax->registerFunction(array(
			'checkQuantityDec',
			$this->cartModel,
			'decreaseAJAXQuantityProduct'
		));
		$this->registry->xajax->registerFunction(array(
			'changeQuantity',
			$this->cartModel,
			'changeQuantity'
		));
		$this->registry->xajax->registerFunction(array(
			'refreshPaymentMethod',
			$this,
			'ajax_refreshPaymentMethods'
		));
		$this->registry->xajax->registerFunction(array(
			'refreshFinalization',
			$this,
			'ajax_refreshFinalization'
		));
		
		$dispatchmethods = App::getModel('delivery')->getDispatchmethodPrice();
		if ($this->registry->session->getActiveDispatchmethodChecked() == NULL){
			$default = array_shift(array_keys($dispatchmethods));
			App::getModel('delivery')->setDispatchmethodChecked($default);
		}
		
		$form = new FE_Form(Array(
			'name' => 'order',
			'action' => '',
			'method' => 'post'
		));
		
		$billingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'billing_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_BILLING_DATA')
		)));
		
		$clientType = $billingData->AddChild(new FE_RadioValueGroup(Array(
			'name' => 'client_type',
			'options' => FE_Option::Make(Array(
				'1' => $this->registry->core->getMessage('TXT_INDIVIDUAL_CLIENT'),
				'2' => $this->registry->core->getMessage('TXT_COMPANY_CLIENT')
			)),
			'value' => '1'
		)));
		
		if ($this->registry->session->getActiveClientid() == NULL){
			$billingData->AddChild(new FE_StaticText(Array(
				'text' => '<p><a href="' . App::getURLAdress() . App::getRegistry()->core->getControllerNameForSeo('clientlogin') . '" ><span>' . $this->registry->core->getMessage('TXT_ORDER_WITH_LOGIN') . '</span></a></p>'
			)));
		}
		
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
			'name' => 'billing_companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $clientType, new FE_ConditionEquals('2'))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_COMPANYNAME'))
			)
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'billing_nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $clientType, new FE_ConditionEquals('2'))
			),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NIP'))
			)
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE')
		)));
		
		$leftBilling->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
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
			'label' => $this->registry->core->getMessage('TXT_PLACENO')
		)));
		
		$rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$billingPostode = $rightBilling->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			)
		)));
		
		$rightBilling->AddChild(new FE_Select(Array(
			'name' => 'country',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('lists')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
		$shippingData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'shipping_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_SHIPPING_DATA')
		)));
		
		$copy = $shippingData->AddChild(new FE_Checkbox(Array(
			'name' => 'copy',
			'label' => $this->registry->core->getMessage('TXT_COPY_DELIVERY_ADRESS'),
			'default' => 1
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
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'surname',
			'label' => $this->registry->core->getMessage('TXT_SURNAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SURNAME'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'shipping_companyname',
			'label' => $this->registry->core->getMessage('TXT_COMPANYNAME'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'shipping_nip',
			'label' => $this->registry->core->getMessage('TXT_NIP'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$leftShipping->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREETNO'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$rightShipping->AddChild(new FE_Select(Array(
			'name' => 'country',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make(App::getModel('lists')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $copy, new FE_ConditionEquals('1'))
			)
		)));
		
		$event = new sfEvent($this, 'frontend.cartbox.renderForm', Array(
			'form' => &$form
		));
		
		$this->registry->dispatcher->notify($event);
		
		$commentData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'comment_data',
			'label' => $this->registry->core->getMessage('TXT_CUSTOMER_OPINION')
		)));
		
		$commentData->AddChild(new FE_Textarea(Array(
			'name' => 'customeropinion',
			'rows' => 5,
			'cols' => 110,
			'default' => ''
		)));
		
		$termsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'terms_data'
		)));
		
		$termsData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmterms',
			'label' => $this->registry->core->getMessage('TXT_ACCERT_TERMS_AND_POLICY_OF_PRIVATE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_TERMS_NOT_AGREED'))
			),
			'default' => 1
		)));
		
		$termsData->AddChild(new FE_StaticText(Array(
			'text' => '<p class="indent">' . $this->registry->core->getMessage('TXT_ACCEPT_PRIVACY_DESCRIPTION') . '</p>'
		)));
		
		$clientData = $this->clientModel->getClient();
		$clientBillingAddress = $this->clientModel->getClientAddress(1);
		$clientShippingAddress = $this->clientModel->getClientAddress(0);
		$orderData = Array(
			'billing_data' => Array(
				'client_type' => Array(
					'value' => 1
				),
				'billing_address_columns' => Array(
					'left_billing' => Array(
						'firstname' => ($clientBillingAddress['firstname'] != '') ? $clientBillingAddress['firstname'] : (string) $clientData['firstname'],
						'surname' => ($clientBillingAddress['surname'] != '') ? $clientBillingAddress['surname'] : (string) $clientData['surname'],
						'billing_companyname' => $clientBillingAddress['companyname'],
						'billing_nip' => $clientBillingAddress['nip'],
						'phone' => isset($clientData['phone']) ? $clientData['phone'] : '',
						'email' => isset($clientData['email']) ? $clientData['email'] : ''
					),
					'right_billing' => Array(
						'street' => $clientBillingAddress['street'],
						'streetno' => $clientBillingAddress['streetno'],
						'postcode' => $clientBillingAddress['postcode'],
						'placename' => $clientBillingAddress['placename'],
						'placeno' => $clientBillingAddress['placeno'],
						'country' => ($this->registry->session->getActiveShippingCountryId() == NULL) ? $clientBillingAddress['countryid'] : $this->registry->session->getActiveShippingCountryId()
					)
				)
			),
			'shipping_data' => Array(
				'copy' => 1,
				'shipping_address_columns' => Array(
					'left_shipping' => Array(
						'firstname' => ($clientShippingAddress['firstname'] != '') ? $clientShippingAddress['firstname'] : (string) $clientData['firstname'],
						'surname' => ($clientShippingAddress['surname'] != '') ? $clientShippingAddress['surname'] : (string) $clientData['surname'],
						'shipping_companyname' => $clientShippingAddress['companyname'],
						'shipping_nip' => $clientShippingAddress['nip'],
						'phone' => isset($clientData['phone']) ? $clientData['phone'] : '',
						'email' => isset($clientData['email']) ? $clientData['email'] : ''
					),
					'right_shipping' => Array(
						'street' => $clientShippingAddress['street'],
						'streetno' => $clientShippingAddress['streetno'],
						'postcode' => $clientShippingAddress['postcode'],
						'placename' => $clientShippingAddress['placename'],
						'placeno' => $clientShippingAddress['placeno'],
						'country' => ($this->registry->session->getActiveShippingCountryId() == NULL) ? $clientShippingAddress['countryid'] : $this->registry->session->getActiveShippingCountryId()
					)
				)
			),
			'terms_data' => Array(
				'confirmterms' => 1
			),
			'comment_data' => Array(
				'customeropinion' => ''
			)
		);
		$form->Populate($orderData);
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			if ($this->registry->session->getActivePaymentMethodChecked() == NULL){
				$this->registry->template->assign('paymenterror', $this->registry->core->getMessage('ERR_NO_PAYMENT_CHECKED'));
			}
			elseif ($this->registry->session->getActiveDispatchmethodChecked() == NULL){
				$this->registry->template->assign('dispatcherror', $this->registry->core->getMessage('ERR_NO_DISPATCH_CHECKED'));
			}
			else{
				$formData = $form->getSubmitValues();
				$Data['clientaddress'] = array_merge($formData['billing_data']['billing_address_columns']['left_billing'], $formData['billing_data']['billing_address_columns']['right_billing']);
				$Data['clientaddress']['companyname'] = $Data['clientaddress']['billing_companyname'];
				$Data['clientaddress']['nip'] = $Data['clientaddress']['billing_nip'];
				
				if (isset($formData['shipping_data']['copy']) && $formData['shipping_data']['copy'] == 1){
					$Data['deliveryAddress'] = $Data['clientaddress'];
				}
				else{
					$Data['deliveryAddress'] = array_merge($formData['shipping_data']['shipping_address_columns']['left_shipping'], $formData['shipping_data']['shipping_address_columns']['right_shipping']);
					$Data['deliveryAddress']['companyname'] = $Data['deliveryAddress']['shipping_companyname'];
					$Data['deliveryAddress']['nip'] = $Data['deliveryAddress']['shipping_nip'];
				}
				
				$Data['comments'] = $formData['comment_data']['customeropinion'];
				if ($this->registry->session->getActiveClientid() != NULL){
					if ($clientBillingAddress['idclientaddress'] == 0){
						$this->clientModel->updateClientAddress($Data['clientaddress'], 1);
					}
					if ($clientShippingAddress['idclientaddress'] == 0){
						$this->clientModel->updateClientAddress($Data['deliveryAddress'], 0);
					}
				}
				App::getModel('finalization')->saveOrder($Data);
			}
		}
		
		$globalprice = $this->cartModel->getGlobalPrice();
		$globalPriceWithoutVat = $this->cartModel->getGlobalPriceWithoutVat();
		
		$checkRulesCart = App::getModel('cart')->checkRulesCart();
		if (is_array($checkRulesCart) && count($checkRulesCart) > 0){
			$this->registry->template->assign('checkRulesCart', $checkRulesCart);
		}
		
		$minimumordervalue = $this->layer['minimumordervalue'] - $globalprice;
		
		$guestcheckout = 1;
		if ($this->layer['guestcheckout'] == 1){
			$guestcheckout = 1;
		}
		else{
			if ($this->registry->session->getActiveClientid() == NULL){
				$guestcheckout = 0;
			}
			else{
				$guestcheckout = 1;
			}
		}

		$assignData = Array(
			'payment' => $this->getPaymentMethodsTemplate(),
			'finalization' => $this->getFinalizationTemplate(),
			'minimumordervalue' => $minimumordervalue,
			'deliverymethods' => $dispatchmethods,
			'checkedDelivery' => $this->registry->session->getActiveDispatchmethodChecked(),
			'form' => $form,
			'uploadSettings' => App::getModel('view')->getUploadSettings(),
			'guestcheckout' => $guestcheckout
		);
		
		$event = new sfEvent($this, 'frontend.cartbox.assign');
		$this->registry->dispatcher->notify($event);
		
		$this->registry->template->assign($assignData);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	protected function getPaymentMethodsTemplate () {
		$this->registry->template->saveState();
		$paymentMethods = App::getModel('payment')->getPaymentMethods();
		if ($this->registry->session->getActivePaymentMethodChecked() == 0){
			if (isset($paymentMethods[0])){
				App::getModel('payment')->setPaymentMethodChecked($paymentMethods[0]['idpaymentmethod'], $paymentMethods[0]['name']);
			}
		}
		$priceWithDispatchMethod = $this->registry->session->getActiveglobalPriceWithDispatchmethod();
		$this->registry->template->assign('priceWithDispatchMethod', $priceWithDispatchMethod);
		$this->registry->template->assign('checkedPayment', $this->registry->session->getActivePaymentMethodChecked());
		$this->registry->template->assign('checkedDelivery', $this->registry->session->getActiveDispatchmethodChecked());
		$this->registry->template->assign('payments', $paymentMethods);
		$result = $this->registry->template->fetch($this->loadTemplate('payment.tpl'));
		$this->registry->template->reloadState();
		return $result;
	}

	protected function getFinalizationTemplate () {
		$order = App::getModel('finalization')->setClientOrder();
		$priceWithDispatchMethod = $this->registry->session->getActiveglobalPriceWithDispatchmethod();
		$this->registry->template->saveState();
		$this->registry->template->assign('summary', App::getModel('finalization')->getOrderSummary());
		$result = $this->registry->template->fetch($this->loadTemplate('finalization.tpl'));
		$this->registry->template->reloadState();
		return $result;
	}

	public function ajax_refreshPaymentMethods () {
		$objResponse = new xajaxResponse();
		$objResponse->clear("payment", "innerHTML");
		$objResponse->append("payment", "innerHTML", $this->getPaymentMethodsTemplate());
		return $objResponse;
	}

	public function ajax_refreshFinalization () {
		$objResponse = new xajaxResponse();
		$objResponse->clear("finalization", "innerHTML");
		$objResponse->append("finalization", "innerHTML", $this->getFinalizationTemplate());
		return $objResponse;
	}

}