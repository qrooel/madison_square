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
 * $Revision: 309 $
 * $Author: gekosale $
 * $Date: 2011-08-01 21:10:16 +0200 (Pn, 01 sie 2011) $
 * $Id: exchange.php 309 2011-08-01 19:10:16Z gekosale $ 
 */

class migrationController extends Controller
{

	public function index ()
	{
		
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
			
			$requiredData->AddChild(new FE_Tip(Array(
				'tip' => '<p>Podaj adres URL wtyczki integracyjnej.</p>',
				'direction' => FE_Tip::DOWN
			)));
			
			$requiredData->AddChild(new FE_TextField(Array(
				'name' => 'apiurl',
				'label' => $this->registry->core->getMessage('TXT_MIGRATION_API_URL')
			)));
			
			$requiredData->AddChild(new FE_Tip(Array(
				'tip' => '<p>Podaj klucz jaki został ustawiony w pliku integracyjnym ($key)</p>',
				'direction' => FE_Tip::DOWN
			)));
			
			$requiredData->AddChild(new FE_TextField(Array(
				'name' => 'apikey',
				'label' => $this->registry->core->getMessage('TXT_MIGRATION_API_KEY')
			)));
			
			$requiredData->AddChild(new FE_Tip(Array(
				'tip' => '<p>Wybierz rodzaj importowanych danych.Sugerujemy import w następującej kolejności:
							<ul>
							<li>Zdjęcia</li>
							<li>Producenci</li>
							<li>Kategorie</li>
							<li>Produkty</li>
							</ul></p>',
				'direction' => FE_Tip::DOWN
			)));
			
			$requiredData->AddChild(new FE_Select(Array(
				'name' => 'entity',
				'label' => $this->registry->core->getMessage('TXT_EXCHANGE_ENTITY'),
				'options' => Array(
					new FE_Option(1, $this->registry->core->getMessage('TXT_PRODUCTS')),
					new FE_Option(2, $this->registry->core->getMessage('TXT_CATEGORIES')),
					new FE_Option(3, $this->registry->core->getMessage('TXT_PRODUCERS')),
					new FE_Option(4, $this->registry->core->getMessage('TXT_PHOTOS'))
				),
				'default' => 1
			)));
			
			if ($form->Validate(FE::SubmittedData())){
				$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				App::redirect(__ADMINPANE__ . '/migration/' . base64_encode(json_encode($Data)));
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
					App::getModel('migration'),
					'doLoadQueque'
				),
				'process' => Array(
					App::getModel('migration'),
					'doProcessQueque'
				),
				'success' => Array(
					App::getModel('migration'),
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

}