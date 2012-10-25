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
 * $Revision: 692 $
 * $Author: gekosale $
 * $Date: 2012-09-06 23:10:30 +0200 (Cz, 06 wrz 2012) $
 * $Id: product.php 692 2012-09-06 21:10:30Z gekosale $ 
 */

class productController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('product');
	}

	public function index ()
	{
		
		$this->registry->xajaxInterface->registerFunction(array(
			'LoadCategoryChildren',
			$this->model,
			'loadCategoryChildren'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteProduct',
			$this->model,
			'doAJAXDeleteProduct'
		));
		$this->registry->xajax->registerFunction(array(
			'doChangeProductStatus',
			$this->model,
			'doAJAXChangeProductStatus'
		));
		$this->registry->xajax->registerFunction(array(
			'setProductEnable',
			$this->model,
			'setProductEnable'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllProduct',
			$this->model,
			'getProductForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNamesForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doUpdateProduct',
			$this->model,
			'doAJAXUpdateProduct'
		));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->assign('productStatuses', json_encode(App::getModel('productstatus')->getProductstatusAll()));
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		
		$form = new FE_Form(Array(
			'name' => 'add_product',
			'action' => '',
			'method' => 'post'
		));
		
		$basicPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'basic_pane',
			'label' => $this->registry->core->getMessage('TXT_BASIC_INFORMATION')
		)));
		
		$basicLanguageData = $basicPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'producttranslation', 'name')
			)
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SEO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_SEO')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_ALPHANUMERIC_INVALID'), '/^[A-Za-z0-9-_\",\'\s]+$/')
			)
		)));
		
		$basicPane->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PRODUCT'),
			'default' => '1'
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'ean',
			'label' => $this->registry->core->getMessage('TXT_EAN')
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'delivelercode',
			'label' => $this->registry->core->getMessage('TXT_DELIVELERCODE')
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'producerid',
			'label' => $this->registry->core->getMessage('TXT_PRODUCER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('producer')->getProducerToSelect())
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'delivererid',
			'label' => $this->registry->core->getMessage('TXT_DELIVERER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('deliverer')->getDelivererToSelect())
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
			'name' => 'keywordtitle',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyworddescription',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$stockPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'stock_pane',
			'label' => $this->registry->core->getMessage('TXT_SHIPPING_STOCK_SETTINGS')
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'stock',
			'label' => $this->registry->core->getMessage('TXT_STOCK'),
			'comment' => $this->registry->core->getMessage('TXT_STANDARD_STOCK'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => $this->registry->core->getMessage('TXT_QTY')
		)));
		
		$stockPane->AddChild(new FE_Checkbox(Array(
			'name' => 'trackstock',
			'label' => $this->registry->core->getMessage('TXT_TRACKSTOCK')
		)));
		
		$stockPane->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">' . $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_DATA_HELP') . '</strong></p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'shippingcost',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_COST'),
			'suffix' => 'netto',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$categoryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_pane',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY')
		)));
		
		$categoryPane->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_EMPTY_CATEGORY_INSTRUCTION') . '</p>'
		)));
		
		$category = $categoryPane->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => false,
			'selectable' => true,
			'sortable' => false,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$pricePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'price_pane',
			'label' => $this->registry->core->getMessage('TXT_PRICE')
		)));
		
		$vat = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'vatid',
			'label' => $this->registry->core->getMessage('TXT_VAT'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('VAT')->getVATAll()),
			'suffix' => '%',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_VAT'))
			),
			'default' => 2
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$sellcurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'sellcurrencyid',
			'label' => $this->registry->core->getMessage('TXT_SELL_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$buycurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'buycurrencyid',
			'label' => $this->registry->core->getMessage('TXT_BUY_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$pricePane->AddChild(new FE_Price(Array(
			'name' => 'buyprice',
			'label' => $this->registry->core->getMessage('TXT_BUYPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BUYPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'vat_field' => $vat
		)));
		
		$standardPrice = $pricePane->AddChild(new FE_Fieldset(Array(
			'name' => 'standard_price',
			'label' => $this->registry->core->getMessage('TXT_STANDARD_SELLPRICE')
		)));
		
		$price = $standardPrice->AddChild(new FE_Price(Array(
			'name' => 'sellprice',
			'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat
		)));
		
		$enablePromotion = $standardPrice->AddChild(new FE_Checkbox(Array(
			'name' => 'promotion',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PROMOTION'),
			'default' => '0'
		)));
		
		$standardPrice->AddChild(new FE_Price(Array(
			'name' => 'discountprice',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat,
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
			
			$groups[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'groupid_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_GROUP_PRICE'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'sellprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $groups[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1)))
				)
			)));
			
			$promotion[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'promotion_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_CLIENTGROUP_PROMOTION'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'discountprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $promotion[$clientGroup['id']], new FE_ConditionEquals(1))
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
		
		$weightPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'weight_pane',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT_DATA')
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'weight',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_WEIGHT')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'kg',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => 1
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'deepth',
			'label' => $this->registry->core->getMessage('TXT_DEEPTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_Select(Array(
			'name' => 'unit',
			'label' => $this->registry->core->getMessage('TXT_UNIT_MEASURE'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_MEASURE_QTY')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_MEASURE_M2')),
				new FE_Option(3, 'Para'),
				new FE_Option(4, 'Komplet'),
			),
			'default' => 1
		)));
		
		$descriptionPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_pane',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$descriptionLanguageData = $descriptionPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'shortdescription',
			'label' => $this->registry->core->getMessage('TXT_SHORTDESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000,
			'rows' => 20
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 30
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'longdescription',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_INFO'),
			'rows' => 30
		)));
		
		$technicalDataPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'technical_data_pane',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA')
		)));
		
		$technicalDataPane->AddChild(new FE_TechnicalDataEditor(Array(
			'name' => 'technical_data',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA'),
			'product_id' => '',
			'set_id' => ''
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$filePane = $form->addChild(new FE_Fieldset(Array(
			'name' => 'files_pane',
			'label' => $this->registry->core->getMessage('TXT_FILES')
		)));
		
		$filePane->AddChild(new FE_Downloader(Array(
			'name' => 'file',
			'label' => $this->registry->core->getMessage('TXT_FILES'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'virtualproduct/add'
		)));
		
		$upsellProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'upsell_products',
			'label' => $this->registry->core->getMessage('TXT_UPSELL')
		)));
		
		$upsellProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'upsell',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$similarProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'similar_products',
			'label' => $this->registry->core->getMessage('TXT_SIMILAR_PRODUCT_LIST')
		)));
		
		$similarProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'similar',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$crosssellProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'crosssell_products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL')
		)));
		
		$crosssellProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'crosssell',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$statusProductPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'statusproduct_pane',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_STATUS')
		)));
		
		$idnew = $statusProductPane->AddChild(new FE_Checkbox(Array(
			'name' => 'newactive',
			'label' => $this->registry->core->getMessage('TXT_NEW')
		)));
		
		$newData = $statusProductPane->AddChild(new FE_Fieldset(Array(
			'name' => 'new_data',
			'label' => $this->registry->core->getMessage('TXT_NEW_DATA'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $idnew, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$newData->AddChild(new FE_Date(Array(
			'name' => 'endnew',
			'label' => $this->registry->core->getMessage('TXT_END_DATE')
		)));
		
		$groups = App::getModel('attributegroup/attributegroup')->getGroupsForCategory(0);
		
		if (! empty($groups)){
			
			$variantsPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'variants_pane',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS')
			)));
			
			$variantsPane->AddChild(new FE_ProductVariantsEditor(Array(
				'name' => 'variants',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS'),
				'category' => $category,
				'price' => $price,
				'vat_field' => $vat,
				'allow_generate' => true
			)));
		
		}
		
		$staticgroups = App::getModel('staticattribute')->getStaticAttributeFull();
		
		if (count($staticgroups) > 0){
			$staticAttributesPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'static_attributes_pane',
				'label' => $this->registry->core->getMessage('TXT_STATIC_ATTRIBUTES')
			)));
			
			foreach ($staticgroups['groups'] as $groupid => $values){
				$staticAttributesPane->AddChild(new FE_MultiSelect(Array(
					'name' => 'static_group_' . $groupid,
					'label' => $values['name'],
					'options' => FE_Option::Make($values['attributes'])
				)));
			}
		}
		
		$event = new sfEvent($this, 'admin.product.renderForm', Array(
			'form' => &$form
		));
		
		$this->registry->dispatcher->notify($event);
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewProduct($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/product/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/product');
			}
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		
		$PopulateData = $this->model->getProductAndAttributesById((int) $this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_product',
			'action' => '',
			'method' => 'post'
		));
		
		$basicPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'basic_pane',
			'label' => $this->registry->core->getMessage('TXT_BASIC_INFORMATION')
		)));
		
		$basicLanguageData = $basicPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$seoname = $basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'producttranslation', 'name', null, Array(
					'column' => 'productid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SEO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_SEO')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_ALPHANUMERIC_INVALID'), '/^[A-Za-z0-9-_\",\'\s]+$/')
			)
		)));
		
		$basicPane->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PRODUCT'),
			'default' => '0'
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'ean',
			'label' => $this->registry->core->getMessage('TXT_EAN')
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'delivelercode',
			'label' => $this->registry->core->getMessage('TXT_DELIVELERCODE')
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'producerid',
			'label' => $this->registry->core->getMessage('TXT_PRODUCER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('producer')->getProducerToSelect())
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'delivererid',
			'label' => $this->registry->core->getMessage('TXT_DELIVERER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('deliverer')->getDelivererToSelect())
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
			'name' => 'keywordtitle',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyworddescription',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$stockPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'stock_pane',
			'label' => $this->registry->core->getMessage('TXT_SHIPPING_STOCK_SETTINGS')
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'stock',
			'label' => $this->registry->core->getMessage('TXT_STOCK'),
			'comment' => $this->registry->core->getMessage('TXT_STANDARD_STOCK'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => $this->registry->core->getMessage('TXT_QTY')
		)));
		
		$stockPane->AddChild(new FE_Checkbox(Array(
			'name' => 'trackstock',
			'label' => $this->registry->core->getMessage('TXT_TRACKSTOCK')
		)));
		
		$stockPane->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">' . $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_DATA_HELP') . '</strong></p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'shippingcost',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_COST'),
			'suffix' => 'netto',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$categoryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_pane',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY')
		)));
		
		$categoryPane->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_EMPTY_CATEGORY_INSTRUCTION') . '</p>'
		)));
		
		$category = $categoryPane->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => false,
			'selectable' => true,
			'sortable' => false,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$pricePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'price_pane',
			'label' => $this->registry->core->getMessage('TXT_PRICE')
		)));
		
		$vat = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'vatid',
			'label' => $this->registry->core->getMessage('TXT_VAT'),
			'options' => FE_Option::Make(App::getModel('vat')->getVATAll()),
			'suffix' => '%'
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$sellcurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'sellcurrencyid',
			'label' => $this->registry->core->getMessage('TXT_SELL_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$buycurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'buycurrencyid',
			'label' => $this->registry->core->getMessage('TXT_BUY_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$pricePane->AddChild(new FE_Price(Array(
			'name' => 'buyprice',
			'label' => $this->registry->core->getMessage('TXT_BUYPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BUYPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'vat_field' => $vat
		)));
		
		$standardPrice = $pricePane->AddChild(new FE_Fieldset(Array(
			'name' => 'standard_price',
			'label' => $this->registry->core->getMessage('TXT_STANDARD_SELLPRICE')
		)));
		
		$price = $standardPrice->AddChild(new FE_Price(Array(
			'name' => 'sellprice',
			'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat
		)));
		
		$enablePromotion = $standardPrice->AddChild(new FE_Checkbox(Array(
			'name' => 'promotion',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PROMOTION'),
			'default' => '0'
		)));
		
		$standardPrice->AddChild(new FE_Price(Array(
			'name' => 'discountprice',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat,
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
			
			$groups[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'groupid_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_GROUP_PRICE'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'sellprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $groups[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1)))
				)
			)));
			
			$promotion[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'promotion_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_CLIENTGROUP_PROMOTION'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'discountprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $promotion[$clientGroup['id']], new FE_ConditionEquals(1))
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
		
		$weightPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'weight_pane',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT_DATA')
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'weight',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_WEIGHT')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'kg',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'deepth',
			'label' => $this->registry->core->getMessage('TXT_DEEPTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_Select(Array(
			'name' => 'unit',
			'label' => $this->registry->core->getMessage('TXT_UNIT_MEASURE'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_MEASURE_QTY')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_MEASURE_M2')),
				new FE_Option(3, 'Para'),
				new FE_Option(4, 'Komplet'),
			),
			'default' => 1
		)));
		
		$descriptionPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_pane',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$descriptionLanguageData = $descriptionPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'shortdescription',
			'label' => $this->registry->core->getMessage('TXT_SHORTDESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000,
			'rows' => 20
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 30
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'longdescription',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_INFO'),
			'rows' => 30
		)));
		
		$technicalDataPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'technical_data_pane',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA')
		)));
		
		$technicalDataPane->AddChild(new FE_TechnicalDataEditor(Array(
			'name' => 'technical_data',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA'),
			'product_id' => (int) $this->registry->core->getParam()
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add',
			'main_id' => $PopulateData['mainphotoid']
		)));
		
		$filePane = $form->addChild(new FE_Fieldset(Array(
			'name' => 'files_pane',
			'label' => $this->registry->core->getMessage('TXT_FILES')
		)));
		
		$filePane->AddChild(new FE_Downloader(Array(
			'name' => 'file',
			'label' => $this->registry->core->getMessage('TXT_FILES'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'virtualproduct/add'
		)));
		
		$upsellProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'upsell_products',
			'label' => $this->registry->core->getMessage('TXT_UPSELL')
		)));
		
		$upsellProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'upsell',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'exclude' => $this->registry->core->getParam()
		)));
		
		$similarProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'similar_products',
			'label' => $this->registry->core->getMessage('TXT_SIMILAR_PRODUCT_LIST')
		)));
		
		$similarProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'similar',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'exclude' => (int) $this->registry->core->getParam()
		)));
		
		$crosssellProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'crosssell_products',
			'label' => $this->registry->core->getMessage('TXT_CROSSSELL')
		)));
		
		$crosssellProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'crosssell',
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE,
			'exclude' => (int) $this->registry->core->getParam()
		)));
		
		$statusProductPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'statusproduct_pane',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_STATUS')
		)));
		
		$idnew = $statusProductPane->AddChild(new FE_Checkbox(Array(
			'name' => 'newactive',
			'label' => $this->registry->core->getMessage('TXT_NEW')
		)));
		
		$newData = $statusProductPane->AddChild(new FE_Fieldset(Array(
			'name' => 'new_data',
			'label' => $this->registry->core->getMessage('TXT_NEW_DATA'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $idnew, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$newData->AddChild(new FE_Date(Array(
			'name' => 'endnew',
			'label' => $this->registry->core->getMessage('TXT_END_DATE')
		)));
		
		$set = App::getModel('attributegroup')->getSugestVariant((int) $this->registry->core->getParam());
		
		$groups = App::getModel('attributegroup/attributegroup')->getGroupsForCategory(0);
		
		if (! empty($groups)){
			
			$variantsPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'variants_pane',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS')
			)));
			
			$variantsPane->AddChild(new FE_ProductVariantsEditor(Array(
				'name' => 'variants',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS'),
				'category' => $category, // pole z kategoria, aby pobrac sugerowane zestawy cech (nieobslugiwane przez baze)
				'price' => $price, // pole z cena, aby moc na jej podstawie wyliczac cene wariantu produktu
				'set' => $set, // id zestawu cech dla tego produktu
				'vat_field' => $vat, // Pole ze stawka VAT,
				'allow_generate' => App::getModel('order')->checkProductWithAttributes($this->registry->core->getParam()),
				'photos' => $PopulateData['photo']
			)));
		
		}
		
		$staticgroups = App::getModel('staticattribute')->getStaticAttributeFull();
		
		if (count($staticgroups) > 0){
			$staticAttributesPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'static_attributes_pane',
				'label' => $this->registry->core->getMessage('TXT_STATIC_ATTRIBUTES')
			)));
			
			foreach ($staticgroups['groups'] as $groupid => $values){
				$staticAttributesPane->AddChild(new FE_MultiSelect(Array(
					'name' => 'static_group_' . $groupid,
					'label' => $values['name'],
					'options' => FE_Option::Make($values['attributes'])
				)));
			}
		}
		
		$form->AddFilter(new FE_FilterTrim());
		
		$groupPrice = App::getModel('product')->getProductGroupPrice($this->registry->core->getParam());
		
		$CurrentViewData = Array(
			'basic_pane' => Array(
				'language_data' => $PopulateData['language'],
				'ean' => $PopulateData['ean'],
				'enable' => $PopulateData['enable'],
				'delivelercode' => $PopulateData['delivelercode'],
				'producerid' => $PopulateData['producerid'],
				'delivererid' => $PopulateData['delivererid']
			),
			'meta_data' => Array(
				'language_data' => $PopulateData['language']
			),
			'category_pane' => Array(
				'category' => $PopulateData['category']
			),
			'price_pane' => Array(
				'vatid' => $PopulateData['vatid'],
				'buyprice' => $PopulateData['buyprice'],
				'buycurrencyid' => $PopulateData['buycurrencyid'],
				'sellcurrencyid' => $PopulateData['sellcurrencyid'],
				'standard_price' => Array(
					'sellprice' => $PopulateData['sellprice'],
					'promotion' => $PopulateData['promotion'],
					'discountprice' => $PopulateData['discountprice'],
					'promotionstart' => $PopulateData['promotionstart'],
					'promotionend' => $PopulateData['promotionend']
				)
			),
			'weight_pane' => Array(
				'weight' => $PopulateData['weight'],
				'width' => $PopulateData['width'],
				'height' => $PopulateData['height'],
				'deepth' => $PopulateData['deepth'],
				'unit' => $PopulateData['unit']
			),
			'stock_pane' => Array(
				'stock' => $PopulateData['standardstock'],
				'trackstock' => $PopulateData['trackstock'],
				'shippingcost' => $PopulateData['shippingcost']
			),
			'description_pane' => Array(
				'language_data' => $PopulateData['language']
			),
			'technical_data_pane' => Array(
				'technical_data' => App::getModel('TechnicalData')->GetValuesForProduct((int) $this->registry->core->getParam())
			),
			'crosssell_products' => Array(
				'crosssell' => App::getModel('crosssell')->getProductsDataGrid((int) $this->registry->core->getParam())
			),
			'upsell_products' => Array(
				'upsell' => App::getModel('upsell')->getProductsDataGrid((int) $this->registry->core->getParam())
			),
			'similar_products' => Array(
				'similar' => App::getModel('similarproduct')->getProductsDataGrid((int) $this->registry->core->getParam())
			),
			'photos_pane' => Array(
				'photo' => $PopulateData['photo']
			),
			'files_pane' => Array(
				'file' => $PopulateData['file']
			),
			'statusproduct_pane' => Array(
				'newactive' => $PopulateData['productnew']['newactive'],
				'new_data' => Array(
					'endnew' => $PopulateData['productnew']['endnew']
				)
			),
			'variants_pane' => Array(
				'variants' => $PopulateData['variants']
			),
			'static_attributes_pane' => $PopulateData['staticattributes']
		);
		
		foreach ($groupPrice as $key => $val){
			$CurrentViewData['price_pane'][$key] = $val;
		}
		
		$event = new sfEvent($this, 'admin.product.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.product.populateForm');
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
				$this->model->productUpdateAll($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			if (FE::IsAction('continue')){
				App::redirect(__ADMINPANE__ . '/product/edit/' . $this->registry->core->getParam());
			}
			else{
				App::redirect(__ADMINPANE__ . '/product');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('productName', isset($PopulateData['language'][Helper::getLanguageId()]['name']) ? $PopulateData['language'][Helper::getLanguageId()]['name'] : $PopulateData['language'][1]['name']);
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function duplicate ()
	{
		$form = new FE_Form(Array(
			'name' => 'duplicate_product',
			'action' => '',
			'method' => 'post'
		));
		
		$basicPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'basic_pane',
			'label' => $this->registry->core->getMessage('TXT_BASIC_INFORMATION')
		)));
		
		$basicLanguageData = $basicPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'producttranslation', 'name')
			)
		)));
		
		$basicLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SEO'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PRODUCT_SEO')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_ALPHANUMERIC_INVALID'), '/^[A-Za-z0-9-_\",\'\s]+$/')
			)
		)));
		
		$basicPane->AddChild(new FE_Checkbox(Array(
			'name' => 'enable',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PRODUCT'),
			'default' => '1'
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'ean',
			'label' => $this->registry->core->getMessage('TXT_EAN')
		)));
		
		$basicPane->AddChild(new FE_TextField(Array(
			'name' => 'delivelercode',
			'label' => $this->registry->core->getMessage('TXT_DELIVELERCODE')
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'producerid',
			'label' => $this->registry->core->getMessage('TXT_PRODUCER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('producer')->getProducerToSelect())
		)));
		
		$basicPane->AddChild(new FE_Select(Array(
			'name' => 'delivererid',
			'label' => $this->registry->core->getMessage('TXT_DELIVERER'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('deliverer')->getDelivererToSelect())
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
			'name' => 'keywordtitle',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_TITLE')
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyworddescription',
			'label' => $this->registry->core->getMessage('TXT_KEYWORD_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$languageData->AddChild(new FE_Textarea(Array(
			'name' => 'keyword',
			'label' => $this->registry->core->getMessage('TXT_KEYWORDS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000
		)));
		
		$stockPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'stock_pane',
			'label' => $this->registry->core->getMessage('TXT_SHIPPING_STOCK_SETTINGS')
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'stock',
			'label' => $this->registry->core->getMessage('TXT_STOCK'),
			'comment' => $this->registry->core->getMessage('TXT_STANDARD_STOCK'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => $this->registry->core->getMessage('TXT_QTY')
		)));
		
		$stockPane->AddChild(new FE_Checkbox(Array(
			'name' => 'trackstock',
			'label' => $this->registry->core->getMessage('TXT_TRACKSTOCK')
		)));
		
		$stockPane->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">' . $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_DATA_HELP') . '</strong></p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$stockPane->AddChild(new FE_TextField(Array(
			'name' => 'shippingcost',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_SHIPPING_COST'),
			'suffix' => 'netto',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$categoryPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'category_pane',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY')
		)));
		
		$categoryPane->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . $this->registry->core->getMessage('TXT_EMPTY_CATEGORY_INSTRUCTION') . '</p>'
		)));
		
		$category = $categoryPane->AddChild(new FE_Tree(Array(
			'name' => 'category',
			'label' => $this->registry->core->getMessage('TXT_CATEGORY'),
			'choosable' => false,
			'selectable' => true,
			'sortable' => false,
			'clickable' => false,
			'items' => App::getModel('category')->getChildCategories(),
			'load_children' => Array(
				App::getModel('category'),
				'getChildCategories'
			)
		)));
		
		$pricePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'price_pane',
			'label' => $this->registry->core->getMessage('TXT_PRICE')
		)));
		
		$vat = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'vatid',
			'label' => $this->registry->core->getMessage('TXT_VAT'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('VAT')->getVATAll()),
			'suffix' => '%',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_VAT'))
			)
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencyForSelect();
		
		$sellcurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'sellcurrencyid',
			'label' => $this->registry->core->getMessage('TXT_SELL_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$buycurrency = $pricePane->AddChild(new FE_Select(Array(
			'name' => 'buycurrencyid',
			'label' => $this->registry->core->getMessage('TXT_BUY_CURRENCY'),
			'options' => FE_Option::Make($currencies),
			'default' => $this->registry->session->getActiveShopCurrencyId()
		)));
		
		$pricePane->AddChild(new FE_Price(Array(
			'name' => 'buyprice',
			'label' => $this->registry->core->getMessage('TXT_BUYPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BUYPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'vat_field' => $vat
		)));
		
		$standardPrice = $pricePane->AddChild(new FE_Fieldset(Array(
			'name' => 'standard_price',
			'label' => $this->registry->core->getMessage('TXT_STANDARD_SELLPRICE')
		)));
		
		$price = $standardPrice->AddChild(new FE_Price(Array(
			'name' => 'sellprice',
			'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat
		)));
		
		$enablePromotion = $standardPrice->AddChild(new FE_Checkbox(Array(
			'name' => 'promotion',
			'label' => $this->registry->core->getMessage('TXT_ENABLE_PROMOTION'),
			'default' => '0'
		)));
		
		$standardPrice->AddChild(new FE_Price(Array(
			'name' => 'discountprice',
			'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'vat_field' => $vat,
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
			
			$groups[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'groupid_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_GROUP_PRICE'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'sellprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_SELLPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $groups[$clientGroup['id']], new FE_ConditionNot(new FE_ConditionEquals(1)))
				)
			)));
			
			$promotion[$clientGroup['id']] = $pricePane->AddChild(new FE_Checkbox(Array(
				'name' => 'promotion_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_ENABLE_CLIENTGROUP_PROMOTION'),
				'default' => '0'
			)));
			
			$pricePane->AddChild(new FE_Price(Array(
				'name' => 'discountprice_' . $clientGroup['id'],
				'label' => $this->registry->core->getMessage('TXT_DISCOUNTPRICE'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SELLPRICE')),
					new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
				),
				'vat_field' => $vat,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SHOW, $promotion[$clientGroup['id']], new FE_ConditionEquals(1))
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
		
		$weightPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'weight_pane',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT_DATA')
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'weight',
			'label' => $this->registry->core->getMessage('TXT_WEIGHT'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_WEIGHT')),
				new FE_RuleFormat($this->registry->core->getMessage('ERR_NUMERIC_INVALID'), '/[0-9]{1,}/')
			),
			'suffix' => 'kg',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => 1
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'width',
			'label' => $this->registry->core->getMessage('TXT_WIDTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'height',
			'label' => $this->registry->core->getMessage('TXT_HEIGHT'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_TextField(Array(
			'name' => 'deepth',
			'label' => $this->registry->core->getMessage('TXT_DEEPTH'),
			'suffix' => 'cm',
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			)
		)));
		
		$weightPane->AddChild(new FE_Select(Array(
			'name' => 'unit',
			'label' => $this->registry->core->getMessage('TXT_UNIT_MEASURE'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_MEASURE_QTY')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_MEASURE_M2')),
				new FE_Option(3, 'Para'),
				new FE_Option(4, 'Komplet'),
			),
			'default' => 1
		)));
		
		$descriptionPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'description_pane',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION')
		)));
		
		$descriptionLanguageData = $descriptionPane->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'shortdescription',
			'label' => $this->registry->core->getMessage('TXT_SHORTDESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 1000',
			'max_length' => 1000,
			'rows' => 20
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000,
			'rows' => 30
		)));
		
		$descriptionLanguageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'longdescription',
			'label' => $this->registry->core->getMessage('TXT_ADDITIONAL_INFO'),
			'rows' => 30
		)));
		
		$technicalDataPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'technical_data_pane',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA')
		)));
		
		$technicalDataPane->AddChild(new FE_TechnicalDataEditor(Array(
			'name' => 'technical_data',
			'label' => $this->registry->core->getMessage('TXT_TECHNICAL_DATA'),
			'product_id' => '',
			'set_id' => ''
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$filePane = $form->addChild(new FE_Fieldset(Array(
			'name' => 'files_pane',
			'label' => $this->registry->core->getMessage('TXT_FILES')
		)));
		
		$filePane->AddChild(new FE_Downloader(Array(
			'name' => 'file',
			'label' => $this->registry->core->getMessage('TXT_FILES'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'virtualproduct/add'
		)));
		
		$statusProductPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'statusproduct_pane',
			'label' => $this->registry->core->getMessage('TXT_PRODUCT_STATUS')
		)));
		
		$idnew = $statusProductPane->AddChild(new FE_Checkbox(Array(
			'name' => 'newactive',
			'label' => $this->registry->core->getMessage('TXT_NEW')
		)));
		
		$newData = $statusProductPane->AddChild(new FE_Fieldset(Array(
			'name' => 'new_data',
			'label' => $this->registry->core->getMessage('TXT_NEW_DATA'),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $idnew, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$newData->AddChild(new FE_Date(Array(
			'name' => 'endnew',
			'label' => $this->registry->core->getMessage('TXT_END_DATE')
		)));
		
		$groups = App::getModel('attributegroup/attributegroup')->getGroupsForCategory(0);
		
		if (! empty($groups)){
			
			$variantsPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'variants_pane',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS')
			)));
			
			$variantsPane->AddChild(new FE_ProductVariantsEditor(Array(
				'name' => 'variants',
				'label' => $this->registry->core->getMessage('TXT_PRODUCT_VARIANTS'),
				'category' => $category,
				'price' => $price,
				'vat_field' => $vat,
				'allow_generate' => true
			)));
		
		}
		
		$staticgroups = App::getModel('staticattribute')->getStaticAttributeFull();
		
		if (count($staticgroups) > 0){
			$staticAttributesPane = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'static_attributes_pane',
				'label' => $this->registry->core->getMessage('TXT_STATIC_ATTRIBUTES')
			)));
			
			foreach ($staticgroups['groups'] as $groupid => $values){
				$staticAttributesPane->AddChild(new FE_MultiSelect(Array(
					'name' => 'static_group_' . $groupid,
					'label' => $values['name'],
					'options' => FE_Option::Make($values['attributes'])
				)));
			}
		}
		
		
		$form->AddFilter(new FE_FilterTrim());
		
		$PopulateData = $this->model->getProductAndAttributesById((int) $this->registry->core->getParam(), true);
		
		foreach ($PopulateData['language'] as $languageid => $values){
			$PopulateData['language'][$languageid]['name'] = '';
			$PopulateData['language'][$languageid]['seo'] = '';
		}
		
		$CurrentViewData = Array(
			'basic_pane' => Array(
				'language_data' => $PopulateData['language'],
				'ean' => $PopulateData['ean'],
				'enable' => $PopulateData['enable'],
				'delivelercode' => $PopulateData['delivelercode'],
				'producerid' => $PopulateData['producerid'],
				'delivererid' => $PopulateData['delivererid']
			),
			'meta_data' => Array(
				'language_data' => $PopulateData['language']
			),
			'category_pane' => Array(
				'category' => $PopulateData['category']
			),
			'price_pane' => Array(
				'vatid' => $PopulateData['vatid'],
				'buyprice' => $PopulateData['buyprice'],
				'buycurrencyid' => $PopulateData['buycurrencyid'],
				'sellcurrencyid' => $PopulateData['sellcurrencyid'],
				'standard_price' => Array(
					'sellprice' => $PopulateData['sellprice'],
					'promotion' => $PopulateData['promotion'],
					'discountprice' => $PopulateData['discountprice'],
					'promotionstart' => $PopulateData['promotionstart'],
					'promotionend' => $PopulateData['promotionend']
				)
			),
			'weight_pane' => Array(
				'weight' => $PopulateData['weight'],
				'width' => $PopulateData['width'],
				'height' => $PopulateData['height'],
				'deepth' => $PopulateData['deepth'],
				'unit' => $PopulateData['unit']
			),
			'stock_pane' => Array(
				'stock' => $PopulateData['standardstock'],
				'trackstock' => $PopulateData['trackstock'],
				'shippingcost' => $PopulateData['shippingcost']
			),
			'description_pane' => Array(
				'language_data' => $PopulateData['language']
			),
			'technical_data_pane' => Array(
				'technical_data' => App::getModel('TechnicalData')->GetValuesForProduct((int) $this->registry->core->getParam())
			),
			'photos_pane' => Array(
				'photo' => $PopulateData['photo']
			),
			'files_pane' => Array(
				'file' => $PopulateData['file']
			),
			'statusproduct_pane' => Array(
				'newactive' => $PopulateData['productnew']['newactive'],
				'new_data' => Array(
					'endnew' => $PopulateData['productnew']['endnew']
				)
			),
			'variants_pane' => Array(
				'variants' => $PopulateData['variants']
			),
			'static_attributes_pane' => $PopulateData['staticattributes']
		);
		
		$event = new sfEvent($this, 'admin.product.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.product.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$CurrentViewData[$tab] = $values;
			}
		}
		
		$form->Populate($CurrentViewData);
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewProduct($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			App::redirect(__ADMINPANE__ . '/product');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('duplicate.tpl'));
	}
}