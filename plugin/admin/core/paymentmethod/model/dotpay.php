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
	class DotpayModel extends Model{
		
		public function __construct($registry, $modelFile){
			parent::__construct($registry, $modelFile);
		}

		public function getConfigurationFields(){
			$sql = 'SELECT * FROM dotpaysettings WHERE viewid = :viewid';		
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();	
			$Values = array(
				'idsprzedawcy' => null,
				'pin' => null,
				'positiveorderstatusid' => null,
				'negativeorderstatusid' => null,
			);
			while($rs->next()){
				$Values = array(
					'idsprzedawcy'=>$rs->getInt('idsprzedawcy'),
					'pin'=>$rs->getString('pin'),
					'positiveorderstatusid'=> $rs->getInt('positiveorderstatusid'),
					'negativeorderstatusid'=> $rs->getInt('negativeorderstatusid'),
				);
			}	
			
			$Data = array();
			
			$Data[] = Array(
				'fe_element' => 'FE_TextField',
				'name' => 'idsprzedawcy',
				'label' => 'Numer ID w Dotpay.pl',
				'comment' => 'Podaj swoj numer ID w serwisie Dotpay',
				'help' => 'Wprowadź id sprzedawcy',
				'value' => $Values['idsprzedawcy']
			);
			
			$Data[] = Array(
				'fe_element' => 'FE_TextField',
				'name' => 'pin',
				'label' => 'Potwierdzenia URLC - PIN',
				'comment' => 'Wprowadz swoj numer PIN do potwierdzen URLC.',
				'help' => 'Wprowadź kod pomocniczy',
				'value' => $Values['pin']
			);
			
			$Data[] = Array(
				'fe_element' => 'FE_Select',
				'name' => 'positiveorderstatusid',
				'label' => 'Status zamówienia dla płatności zakończonej',
				'comment' => 'Wybierz status zamówienia po zaakceptowaniu płatności',
				'help' => 'Wybierz status zamówienia po zaakceptowaniu płatności',
				'options' => App::getModel('orderstatus')->getOrderStatusToSelect(),
				'value' => $Values['positiveorderstatusid']
			);
			
			$Data[] = Array(
				'fe_element' => 'FE_Select',
				'name' => 'negativeorderstatusid',
				'label' => 'Status zamówienia dla płatności anulowanej',
				'comment' => 'Wybierz status zamówienia po anulowaniu płatności',
				'help' => 'Wybierz status zamówienia po anulowaniu płatności',
				'options' => App::getModel('orderstatus')->getOrderStatusToSelect(),
				'value' => $Values['negativeorderstatusid']
			);
			
			return $Data;
		}
		
		public function editPaymentmethodConfiguration($Data){
			$sql = 'DELETE FROM dotpaysettings WHERE viewid = :viewid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->executeUpdate();
			
			$sql = 'INSERT INTO dotpaysettings SET 
						idsprzedawcy=:idsprzedawcy,
						pin = :pin,
						viewid = :viewid,
						positiveorderstatusid = :positiveorderstatusid,
						negativeorderstatusid = :negativeorderstatusid
						';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('idsprzedawcy', $Data['idsprzedawcy']);
			$stmt->setInt('positiveorderstatusid', $Data['positiveorderstatusid']);
			$stmt->setInt('negativeorderstatusid', $Data['negativeorderstatusid']);
			$stmt->setString('pin', $Data['pin']);
			$stmt->setInt('viewid', Helper::getViewId());
			try{
				$stmt->executeQuery();
			}catch(Exception $e){
				echo $e->getMessage();
				return false;
			}
			return true;
		}

}