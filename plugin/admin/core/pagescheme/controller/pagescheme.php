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
 * $Id: pagescheme.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class pageschemeController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeletePagescheme',
			App::getModel('pagescheme'),
			'doAJAXDeletePagescheme'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllPagescheme',
			App::getModel('pagescheme'),
			'getPageschemeForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetValueSuggestions',
			App::getModel('pagescheme'),
			'getValueForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'setDefaultPagescheme',
			App::getModel('pagescheme'),
			'doAJAXDefaultPagescheme'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////         ADD PAGESCHEME       /////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add ()
	{
		
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('.layout-box');
		
		$form = new FE_Form(Array(
			'name' => 'add_pagescheme',
			'action' => '',
			'method' => 'post'
		));
		
		/////////////////////////////////////          MAIN OPTION       ///////////////////////////////////////
		$mainAdd = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainAdd->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_TEMPLATE_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEMPLATE_NAME'))
			)
		)));
		
		$mainAdd->AddChild(new FE_Checkbox(Array(
			'name' => 'default',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_TEMPLATE'),
		)));
		
		$fieldGenerator->AddFields($form);
		
		$populate = $fieldGenerator->GetDefaultValues();
		
		$form->Populate($populate);
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('pagescheme')->addNewPageScheme($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)));
			$check = $form->getSubmitValues();
			if ($check['main']['default'] == 1){
				App::getModel('cssgenerator')->createPageSchemeStyleSheetDocument();
			}
			//$this->helper->autoExecute(Array($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam()));
			App::redirect(__ADMINPANE__ . '/pagescheme/');
		}
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////         EDIT PAGESCHEME       ////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	public function edit ()
	{
		
		$viewid = Helper::getViewId();
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('.layout-box');
		
		$templateMainInfo = App::getModel('pagescheme')->getTemplateNameToEdit($this->registry->core->getParam());
		$templateCss = App::getModel('pagescheme')->getTemplateCssToEdit($this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_pagescheme',
			'action' => '',
			'method' => 'post'
		));
		
		$mainEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainEdit->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_TEMPLATE_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEMPLATE_NAME'))
			)
		)));
		
		$mainEdit->AddChild(new FE_Checkbox(Array(
			'name' => 'default',
			'label' => $this->registry->core->getMessage('TXT_DEFAULT_TEMPLATE'),
		)));
		
		$fieldGenerator->AddFields($form);
		
		$populate = $fieldGenerator->GetDefaultValues();
		
		$populate['main'] = Array(
			'name' => $templateMainInfo['name'],
			'default' => $templateMainInfo['default']
		);
		
		$populate = $fieldGenerator->PopulateFormWithValues($form, $templateCss) + $populate;
		$form->Populate($populate);
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('pagescheme')->editPageScheme($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)), $this->registry->core->getParam());
			$check = $form->getSubmitValues();
			if ($check['main']['default'] == 1){
				App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
			}
			if (FE::IsAction('continue')) {
				App::redirect(__ADMINPANE__.'/pagescheme/edit/'.$this->registry->core->getParam());
			}
			else {
				App::redirect(__ADMINPANE__.'/pagescheme');
			}
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	/*
		* ARTIFICIAL MECHANICS
		*/
	protected function _performArtificialMechanics ($data)
	{
		if (isset($data['db_border-radius']['value'])){
			$value = max(0, substr($data['db_border-radius']['value'], 0, - 2) - 1);
			$data['db_content_border-radius'] = Array(
				'selector' => '.layout-box-content',
				'bottom-left' => Array(
					'value' => "{$value}px"
				),
				'bottom-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_border-radius'] = Array(
				'selector' => '.layout-box-header',
				'top-left' => Array(
					'value' => "{$value}px"
				),
				'top-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_collapsed_border-radius'] = Array(
				'selector' => '.layout-box-collapsed .layout-box-header, .layout-box-option-header-false .layout-box-content',
				'top-left' => Array(
					'value' => "{$value}px"
				),
				'top-right' => Array(
					'value' => "{$value}px"
				),
				'bottom-left' => Array(
					'value' => "{$value}px"
				),
				'bottom-right' => Array(
					'value' => "{$value}px"
				)
			);
		}
		if (isset($data['hn_height'])){
			$data['hn_line-height'] = Array(
				'selector' => '#horizontal-navigation ul li a',
				'value' => "{$data['hn_height']['value']}px"
			);
		}
		if (isset($data['db_header_line-height'])){
			$data['db_icon_height'] = Array(
				'selector' => '.layout-box-icons .layout-box-icon',
				'value' => $data['db_header_line-height']['value']
			);
		}
		return $data;
	}
}