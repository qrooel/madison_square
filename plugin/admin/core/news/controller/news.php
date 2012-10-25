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
 * $Revision: 681 $
 * $Author: gekosale $
 * $Date: 2012-04-25 22:05:12 +0200 (Śr, 25 kwi 2012) $
 * $Id: news.php 681 2012-04-25 20:05:12Z gekosale $
 */

class newsController extends Controller {

	public function __construct ($registry) {
		parent::__construct($registry);
		$this->model = App::getModel('news');
	}

	public function index () {
		$this->registry->xajax->registerFunction(array(
			'LoadAllNews',
			$this->model,
			'getNewsForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetTopicSuggestions',
			$this->model,
			'getTopicForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteNews',
			$this->model,
			'doAJAXDeleteNews'
		));
		$this->registry->xajax->registerFunction(array(
			'disableNews',
			$this->model,
			'doAJAXDisableNews'
		));
		$this->registry->xajax->registerFunction(array(
			'enableNews',
			$this->model,
			'doAJAXEnableNews'
		));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add () {
		
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		
		$form = new FE_Form(Array(
			'name' => 'add_news',
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
			'name' => 'topic',
			'label' => $this->registry->core->getMessage('TXT_TOPIC'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOPIC')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_TOPIC_ALREADY_EXISTS'), 'newstranslation', 'topic')
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
			'name' => 'summary',
			'label' => $this->registry->core->getMessage('TXT_NEWS_SUMMARY')
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'featured',
			'label' => $this->registry->core->getMessage('Polecany'),
			'default' => '1'
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
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
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
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewNews($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/news/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/news');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit () {
		
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		
		$rawNewsData = $this->model->getNewsView((int) $this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_news',
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
			'name' => 'topic',
			'label' => $this->registry->core->getMessage('TXT_TOPIC'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOPIC')),
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_TOPIC_ALREADY_EXISTS'), 'newstranslation', 'topic', null, Array(
					'column' => 'newsid',
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
			'name' => 'summary',
			'label' => $this->registry->core->getMessage('TXT_NEWS_SUMMARY')
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000',
			'max_length' => 5000
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'featured',
			'label' => $this->registry->core->getMessage('Polecany'),
			'default' => '1'
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
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add',
			'main_id' => $rawNewsData['mainphotoid']
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
		
		$newsData = Array(
			'required_data' => Array(
				'publish' => $rawNewsData['publish'],
				'featured' => $rawNewsData['featured'],
				'language_data' => $rawNewsData['language']
			),
			'meta_data' => Array(
				'language_data' => $rawNewsData['language']
			),
			'photos_pane' => Array(
				'photo' => $rawNewsData['photo']
			),
			'view_data' => Array(
				'view' => $rawNewsData['view']
			)
		);
		
		$form->Populate($newsData);
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editNews($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/news');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajaxInterface->registerFunction(array(
			'doAJAXCreateSeo',
			App::getModel('seo'),
			'doAJAXCreateSeo'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}