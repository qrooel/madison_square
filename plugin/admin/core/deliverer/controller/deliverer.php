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
 * $Id: deliverer.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class delivererController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('deliverer');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteDeliverer',
			$this->model,
			'doAJAXDeleteDeliverer'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllDeliverer',
			$this->model,
			'getDelivererForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('deliverer', $this->model->getDelivererAll());
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_deliverer',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'deliverertranslation', 'name')
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'www',
			'label' => $this->registry->core->getMessage('TXT_WWW'),
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': gekosale@gekosale.pl',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$relatedProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'related_products',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS')
		)));
		
		$relatedProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewDeliverer($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/deliverer/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/deliverer');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_deliverer',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'deliverertranslation', 'name', null, Array(
					'column' => 'delivererid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'www',
			'label' => $this->registry->core->getMessage('TXT_WWW'),
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': gekosale@gekosale.pl',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$relatedProducts = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'related_products',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS')
		)));
		
		$relatedProducts->AddChild(new FE_ProductSelect(Array(
			'name' => 'products',
			'label' => $this->registry->core->getMessage('TXT_SELECT_PRODUCTS'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_SINGLE_PHOTO'),
			'repeat_min' => 0,
			'repeat_max' => 1,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawDelivererData = $this->model->getDelivererView($this->registry->core->getParam());
		
		$form->Populate(Array(
			'required_data' => Array(
				'language_data' => $rawDelivererData['language']
			),
			'related_products' => Array(
				'products' => $this->model->getProductsForDelilverer((int) $this->registry->core->getParam())
			),
			'photos_pane' => Array(
				'photo' => $rawDelivererData['photo']
			)
		));
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editDeliverer($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/deliverer');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}