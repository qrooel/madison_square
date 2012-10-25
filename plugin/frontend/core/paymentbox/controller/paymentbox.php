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
 * $Id: paymentbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class PaymentBoxController extends BoxController
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'setPeymentChecked',
			App::getModel('payment'),
			'setAJAXPaymentMethodChecked'
		));
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->xajax->processRequest();
		$clientModel = App::getModel('client');
		$this->registry->template->assign('payments', App::getModel('payment')->getPaymentMethods());
		$this->registry->template->assign('checkedPayment', $this->registry->session->getActivePaymentMethodChecked());
		$this->registry->template->assign('priceWithDispatch', $this->registry->session->getActiveglobalPriceWithDispatchmethod());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function accept ()
	{
		$clientorder = $this->registry->session->getActivePaymentData();
		if (isset($clientorder) && $clientorder != NULL){
			$paymentMethodModel = App::getModel('payment')->getPaymentMethodById($clientorder['orderData']['payment']['idpaymentmethod']);
			$paymentMethodData = App::getModel('payment/' . $paymentMethodModel)->getData($clientorder);
			$this->registry->template->assign('content', $paymentMethodData);
			$this->registry->template->assign('orderId', $clientorder['orderId']);
			$this->registry->template->assign('orderData', $clientorder['orderData']);
			$this->registry->template->display($this->loadTemplate($paymentMethodModel . '.tpl'));
			echo App::getModel('googleanalitycs')->addTransGoogleAnalitycsJs($clientorder);
		}
		else{
			App::redirect(App::getRegistry()->core->getControllerNameForSeo('mainside'));
		}
	}

	public function confirm ()
	{
		$clientorder = $this->registry->session->getActivePaymentData();
		if (isset($clientorder) && $clientorder != NULL){
			$paymentMethodModel = App::getModel('payment')->getPaymentMethodById($clientorder['orderData']['payment']['idpaymentmethod']);
			$paymentMethodData = App::getModel('payment/' . $paymentMethodModel)->getData($clientorder);
			$paymentMethodData = App::getModel('payment/' . $paymentMethodModel)->confirmPayment($clientorder, $this->registry->core->getParam());
			$this->registry->template->assign('content', $paymentMethodData);
			$this->registry->template->assign('orderId', $clientorder['orderId']);
			$this->registry->template->assign('orderData', $clientorder['orderData']);
			$this->registry->template->display($this->loadTemplate($paymentMethodModel . '.tpl'));
		}
		else{
			App::redirect(App::getRegistry()->core->getControllerNameForSeo('mainside'));
		}
	}

	public function cancel ()
	{
		$clientorder = $this->registry->session->getActivePaymentData();
		if (isset($clientorder) && $clientorder != NULL){
			$paymentMethodModel = App::getModel('payment')->getPaymentMethodById($clientorder['orderData']['payment']['idpaymentmethod']);
			$paymentMethodData = App::getModel('payment/' . $paymentMethodModel)->getData($clientorder);
			$paymentMethodData = App::getModel('payment/' . $paymentMethodModel)->cancelPayment($clientorder, $this->registry->core->getParam());
			$this->registry->template->assign('content', $paymentMethodData);
			$this->registry->template->assign('orderId', $clientorder['orderId']);
			$this->registry->template->assign('orderData', $clientorder['orderData']);
			$this->registry->template->display($this->loadTemplate($paymentMethodModel . '.tpl'));
		}
		else{
			App::redirect(App::getRegistry()->core->getControllerNameForSeo('mainside'));
		}
	}

	public function getBoxHeading ()
	{
		$clientorder = $this->registry->session->getActivePaymentData();
		if ($clientorder != NULL && isset($clientorder['orderData']['payment']['paymentmethodname'])){
			return $clientorder['orderData']['payment']['paymentmethodname'];
		}
	}

}