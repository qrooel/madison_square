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
 * $Id: staticblocks.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class staticblocksController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('staticblocks');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllStaticBlocks',
			$this->model,
			'getStaticBlocksForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetTopicSuggestions',
			$this->model,
			'getTopicForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteStaticBlocks',
			$this->model,
			'doAJAXDeleteStaticBlocks'
		));
		$this->registry->xajax->registerFunction(array(
			'disableStaticBlocks',
			$this->model,
			'doAJAXDisableStaticBlocks'
		));
		$this->registry->xajax->registerFunction(array(
			'enableStaticBlocks',
			$this->model,
			'doAJAXEnableStaticBlocks'
		));
		$this->registry->xajax->registerFunction(array(
			'doAJAXUpdateStaticblocks',
			$this->model,
			'doAJAXUpdateStaticblocks'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_staticblocks',
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
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000'
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'contentcategory',
			'label' => $this->registry->core->getMessage('TXT_CONTENT_CATEGORY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('contentcategory')->getContentCategoryALLToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTENT_CATEGORY'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewStaticBlocks($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/staticblocks/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/staticblocks');
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
			'name' => 'edit_staticblocks',
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
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'content',
			'label' => $this->registry->core->getMessage('TXT_CONTENT'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000'
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'contentcategoryid',
			'label' => $this->registry->core->getMessage('TXT_CONTENT_CATEGORY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('contentcategory')->getContentCategoryALLToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CONTENT_CATEGORY'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
		)));
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$rawStaticblocksData = $this->model->getStaticBlocksView($this->registry->core->getParam());
		
		$staticblocksData = Array(
			'required_data' => Array(
				'language_data' => $rawStaticblocksData['language'],
				'contentcategoryid' => $rawStaticblocksData['contentcategory'],
				'publish' => $rawStaticblocksData['publish']
			),
			'view_data' => Array(
				'view' => $rawStaticblocksData['view']
			)
		);
		
		$form->Populate($staticblocksData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editStaticBlocks($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/staticblocks');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}