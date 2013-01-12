<?php
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.com
 *
 * Copyright (c) 2009 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */
class TransferujModel extends Model{

	public function __construct($registry, $modelFile){
		parent::__construct($registry, $modelFile);
	}
	
	public function confirmPayment($Data, $params) {
		return false;
	}
	
	public function cancelPayment($Data, $params) {
		return false;
	}
	
	public function getData(){
		$clientorder = $this->registry->session->getActivePaymentData();
		
		$sql = 'SELECT 
					idsprzedawcy,
					kodsprzedawcy
				FROM transferujsettings WHERE viewid = :viewid';		
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		try {
			$rs = $stmt->executeQuery();
			$Data = Array();
			if($rs->first()) {
				$Data = Array (
					'idsprzedawcy' => $rs->getInt('idsprzedawcy'),
					'kodsprzedawcy' => $rs->getString('kodsprzedawcy'),
					'crc' => base64_encode(session_id().'-'.$clientorder['orderId']),
					'md5sum' => md5($rs->getInt('idsprzedawcy').$clientorder['orderData']['priceWithDispatchMethod'].base64_encode(session_id().'-'.$clientorder['orderId']).$rs->getString('kodsprzedawcy')),
				);
			}
		} catch (FrontendException $e) {
			throw new FrontendException ('Error while doing sql query- getData- transferujModel.');
		}
		return $Data;
	}
}