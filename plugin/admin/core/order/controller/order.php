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
 * $Revision: 671 $
 * $Author: gekosale $
 * $Date: 2012-04-25 21:13:36 +0200 (Śr, 25 kwi 2012) $
 * $Id: order.php 671 2012-04-25 19:13:36Z gekosale $ 
 */

class OrderController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('order');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doChangeOrderStatus',
			$this->model,
			'doAJAXChangeOrderStatus'
		));
		
		$this->registry->xajax->registerFunction(array(
			'doDeleteOrder',
			$this->model,
			'doAJAXDeleteOrder'
		));
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllOrder',
			$this->model,
			'getOrderForAjax'
		));
		
		$this->registry->xajax->registerFunction(array(
			'GetClientSuggestions',
			$this->model,
			'getClientForAjax'
		));
		
		$this->registry->template->assign(Array(
			'datagrid_filter' => $this->model->getDatagridFilterData(),
			'order_statuses' => json_encode(App::getModel('orderstatus')->getOrderStatusToSelect()),
			'datagrid_filter' => $this->model->getDatagridFilterData()
		));
		
		$this->Render();
	}

	public function add ()
	{
		
		$currencyid = $this->registry->session->getActiveShopCurrencyId();
		
		$form = new FE_Form(Array(
			'name' => 'edit_order',
			'action' => '',
			'class' => 'editOrder',
			'method' => 'post'
		));
		
		$productsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'products_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_ORDERED_PRODUCTS')
		)));
		
		$products = $productsData->AddChild(new FE_OrderEditor(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_ORDERED_PRODUCTS'),
			'advanced_editor' => true,
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'viewid' => Helper::getViewId(),
			'on_change' => 'OnProductListChanged'
		)));
		
		$addressData = $form->AddChild(new FE_Columns(Array(
			'name' => 'address_data'
		)));
		
		$billingData = $addressData->AddChild(new FE_Fieldset(Array(
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
			'name' => 'place',
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
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$shippingData = $addressData->AddChild(new FE_Fieldset(Array(
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
			'name' => 'place',
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
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$additionalData = $form->AddChild(new FE_Columns(Array(
			'name' => 'additional_data'
		)));
		
		$paymentData = $additionalData->AddChild(new FE_Fieldset(Array(
			'name' => 'payment_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_PAYMENT_METHOD')
		)));
		
		$paymentData->AddChild(new FE_Select(Array(
			'name' => 'payment_method',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_PAYMENT_METHOD'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + $this->model->getPaymentmethodAllToSelect($this->registry->core->getParam()))
		)));
		
		$paymentData->AddChild(new FE_Select(Array(
			'name' => 'delivery_method',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_DELIVERY_METHOD'),
			'options' => FE_Option::Make(App::getModel('dispatchmethod')->getDispatchmethodToSelect())
		)));
		
		$paymentData->AddChild(new FE_Constant(Array(
			'name' => 'currency',
			'label' => $this->registry->core->getMessage('TXT_KIND_OF_CURRENCY'),
			'default' => $this->registry->session->getActiveCurrencySymbol()
		)));
		
		$summaryData = $additionalData->AddChild(new FE_Fieldset(Array(
			'name' => 'summary_data',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_SUMMARY')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_net_total',
			'label' => $this->registry->core->getMessage('TXT_NETTO_AMOUNT')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_vat_value',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_TAX')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_delivery',
			'label' => $this->registry->core->getMessage('TXT_DELIVERERPRICE')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_total',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_TOTAL')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$id = $this->model->addNewOrder($_POST, $this->registry->core->getParam());
				$orderData = $this->model->getOrderById($id);
				
				App::redirect(__ADMINPANE__ . '/order/');
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
		}
		$this->registry->template->assign('viewid', Helper::getViewId());
		$this->registry->xajaxInterface->registerFunction(array(
			'CalculateDeliveryCost',
			$this->model,
			'calculateDeliveryCostAdd'
		));
		$this->registry->xajaxInterface->registerFunction(array(
			'GetDispatchMethodForPrice',
			$this->model,
			'getDispatchMethodForPriceForAjaxAdd'
		));
		$this->registry->template->assign('currencyid', $this->registry->session->getActiveCurrencyId());
		$this->registry->template->assign('currencysymbol', $this->registry->session->getActiveCurrencySymbol());
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		
		$rawOrderData = $this->model->getOrderById($this->registry->core->getParam());
		if (isset($rawOrderData['currencyid']) && ! empty($rawOrderData['currencyid'])){
			$currencyid = $rawOrderData['currencyid'];
		}
		else{
			$currencyid = $this->registry->session->getActiveShopCurrencyId();
		}
		try{
			$order = $this->model->getOrderById((int) $this->registry->core->getParam());
			$clientNotes = $this->model->getClientNotes($order['clientid']);
			$order['id'] = (int) $this->registry->core->getParam();
			$orderNotes = $this->model->getOrderNotes($order['id']);
			$clientOrderHistory = $this->model->getclientOrderHistory($order['clientid']);
		}
		catch (Exception $e){
			throw new CoreException($e->getMessage());
		}
		
		$addNotes = new FE_Form(Array(
			'name' => 'add_notes',
			'class' => 'statusChange',
			'action' => '',
			'method' => 'post'
		));
		
		$noteType = $addNotes->AddChild(new FE_Select(Array(
			'name' => 'ordernotes',
			'label' => $this->registry->core->getMessage('TXT_NOTES_TYPE'),
			'options' => FE_Option::Make($this->model->getOrderNotesType()),
			'default' => 0
		)));
		
		$addNotes->AddChild(new FE_Textarea(Array(
			'name' => 'contents',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTENT'))
			)
		)));
		
		$addNotes->AddChild(new FE_Submit(Array(
			'name' => 'add',
			'label' => $this->registry->core->getMessage('TXT_ADD'),
			'icon' => '_images_panel/icons/buttons/add.png'
		)));
		
		$form = new FE_Form(Array(
			'name' => 'edit_order',
			'action' => '',
			'class' => 'editOrder',
			'method' => 'post'
		));
		
		$productsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'products_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_ORDERED_PRODUCTS')
		)));
		
		$products = $productsData->AddChild(new FE_OrderEditor(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_ORDERED_PRODUCTS'),
			'advanced_editor' => true,
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'clientgroupid' => (int) $rawOrderData['clientgroupid'],
			'currencyid' => (int) $rawOrderData['currencyid'],
			'viewid' => $rawOrderData['viewid'],
			'on_change' => 'OnProductListChanged'
		)));
		
		$addressData = $form->AddChild(new FE_Columns(Array(
			'name' => 'address_data'
		)));
		
		$billingData = $addressData->AddChild(new FE_Fieldset(Array(
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
			'name' => 'place',
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
		
		$billingData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$shippingData = $addressData->AddChild(new FE_Fieldset(Array(
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
			'name' => 'place',
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
		
		$shippingData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$additionalData = $form->AddChild(new FE_Columns(Array(
			'name' => 'additional_data'
		)));
		
		$paymentData = $additionalData->AddChild(new FE_Fieldset(Array(
			'name' => 'payment_data',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_PAYMENT_METHOD')
		)));
		
		$paymentData->AddChild(new FE_Select(Array(
			'name' => 'payment_method',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_PAYMENT_METHOD'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + $this->model->getPaymentmethodAllToSelect($this->registry->core->getParam()))
		)));
		
		$paymentData->AddChild(new FE_Select(Array(
			'name' => 'delivery_method',
			'label' => $this->registry->core->getMessage('TXT_EDIT_ORDER_DELIVERY_METHOD'),
			'options' => FE_Option::Make($this->model->getDispatchmethodAllToSelect($order['total'], $this->registry->core->getParam(), $currencyid))
		)));
		
		$paymentData->AddChild(new FE_Select(Array(
			'name' => 'rules_cart',
			'label' => $this->registry->core->getMessage('TXT_RULES_CART'),
			'options' => FE_Option::Make($this->model->getAllRulesForOrder($this->registry->core->getParam()))
		)));
		
		if (isset($order['coupon']['couponcode'])){
			$paymentData->AddChild(new FE_Constant(Array(
				'name' => 'coupon',
				'label' => 'Kupon rabatowy'
			)));
		}
		
		$paymentData->AddChild(new FE_Constant(Array(
			'name' => 'currency',
			'label' => $this->registry->core->getMessage('TXT_KIND_OF_CURRENCY')
		)));
		
		$summaryData = $additionalData->AddChild(new FE_Fieldset(Array(
			'name' => 'summary_data',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_SUMMARY')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_net_total',
			'label' => $this->registry->core->getMessage('TXT_NETTO_AMOUNT')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_vat_value',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_TAX')
		)));
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_delivery',
			'label' => $this->registry->core->getMessage('TXT_DELIVERERPRICE')
		)));
		
		if (isset($order['coupon']['couponcode'])){
			$summaryData->AddChild(new FE_Constant(Array(
				'name' => 'total_coupon',
				'label' => 'Kupon'
			)));
		}
		
		$summaryData->AddChild(new FE_Constant(Array(
			'name' => 'total_total',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_TOTAL')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$orderData = Array(
			'address_data' => Array(
				'billing_data' => Array(
					'firstname' => $rawOrderData['billing_address']['firstname'],
					'surname' => $rawOrderData['billing_address']['surname'],
					'street' => $rawOrderData['billing_address']['street'],
					'streetno' => $rawOrderData['billing_address']['streetno'],
					'placeno' => $rawOrderData['billing_address']['placeno'],
					'place' => $rawOrderData['billing_address']['city'],
					'postcode' => $rawOrderData['billing_address']['postcode'],
					'countryid' => $rawOrderData['billing_address']['countryid'],
					'companyname' => $rawOrderData['billing_address']['companyname'],
					'nip' => $rawOrderData['billing_address']['nip'],
					'phone' => $rawOrderData['billing_address']['phone'],
					'email' => $rawOrderData['billing_address']['email']
				),
				'shipping_data' => Array(
					'firstname' => $rawOrderData['delivery_address']['firstname'],
					'surname' => $rawOrderData['delivery_address']['surname'],
					'street' => $rawOrderData['delivery_address']['street'],
					'streetno' => $rawOrderData['delivery_address']['streetno'],
					'placeno' => $rawOrderData['delivery_address']['placeno'],
					'place' => $rawOrderData['delivery_address']['city'],
					'postcode' => $rawOrderData['delivery_address']['postcode'],
					'countryid' => $rawOrderData['delivery_address']['countryid'],
					'companyname' => $rawOrderData['delivery_address']['companyname'],
					'nip' => $rawOrderData['delivery_address']['nip'],
					'phone' => $rawOrderData['delivery_address']['phone'],
					'email' => $rawOrderData['delivery_address']['email']
				)
			),
			'additional_data' => Array(
				'payment_data' => Array(
					'delivery_method' => $rawOrderData['delivery_method']['dispatchmethodid'],
					'payment_method' => $rawOrderData['payment_method']['paymentmethodid'],
					'rules_cart' => $rawOrderData['rulescartid'],
					'currency' => $rawOrderData['currencysymbol'],
					'coupon' => $order['coupon']['couponcode'],
				),
				'summary_data' => Array(
					'total_net_total' => 132,
					'total_coupon' => isset($order['coupon']['coupondiscount']) ? $order['coupon']['coupondiscount'] : ''
				)
			),
			'products_data' => Array(
				'products' => $this->model->getProductsDataGrid((int) $this->registry->core->getParam())
			)
		);
		
		$form->Populate($orderData);
		
		$statusChange = new FE_Form(Array(
			'name' => 'add_status_change',
			'class' => 'statusChange',
			'action' => '',
			'method' => 'post'
		));
		
		$idstatus = $statusChange->AddChild(new FE_Select(Array(
			'name' => 'status',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_CHANGE_STATUS'),
			'options' => FE_Option::Make(App::getModel('orderstatus')->getOrderStatusToSelect())
		)));
		
		$statusChange->AddChild(new FE_Textarea(Array(
			'name' => 'comment',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_CHANGE_COMMENT'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SUGGEST, $idstatus, Array(
					App::getModel('orderstatus'),
					'getDefaultComment'
				))
			)
		)));
		
		$statusChange->AddChild(new FE_Checkbox(Array(
			'name' => 'inform',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_CHANGE_INFORM_CLIENT')
		)));
		
		$statusChange->AddChild(new FE_Submit(Array(
			'name' => 'update',
			'label' => $this->registry->core->getMessage('TXT_VIEW_ORDER_CHANGE_UPDATE'),
			'icon' => '_images_panel/icons/buttons/flag-green.png'
		)));
		
		$statusChange->Populate(Array(
			'status' => $order['current_status_id']
		));
		
		$statusChange->AddFilter(new FE_FilterTrim());
		
		$addNotes->AddFilter(new FE_FilterTrim());
		$addNotes->AddFilter(new FE_FilterNoCode());
		
		if ($addNotes->Validate(FE::SubmittedData())){
			try{
				$notes = $addNotes->getSubmitValues(FE_Form::FORMAT_FLAT);
				switch ($notes['ordernotes']) {
					case 0:
						$this->model->addOrderNotes($addNotes->getSubmitValues(FE_Form::FORMAT_FLAT), $order['id']);
						break;
					case 1:
						$this->model->addProductNotes($addNotes->getSubmitValues(FE_Form::FORMAT_FLAT), $order['id']);
						break;
					case 2:
						$this->model->addClientNotes($addNotes->getSubmitValues(FE_Form::FORMAT_FLAT), $order['clientid']);
						break;
				}
				App::redirect(__ADMINPANE__ . '/order/edit/' . (int) $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
		}
		
		if ($statusChange->Validate(FE::SubmittedData())){
			$this->model->addOrderHistory($statusChange->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			try{
				$email = $statusChange->getSubmitValues(FE_Form::FORMAT_FLAT);
				if ($email['inform'] == 1){
					$orderhistory = $this->model->getLastOrderHistory((int) $this->registry->core->getParam(), $email['status']);
					$this->registry->template->assign('orderid', (int) $this->registry->core->getParam());
					$this->registry->template->assign('orderhistory', $orderhistory);
					
					$mailer = new Mailer($this->registry);
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->loadContentToBody('orderhistory');
					$mailer->addAddress($orderhistory['email']);
					$mailer->addBCC($this->registry->session->getActiveShopEmail());
					$mailer->setSubject($this->registry->core->getMessage('TXT_CHANGE_ORDER_STATUS_NR') . $orderhistory['ids']);
					try{
						$mailer->Send();
					}
					catch (phpmailerException $e){
					
					}
				}
				$this->model->updateOrderStatus($_POST, $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
		}
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->updateOrderById($_POST, $this->registry->core->getParam());
				App::redirect(__ADMINPANE__ . '/order/');
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
		}
		$this->registry->template->assign('viewid', Helper::getViewId());
		$this->registry->xajaxInterface->registerFunction(array(
			'CalculateDeliveryCost',
			$this->model,
			'calculateDeliveryCostEdit'
		));
		$this->registry->xajaxInterface->registerFunction(array(
			'GetDispatchMethodForPrice',
			$this->model,
			'getDispatchMethodForPriceForAjaxEdit'
		));
		$this->registry->template->assign('statusChange', $statusChange);
		$this->registry->template->assign('addNotes', $addNotes);
		$this->registry->template->assign('orderProductNotes', $this->model->getOrderProductNotes($this->registry->core->getParam()));
		$this->registry->template->assign('orderNotes', $orderNotes);
		$this->registry->template->assign('clientNotes', $clientNotes);
		$this->registry->template->assign('clientOrderHistory', $clientOrderHistory);
		$this->registry->template->assign('order', $order);
		$this->registry->template->assign('currencyid', $this->registry->session->getActiveCurrencyId());
		$this->registry->template->assign('currencysymbol', $this->registry->session->getActiveCurrencySymbol());
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function confirm ()
	{
		$this->disableLayout();
		$tpl = $this->loadTemplate('confirm.tpl');
		App::getModel('order')->getPrintableOrderById((int) $this->registry->core->getParam(), $tpl);
	}
}