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
 * $Id: eraty.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class EratyModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
		$this->install();
	}

	public function install ()
	{
		//numersklepu- numer sklepu
		//typsprzedazy- zawsze 0- sprzedaż ratalna
		//wariantsklepu- identyfikacja sklepu (np. aukcja, sklep nr 1, etc.)
		//char- kodowanie znaków ()
		$sql = 'CREATE TABLE IF NOT EXISTS `eratysettings` (
					  `ideratysettings` int(10) unsigned NOT NULL auto_increment,
					  `numersklepu` varchar(10) default NULL,
					  `wariantsklepu` varchar(30) default NULL,
					  `typproduktu` int(2) unsigned default NULL,
					  `char` varchar(5) default NULL,
					  `paymentmethodid` int(10) unsigned default NULL,
					  `adddate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
					  `editid` int(10) unsigned default NULL,
					  `editdate` datetime default NULL,
 				  PRIMARY KEY  (`ideratysettings`)
				  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->executeUpdate();
	}

	public function getConfigurationFields ()
	{
		$sql = ' SELECT numersklepu, wariantsklepu, typproduktu, `char`, paymentmethodid
			 		 FROM eratysettings';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$values = array(
			'numersklepu' => 28019999,
			'wariantsklepu' => 1,
			'typproduktu' => 0,
			'char' => 'UTF',
			'paymentmethodid' => $this->getPaymentMethodIdForZagiel()
		);
		
		while ($rs->next()){
			$values = array(
				'numersklepu' => $rs->getInt('numersklepu'),
				'wariantsklepu' => $rs->getString('wariantsklepu'),
				'typproduktu' => $rs->getInt('typproduktu'),
				'char' => $rs->getString('char'),
				'paymentmethodid' => $rs->getInt('paymentmethodid')
			);
		}
		
		$Data = Array();
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'numersklepu',
			'label' => 'Numer sklepu',
			'comment' => 'Wprowadź numer sklepu. Integracja testowa 28019999',
			'help' => 'Wprowadź numer sklepu',
			'value' => $values['numersklepu']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'wariantsklepu',
			'label' => 'Wariant sklepu',
			'comment' => 'Wprowadź wariant sklepu. Itnegracja testowya 1',
			'help' => 'Wprowadź wariant sklepu',
			'value' => $values['wariantsklepu']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_Select',
			'name' => 'typproduktu',
			'label' => 'Typ produktu',
			'comment' => 'Zmienna używana przez system Żagiel. Dla sprzedaży intenretowej bezwzględnie posiada wartość 0',
			'help' => 'Wpisz typ produktu',
			'options' => Array(
				'0' => '0'
			),
			'value' => $values['typproduktu']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_Select',
			'name' => 'char',
			'label' => 'Kodowanie znaków',
			'comment' => 'Kodowanie znaków. Możesz wybrać jedną z wartości: ISO (ISO-8859-2), UTF (UTF-8) lub WIN (WINDOWS-1250)',
			'help' => 'Wprowadź kodowanie znaków znaków',
			'options' => Array(
				'UTF' => 'UTF-8',
				'ISO' => 'ISO-8859-2',
				'WIN' => 'WINDOWS-1250'
			),
			'value' => $values['char']
		);
		
		return $Data;
	}

	public function editPaymentmethodConfiguration ($Data)
	{
		$sql = 'TRUNCATE eratysettings';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->executeUpdate();
		
		$sql = 'INSERT INTO eratysettings 
					SET 
						numersklepu = :numersklepu,
						wariantsklepu = :wariantsklepu,
						typproduktu = :typproduktu,
						`char` = :char,
						paymentmethodid = :paymentmethodid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('numersklepu', $Data['numersklepu']);
		$stmt->setString('wariantsklepu', $Data['wariantsklepu']);
		$stmt->setInt('typproduktu', $Data['typproduktu']);
		$stmt->setString('char', $Data['char']);
		$paymentmethodid = $this->getPaymentMethodIdForZagiel();
		if ($paymentmethodid > 0){
			$stmt->setInt('paymentmethodid', $paymentmethodid);
		}
		else{
			$stmt->setNull('paymentmethodid');
		}
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function getPaymentMethodIdForZagiel ()
	{
		$paymentmethodid = 0;
		$sql = "SELECT idpaymentmethod
					FROM paymentmethod
					WHERE controller = 'eraty'";
		$stmt = $this->registry->db->prepareStatement($sql);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$paymentmethodid = $rs->getInt('idpaymentmethod');
				return $paymentmethodid;
			}
		}
		catch (Exception $e){
			echo $e->getMessage();
			return 0;
		}
	}
}