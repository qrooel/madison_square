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
 * $Id: currencieslist.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class currencieslistController extends Controller
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('currencieslist');
	}

	public function index ()
	{
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllCurrencieslist',
			$this->model,
			'getCurrencieslistForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteCurrencieslist',
			$this->model,
			'doAJAXDeleteCurrencieslist'
		));
		$this->registry->xajax->registerFunction(array(
			'doUpdateCurrency',
			$this->model,
			'doAJAXUpdateCurrencieslist'
		));
		$this->registry->xajax->registerFunction(array(
			'refreshAllCurrencies',
			$this->model,
			'doAJAXRefreshAllCurrencies'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', $this->model->getDatagridFilterData());
		$this->registry->template->assign('dgFilterDefaultCurrency', $this->layer['currencysymbol']);
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_currencieslist',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'symbol',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_SYMBOL'),
			'options' => FE_Option::Make(App::getModel('currencieslist')->getCurrenciesALLToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CURRENCY_SYMBOL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'decimalseparator',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DECIMAL_SEPARATOR')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'decimalcount',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DECIMAL_COUNT')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'thousandseparator',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_THOUSAND_SEPARATOR')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'positivepreffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_POSITIVE_PREFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'positivesuffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_POSITIVE_SUFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'negativepreffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NEGATIVE_PREFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'negativesuffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NEGATIVE_SUFFIX')
		)
		));
		
		$exchangeData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'exchange_data',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_EXCHANGE')
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencies();
		
		foreach ($currencies as $key => $currency){
			
			$exchangeData->AddChild(new FE_TextField(Array(
				'name' => $currency['idcurrency'],
				'label' => $currency['currencysymbol']
			)));
		
		}
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->addCurrencieslist($form->getSubmitValues(FE_Form::FORMAT_FLAT));
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/currencieslist');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('add.tpl'));
	}

	public function edit ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'edit_currencieslist',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_MAIN_DATA')
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NAME'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_NAME'))
			)
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'symbol',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_SYMBOL'),
			'options' => FE_Option::Make(App::getModel('currencieslist')->getCurrenciesALLToSelect()),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_CURRENCY_SYMBOL'))
			)
		)));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'decimalseparator',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DECIMAL_SEPARATOR')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'decimalcount',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_DECIMAL_COUNT')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'thousandseparator',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_THOUSAND_SEPARATOR')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'positivepreffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_POSITIVE_PREFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'positivesuffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_POSITIVE_SUFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'negativepreffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NEGATIVE_PREFFIX')
		)
		));
		
		$requiredData->AddChild(new FE_TextField(Array(
			'name' => 'negativesuffix',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_NEGATIVE_SUFFIX')
		)
		));
		
		$exchangeData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'exchange_data',
			'label' => $this->registry->core->getMessage('TXT_CURRENCY_EXCHANGE')
		)));
		
		$currencies = App::getModel('currencieslist')->getCurrencies();
		
		foreach ($currencies as $key => $currency){
			
			$exchangeData->AddChild(new FE_TextField(Array(
				'name' => 'currency_'.$currency['idcurrency'],
				'label' => $currency['currencysymbol']
			)));
		
		}
		
		$layerData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'view_data',
			'label' => $this->registry->core->getMessage('TXT_STORES')
		)));
		
		$layerData->AddChild(new FE_LayerSelector(Array(
			'name' => 'view',
			'label' => $this->registry->core->getMessage('TXT_VIEW')
		)));
		
		$PopulateData = $this->model->getCurrencieslistView($this->registry->core->getParam());
		
		$CurrentCurrencieslistData = Array(
			'required_data' => Array(
				'name' => $PopulateData['name'],
				'symbol' => $PopulateData['symbol'],
				'decimalseparator' => $PopulateData['decimalseparator'],
				'decimalcount' => $PopulateData['decimalcount'],
				'thousandseparator' => $PopulateData['thousandseparator'],
				'positivepreffix' => $PopulateData['positivepreffix'],
				'positivesuffix' => $PopulateData['positivesuffix'],
				'negativepreffix' => $PopulateData['negativepreffix'],
				'negativesuffix' => $PopulateData['negativesuffix']
			),
			'exchange_data' => Array(
				$PopulateData['exchangerates']
			),
			'view_data' => Array(
				'view' => $PopulateData['view']
			)
		);
		
		$form->Populate($CurrentCurrencieslistData);
		
		if ($form->Validate(FE::SubmittedData())){
			try{
				$this->model->editCurrencieslist($form->getSubmitValues(FE_Form::FORMAT_FLAT), $this->registry->core->getParam());
			}
			catch (Exception $e){
				$this->registry->template->assign('error', $e->getMessage());
			}
			App::redirect(__ADMINPANE__ . '/currencieslist');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}
}