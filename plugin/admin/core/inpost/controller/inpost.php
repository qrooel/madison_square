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
 * $Revision: 652 $
 * $Author: gekosale $
 * $Date: 2012-03-06 22:10:50 +0100 (Wt, 06 mar 2012) $
 * $Id: order.php 652 2012-03-06 21:10:50Z gekosale $
 */

class InpostController extends Admin {

	public function __construct ($registry) {
		parent::__construct($registry);
		$this->model = App::getModel('inpost');
	}

	public function index () {
		$this->registry->xajax->registerFunction(array(
			'LoadAllOrder',
			$this->model,
			'getOrderForAjax'
		));
		
		$this->registry->template->assign(Array(
			'datagrid_filter' => $this->model->getDatagridFilterData()
		));
		
		$this->Render();
	}

	public function view () {
		$this->disableLayout();
		$viewid = App::getModel('inpost')->getOrderViewIdByInpostPackage($this->registry->core->getParam());
		$settings = $this->registry->core->loadModuleSettings('inpost', $viewid);
		$pdf = App::getModel('inpost')->inpost_get_sticker($settings['inpostlogin'], $settings['inpostpassword'], $this->registry->core->getParam());
		$filename = $this->registry->core->getParam() . '.pdf';
		header("Pragma: public");
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header("Content-Disposition: attachment; filename=$filename");
		header('Content-Transfer-Encoding: binary');
		echo $pdf;
	}

	public function confirm () {
		$inpostOrders = App::getModel('inpost')->getInpostOrders();
		if (! empty($inpostOrders)){
			$views = Helper::getViewIds();
			foreach ($views as $view){
				if ($view > 0){
					$settings = $this->registry->core->loadModuleSettings('inpost', $view);
					if (! empty($settings)){
						$packages = App::getModel('inpost')->inpost_get_packs_by_sender($settings['inpostlogin'], $settings['inpostpassword']);
						foreach ($packages as $package){
							if (isset($inpostOrders[$package['packcode']])){
								App::getModel('inpost')->updatePackageStatus($package['packcode'], $package['status']);
							}
						}
					}
				}
			}
		}
		App::redirect(__ADMINPANE__ . '/inpost/');
	}

	public function add () {
		
		$orderData = App::getModel('order')->getOrderById((int) $this->registry->core->getParam());
		$viewData = App::getModel('view')->getView($orderData['viewid']);
		$settings = $this->registry->core->loadModuleSettings('inpost', $orderData['viewid']);
		$packsData = App::getModel('inpost')->getDataByOrderId((int) $this->registry->core->getParam());
		
		$form = new FE_Form(Array(
			'name' => 'add_inpost',
			'action' => '',
			'method' => 'post'
		));
		
		$invoiceData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'inpost_data',
			'label' => 'Dane paczki'
		)));
		
		$invoiceData->AddChild(new FE_Hidden(Array(
			'name' => 'senderEmail',
			'default' => $settings['inpostlogin']
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'adreseeEmail',
			'label' => 'Adres e-mail odbiorcy',
			'default' => $orderData['delivery_address']['email']
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'phoneNum',
			'label' => 'Numer telefonu odbiorcy',
			'default' => $orderData['delivery_address']['phone']
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'boxMachineName',
			'label' => 'Numer paczkomatu',
			'default' => $packsData
		)));
		
		$invoiceData->AddChild(new FE_Select(Array(
			'name' => 'packType',
			'label' => 'Typ paczki',
			'options' => Array(
				new FE_Option('A', 'A'),
				new FE_Option('B', 'B'),
				new FE_Option('C', 'C')
			)
		)));
		
		$insurance = $invoiceData->AddChild(new FE_Checkbox(Array(
			'name' => 'insurance',
			'label' => 'Ubezpieczenie przesyłki'
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'insuranceAmount',
			'label' => 'Kwota ubezpieczenia',
			'default' => $orderData['total'],
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $insurance, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$ondelivery = $invoiceData->AddChild(new FE_Checkbox(Array(
			'name' => 'ondelivery',
			'label' => 'Przesyłka pobraniowa',
			'default' => ($orderData['pricebeforepromotion'] > 0 && ($orderData['pricebeforepromotion'] < $orderData['total'])) ? 1 : 0
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'onDeliveryAmount',
			'label' => 'Kwota pobrania',
			'default' => $orderData['total'],
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::HIDE, $ondelivery, new FE_ConditionNot(new FE_ConditionEquals('1')))
			)
		)));
		
		$invoiceData->AddChild(new FE_TextField(Array(
			'name' => 'customerRef',
			'label' => 'Informacja dodatkowa',
			'default' => $this->registry->core->getMessage('TXT_ORDER') . ' ' . (int) $this->registry->core->getParam()
		)));
		
		$senderAddress = $invoiceData->AddChild(new FE_Fieldset(Array(
			'name' => 'senderAddress',
			'label' => 'Adres wysyłki'
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'name',
			'label' => 'Imię odbiorcy',
			'default' => $orderData['delivery_address']['firstname']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'surName',
			'label' => 'Nazwisko odbiorcy',
			'default' => $orderData['delivery_address']['surname']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'email',
			'label' => 'E-mail odbiorcy',
			'default' => $orderData['delivery_address']['email']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'phoneNum',
			'label' => 'Numer telefonu odbiorcy',
			'default' => $orderData['delivery_address']['phone']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'street',
			'label' => 'Ulica',
			'default' => $orderData['delivery_address']['street']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'buildingNo',
			'label' => 'Numer ulicy',
			'default' => $orderData['delivery_address']['streetno']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'flatNo',
			'label' => 'Numer lokalu',
			'default' => $orderData['delivery_address']['placeno']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'town',
			'label' => 'Miasto',
			'default' => $orderData['delivery_address']['city']
		)));
		
		$senderAddress->AddChild(new FE_TextField(Array(
			'name' => 'zipCode',
			'label' => 'Kod pocztowy',
			'default' => $orderData['delivery_address']['postcode']
		)));
		
		$senderAddress->AddChild(new FE_Hidden(Array(
			'name' => 'province',
			'default' => ''
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			
			$formData = $form->getSubmitValues();
			$formData['inpost_data']['phoneNum'] = $this->parseNumber($formData['inpost_data']['phoneNum']);
			$formData['inpost_data']['senderAddress']['phoneNum'] = $this->parseNumber($formData['inpost_data']['senderAddress']['phoneNum']);
			if ($formData['inpost_data']['insurance'] != 1){
				$formData['inpost_data']['insuranceAmount'] = '';
			}
			if ($formData['inpost_data']['ondelivery'] != 1){
				$formData['inpost_data']['onDeliveryAmount'] = '';
			}
			$Package[(int) $this->registry->core->getParam()] = $formData['inpost_data'];
			$packcode = App::getModel('inpost')->inpost_send_packs($settings['inpostlogin'], $settings['inpostpassword'], $Package, 0, 0);
			App::getModel('inpost')->updatePackCodeNo((int) $this->registry->core->getParam(), $packcode[(int) $this->registry->core->getParam()]['packcode']);
			App::redirect(__ADMINPANE__ . 'inpost');
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function parseNumber ($number) {
		$chars = array(
			'-',
			',',
			' ',
			'+'
		);
		$number = str_replace($chars, '', $number);
		$number = trim($number);
		if (strlen($number) == 9){
			$number = "48" . $number;
		}
		return substr($number, 0, 9);
	}

}