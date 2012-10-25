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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: view.php 687 2012-09-01 12:02:47Z gekosale $
 */

class viewController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteView',
			App::getModel('view'),
			'doAJAXDeleteView'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllView',
			App::getModel('view'),
			'getViewForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_view',
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
				new FE_RuleUnique($this->registry->core->getMessage('ERR_VIEW_ALREADY_EXISTS'), 'view', 'name')
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'namespace',
			'label' => $this->registry->core->getMessage('TXT_NAMESPACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAMESPACE'))
			),
			'default' => 'core'
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'store',
			'label' => $this->registry->core->getMessage('TXT_STORE'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('store')->getStoreToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STORE'))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'showtax',
			'label' => $this->registry->core->getMessage('TXT_SHOW_TAX_VALUE'),
			'options' => FE_Option::Make(App::getModel('suffix/suffix')->getPrice()),
			'default' => 1
		)));
		
		$offline = $requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'offline',
			'label' => $this->registry->core->getMessage('TXT_SHOP_OFFLINE'),
			'comment' => $this->registry->core->getMessage('TXT_OFFLINE_INSTRUCTION')
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'offlinetext',
			'label' => $this->registry->core->getMessage('TXT_OFFLINE_MESSAGE'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'rows' => 50,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $offline, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$metaData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'meta_data',
			'label' => $this->registry->core->getMessage('TXT_META_INFORMATION')
		)));
		
		$languageData = $metaData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'keyword_title',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword_description',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS')
		)));
		
		$url = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'url_pane',
			'label' => $this->registry->core->getMessage('TXT_WWW')
		)));
		
		$urlData = $url->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'url_data',
			'label' => $this->registry->core->getMessage('TXT_URL'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$urlData->AddChild(new FE_TextField(Array(
			'name' => 'url',
			'label' => $this->registry->core->getMessage('TXT_URL'),
			'prefix' => 'http://',
			'comment' => $this->registry->core->getMessage('TXT_WITHOUT_HTTP'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_URL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_URL_ALREADY_EXISTS'), 'viewurl', 'url')
			)
		)));
		
		$categoryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_pane',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY')
		)));
		
		$categoryPane->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_VIEW_CATEGORY_INSTRUCTION') . '</p>'
		)));
		
		$category = $categoryPane->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'sortable' => false,
			'selectable' => true,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$dispatchmethodPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethod_pane',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_PANE')
		)));
		
		$dispatchmethodPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'dispatchmethod',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD'),
			'options' => FE_Option::Make(App::getModel('dispatchmethod')->getDispatchmethodToSelect())
		)));
		
		$paymentmethodPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'paymentmethod_pane',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD_PANE')
		)));
		
		$paymentmethodPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'paymentmethod',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD'),
			'options' => FE_Option::Make(App::getModel('paymentmethod')->getPaymentmethodToSelect())
		)));
		
		$assignToGroupData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'assigntogroup_data',
			'label' => $this->registry->core->getMessage('TXT_AUTOMATICLY_ASSIGN_TO_GROUP')
		)));
		
		$assignToGroupData->AddChild(new FE_Select(Array(
			'name' => 'taxes',
			'label' => $this->registry->core->getMessage('TXT_TAKE_THE_VALUE'),
			'options' => Array(
				new FE_Option('0', $this->registry->core->getMessage('TXT_NETTO')),
				new FE_Option('1', $this->registry->core->getMessage('TXT_PRICE_GROSS'))
			),
			'suffix' => $this->registry->core->getMessage('TXT_CLIENT_ORDERS')
		)));
		
		$assignToGroupData->AddChild(new FE_Select(Array(
			'name' => 'periodid',
			'label' => $this->registry->core->getMessage('TXT_PERIOD'),
			'options' => FE_Option::Make(App::getModel('period/period')->getPeriod())
		)));
		
		$assignToGroupData->AddChild(new FE_RangeEditor(Array(
			'name' => 'table',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'allow_vat' => false,
			'options' => FE_Option::Make(App::getModel('clientgroup')->getClientGroupToRangeEditor())
		)));
		
		$analitycsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'analitycs_data',
			'label' => $this->registry->core->getMessage('TXT_ANALYTICS_DATA')
		)));
		
		$analitycsData->AddChild(new FE_TextField(Array(
			'name' => 'gacode',
			'label' => $this->registry->core->getMessage('TXT_GA_CODE')
		)));
		
		$analitycsData->AddChild(new FE_Checkbox(Array(
			'name' => 'gatransactions',
			'label' => $this->registry->core->getMessage('TXT_GA_TRANSACTIONS')
		)));
		
		$analitycsData->AddChild(new FE_Checkbox(Array(
			'name' => 'gapages',
			'label' => $this->registry->core->getMessage('TXT_GA_PAGES')
		)));
		
		$facebookData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'facebook_data',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_DATA')
		)));
		
		$facebookData->AddChild(new FE_TextField(Array(
			'name' => 'faceboookauthkey',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_AUTHKEY')
		)));
		
		$facebookData->AddChild(new FE_Password(Array(
			'name' => 'faceboooksecret',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_SECRET')
		)));
		
		$registrationData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'registration_data',
			'label' => $this->registry->core->getMessage('TXT_REGISTRATION_SETTINGS')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'forcelogin',
			'label' => $this->registry->core->getMessage('TXT_FORCE_CLIENT_LOGIN'),
			'comment' => $this->registry->core->getMessage('TXT_FORCE_CLIENT_LOGIN_HELP')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'enableregistration',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_REGISTRATION'),
			'comment' => $this->registry->core->getMessage('TXT_ENABLE_REGISTRATION_HELP')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmregistration',
			'label' => $this->registry->core->getMessage('TXT_REGISTRATION_CONFIRM'),
			'comment' => $this->registry->core->getMessage('TXT_REGISTRATION_CONFIRM_HELP')
		)));
		
		$cartData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'cart_data',
			'label' => $this->registry->core->getMessage('TXT_CART_SETTINGS')
		)));
		
		$cartData->AddChild(new FE_Select(Array(
			'name' => 'cartredirect',
			'label' => $this->registry->core->getMessage('TXT_CART_REDIRECT'),
			'options' => Array(
				new FE_Option('', $this->registry->core->getMessage('TXT_NO_CART_REDIRECT')),
				new FE_Option('cart', $this->registry->core->getMessage('TXT_CONTROLLER_CART'))
			)
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enableopinions',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_OPINIONS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enabletags',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_TAGS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enablerss',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_RSS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'catalogmode',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_CATALOG_MODE')
		)));
		
		$cartData->AddChild(new FE_TextField(Array(
			'name' => 'minimumordervalue',
			'label' => $this->registry->core->getMessage('TXT_MINIMUM_ORDER_VALUE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'default' => 0
		)));
		
		$orderData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'order_data',
			'label' => $this->registry->core->getMessage('TXT_ORDER_SETTINGS')
		)));
		
		$confirmorder = $orderData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmorder',
			'label' => $this->registry->core->getMessage('TXT_FORCE_ORDER_CONFIRM')
		)));
		
		$orderData->AddChild(new FE_Select(Array(
			'name' => 'confirmorderstatusid',
			'label' => $this->registry->core->getMessage('TXT_CONFIRM_ORDER_STATUS_ID'),
			'options' => FE_Option::Make(App::getModel('orderstatus')->getOrderStatusToSelect()),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $confirmorder, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$orderData->AddChild(new FE_Checkbox(Array(
			'name' => 'guestcheckout',
			'label' => $this->registry->core->getMessage('TXT_GUEST_CHECKOUT')
		)));
		
		$ordernotifyaddresses = $orderData->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'ordernotifyaddresses_data',
			'label' => 'Dodatkowe adresy do powiadomień',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$ordernotifyaddresses->AddChild(new FE_TextField(Array(
			'name' => 'ordernotifyaddresses',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'logo',
			'label' => $this->registry->core->getMessage('TXT_LOGO')
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_LOGO'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'png',
				'jpg',
				'gif'
			)
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'favicon',
			'label' => $this->registry->core->getMessage('TXT_FAVICON'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'ico'
			)
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'watermark',
			'label' => $this->registry->core->getMessage('TXT_WATERMARK'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'png'
			)
		)));
		
		$orderUploaderData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'order_uploader_data',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_DATA')
		)));
		
		$uploaderenabled = $orderUploaderData->AddChild(new FE_Checkbox(Array(
			'name' => 'uploaderenabled',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_ENABLED')
		)));
		
		$orderUploaderData->AddChild(new FE_TextField(Array(
			'name' => 'uploadmaxfilesize',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_MAX_FILESIZE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'mb',
			'default' => 10,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$orderUploaderData->AddChild(new FE_TextField(Array(
			'name' => 'uploadchunksize',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_CHUNKSIZE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'kb',
			'default' => 100,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$allowedExtensions = array(
			'csv',
			'xml',
			'png',
			'gif',
			'jpg',
			'jpeg',
			'txt',
			'doc',
			'xls',
			'mpp',
			'pdf',
			'vsd',
			'ppt',
			'docx',
			'xlsx',
			'pptx',
			'tif',
			'zip',
			'tgz'
		);
		natsort($allowedExtensions);
		
		foreach ($allowedExtensions as $key){
			$tmp[] = new FE_Option($key, $key);
		}
		
		$orderUploaderData->AddChild(new FE_MultiSelect(Array(
			'name' => 'uploadextensions',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_ALLOWED_EXTENSIONS'),
			'options' => $tmp,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$invoicedata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'invoice_data',
			'label' => $this->registry->core->getMessage('TXT_INVOICE')
		)));
		
		$invoicedata->AddChild(new FE_Select(Array(
			'name' => 'invoicenumerationkind',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_NUMERATION'),
			'options' => FE_Option::Make(Array(
				'ntmr' => 'numer faktury w roku / typ faktury / miesiąc / rok',
				'trmn' => 'typ faktury / rok / miesiąc / numer faktury w roku',
				'tmnr' => 'typ faktury / miesiąc / numer faktury w roku / rok',
				'tnr' => 'typ faktury / numer faktury w roku / rok',
				'trn' => 'typ faktury / rok / numer faktury w roku',
				'rnt' => 'rok / numer faktury w roku / typ faktury',
				'rtn' => 'rok / typ faktury / numer faktury w roku'
			))
		)));
		
		$invoicedata->AddChild(new FE_TextField(Array(
			'name' => 'invoicedefaultpaymentdue',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_DEFAULT_PAYMENT_DUE'),
			'default' => 7,
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			)
		)));
		
		$apidata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'api_data',
			'label' => $this->registry->core->getMessage('TXT_API')
		)));
		
		$apidata->AddChild(new FE_TextField(Array(
			'name' => 'apikey',
			'label' => $this->registry->core->getMessage('TXT_API_KEY')
		)));
		
		$event = new sfEvent($this, 'admin.view.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('view')->addView($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/view/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/view');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		$ViewData = App::getModel('view')->getView((int) $this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_view',
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
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'namespace',
			'label' => $this->registry->core->getMessage('TXT_NAMESPACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAMESPACE'))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'store',
			'label' => $this->registry->core->getMessage('TXT_STORE'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('store')->getStoreToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STORE'))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'showtax',
			'label' => $this->registry->core->getMessage('TXT_SHOW_TAX_VALUE'),
			'options' => FE_Option::Make(App::getModel('suffix/suffix')->getPrice()),
			'default' => 1
		)));
		
		$offline = $requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'offline',
			'label' => $this->registry->core->getMessage('TXT_SHOP_OFFLINE'),
			'comment' => $this->registry->core->getMessage('TXT_OFFLINE_INSTRUCTION')
		)));
		
		$requiredData->AddChild(new FE_Textarea(Array(
			'name' => 'offlinetext',
			'label' => $this->registry->core->getMessage('TXT_OFFLINE_MESSAGE'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'rows' => 50,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $offline, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$metaData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'meta_data',
			'label' => $this->registry->core->getMessage('TXT_META_INFORMATION')
		)));
		
		$languageData = $metaData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'keyword_title',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword_description',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS')
		)));
		
		$url = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'url_pane',
			'label' => $this->registry->core->getMessage('TXT_WWW')
		)));
		
		$urlData = $url->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'url_data',
			'label' => $this->registry->core->getMessage('TXT_URL'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$urlData->AddChild(new FE_TextField(Array(
			'name' => 'url',
			'label' => $this->registry->core->getMessage('TXT_URL'),
			'prefix' => 'http://',
			'comment' => $this->registry->core->getMessage('TXT_WITHOUT_HTTP'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_URL')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_URL_ALREADY_EXISTS'), 'viewurl', 'url', null, Array(
					'column' => 'viewid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$categoryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_pane',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY')
		)));
		
		$categoryPane->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_VIEW_CATEGORY_INSTRUCTION') . '</p>'
		)));
		
		$category = $categoryPane->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'sortable' => false,
			'selectable' => true,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$dispatchmethodPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'dispatchmethod_pane',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_PANE')
		)));
		
		$dispatchmethodPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'dispatchmethod',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD'),
			'options' => FE_Option::Make(App::getModel('dispatchmethod')->getDispatchmethodToSelect())
		)));
		
		$paymentmethodPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'paymentmethod_pane',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD_PANE')
		)));
		
		$paymentmethodPane->AddChild(new FE_MultiSelect(Array(
			'name' => 'paymentmethod',
			'label' => $this->registry->core->getMessage('TXT_PAYMENTMETHOD'),
			'options' => FE_Option::Make(App::getModel('paymentmethod')->getPaymentmethodToSelect())
		)));
		
		$assignToGroupData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'assigntogroup_data',
			'label' => $this->registry->core->getMessage('TXT_AUTOMATICLY_ASSIGN_TO_GROUP')
		)));
		
		$assignToGroupData->AddChild(new FE_Select(Array(
			'name' => 'taxes',
			'label' => $this->registry->core->getMessage('TXT_TAKE_THE_VALUE'),
			'options' => Array(
				new FE_Option('0', $this->registry->core->getMessage('TXT_NETTO')),
				new FE_Option('1', $this->registry->core->getMessage('TXT_PRICE_GROSS'))
			),
			'suffix' => $this->registry->core->getMessage('TXT_CLIENT_ORDERS')
		)));
		
		$assignToGroupData->AddChild(new FE_Select(Array(
			'name' => 'periodid',
			'label' => $this->registry->core->getMessage('TXT_PERIOD'),
			'options' => FE_Option::Make(App::getModel('period/period')->getPeriod())
		)));
		
		$assignToGroupData->AddChild(new FE_RangeEditor(Array(
			'name' => 'table',
			'label' => $this->registry->core->getMessage('TXT_DISPATCHMETHOD_TABLE_PRICE'),
			'suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'range_suffix' => $this->registry->core->getMessage('TXT_CURRENCY'),
			'allow_vat' => false,
			'options' => FE_Option::Make(App::getModel('clientgroup')->getClientGroupToRangeEditor())
		)));
		
		$analitycsData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'analitycs_data',
			'label' => $this->registry->core->getMessage('TXT_ANALYTICS_DATA')
		)));
		
		$analitycsData->AddChild(new FE_TextField(Array(
			'name' => 'gacode',
			'label' => $this->registry->core->getMessage('TXT_GA_CODE')
		)));
		
		$analitycsData->AddChild(new FE_Checkbox(Array(
			'name' => 'gatransactions',
			'label' => $this->registry->core->getMessage('TXT_GA_TRANSACTIONS')
		)));
		
		$analitycsData->AddChild(new FE_Checkbox(Array(
			'name' => 'gapages',
			'label' => $this->registry->core->getMessage('TXT_GA_PAGES')
		)));
		
		$facebookData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'facebook_data',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_DATA')
		)));
		
		$facebookData->AddChild(new FE_TextField(Array(
			'name' => 'faceboookappid',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_APP_ID')
		)));
		
		$facebookData->AddChild(new FE_Password(Array(
			'name' => 'faceboooksecret',
			'label' => $this->registry->core->getMessage('TXT_FACEBOOK_SECRET')
		)));
		
		$registrationData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'registration_data',
			'label' => $this->registry->core->getMessage('TXT_REGISTRATION_SETTINGS')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'forcelogin',
			'label' => $this->registry->core->getMessage('TXT_FORCE_CLIENT_LOGIN'),
			'comment' => $this->registry->core->getMessage('TXT_FORCE_CLIENT_LOGIN_HELP')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'enableregistration',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_REGISTRATION'),
			'comment' => $this->registry->core->getMessage('TXT_ENABLE_REGISTRATION_HELP')
		)));
		
		$registrationData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmregistration',
			'label' => $this->registry->core->getMessage('TXT_REGISTRATION_CONFIRM'),
			'comment' => $this->registry->core->getMessage('TXT_REGISTRATION_CONFIRM_HELP')
		)));
		
		$cartData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'cart_data',
			'label' => $this->registry->core->getMessage('TXT_CART_SETTINGS')
		)));
		
		$cartData->AddChild(new FE_Select(Array(
			'name' => 'cartredirect',
			'label' => $this->registry->core->getMessage('TXT_CART_REDIRECT'),
			'options' => Array(
				new FE_Option('', $this->registry->core->getMessage('TXT_NO_CART_REDIRECT')),
				new FE_Option('cart', $this->registry->core->getMessage('TXT_CONTROLLER_CART'))
			)
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enableopinions',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_OPINIONS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enabletags',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_TAGS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'enablerss',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_RSS')
		)));
		
		$cartData->AddChild(new FE_Checkbox(Array(
			'name' => 'catalogmode',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_CATALOG_MODE')
		)));
		
		$cartData->AddChild(new FE_TextField(Array(
			'name' => 'minimumordervalue',
			'label' => $this->registry->core->getMessage('TXT_MINIMUM_ORDER_VALUE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'default' => 0
		)));
		
		$orderData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'order_data',
			'label' => $this->registry->core->getMessage('TXT_ORDER_SETTINGS')
		)));
		
		$confirmorder = $orderData->AddChild(new FE_Checkbox(Array(
			'name' => 'confirmorder',
			'label' => $this->registry->core->getMessage('TXT_FORCE_ORDER_CONFIRM')
		)));
		
		$orderData->AddChild(new FE_Select(Array(
			'name' => 'confirmorderstatusid',
			'label' => $this->registry->core->getMessage('TXT_CONFIRM_ORDER_STATUS_ID'),
			'options' => FE_Option::Make(App::getModel('orderstatus')->getOrderStatusToSelect()),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $confirmorder, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$orderData->AddChild(new FE_Checkbox(Array(
			'name' => 'guestcheckout',
			'label' => $this->registry->core->getMessage('TXT_GUEST_CHECKOUT')
		)));
		
		$ordernotifyaddresses = $orderData->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'ordernotifyaddresses_data',
			'label' => 'Dodatkowe adresy do powiadomień',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$ordernotifyaddresses->AddChild(new FE_TextField(Array(
			'name' => 'ordernotifyaddresses',
			'label' => $this->registry->core->getMessage('TXT_EMAIL')
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'logo',
			'label' => $this->registry->core->getMessage('TXT_LOGO')
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_LOGO'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'png',
				'jpg',
				'gif'
			)
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'favicon',
			'label' => $this->registry->core->getMessage('TXT_FAVICON'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'ico'
			)
		)));
		
		$photosPane->AddChild(new FE_LocalFile(Array(
			'name' => 'watermark',
			'label' => $this->registry->core->getMessage('TXT_WATERMARK'),
			'file_source' => 'design/_images_frontend/core/logos/',
			'file_types' => Array(
				'png'
			)
		)));
		
		$orderUploaderData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'order_uploader_data',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_DATA')
		)));
		
		$uploaderenabled = $orderUploaderData->AddChild(new FE_Checkbox(Array(
			'name' => 'uploaderenabled',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_ENABLED')
		)));
		
		$orderUploaderData->AddChild(new FE_TextField(Array(
			'name' => 'uploadmaxfilesize',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_MAX_FILESIZE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'mb',
			'default' => 10,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$orderUploaderData->AddChild(new FE_TextField(Array(
			'name' => 'uploadchunksize',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_CHUNKSIZE'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'kb',
			'default' => 100,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$allowedExtensions = array(
			'csv',
			'xml',
			'png',
			'gif',
			'jpg',
			'jpeg',
			'txt',
			'doc',
			'xls',
			'mpp',
			'pdf',
			'vsd',
			'ppt',
			'docx',
			'xlsx',
			'pptx',
			'tif',
			'zip',
			'tgz'
		);
		natsort($allowedExtensions);
		
		foreach ($allowedExtensions as $key){
			$tmp[] = new FE_Option($key, $key);
		}
		
		$orderUploaderData->AddChild(new FE_MultiSelect(Array(
			'name' => 'uploadextensions',
			'label' => $this->registry->core->getMessage('TXT_ORDER_UPLOADER_ALLOWED_EXTENSIONS'),
			'options' => $tmp,
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $uploaderenabled, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$invoicedata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'invoice_data',
			'label' => $this->registry->core->getMessage('TXT_INVOICE')
		)));
		
		$invoicedata->AddChild(new FE_Select(Array(
			'name' => 'invoicenumerationkind',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_NUMERATION'),
			'options' => FE_Option::Make(Array(
				'ntmr' => 'numer faktury w roku / typ faktury / miesiąc / rok',
				'trmn' => 'typ faktury / rok / miesiąc / numer faktury w roku',
				'tmnr' => 'typ faktury / miesiąc / numer faktury w roku / rok',
				'tnr' => 'typ faktury / numer faktury w roku / rok',
				'trn' => 'typ faktury / rok / numer faktury w roku',
				'rnt' => 'rok / numer faktury w roku / typ faktury',
				'rtn' => 'rok / typ faktury / numer faktury w roku'
			))
		)));
		
		$invoicedata->AddChild(new FE_TextField(Array(
			'name' => 'invoicedefaultpaymentdue',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_DEFAULT_PAYMENT_DUE'),
			'suffix' => $this->registry->core->getMessage('TXT_DAYS'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			)
		)));
		
		$apidata = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'api_data',
			'label' => $this->registry->core->getMessage('TXT_API')
		)));
		
		$apidata->AddChild(new FE_TextField(Array(
			'name' => 'apikey',
			'label' => $this->registry->core->getMessage('TXT_API_KEY')
		)));
		
		$CurrentViewData = Array(
			'required_data' => Array(
				'name' => $ViewData['name'],
				'namespace' => $ViewData['namespace'],
				'store' => $ViewData['store'],
				'showtax' => $ViewData['showtax'],
				'offline' => $ViewData['offline'],
				'offlinetext' => $ViewData['offlinetext']
			),
			'meta_data' => Array(
				'language_data' => $ViewData['language']
			),
			'url_pane' => Array(
				'url_data' => Array(
					'url' => $ViewData['url']
				)
			),
			'category_pane' => Array(
				'category' => $ViewData['category']
			),
			'paymentmethod_pane' => Array(
				'paymentmethod' => $ViewData['paymentmethods']
			),
			'dispatchmethod_pane' => Array(
				'dispatchmethod' => $ViewData['dispatchmethods']
			),
			'assigntogroup_data' => Array(
				'taxes' => $ViewData['taxes'],
				'periodid' => $ViewData['periodid'],
				'table' => App::getModel('clientgroup')->getAssignToGroupPerView((int) $this->registry->core->getParam())
			),
			'analitycs_data' => Array(
				'gacode' => $ViewData['gacode'],
				'gapages' => $ViewData['gapages'],
				'gatransactions' => $ViewData['gatransactions']
			),
			'facebook_data' => Array(
				'faceboookappid' => $ViewData['faceboookappid'],
				'faceboooksecret' => $ViewData['faceboooksecret']
			),
			'registration_data' => Array(
				'forcelogin' => $ViewData['forcelogin'],
				'confirmregistration' => $ViewData['confirmregistration'],
				'enableregistration' => $ViewData['enableregistration']
			),
			'order_data' => Array(
				'confirmorder' => $ViewData['confirmorder'],
				'confirmorderstatusid' => $ViewData['confirmorderstatusid'],
				'guestcheckout' => $ViewData['guestcheckout'],
				'ordernotifyaddresses_data' => Array(
					'ordernotifyaddresses' => $ViewData['ordernotifyaddresses']
				)
			),
			'cart_data' => Array(
				'cartredirect' => $ViewData['cartredirect'],
				'enableopinions' => $ViewData['enableopinions'],
				'enabletags' => $ViewData['enabletags'],
				'enablerss' => $ViewData['enablerss'],
				'catalogmode' => $ViewData['catalogmode'],
				'minimumordervalue' => $ViewData['minimumordervalue']
			),
			'logo' => Array(
				'photo' => $ViewData['photo'],
				'favicon' => $ViewData['favicon'],
				'watermark' => $ViewData['watermark']
			),
			'order_uploader_data' => Array(
				'uploaderenabled' => $ViewData['uploaderenabled'],
				'uploadmaxfilesize' => $ViewData['uploadmaxfilesize'],
				'uploadchunksize' => $ViewData['uploadchunksize'],
				'uploadextensions' => $ViewData['uploadextensions']
			),
			'invoice_data' => Array(
				'invoicenumerationkind' => $ViewData['invoicenumerationkind'],
				'invoicedefaultpaymentdue' => $ViewData['invoicedefaultpaymentdue']
			),
			'api_data' => Array(
				'apikey' => $ViewData['apikey']
			)
		);
		
		$event = new sfEvent($this, 'admin.view.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.view.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$CurrentViewData[$tab] = $values;
			}
		}
		
		$form->Populate($CurrentViewData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('view')->editView($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/view');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}