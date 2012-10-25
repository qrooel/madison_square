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
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: paypal.php 687 2012-09-01 12:02:47Z gekosale $
 */

class PaypalModel extends Model
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->business = '';
		$this->sandbox = 1;
		$this->setPaypalSettings();
		$this->gatewayurl = "https://www.paypal.com/cgi-bin/webscr";
		if ($this->sandbox === 1){
			$this->gatewayurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}
		$this->returnurl = App::getURLAdress() . $this->registry->core->getControllerNameForSeo('payment') . '/confirm';
		$this->cancelurl = App::getURLAdress() . $this->registry->core->getControllerNameForSeo('payment') . '/cancel';
		$this->notifyurl = App::getURLAdress() . 'paypalreport';
		$this->ipnLogFile = ROOTPATH . 'logs/paypal.ipn_results.log';
		$this->lastError = '';
		$this->ipnData = Array();
		$this->ipnResponse = '';
	}

	public function setPaypalSettings ()
	{
		$sql = 'SELECT 
					business,
					apiusername,
					apipassword,
					apisignature,
					sandbox
				FROM paypalsettings
				';
		$stmt = $this->registry->db->prepareStatement($sql);
		
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = array();
		if ($rs->first()){
			$this->business = $rs->getString('business');
			$this->apiusername = $rs->getString('apiusername');
			$this->apipassword = $rs->getString('apipassword');
			$this->apisignature = $rs->getString('apisignature');
			$this->sandbox = $rs->getInt('sandbox');
		}
	}

	public function confirmPayment ($Data, $params)
	{
		return false;
	}

	public function cancelPayment ($Data, $params)
	{
		return false;
	}

	public function getData ()
	{
		
		$clientorder = $this->registry->session->getActivePaymentData();
		
		$Data = Array();
		$Data['rm'] = 2;
		$Data['cmd'] = '_xclick';
		$Data['business'] = $this->business;
		$Data['currency_code'] = $this->registry->session->getActiveCurrencySymbol();
		$Data['gateway'] = $this->gatewayurl;
		$Data['return'] = $this->returnurl;
		$Data['cancel_return'] = $this->cancelurl;
		$Data['notify_url'] = $this->notifyurl;
		$Data['item_name'] = $this->registry->core->getMessage('TXT_ORDERS_NR') . ' ' . $clientorder['orderId'];
		$Data['amount'] = number_format($clientorder['orderData']['priceWithDispatchMethod'],2);
		$Data['item_number'] = $clientorder['orderId'];
		$signature = base64_encode(session_id() . '-' . $clientorder['orderId']);
		$Data['session_id'] = $signature;
		return $Data;
	
	}

	public function validateIpn ($Data)
	{
		
		$urlParsed = parse_url($this->gatewayurl);
		$postString = '';
		
		foreach ($Data as $field => $value){
			$this->ipnData["$field"] = $value;
			$postString .= $field . '=' . urlencode(stripslashes($value)) . '&';
		}
		
		$postString .= "cmd=_notify-validate";
		
		// setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->gatewayurl);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
		// turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		$this->ipnResponse = curl_exec($ch);
		
		if (@eregi("VERIFIED", $this->ipnResponse)){
			$this->logResults(true);
			return true;
		}
		else{
			$this->logResults(false);
			return false;
		}
	}

	public function logResults ($success)
	{
		
		$text = '[' . date('m/d/Y g:i A') . '] - ';
		$text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";
		$text .= "IPN POST Vars from gateway:\n";
		foreach ($this->ipnData as $key => $value){
			$text .= "$key=$value, ";
		}
		$text .= "\nIPN Response from gateway Server:\n " . $this->ipnResponse;
		$fp = fopen($this->ipnLogFile, 'a');
		fwrite($fp, $text . "\n\n");
		fclose($fp);
	}

	public function notifyPayment ($Data)
	{
		
		if ($this->validateIpn($Data)){
			if ($this->ipnData['payment_status'] == 'Completed'){
				
				$sql = 'SELECT
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :sessionid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('sessionid', base64_decode($this->ipnData['custom']));
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = "UPDATE `order` SET
								orderstatusid = (SELECT positiveorderstatusid FROM paypalsettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid')); 
					$stmt->executeQuery();
				}
			
			}
			else{
				
				$sql = 'SELECT
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :sessionid';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('sessionid', base64_decode($this->ipnData['custom']));
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = "UPDATE `order` SET
								orderstatusid = (SELECT negativeorderstatusid FROM paypalsettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid'));
					$stmt->executeQuery();
				}
			
			}
		}
	
	}

}