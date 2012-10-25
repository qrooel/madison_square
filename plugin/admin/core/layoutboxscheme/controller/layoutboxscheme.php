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
 * $Id: layoutboxscheme.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class LayoutboxschemeController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteLayoutboxscheme',
			App::getModel('layoutboxscheme'),
			'doAJAXDeleteLayoutboxscheme'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllLayoutboxscheme',
			App::getModel('layoutboxscheme'),
			'getLayoutboxschemeForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetValueSuggestions',
			App::getModel('layoutboxscheme'),
			'getValueForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////         ADD LAYOUTBOXSCHEME       //////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	public function add ()
	{
		
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('.layout-box-scheme-__id__', 'layoutbox');
		
		$form = new FE_Form(Array(
			'name' => 'add_layoutboxscheme',
			'action' => '',
			'method' => 'post'
		));
		
		/////////////////////////////////////          MAIN OPTION       ///////////////////////////////////////
		$boxSchemeAdd = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'boxscheme',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$boxSchemeAdd->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_TEMPLATE_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEMPLATE_NAME'))
			)
		)));
		
		$defaultBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'look',
			'label' => 'Wygląd'
		)));
		
		$preview = $defaultBox->AddChild(new FE_LayoutBoxSchemePreview(Array(
			'triggers' => $fieldGenerator->GetFieldNames(),
			'stylesheets' => Array(
				DESIGNPATH . '_css_frontend/core/static.css',
				DESIGNPATH . '_css_frontend/core/scheme.css'
			),
			'layout_box_tpl' => ROOTPATH . 'design/_tpl/frontend/core/layoutbox.tpl',
			'box_scheme' => '__id__'
		)));
		
		$fieldGenerator->AddFields($defaultBox);
		
		$populate = Array(
			'look' => $fieldGenerator->GetDefaultValues()
		);
		
		$form->Populate($populate);
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('layoutboxscheme')->addNewLayoutBoxScheme($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)));
			App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/layoutboxscheme/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/layoutboxscheme');
			}
		}
		
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////         EDIT LAYOUTBOXSCHEME        ////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit ()
	{
		
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('.layout-box-scheme-__id__', 'layoutbox');
		
		$id = $this->registry->core->getParam();
		$layoutBoxSchemeName = App::getModel('layoutboxscheme')->getLayoutBoxSchemeToEdit($this->registry->core->getParam());
		$layoutBoxSchemeCssArray = App::getModel('layoutboxscheme')->getLayoutBoxSchemeCSSToEdit($this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'edit_layoutboxscheme',
			'action' => '',
			'method' => 'post'
		));
		
		/////////////////////////////////////          MAIN OPTION       ///////////////////////////////////////
		$boxSchemeEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'boxscheme',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$boxSchemeEdit->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_TEMPLATE_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TEMPLATE_NAME'))
			)
		)));
		
		$defaultBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'look',
			'label' => 'Wygląd'
		)));
		
		$preview = $defaultBox->AddChild(new FE_LayoutBoxSchemePreview(Array(
			'triggers' => $fieldGenerator->GetFieldNames(),
			'stylesheets' => Array(
				DESIGNPATH . '_css_frontend/core/static.css',
				DESIGNPATH . '_css_frontend/core/scheme.css'
			),
			'layout_box_tpl' => ROOTPATH . 'design/_tpl/frontend/core/layoutbox.tpl',
			'box_scheme' => '__id__'
		)));
		
		$fieldGenerator->AddFields($defaultBox);
		
		$populate = Array(
			'look' => $fieldGenerator->GetDefaultValues()
		);
		
		$populate['view_data'] = Array(
			'view' => $layoutBoxSchemeName['view']
		);
		
		if (isset($layoutBoxSchemeName) && $layoutBoxSchemeName != NULL){
			$populate['boxscheme']['name'] = $layoutBoxSchemeName['name'];
		}
		
		$populate = $fieldGenerator->PopulateFormWithValues($form, $layoutBoxSchemeCssArray, Array(
			App::getModel('layoutboxscheme'),
			'GetSelector'
		)) + $populate;
		
		$form->Populate($populate);
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		

		if ($form->Validate(FE::SubmittedData())){
			
			App::getModel('layoutboxscheme')->editLayoutBoxScheme($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)), $this->registry->core->getParam());
			App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
			App::redirect(__ADMINPANE__ . '/layoutboxscheme');
		
		}
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	/**
	 * _performArtificialMechanics
	 * 
	 * Niejawne utworzenie wartosci na podstawie tych uzyskanych
	 * z pol wypelnionych przez uzytkownika. Niektore wartosci powinny
	 * sie uzupelniac automatycznie - za to odpowiada ta metoda.
	 * 
	 * @param array $data Tablica z danymi wejsciowymi, a wiec danymi submitowanymi z formularza
	 * @return array Tablica wejsciowa poszerzona o sztucznie utworzone wartosci
	 */
	protected function _performArtificialMechanics ($data)
	{
		if (isset($data['db_border-radius']['value'])){
			$value = max(0, substr($data['db_border-radius']['value'], 0, - 2) - 1);
			$data['db_content_border-radius'] = Array(
				'selector' => '.layout-box-scheme-__id__ .layout-box-content',
				'bottom-left' => Array(
					'value' => "{$value}px"
				),
				'bottom-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_border-radius'] = Array(
				'selector' => '.layout-box-scheme-__id__ .layout-box-header',
				'top-left' => Array(
					'value' => "{$value}px"
				),
				'top-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_collapsed_border-radius'] = Array(
				'selector' => '.layout-box-scheme-__id__.layout-box-collapsed .layout-box-header, .layout-box-scheme-__id__.layout-box-option-header-false .layout-box-content',
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
		if (isset($data['db_header_line-height'])){
			$data['db_icon_height'] = Array(
				'selector' => '.layout-box-scheme-__id__ .layout-box-icons .layout-box-icon',
				'value' => $data['db_header_line-height']['value']
			);
		}
		return $data;
	}
}