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
 * $Id: producer.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class producerController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('producer');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteProducer',
			$this->model,
			'doAJAXDeleteProducer'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllProducer',
			$this->model,
			'getProducerForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
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
			'name' => 'add_producer',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_PRODUCER_NAME_ALREADY_EXISTS'), 'producertranslation', 'name')
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_SEO_URL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SEO_URL'))
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'rows' => 10
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'deliverer',
			'label' => $this->registry->core->getMessage('TXT_DELIVERER'),
			'options' => FE_Option::Make(App::getModel('deliverer')->getDelivererToSelect())
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
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$event = new sfEvent($this, 'admin.producer.renderForm', Array(
			'form' => &$form
		));
		
		$this->registry->dispatcher->notify($event);
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewProducer($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/producer/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/producer');
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
		
		$form = new FE_Form(Array(
			'name' => 'edit_producer',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'producertranslation', 'name', null, Array(
					'column' => 'producerid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'seo',
			'label' => $this->registry->core->getMessage('TXT_SEO_URL'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SEO_URL'))
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'description',
			'label' => $this->registry->core->getMessage('TXT_DESCRIPTION'),
			'rows' => 10
		)));
		
		$requiredData->AddChild(new FE_MultiSelect(Array(
			'name' => 'deliverer',
			'label' => $this->registry->core->getMessage('TXT_DELIVERER'),
			'options' => FE_Option::Make(App::getModel('deliverer')->getDelivererToSelect())
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
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawProducerData = $this->model->getProducerView($this->registry->core->getParam());
		
		$producerData = Array(
			'required_data' => Array(
				'language_data' => $rawProducerData['language'],
				'deliverer' => $rawProducerData['deliverers']
			),
			'meta_data' => Array(
				'language_data' => $rawProducerData['language']
			),
			'photos_pane' => Array(
				'photo' => $rawProducerData['photo']
			),
			'view_data' => Array(
				'view' => $rawProducerData['view']
			)
		);
		
		$event = new sfEvent($this, 'admin.producer.renderForm', Array(
			'form' => &$form
		));
		$this->registry->dispatcher->notify($event);
		
		$event2 = new sfEvent($this, 'admin.producer.populateForm');
		$this->registry->dispatcher->filter($event2, $this->registry->core->getParam());
		$arguments = $event2->getReturnValues();
		foreach ($arguments as $key => $Data){
			foreach ($Data as $tab => $values){
				$producerData[$tab] = $values;
			}
		}
		
		$form->Populate($producerData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editProducer($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/producer');
		}
		
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}