<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: platnosci.php 687 2012-09-01 12:02:47Z gekosale $
 */
class PlatnosciModel extends Model {

	public function __construct ($registry, $modelFile) {
		parent::__construct($registry, $modelFile);
	}

	public function confirmPayment ($Data, $params) {
		return false;
	}

	public function cancelPayment ($Data, $params) {
		return false;
	}

	public function getData () {
		$clientorder = $this->registry->session->getActivePaymentData();
		$sql = 'SELECT 
					*
				FROM platnoscisettings
				WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			
			if (isset($clientorder['orderData']['priceWithDispatchMethodPromo'])){
				$cost = $clientorder['orderData']['priceWithDispatchMethodPromo'];
			}
			else{
				$cost = $clientorder['orderData']['priceWithDispatchMethod'];
			}
			$amount = $cost * 100;
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$Data = Array();
			if ($rs->first()){
				$Data['language'] = 'PL';
				$Data['session_id'] = session_id() . '-' . $clientorder['orderId'];
				$Data['order_id'] = $clientorder['orderId'];
				$Data['js'] = 1;
				$Data['pos_id'] = $rs->getInt('idpos');
				$Data['pos_auth_key'] = $rs->getString('authkey');
				$Data['amount'] = $amount;
				$Data['desc'] = 'Zamowienie ' . $clientorder['orderId'] . ' - ' . $clientorder['orderData']['clientdata']['firstname'] . ' ' . $clientorder['orderData']['clientdata']['surname'];
				$Data['first_name'] = $clientorder['orderData']['clientdata']['firstname'];
				$Data['last_name'] = $clientorder['orderData']['clientdata']['surname'];
				$Data['street'] = $clientorder['orderData']['deliveryAddress']['street'];
				$Data['street_hn'] = $clientorder['orderData']['deliveryAddress']['streetno'];
				$Data['city'] = $clientorder['orderData']['deliveryAddress']['placename'];
				$Data['post_code'] = $clientorder['orderData']['deliveryAddress']['postcode'];
				$Data['country'] = 'Poland';
				$Data['phone'] = $clientorder['orderData']['deliveryAddress']['phone'];
				$Data['email'] = $clientorder['orderData']['deliveryAddress']['email'];
				$Data['client_ip'] = $_SERVER["REMOTE_ADDR"];
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while doing sql query- getData- transferujModel.');
		}
		return $Data;
	}
}