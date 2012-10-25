<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o
 * licencji i autorach.
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
 * $Id: exchange.php 655 2012-04-24 08:51:44Z gekosale $
 */

class exchangeController extends Controller {

	public function index () {
		
		$form = new FE_Form(Array(
			'name' => 'exchange',
			'action' => '',
			'method' => 'post'
		));
		
		$typePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'type_pane',
			'label' => $this->registry->core->getMessage('TXT_EXCHANGE_FILES')
		)));
		
		$exchangetype = $typePane->AddChild(new FE_Select(Array(
			'name' => 'type',
			'label' => $this->registry->core->getMessage('TXT_EXCHANGE_TYPE'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_EXCHANGE_TYPE_EXPORT')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_EXCHANGE_TYPE_IMPORT'))
			),
			'default' => 1
		)));
		
		$entity = $typePane->AddChild(new FE_Select(Array(
			'name' => 'entity',
			'label' => $this->registry->core->getMessage('TXT_EXCHANGE_ENTITY'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_PRODUCTS')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_CATEGORIES')),
				new FE_Option(3, $this->registry->core->getMessage('TXT_CLIENTS')),
				new FE_Option(4, $this->registry->core->getMessage('TXT_ORDERS'))
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::EXCHANGE_OPTIONS, $exchangetype, Array(
					$this,
					'getEntityTypes'
				))
			),
			'default' => 1
		)));
		
		$filesPane = $typePane->AddChild(new FE_Fieldset(Array(
			'name' => 'files_pane',
			'label' => $this->registry->core->getMessage('TXT_EXCHANGE_FILES')
		)));
		
		$files = $filesPane->AddChild(new FE_LocalFile(Array(
			'name' => 'files',
			'label' => 'Plik',
			'file_source' => 'upload/',
			'file_types' => Array(
				'csv'
			)
		)));
		
		$filesPane->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $exchangetype, new FE_ConditionEquals(2)));
		
		if ($form->Validate(FE::SubmittedData())){
			$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			
			switch ($Data['type']) {
				case 1:
					$this->disableLayout();
					$this->registry->session->setActiveExchangeEntityType($Data['entity']);
					App::getModel('exchange')->exportFile($Data['entity']);
					break;
				case 2:
					App::getModel('exchange')->importFromFile($Data['files']['file'], $Data['entity']);
					App::redirect(__ADMINPANE__ . '/exchange/confirm');
					break;
			}
		
		}
		else{
			
			$this->registry->template->assign('form', $form);
			$this->registry->xajax->processRequest();
			$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
			$this->registry->template->display($this->loadTemplate('index.tpl'));
		}
	}

	public function add () {
		
		if ($this->registry->core->getParam() == ''){
			$form = new FE_Form(Array(
				'name' => 'exchange',
				'action' => '',
				'method' => 'post'
			));
			
			$requiredData = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'type_pane',
				'label' => $this->registry->core->getMessage('TXT_EXCHANGE_TYPE_MIGRATION_SETTINGS')
			)));
			
			$requiredData->AddChild(new FE_TextField(Array(
				'name' => 'apiurl',
				'label' => $this->registry->core->getMessage('TXT_MIGRATION_API_URL')
			)));
			
			$requiredData->AddChild(new FE_TextField(Array(
				'name' => 'apikey',
				'label' => $this->registry->core->getMessage('TXT_MIGRATION_API_KEY')
			)));
			
			$requiredData->AddChild(new FE_Select(Array(
				'name' => 'entity',
				'label' => $this->registry->core->getMessage('TXT_EXCHANGE_ENTITY'),
				'options' => Array(
					new FE_Option(1, $this->registry->core->getMessage('TXT_PRODUCTS')),
					new FE_Option(2, $this->registry->core->getMessage('TXT_CATEGORIES')),
					new FE_Option(3, $this->registry->core->getMessage('TXT_PRODUCERS')),
					new FE_Option(4, $this->registry->core->getMessage('TXT_PHOTOS')),
					new FE_Option(5, $this->registry->core->getMessage('TXT_CLIENTS'))
				),
				'default' => 1
			)));
			
			if ($form->Validate(FE::SubmittedData())){
				$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				App::redirect(__ADMINPANE__ . '/exchange/add/' . base64_encode(json_encode($Data)));
			}
			
			$this->registry->template->assign('form', $form);
			$this->registry->xajax->processRequest();
			$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
			$this->registry->template->display($this->loadTemplate('index.tpl'));
		
		}
		else{
			
			$form = new FE_Form(Array(
				'name' => 'add_migration',
				'action' => '',
				'method' => 'post'
			));
			
			$progress = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'progres_data',
				'label' => 'Aktualizacja'
			)));
			
			$progress->AddChild(new FE_ProgressIndicator(Array(
				'name' => 'progress',
				'label' => 'Postęp migracji',
				'chunks' => 1,
				'load' => Array(
					App::getModel('exchange/migration'),
					'doLoadQueque'
				),
				'process' => Array(
					App::getModel('exchange/migration'),
					'doProcessQueque'
				),
				'success' => Array(
					App::getModel('exchange/migration'),
					'doSuccessQueque'
				),
				'preventSubmit' => true
			)));
			
			$this->registry->template->assign('form', $form);
			$this->registry->xajax->processRequest();
			$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
			$this->registry->template->display($this->loadTemplate('index.tpl'));
		}
	}

	public function confirm () {
		
		$form = new FE_Form(Array(
			'name' => 'confirm_exchange',
			'action' => '',
			'method' => 'post'
		));
		
		$parsePane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'parse',
			'label' => 'Import danych'
		)));
		
		$parsePane->AddChild(new FE_Tip(Array(
			'tip' => '<p>Import zakończony powodzeniem</p>',
			'direction' => FE_Tip::DOWN
		)));
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function view () {
		$this->disableLayout();
		App::getModel('exchange')->exportFile($this->registry->core->getParam());
	
	}

	public function getEntityTypes ($type) {
		$tmp[1] = $this->registry->core->getMessage('TXT_PRODUCTS');
		$tmp[2] = $this->registry->core->getMessage('TXT_CATEGORIES');
		if ($type == 1){
			$tmp[3] = $this->registry->core->getMessage('TXT_CLIENTS');
			$tmp[4] = $this->registry->core->getMessage('TXT_ORDERS');
		}
		return FE_Option::Make($tmp);
	}
}