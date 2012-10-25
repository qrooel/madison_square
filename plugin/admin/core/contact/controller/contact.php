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
 * $Revision: 689 $
 * $Author: gekosale $
 * $Date: 2012-09-01 19:55:28 +0200 (So, 01 wrz 2012) $
 * $Id: contact.php 689 2012-09-01 17:55:28Z gekosale $ 
 */

class contactController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('contact');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteContact',
			$this->model,
			'doAJAXDeleteContact'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllContact',
			$this->model,
			'getContactForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetNameSuggestions',
			$this->model,
			'getNameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetStreetSuggestions',
			$this->model,
			'getStreetForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetPlacenameSuggestions',
			$this->model,
			'getPlacenameForAjax'
		));
		
		$this->Render();
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_contact',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'contacttranslation', 'name')
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'fax',
			'label' => $this->registry->core->getMessage('TXT_FAX'),
			'comment' => $this->registry->core->getMessage('TXT_FAX_FORM'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT_FAX'), '/^(\d{1,}(-|\s)?\d{1,})*$/')
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'businesshours',
			'label' => $this->registry->core->getMessage('TXT_BUSINESS_HOURS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000'
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'options' => Array(
				new FE_Option('1', $this->registry->core->getMessage('TXT_YES')),
				new FE_Option('0', $this->registry->core->getMessage('TXT_NO'))
			),
			'default' => '1'
		)));
		
		$addressData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'address_data',
			'label' => $this->registry->core->getMessage('TXT_CONTACT_ADDRESS_DATA')
		)));
		
		$addressLanguageData = $addressData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$place = $addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'comment' => $this->registry->core->getMessage('TXT_POSTCODE_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$addressLanguageData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('countrieslist')->getCountryForSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
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
			$this->model->addNewContact($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/contact/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/contact');
			}
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_contact',
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
				new FE_RuleLanguageUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'contacttranslation', 'name', null, Array(
					'column' => 'contactid',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => $this->registry->core->getMessage('TXT_EMAIL'),
			'comment' => $this->registry->core->getMessage('TXT_EMAIL_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_EMAIL')),
				new FE_RuleEmail($this->registry->core->getMessage('ERR_WRONG_EMAIL'))
			)
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'phone',
			'label' => $this->registry->core->getMessage('TXT_PHONE'),
		)));
		
		$languageData->AddChild(new FE_TextField(Array(
			'name' => 'fax',
			'label' => $this->registry->core->getMessage('TXT_FAX'),
			'comment' => $this->registry->core->getMessage('TXT_FAX_FORM'),
			'rules' => Array(
				new FE_RuleFormat($this->registry->core->getMessage('ERR_WRONG_FORMAT_FAX'), '/^(\d{1,}(-|\s)?\d{1,})*$/')
			)
		)));
		
		$languageData->AddChild(new FE_RichTextEditor(Array(
			'name' => 'businesshours',
			'label' => $this->registry->core->getMessage('TXT_BUSINESS_HOURS'),
			'comment' => $this->registry->core->getMessage('TXT_MAX_LENGTH') . ' 5000'
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'publish',
			'label' => $this->registry->core->getMessage('TXT_PUBLISH'),
			'options' => Array(
				new FE_Option('1', $this->registry->core->getMessage('TXT_YES')),
				new FE_Option('0', $this->registry->core->getMessage('TXT_NO'))
			),
			'default' => '1'
		)));
		
		$addressData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'address_data',
			'label' => $this->registry->core->getMessage('TXT_CONTACT_ADDRESS_DATA')
		)));
		
		$addressLanguageData = $addressData->AddChild(new FE_FieldsetLanguage(Array(
			'name' => 'language_data',
			'label' => $this->registry->core->getMessage('TXT_LANGUAGE_DATA')
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => $this->registry->core->getMessage('TXT_STREET'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_STREET'))
			)
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'streetno',
			'label' => $this->registry->core->getMessage('TXT_STREETNO'),
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'placeno',
			'label' => $this->registry->core->getMessage('TXT_PLACENO'),
		)));
		
		$place = $addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'placename',
			'label' => $this->registry->core->getMessage('TXT_PLACE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_PLACE'))
			)
		)));
		
		$addressLanguageData->AddChild(new FE_TextField(Array(
			'name' => 'postcode',
			'label' => $this->registry->core->getMessage('TXT_POSTCODE'),
			'comment' => $this->registry->core->getMessage('TXT_POSTCODE_FORM'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_POSTCODE')),
			)
		)));
		
		$addressLanguageData->AddChild(new FE_Select(Array(
			'name' => 'countryid',
			'label' => $this->registry->core->getMessage('TXT_NAME_OF_COUNTRY'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('countrieslist')->getCountryForSelect()),
			'default' => 0,
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME_OF_COUNTRY'))
			)
		)));
		
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
		
		$rawContactData = $this->model->getContactView($this->registry->core->getParam());
		$contactData = Array(
			'required_data' => Array(
				'language_data' => $rawContactData['language'],
				'publish' => $rawContactData['publish']
			),
			'address_data' => Array(
				'language_data' => $rawContactData['language']
			),
			'view_data' => Array(
				'view' => $rawContactData['view']
			)
		);
		
		$form->Populate($contactData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editContact($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/contact');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}