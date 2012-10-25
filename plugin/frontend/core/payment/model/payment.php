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
 * $Id: payment.php 655 2012-04-24 08:51:44Z gekosale $
 */

class paymentModel extends Model
{

	public function getPaymentMethods ()
	{
		$iddispatchmethod = $this->registry->session->getActiveDispatchmethodChecked();
		$sql = "SELECT PM.name, PM.idpaymentmethod, PM.controller
					FROM paymentmethod PM
					LEFT JOIN paymentmethodview PV ON PV.paymentmethodid = idpaymentmethod
					LEFT JOIN dispatchmethodpaymentmethod DMPM ON PM.idpaymentmethod= DMPM.paymentmethodid
					LEFT JOIN dispatchmethod DM ON DM.iddispatchmethod=DMPM.dispatchmethodid
					WHERE DM.iddispatchmethod=:iddispatchmethod AND PM.active = 1 AND PV.viewid=:viewid
					ORDER BY PM.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('iddispatchmethod', $iddispatchmethod['dispatchmethodid']);
		try{
			$res = $stmt->executeQuery();
			$Data = Array();
			while ($res->next()){
				$controller = $res->getString('controller');
				if ($controller == 'eraty'){
					$idpaymentmethod = $res->getInt('idpaymentmethod');
					$eraty = $this->checkEraty($idpaymentmethod);
					if (! empty($eraty) && $eraty > 0){
						$Data[] = Array(
							'name' => $this->registry->core->getMessage($res->getString('name')),
							'idpaymentmethod' => $res->getInt('idpaymentmethod'),
							'wariantsklepu' => $eraty['wariantsklepu'],
							'numersklepu' => $eraty['numersklepu']
						);
					}
				}
				else{
					$Data[] = Array(
						'name' => $this->registry->core->getMessage($res->getString('name')),
						'idpaymentmethod' => $res->getInt('idpaymentmethod')
					);
				}
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while doing sql query- getPaymentMethods- paymentModel.');
		}
		return $Data;
	}

	public function setAJAXPaymentMethodChecked ($idpaymentmethod, $paymentmethodname)
	{
		$objResponse = new xajaxResponse();
		$designPath = DESIGNPATH . '_images_frontend/buttons/';
		$url = App::getURLAdress();
		$this->setPaymentMethodChecked($idpaymentmethod, $paymentmethodname);
		$this->registry->session->setActiveClientOrder(0);
		
		$objResponse->script("document.getElementById('payment-" . $idpaymentmethod . "').checked = true;");
		$objResponse->script('xajax_refreshFinalization();');
		return $objResponse;
	}

	public function setPaymentMethodChecked ($idpaymentmethod, $paymentmethodname)
	{
		if ($idpaymentmethod != NULL){
			$activePayment = Array(
				'idpaymentmethod' => $idpaymentmethod,
				'paymentmethodname' => $paymentmethodname
			);
			$this->registry->session->setActivePaymentMethodChecked($activePayment);
		}
		else{
			$this->registry->session->setActivePaymentMethodChecked(0);
		}
	}

	public function getPaymentMethodById ($id)
	{
		$sql = 'SELECT controller 
					FROM paymentmethod 
					LEFT JOIN paymentmethodview PV ON PV.paymentmethodid = idpaymentmethod
					WHERE idpaymentmethod = :idpaymentmethod AND PV.viewid=:viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idpaymentmethod', $id);
		$rs = $stmt->executeQuery();
		$Data = array();
		if ($rs->first()){
			return $rs->getString('controller');
		}
	}

	public function checkEraty ($idpaymentmethod)
	{
		$price = $this->registry->session->getActiveglobalPriceWithDispatchmethod();
		if ($price > 0){
			if ($price < 100){
				return 0;
			}
		}
		else{
			$order = $this->registry->session->getActiveClientOrder();
			if (! isset($order['priceWithDispatchMethod']) || $order['priceWithDispatchMethod'] < 100){
				return 0;
			}
		}
		$sql = "SELECT ES.wariantsklepu, ES.numersklepu, ES.`char`
					FROM eratysettings ES
						LEFT JOIN paymentmethodview PV ON  ES.paymentmethodid  = PV.paymentmethodid
					WHERE PV.viewid = :viewid
					AND ES.paymentmethodid = :idpaymentmethod";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idpaymentmethod', $idpaymentmethod);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'wariantsklepu' => $rs->getInt('wariantsklepu'),
				'numersklepu' => $rs->getString('numersklepu'),
				'char' => $rs->getString('char')
			);
			return $Data;
		}
		return 0;
	}
}
?>