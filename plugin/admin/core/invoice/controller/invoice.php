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
 * $Id: invoice.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class invoiceController extends Admin
{

	public function index ()
	{
		
		$this->registry->xajax->registerFunction(array(
			'doDeleteInvoice',
			App::getModel('invoice'),
			'doAJAXDeleteInvoice'
		));
		
		$this->registry->xajax->registerFunction(array(
			'LoadAllInvoice',
			App::getModel('invoice'),
			'getInvoiceForAjax'
		));
		$this->Render();
	}

	public function view ()
	{
		$this->disableLayout();
		App::getModel('invoice')->getInvoiceById((int) $this->registry->core->getParam(0), (int) $this->registry->core->getParam(1));
	
	}

	public function confirm ()
	{
		$this->disableLayout();
		App::getModel('invoice')->exportInvoice(json_decode(base64_decode($this->registry->core->getParam())));
	}

	public function add ()
	{
		
		$orderData = App::getModel('order')->getOrderById((int) $this->registry->core->getParam());
		$viewData = App::getModel('view')->getView($orderData['viewid']);
		$invoiceType = (int) $this->registry->core->getParam(1);
		
		$invoiceNumber = App::getModel('invoice')->generateInvoiceNumber($viewData['invoicenumerationkind'], $invoiceType, $orderData['order_date'], $orderData['viewid']);
		
		$form = new FE_Form(Array(
			'name' => 'add_invoice',
			'action' => '',
			'method' => 'post'
		));
		
		$invoiceData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'invoice_data',
			'label' => $this->registry->core->getMessage('TXT_INVOICE')
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'salesperson',
			'label' => $this->registry->core->getMessage('TXT_SALES_PERSON'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_SALES_PERSON'))
			),
			'default' => App::getModel('users')->getUserFullName()
		)));
		
		$invoiceDate = $invoiceData->AddChild(new FE_Date(Array(
			'name' => 'invoicedate',
			'label' => $this->registry->core->getMessage('TXT_INVOICE_DATE'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_INVOICE_DATE'))
			),
			'default' => date('Y-m-d')
		)));
		
		if (file_exists(ROOTPATH . 'plugin' . DS . 'super' . DS . 'core' . DS . 'wfirma' . DS . 'model' . DS . 'wfirma.php')){
			$invoiceData->AddChild(new FE_StaticText(Array(
				'text' => '<p>Numer faktury zostanie wygenerowany poprzez API wFirma automatycznie.'
			)));
		}
		elseif (file_exists(ROOTPATH . 'plugin' . DS . 'super' . DS . 'core' . DS . 'infakt' . DS . 'model' . DS . 'infakt.php')){
			$invoiceData->AddChild(new FE_StaticText(Array(
				'text' => '<p>Numer faktury zostanie wygenerowany poprzez API inFakt automatycznie.'
			)));
		}
		else{
			$invoiceData->AddChild(new FE_TextField(Array(
				'name' => 'invoicenumber',
				'label' => $this->registry->core->getMessage('TXT_INVOICE_NUMBER'),
				'rules' => Array(
					new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_INVOICE_NUMBER'))
				),
				'default' => $invoiceNumber,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::SUGGEST, $invoiceDate, Array(
						App::getModel('invoice'),
						'getInvoiceNumberFormat'
					))
				)
			)));
		
		}
		
		$invoiceData->AddChild(new FE_Date(Array(
			'name' => 'duedate',
			'label' => $this->registry->core->getMessage('TXT_MATURITY'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_MATURITY'))
			),
			'default' => date('Y-m-d', strtotime('+' . $viewData['invoicedefaultpaymentdue'] . ' days'))
		)));
		
		$invoiceData->AddChild(new FE_Textarea(Array(
			'name' => 'comment',
			'label' => $this->registry->core->getMessage('TXT_COMMENT'),
			'default' => $this->registry->core->getMessage('TXT_ORDER') . ': ' . $orderData['order_id']
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'totalpayed',
			'label' => $this->registry->core->getMessage('TXT_TOTAL_PAYED'),
			'rules' => Array(
				new FE_RuleRequired($this->registry->core->getMessage('ERR_EMPTY_TOTAL_PAYED'))
			),
			'filters' => Array(
				new FE_FilterCommaToDotChanger()
			),
			'default' => '0.00',
			'suffix' => $orderData['currencysymbol']
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			
			$formData = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			if (file_exists(ROOTPATH . 'plugin' . DS . 'super' . DS . 'core' . DS . 'wfirma' . DS . 'model' . DS . 'wfirma.php')){
				App::getModel('wfirma')->addInvoice($formData, (int) $this->registry->core->getParam(0), (int) $this->registry->core->getParam(1), $orderData);
			}
			elseif (file_exists(ROOTPATH . 'plugin' . DS . 'super' . DS . 'core' . DS . 'infakt' . DS . 'model' . DS . 'infakt.php')){
				App::getModel('infakt')->addInvoice($formData, (int) $this->registry->core->getParam(0), (int) $this->registry->core->getParam(1), $orderData);
			}
			else{
				App::getModel('invoice')->addInvoice($formData, (int) $this->registry->core->getParam(0), (int) $this->registry->core->getParam(1), $orderData);
			}
			App::redirect(__ADMINPANE__ . '/order/edit/' . (int) $this->registry->core->getParam());
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}