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
	class Przelewy24Model extends Model{
		
		public function __construct($registry, $modelFile){
			parent::__construct($registry, $modelFile);
		}

		public function getConfigurationFields(){
			$sql = 'SELECT * FROM przelewy24settings WHERE viewid = :viewid';		
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();	
			$Values = array(
				'idsprzedawcy' => null,
				'crc' => null,
				'positiveorderstatusid' => null,
				'negativeorderstatusid' => null,
			);
			while($rs->next()){
				$Values = array(
					'idsprzedawcy'=>$rs->getInt('idsprzedawcy'),
					'crc'=>$rs->getString('crc'),
					'positiveorderstatusid'=> $rs->getInt('positiveorderstatusid'),
					'negativeorderstatusid'=> $rs->getInt('negativeorderstatusid'),
				);
			}	
			
			$Data = array();
			
			$Data[] = Array(
				'fe_element' => 'FE_TextField',
				'name' => 'idsprzedawcy',
				'label' => 'Id sprzedawcy',
				'comment' => 'Identyfikator sprzedawcy',
				'help' => 'Wprowadź id sprzedawcy',
				'value' => $Values['idsprzedawcy']
			);
			
			$Data[] = Array(
				'fe_element' => 'FE_TextField',
				'name' => 'crc',
				'label' => 'Klucz do CRC',
				'comment' => 'znajduje się w panelu Przelewy24 -> Moje dane',
				'help' => 'Wprowadź klucz CRC',
				'value' => $Values['crc']
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
			$sql = 'DELETE FROM przelewy24settings WHERE viewid = :viewid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->executeUpdate();
			
			$sql = 'INSERT INTO przelewy24settings SET 
						idsprzedawcy=:idsprzedawcy,
						crc = :crc,
						viewid = :viewid,
						positiveorderstatusid = :positiveorderstatusid,
						negativeorderstatusid = :negativeorderstatusid
						';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('idsprzedawcy', $Data['idsprzedawcy']);
			$stmt->setInt('positiveorderstatusid', $Data['positiveorderstatusid']);
			$stmt->setInt('negativeorderstatusid', $Data['negativeorderstatusid']);
			$stmt->setString('crc', $Data['crc']);
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