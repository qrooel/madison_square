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
 * $Id: substitutedservice.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class substitutedserviceController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'doDeleteSubstitutedservice',
			App::getModel('substitutedservice'),
			'doAJAXDeleteSubstitutedservice'
		));
		$this->registry->xajax->registerFunction(array(
			'LoadAllSubstitutedservice',
			App::getModel('substitutedservice'),
			'getSubstitutedserviceForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('substitutedservice')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_substitutedservice',
			'action' => '',
			'method' => 'post'
		));
		
		$mainData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME') . '/ ' . $this->registry->core->getMessage('TXT_MAIL_TITLE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'substitutedservice', 'name')
			)
		)));
		
		$transmailid = $mainData->AddChild(new FE_Select(Array(
			'name' => 'transmailid',
			'label' => $this->registry->core->getMessage('TXT_TRANSACTION_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getTransMailAllToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TRANSACTION_TEMPLATES'))
			)
		)));
		App::getRegistry()->xajaxInterface->registerFunction(array(
			'SetContentTransMail',
			App::getModel('substitutedservice'),
			'SetContentTransMail'
		));
		$transmailid->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $transmailid, 'ChangeContentTransMail'));
		
		$mainData->AddChild(new FE_Preview(Array(
			'name' => 'preview',
			'label' => $this->registry->core->getMessage('TXT_PREVIEW'),
			'url' => App::getURLAdressWithAdminPane() .'substitutedservice/confirm'
		)));
		
		$mainData->AddChild(new FE_StaticText(Array(
			'text' => '&nbsp;'
		)));
		
		$mainData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center"><strong>Określ zdarzenie oraz zakres czasowy</strong> <br />
						</p>',
			'direction' => FE_Tip::DOWN,
			'short_tip' => '<p align="center"><strong>Określ zdarzenie oraz zakres czasowy</strong></p>'
		)));
		
		$mainData->AddChild(new FE_RadioValueGroup(Array(
			'name' => 'actionid',
			'label' => $this->registry->core->getMessage('TXT_ACTION'),
			'options' => Array(
				new FE_Option('1', 'Klient zarejestrował się i przez %select% nie złożył zamówienia'),
				new FE_Option('2', 'Klient nie logował się w sklepie od... %date%'),
				new FE_Option('3', 'Ostatnie logowanie klienta było %select% temu'),
				new FE_Option('4', 'Klient nie dokonał płatności online za zamówienie przez %select% od daty jego złożenia'),
				new FE_Option('5', 'Klient nie potwierdził zamówienia przez %select% od daty jego złożenia')
			),
			'suboptions' => Array(
				'1' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'3' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'4' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'5' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect())
			)
		)));
		
		$mainData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">Zaznacz checkbox, jeśli chcesz, by administrator
							dostał kopię wiadomości, która zostanie wysłana do klienta.</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$mainData->AddChild(new FE_Checkbox(Array(
			'name' => 'admin',
			'label' => $this->registry->core->getMessage('TXT_ADMIN')
		)));
		
		$populate = Array(
			'main_data' => Array(
				'transmailid' => Array(
					'value' => '0'
				),
				'actionid' => Array(
					'value' => '1'
				)
			)
		);
		
		$form->Populate($populate);
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('substitutedservice')->addSubstitutedService($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			if (FE::IsAction('next')){
				App::redirect(__ADMINPANE__ . '/substitutedservice/add');
			}
			else{
				App::redirect(__ADMINPANE__ . '/substitutedservice');
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
			'name' => 'edit_substitutedservice',
			'action' => '',
			'method' => 'post'
		));
		
		$mainData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'main_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_OPTIONS')
		)));
		
		$mainData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_NAME') . '/ ' . $this->registry->core->getMessage('TXT_MAIL_TITLE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME')),
				new FE_RuleUnique($this->registry->core->getMessage('ERR_NAME_ALREADY_EXISTS'), 'substitutedservice', 'name', null, Array(
					'column' => 'idsubstitutedservice',
					'values' => (int) $this->registry->core->getParam()
				))
			)
		)));
		
		$transmailid = $mainData->AddChild(new FE_Select(Array(
			'name' => 'transmailid',
			'label' => $this->registry->core->getMessage('TXT_HEADERS_TEMPLATES'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getTransMailAllToSelect())
		)));
		App::getRegistry()->xajaxInterface->registerFunction(array(
			'SetContentTransMail',
			App::getModel('substitutedservice'),
			'SetContentTransMail'
		));
		$transmailid->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $transmailid, 'ChangeContentTransMail'));
		
		$mainData->AddChild(new FE_Preview(Array(
			'name' => 'preview',
			'label' => $this->registry->core->getMessage('TXT_PREVIEW'),
			'url' => App::getURLAdressWithAdminPane() . 'substitutedservice/confirm'
		)));
		
		$mainData->AddChild(new FE_StaticText(Array(
			'text' => '&nbsp;'
		)));
		
		$mainData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center"><strong>Określ zdarzenie oraz zakres czasowy</strong> <br />
						</p>',
			'direction' => FE_Tip::DOWN,
			'short_tip' => '<p align="center"><strong>Określ zdarzenie oraz zakres czasowy</strong></p>'
		)));
		
		$mainData->AddChild(new FE_RadioValueGroup(Array(
			'name' => 'actionid',
			'label' => $this->registry->core->getMessage('TXT_ACTION'),
			'options' => Array(
				new FE_Option('1', 'Klient zarejestrował się i przez %select% nie złożył zamówienia'),
				new FE_Option('2', 'Klient nie logował się w sklepie od... %date%'),
				new FE_Option('3', 'Ostatnie logowanie klienta było %select% temu'),
				new FE_Option('4', 'Klient nie dokonał płatności online za zamówienie przez %select% od daty jego złożenia'),
				new FE_Option('5', 'Klient nie potwierdził zamówienia przez %select% od daty jego złożenia')
			),
			'suboptions' => Array(
				'1' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'3' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'4' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect()),
				'5' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + App::getModel('substitutedservice')->getPeriodsAllToSelect())
			)
		)));
		
		$mainData->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center">Zaznacz checkbox, jeśli chcesz, by administrator
							dostał kopię wiadomości, która zostanie wysłana do klienta.</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$mainData->AddChild(new FE_Checkbox(Array(
			'name' => 'admin',
			'label' => $this->registry->core->getMessage('TXT_ADMIN')
		)));
		
		$rawNotificationEdit = App::getModel('substitutedservice')->getSubstitutedServiceToEdit($this->registry->core->getParam());
		
		if (is_array($rawNotificationEdit) && ! empty($rawNotificationEdit)){
			$populate = Array(
				'main_data' => Array(
					'name' => $rawNotificationEdit['name'],
					'transmailid' => $rawNotificationEdit['transmailid']
				)
			);
			if ($rawNotificationEdit['actionid'] == 2){
				$populate['main_data']['actionid'] = Array(
					'value' => $rawNotificationEdit['actionid'],
					$rawNotificationEdit['actionid'] => $rawNotificationEdit['date']
				);
			}
			else{
				$populate['main_data']['actionid'] = Array(
					'value' => $rawNotificationEdit['actionid'],
					$rawNotificationEdit['actionid'] => $rawNotificationEdit['periodid']
				);
			}
			
			$form->Populate($populate);
		}
		
		if ($form->Validate(FE::SubmittedData())){
			App::getModel('substitutedservice')->editSubstitutedService($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			App::redirect(__ADMINPANE__.'/substitutedservice');
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	public function confirm ()
	{
		$this->disableLayout();
		$fileContent = '';
		$fileTmp = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . 'substituteservicetemp.tpl';
		$fileContent = @file_get_contents($fileTmp);
		$fileContent = str_replace('cid:logo', '/design/_images_panel/logos/admin.png', $fileContent);
		while (preg_match("/(\{trans\})(\w+)(\{\/trans\})/i", $fileContent)){
			$fileContent = preg_replace_callback("/\{trans\}(\w+)\{\/trans\}/i", Array(
				App::getModel('substitutedservice'),
				'replace'
			), $fileContent);
		}
		echo $fileContent;
	}
}