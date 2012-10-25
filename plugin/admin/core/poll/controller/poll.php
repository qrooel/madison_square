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
 * $Id: poll.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class pollController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllPoll',
			App::getModel('poll'),
			'getPollForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetQuestionsSuggestions',
			App::getModel('poll'),
			'getQuestionsForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeletePoll',
			App::getModel('poll'),
			'doAJAXDeletePoll'
		));
		$this->registry->xajax->registerFunction(array(
			'disablePoll',
			APP::getModel('poll'),
			'doAJAXDisablePoll'
		));
		$this->registry->xajax->registerFunction(array(
			'enablePoll',
			APP::getModel('poll'),
			'doAJAXEnablePoll'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('poll')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		$form = new FE_Form(Array(
			'name' => 'add_poll',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$langData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'lang_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$langData->AddChild(new FE_TextField(Array(
			'name' => 'questions',
			'label' => $this->registry->core->getMessage('TXT_QUESTIONS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_QUESTIONS'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$answers = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'answers_book',
			'label' => $this->registry->core->getMessage('TXT_ANSWERS_DATA')
		)));
		
		$answersData = $answers->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'answers_data',
			'label' => $this->registry->core->getMessage('TXT_ANSWERS_DATA'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$languages = App::getModel('language')->getLanguageALL();
		foreach ($languages as $language){
			$answersData->AddChild(new FE_TextField(Array(
				'name' => 'name_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_ANSWERS'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ANSWERS'))
				),
				'suffix' => '<img src="' . DESIGNPATH . '/_images_common/icons/languages/' . $language['flag'] . '" />'
			)));
		}
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('poll')->addNewPoll($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/poll/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/poll');
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
			'name' => 'edit_poll',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$langData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'lang_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$langData->AddChild(new FE_TextField(Array(
			'name' => 'questions',
			'label' => $this->registry->core->getMessage('TXT_QUESTIONS'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_QUESTIONS'))
			)
		)));
		
		$requiredData->AddChild(new FE_Checkbox(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'default' => '1'
		)));
		
		$answers = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'answers_book',
			'label' => $this->registry->core->getMessage('TXT_ANSWERS_DATA')
		)));
		
		$answersData = $answers->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'answers_data',
			'label' => $this->registry->core->getMessage('TXT_ANSWERS_DATA'),
			'repeat_min' => 1,
			'repeat_max' => FE::INFINITE
		)));
		
		$languages = App::getModel('language')->getLanguageALL();
		foreach ($languages as $language){
			$answersData->AddChild(new FE_TextField(Array(
				'name' => 'name_' . $language['id'],
				'label' => $this->registry->core->getMessage('TXT_ANSWERS'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_ANSWERS'))
				),
				'suffix' => '<img src="' . DESIGNPATH . '/_images_common/icons/languages/' . $language['flag'] . '" />'
			)));
		}
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES'),
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW'),
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		$rawPollData = App::getModel('poll')->getPollView($this->registry->core->getParam());
		
		$pollData = Array(
			'required_data' => Array(
				'publish' => $rawPollData['publish'],
				'lang_data' => $rawPollData['language']
			),
			'answers_book' => Array(
				'answers_data' => $rawPollData['answers']
			),
			'view_data' => Array(
				'view' => $rawPollData['view']
			)
		);
		
		$form->Populate($pollData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('poll')->editPoll($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/poll');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}