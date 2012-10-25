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
 * $Id: layoutbox.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class LayoutboxController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('layoutbox');
		$this->categories = App::getModel('category')->getChildCategories();
		$this->categoryActive = 0;
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteLayoutbox',
			$this->model,
			'doAJAXDeleteLayoutbox'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllLayoutbox',
			$this->model,
			'getLayoutboxForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetValueSuggestions',
			$this->model,
			'getValueForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////          ADD LAYOUTBOX         ////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function add ()
	{
		
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('#layout-box-__id__', 'layoutbox');
		
		$form = new FE_Form(Array(
			'name' => 'add_layoutbox',
			'action' => '',
			'method' => 'post'
		));
		
		$contentTypes = $this->model->getLayoutBoxContentTypeOptions();
		
		/////////////////////////////////////          MAIN OPTION       ///////////////////////////////////////
		$boxAdd = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'box',
			'label' => $this->registry->core->getMessage('TXT_BOX_SETTINGS')
		)));
		
		$boxAdd->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'comment' => 'Wewnętrzna nazwa boksu, niewidoczna dla Klientów',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$title = $boxAdd->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data'
		)));
		
		$title->AddChild(new FE_TextField(Array(
			'name' => 'title',
			'label' => $this->registry->core->getMessage('TXT_BOX_TITLE'),
			'comment' => 'Tytuł boksu, który zobaczą Klienci',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BOX_TITLE'))
			)
		)));
		
		$boxContent = $boxAdd->AddChild(new FE_Select(Array(
			'name' => 'box_content',
			'label' => $this->registry->core->getMessage('TXT_BOX_CONTENT'),
			'options' => FE_Option::Make($this->model->getLayoutBoxContentTypeOptionsAllToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BOX_CONTENT'))
			)
		)));
		
		$this->_addContentTypeSpecificFields($form, $boxContent, $contentTypes);
		
		$defaultBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'look',
			'label' => 'Wygląd'
		)));
		
		$defaultBox->AddChild(new FE_Checkbox(Array(
			'name' => 'save_changes',
			'label' => 'Zapisz zmiany w wyglądzie'
		)));
		
		$templateid = $defaultBox->AddChild(new FE_Select(Array(
			'name' => 'choose_template',
			'label' => 'Skopiuj z szablonu',
			'options' => array_merge(Array(
				new FE_Option('', '(wybierz)'),
				new FE_Option('0', 'Szablon standardowy')
			), FE_Option::Make($this->model->getLayoutBoxSchemeTemplatesAllToSelect()))
		)));
		
		$preview = $defaultBox->AddChild(new FE_LayoutBoxSchemePreview(Array(
			'triggers' => $fieldGenerator->GetFieldNames(),
			'stylesheets' => Array(
				DESIGNPATH . '_css_frontend/core/static.css',
				DESIGNPATH . '_css_frontend/core/scheme.css'
			),
			'layout_box_tpl' => ROOTPATH . 'design/_tpl/frontend/core/layoutbox.tpl',
			'box_name' => '__id__'
		)));
		$preview->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $templateid, 'ChangeScheme'));
		$this->registry->xajaxInterface->registerFunction(array(
			'GetSchemeValues',
			$this->model,
			'getSchemeValuesForAjax'
		));
		
		$fieldGenerator->AddFields($defaultBox);
		
		/////////////////////////////////        BEHAVIOUR        /////////////////////////////////////////////
		$boxBehaviourEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'behaviour',
			'label' => $this->registry->core->getMessage('TXT_BOX_BEHAVOIUR')
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bFixedPosition',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			),
			'label' => 'Przenoszenie boksu'
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bClosingProhibited',
			'label' => 'Zamykanie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bNoHeader',
			'label' => 'Wyświetlaj nagłówek',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bCollapsingProhibited',
			'label' => 'Zwijanie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bExpandingProhibited',
			'label' => 'Rozciąganie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_TextField(Array(
			'name' => 'iDefaultSpan',
			'label' => 'Domyślne rozciągnięcie',
			'comment' => 'Wpisz liczbę kolumn',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^(([0-9]{1,2})|(\0)?)$/')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'iEnableBox',
			'label' => 'Wyświetlanie boksu',
			'options' => Array(
				new FE_Option('0', 'Dla wszystkich'),
				new FE_Option('1', 'Dla zalogowanych'),
				new FE_Option('2', 'Dla niezalogowanych'),
				new FE_Option('3', 'Nie wyświetlaj')
			)
		)));
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		

		$populate = Array(
			'look' => $fieldGenerator->GetDefaultValues() + Array(
				'choose_template' => '0'
			),
			'box' => Array(
				'box_content' => '1'
			),
			'behaviour' => Array(
				'bFixedPosition' => 0,
				'bClosingProhibited' => 0,
				'bNoHeader' => 0,
				'bCollapsingProhibited' => 0,
				'bExpandingProhibited' => 0,
				'iDefaultSpan' => '1',
				'iEnableBox' => 0
			)
		);
		
		$populate = $this->_populateContentTypeFields($contentTypes, $populate);
		
		$form->Populate($populate);
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($form->Validate(FE::SubmittedData())){
			$this->model->addNewLayoutBox($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)));
			App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
			App::redirect(__ADMINPANE__ . '/layoutbox');
		}
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('id', 'new');
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////          EDIT  LAYOUTBOX         ///////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit ()
	{
		
		$fieldGenerator = App::getModel('fieldgenerator/fieldgenerator')->LoadSchemeFields('#layout-box-__id__', 'layoutbox');
		
		$id = $this->registry->core->getParam();
		$layoutBox = $this->model->getLayoutBoxToEdit($this->registry->core->getParam());
		$layoutBoxCssArray = $this->model->getLayoutBoxCSSToEdit($this->registry->core->getParam());
		$behaviourBoxArray = $this->model->getLayoutBoxJSValuesToEdit($this->registry->core->getParam());
		$ctValues = $this->model->getLayoutBoxContentTypeSpecificValues($id);
		
		if (isset($ctValues['categoryId']) && ($ctValues['categoryId'] > 0)){
			$this->categoryActive = $ctValues['categoryId'];
			$this->categories = App::getModel('category')->getChildCategories(0, Array(
				$this->categoryActive
			));
		}
		
		$form = new FE_Form(Array(
			'name' => 'edit_layoutbox',
			'action' => '',
			'method' => 'post'
		));
		
		$contentTypes = $this->model->getLayoutBoxContentTypeOptions();
		
		/////////////////////////////////////          MAIN OPTION       ///////////////////////////////////////
		$boxEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'box',
			'label' => $this->registry->core->getMessage('TXT_BOX_SETTINGS')
		)));
		
		$boxEdit->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME'),
			'comment' => 'Wewnętrzna nazwa boksu, niewidoczna dla Klientów',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$title = $boxEdit->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data'
		)));
		
		$title->AddChild(new FE_TextField(Array(
			'name' => 'title',
			'label' => $this->registry->core->getMessage('TXT_BOX_TITLE'),
			'comment' => 'Tytuł boksu, który zobaczą Klienci',
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BOX_TITLE'))
			)
		)));
		
		$boxContent = $boxEdit->AddChild(new FE_Select(Array(
			'name' => 'box_content',
			'label' => $this->registry->core->getMessage('TXT_BOX_CONTENT'),
			'options' => FE_Option::Make($this->model->getLayoutBoxContentTypeOptionsAllToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_BOX_CONTENT'))
			)
		)));
		
		$this->_addContentTypeSpecificFields($form, $boxContent, $contentTypes);
		
		$defaultBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'look',
			'label' => 'Wygląd'
		)));
		
		$defaultBox->AddChild(new FE_Checkbox(Array(
			'name' => 'save_changes',
			'label' => 'Zapisz zmiany w wyglądzie'
		)));
		
		$templateid = $defaultBox->AddChild(new FE_Select(Array(
			'name' => 'choose_template',
			'label' => 'Skopiuj z szablonu',
			'options' => array_merge(Array(
				new FE_Option('-1', '(wybierz)'),
				new FE_Option('0', 'Szablon standardowy')
			), FE_Option::Make($this->model->getLayoutBoxSchemeTemplatesAllToSelect())),
			'default' => '-1'
		)));
		
		$preview = $defaultBox->AddChild(new FE_LayoutBoxSchemePreview(Array(
			'triggers' => $fieldGenerator->GetFieldNames(),
			'stylesheets' => Array(
				DESIGNPATH . '_css_frontend/core/static.css',
				DESIGNPATH . '_css_frontend/core/scheme.css'
			),
			'layout_box_tpl' => ROOTPATH . 'design/_tpl/frontend/core/layoutbox.tpl',
			'box_name' => '__id__'
		)));
		$preview->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $templateid, 'ChangeScheme'));
		$this->registry->xajaxInterface->registerFunction(array(
			'GetSchemeValues',
			$this->model,
			'getSchemeValuesForAjax'
		));
		
		$fieldGenerator->AddFields($defaultBox);
		
		/////////////////////////////////        BEHAVIOUR        /////////////////////////////////////////////
		$boxBehaviourEdit = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'behaviour',
			'label' => $this->registry->core->getMessage('TXT_BOX_BEHAVOIUR')
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bFixedPosition',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			),
			'label' => 'Przenoszenie boksu'
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bClosingProhibited',
			'label' => 'Zamykanie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bNoHeader',
			'label' => 'Wyświetlaj nagłówek',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bCollapsingProhibited',
			'label' => 'Zwijanie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'bExpandingProhibited',
			'label' => 'Rozciąganie boksu',
			'options' => Array(
				new FE_Option('0', 'Tak'),
				new FE_Option('1', 'Nie')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_TextField(Array(
			'name' => 'iDefaultSpan',
			'label' => 'Domyślne rozciągnięcie',
			'comment' => 'Wpisz liczbę kolumn',
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_VALUE_INVALID'), '/^(([0-9]{1,2})|(\0)?)$/')
			)
		)));
		
		$boxBehaviourEdit->AddChild(new FE_Select(Array(
			'name' => 'iEnableBox',
			'label' => 'Wyświetlanie boksu',
			'options' => Array(
				new FE_Option('0', 'Dla wszystkich'),
				new FE_Option('1', 'Dla zalogowanych'),
				new FE_Option('2', 'Dla niezalogowanych'),
				new FE_Option('3', 'Nie wyświetlaj')
			)
		)));
		
		$populate = Array(
			'look' => array_merge(Array(
				'choose_template' => ''
			), $fieldGenerator->GetDefaultValues()),
			'box' => Array(
				'name' => $layoutBox['name'],
				'language_data' => Array(
					'title' => $layoutBox['title']
				),
				'box_content' => $layoutBox['controller']
			),
			'behaviour' => Array(
				'bFixedPosition' => 0,
				'bClosingProhibited' => 0,
				'bNoHeader' => 0,
				'bCollapsingProhibited' => 0,
				'bExpandingProhibited' => 0,
				'iDefaultSpan' => '1',
				'iEnableBox' => 0
			)
		);
		
		if (isset($behaviourBoxArray) && count($behaviourBoxArray) > 0){
			foreach ($behaviourBoxArray as $js => $value){
				$populate['behaviour'][$js] = $value;
			}
		}
		$populate = $this->_populateContentTypeFields($contentTypes, $populate, $ctValues, $layoutBox['controller']);
		$populate = $fieldGenerator->PopulateFormWithValues($form, $layoutBoxCssArray, Array(
			$this->model,
			'GetSelector'
		)) + $populate;
		
		$form->Populate($populate);
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($form->Validate(FE::SubmittedData())){
			
			$this->model->editLayoutBox($this->_performArtificialMechanics($form->getSubmitValues(FE_Form::FORMAT_FLAT)), $this->registry->core->getParam());
			App::getmodel('cssgenerator')->createPageSchemeStyleSheetDocument();
			if (FE::IsAction('continue')){
				App::redirect(__ADMINPANE__ . '/layoutbox/edit/' . $this->registry->core->getParam());
			}
			else{
				App::redirect(__ADMINPANE__ . '/layoutbox');
			}
		
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('form', $form);
		$this->registry->template->assign('id', $id);
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
				'selector' => '#layout-box-__id__ .layout-box-content',
				'bottom-left' => Array(
					'value' => "{$value}px"
				),
				'bottom-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_border-radius'] = Array(
				'selector' => '#layout-box-__id__ .layout-box-header',
				'top-left' => Array(
					'value' => "{$value}px"
				),
				'top-right' => Array(
					'value' => "{$value}px"
				)
			);
			$data['db_header_collapsed_border-radius'] = Array(
				'selector' => '#layout-box-__id__.layout-box-collapsed .layout-box-header, #layout-box-__id__.layout-box-option-header-false .layout-box-content',
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
				'selector' => '#layout-box-__id__ .layout-box-icons .layout-box-icon',
				'value' => $data['db_header_line-height']['value']
			);
		}
		return $data;
	}

	/**
	 * CONTENT-TYPE SPECIFIC FIELDS
	 * Przygotowanie do przyszlego, pelnego wydzielenia content-types do oddzielnych paczek.
	 */
	protected function _addContentTypeSpecificFields ($form, $boxContent, $contentTypes)
	{
		foreach ($contentTypes as $controller => $contentType){
			if (file_exists(ROOTPATH . 'plugin' . DS . 'admin' . DS . 'core' . DS . $this->getName() . DS . 'model' . DS . strtolower($controller) . '.php')){
				$function = Array(
					App::getModel('layoutbox/' . strtolower($controller)),
					"_addFieldsContentType{$controller}"
				);
				if (is_callable($function)){
					call_user_func($function, $form, $boxContent);
				}
			}
		}
	}

	/*
		* CONTENT-TYPE SPECIFIC FIELDS POPULATE
		*/
	protected function _populateContentTypeFields ($contentTypes, &$populate, $ctValues = Array(), $currentContentType = 0)
	{
		foreach ($contentTypes as $controller => $translation){
			if (file_exists(ROOTPATH . 'plugin' . DS . 'admin' . DS . 'core' . DS . $this->getName() . DS . 'model' . DS . strtolower($controller) . '.php')){
				$function = Array(
					App::getModel('layoutbox/' . strtolower($controller)),
					"_populateFieldsContentType{$controller}"
				);
				if (is_callable($function)){
					$populate = call_user_func($function, $populate, ($controller == $currentContentType) ? $ctValues : Array());
				}
			}
		}
		return $populate;
	}

}