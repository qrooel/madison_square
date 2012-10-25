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
 * $Id: translation.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class TranslationController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteTranslation',
			App::getModel('translation'),
			'doAJAXDeleteTranslation'
		));
		$this->registry->xajax->registerFunction(array(
			'doUpdateTranslation',
			App::getModel('translation'),
			'doAJAXUpdateTranslation'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllTranslation',
			App::getModel('translation'),
			'getTranslationForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			App::getModel('translation'),
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetTranslationSuggestions',
			App::getModel('translation'),
			'getTranslationNameForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('translation')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$language = App::getModel('language')->getLanguageALLToSelect();
		
		$form = new FE_Form(Array(
			'name' => 'add_translation',
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
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': TXT_EXAMPLE',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'translation', 'name')
			)
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('translation')->addTranslation($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/translation/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/translation');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		$language = App::getModel('language')->getLanguageALLToSelect();
		
		$form = new FE_Form(Array(
			'name' => 'edit_translation',
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
			'comment' => $this->registry->core->getMessage('TXT_EXAMPLE') . ': TXT_EXAMPLE',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'translation', 'name', null, Array(
					'column' => 'idtranslation',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$languageData = $requiredData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'translation',
			'label' => $this->registry->core->getMessage('TXT_TRANSLATION'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSLATION'))
			)
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		$rawTranslationData = App::getModel('translation')->getTranslationView($this->registry->core->getParam());
		$translationData = Array(
			'required_data' => Array(
				'name' => $rawTranslationData['name'],
				'language_data' => $rawTranslationData['language']
			)
		);
		
		$form->Populate($translationData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				App::getModel('translation')->editTranslation($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/translation');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function view ()
	{
		$this->disableLayout();
		$sql = 'SELECT 
					T.name, 
					TD.translation 
				FROM translation T 
				LEFT JOIN translationdata TD ON T.idtranslation = TD.translationid AND TD.languageid = :languageid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$xml = new SimpleXMLElement('<rows></rows>');
		while ($rs->next()){
			$node = $xml->addChild('row');
			$name = $node->addChild('field', $rs->getString('name'));
			$name->addAttribute('name', 'name');
			$translation = $node->addChild('field', htmlspecialchars($rs->getString('translation')));
			$translation->addAttribute('name', 'translation');
		}
		header('Content-type: text/xml; charset=utf-8');
		header('Content-disposition: attachment; filename=pl_PL.xml');
		header('Content-type: text/xml');
		header('Cache-Control: max-age=0');
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;
		$domnode = dom_import_simplexml($xml);
		$domnode = $doc->importNode($domnode, true);
		$domnode = $doc->appendChild($domnode);
		echo $doc->saveXML();
	}
}