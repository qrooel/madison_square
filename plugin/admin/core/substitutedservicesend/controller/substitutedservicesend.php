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
 * $Id: substitutedservicesend.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class substitutedservicesendController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('substitutedservicesend');
	}

	public function index ()
	{
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllSubstitutedservice',
			$this->model,
			'getSubstitutedserviceForAjax'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function confirm ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'confirm_substitutedservicesend',
			'action' => '',
			'method' => 'post'
		));
		
		$clientData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'client_data',
			'label' => $this->registry->core->getMessage('TXT_CLIENTS_LIST')
		)));
		
		$clientData->AddChild(new FE_DatagridSelect(Array(
			'name' => 'clients',
			'label' => $this->registry->core->getMessage('TXT_CLIENT'),
			'key' => 'idclient',
			'datagrid_init_function' => Array(
				App::getModel('client'),
				'initDatagrid'
			),
			'repeat_max' => FE::INFINITE,
			'columns' => $this->getClientDatagridColumns()
		)));
		
		$clientsArrayRaw = $this->model->getClientsForSubstitutedServicesSend((int) $this->registry->core->getParam());
		$clients = Array();
		if (! empty($clientsArrayRaw)){
			foreach ($clientsArrayRaw as $client){
				array_push($clients, $client['idclient']);
			}
			$clients = Array(
				'client_data' => Array(
					'clients' => $clients
				)
			);
			$form->Populate($clients);
		}
		
		if ($form->Validate(FE::SubmittedData())){
			$send = $this->model->saveSendingInfoNotification($form->getSubmitValues(FE_Form::FORMAT_FLAT), (int) $this->registry->core->getParam());
			if ($send > 0){
				App::redirect(__ADMINPANE__ . '/substitutedservicesend/add/' . $send);
			}
			else{
				App::redirect(__ADMINPANE__ . '/substitutedservicesend/confirm/' . (int) $this->registry->core->getParam());
			}
		}
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('confirm.tpl'));
	}

	public function add ()
	{
		
		$this->registry->session->setActiveQuequeParam((int) $this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'add_substitutedservicesend',
			'action' => '',
			'method' => 'post'
		));
		
		$progress = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'progres_data',
			'label' => $this->registry->core->getMessage('TXT_SENDING')
		)));
		
		$progress->AddChild(new FE_ProgressIndicator(Array(
			'name' => 'progress',
			'label' => $this->registry->core->getMessage('TXT_PROGRESS'),
			'chunks' => 30,
			'load' => Array(
				$this->model,
				'doLoadQueque'
			),
			'process' => Array(
				$this->model,
				'doProcessQueque'
			),
			'success' => Array(
				$this->model,
				'doSuccessQueque'
			)
		)));
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function view ()
	{
		
		$substitutedserviceid = $this->registry->core->getParam();
		
		$form = new FE_Form(Array(
			'name' => 'view_substitutedservicesend',
			'action' => '',
			'method' => 'post'
		));
		
		$listNotifications = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'substitutedservicesend',
			'label' => $this->registry->core->getMessage('TXT_NOTIFICATIONS_REPORT')
		)));
		
		$list = $listNotifications->AddChild(new FE_Select(Array(
			'name' => 'list',
			'label' => $this->registry->core->getMessage('TXT_CHOOSE_NOTIFICATION_DATE_FOR_REPORT'),
			'options' => FE_Option::Make($this->registry->core->getDefaultValueToSelect() + $this->model->getNotificationstAllToSelect($substitutedserviceid))
		)));
		
		$listNotifications->AddChild(new FE_StaticText(Array(
			'text' => '<p id="link" />'
		)));
		
		$listNotifications->AddChild(new FE_Tip(Array(
			'tip' => '<p align="center"><strong>' . $this->registry->core->getMessage('TXT_ATTENTION') . '!!!</strong></p>
					 <p> Wysłanie powiadomienia spowoduje przesłanie informacji tylko do tych
						klientów, którzy dla określonego powiadomienia
						posiadają status "Wiadomość nie została jeszcze wysłana".
					 </p>',
			'direction' => FE_Tip::UP,
			'short_tip' => '<p><strong>' . $this->registry->core->getMessage('TXT_ATTENTION') . '!!!</strong></p>',
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $list, new FE_ConditionEquals(0))
			)
		)));
		
		$clients = $listNotifications->AddChild(new FE_StaticListing(Array(
			'name' => 'clients',
			'title' => '',
			'values' => Array(
				new FE_ListItem('', '')
			)
		)));
		App::getRegistry()->xajaxInterface->registerFunction(array(
			'GetAllClientsForNotification',
			$this->model,
			'GetAllClientsForNotification'
		));
		$clients->AddDependency(new FE_Dependency(FE_Dependency::INVOKE_CUSTOM_FUNCTION, $list, 'ChangeClientsListForNotification'));
		
		if ($form->Validate(FE::SubmittedData())){
			App::redirect(__ADMINPANE__ . '/substitutedservicesend/index');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('view.tpl'));
	}

	protected function getClientDatagridColumns ()
	{
		return Array(
			Array(
				'id' => 'idclient',
				'caption' => $this->registry->core->getMessage('TXT_ID'),
				'appearance' => Array(
					'width' => 50
				)
			),
			Array(
				'id' => 'firstname',
				'caption' => $this->registry->core->getMessage('TXT_FIRSTNAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'surname',
				'caption' => $this->registry->core->getMessage('TXT_SURNAME'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'groupname',
				'caption' => $this->registry->core->getMessage('TXT_GROUPS_CLIENT'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			),
			Array(
				'id' => 'adddate',
				'caption' => $this->registry->core->getMessage('TXT_DATE'),
				'appearance' => Array(
					'width' => FE_DatagridSelect::WIDTH_AUTO
				)
			)
		);
	}
}