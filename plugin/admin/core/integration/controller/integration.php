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
 * $Id: integration.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class IntegrationController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllIntegration',
			App::getModel('integration'),
			'getIntegrationForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'disableIntegration',
			APP::getModel('integration'),
			'doAJAXDisableIntegration'
		));
		$this->registry->xajax->registerFunction(array(
			'enableIntegration',
			APP::getModel('integration'),
			'doAJAXEnableIntegration'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('integration')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_integration',
			'action' => '',
			'method' => 'post'
		));
		
		$integrationModel = App::getModel('integration')->getIntegrationModelById($this->registry->core->getParam());
		
		if (method_exists(App::getModel('integration/' . $integrationModel), 'updateCategories')){
			App::getModel('integration/' . $integrationModel)->updateCategories();
		}
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_INFORMATION')
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p>' . App::getModel('integration/' . $integrationModel)->getDescription() . '</p>'
		)));
		
		$requiredData->AddChild(new FE_StaticText(Array(
			'text' => '<p><a href="' . App::getURLAdress() . 'integration/' . $integrationModel . '" target="_blank"><b>Link do pliku integracyjnego</b></a></p>'
		)));
		
		$configurationFields = App::getModel('integration/' . $integrationModel)->getConfigurationFields();
		
		if (is_array($configurationFields) && ! empty($configurationFields)){
			$configurationData = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'configuration_data',
				'label' => $this->registry->core->getMessage('TXT_CONFIGURATION_DATA')
			)));
		}
		
		$whitelist = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'whitelist_data',
			'label' => $this->registry->core->getMessage('TXT_INTEGRATION_WHITELIST')
		)));
		
		$fieldset = $whitelist->AddChild(new FE_FieldsetRepeatable(Array(
			'name' => 'whitelist',
			'label' => $this->registry->core->getMessage('TXT_INTEGRATION_WHITELIST'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE
		)));
		
		$fieldset->AddChild(new FE_TextField(Array(
			'name' => 'ip',
			'label' => 'IP'
		)));
		
		$rawData = App::getModel('integration')->getIntegrationView($this->registry->core->getParam());
		
		$pollData = Array(
			'whitelist_data' => Array(
				'whitelist' => $rawData['whitelist']
			),
		);
		
		$form->Populate($pollData);
		
		$form->AddFilter(new FE_FilterTrim());
		$form->AddFilter(new FE_FilterNoCode());
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				
				App::getModel('integration')->editIntegration($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/integration');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}