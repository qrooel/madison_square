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
 * $Id: platnosci.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class PlatnosciModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getConfigurationFields ()
	{
		$sql = 'SELECT idpos,firstmd5,secondmd5,authkey FROM platnoscisettings WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Values = array(
			'idpos' => null,
			'firstmd5' => null,
			'secondmd5' => null,
			'authkey' => null
		);
		while ($rs->next()){
			$Values = array(
				'idpos' => $rs->getInt('idpos'),
				'firstmd5' => $rs->getString('firstmd5'),
				'secondmd5' => $rs->getString('secondmd5'),
				'authkey' => $rs->getString('authkey')
			);
		}
		
		$Data = array();
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'idpos',
			'label' => 'Id punktu płatności (pos_id)',
			'comment' => 'Identyfikator punktu płatności (pos_id)',
			'help' => 'Wprowadź id punktu płatności (pos_id)',
			'value' => $Values['idpos']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'firstmd5',
			'label' => 'Klucz (MD5)',
			'comment' => '',
			'help' => 'Wprowadź klucz (MD5)',
			'value' => $Values['firstmd5']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'secondmd5',
			'label' => 'Drugi klucz (MD5)',
			'comment' => '',
			'help' => 'Wprowadź drugi klucz (MD5)',
			'value' => $Values['secondmd5']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'authkey',
			'label' => 'Klucz autoryzacji płatności (pos_auth_key)',
			'comment' => '',
			'help' => 'Wprowadź klucz autoryzacji płatności (pos_auth_key)',
			'value' => $Values['authkey']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_StaticText',
			'text' => '<h3>Adresy do podania w panelu platnosci.pl</h3><br /><br /><strong>Adres raportów:</strong> '.App::getURLAdress().'platnoscireport<br><br>
					   <strong>Adres powrotu pozytywnego:</strong> '.App::getURLAdress().'payment/confirm<br><br>
					   <strong>Adres powrotu negatywnego:</strong> '.App::getURLAdress().'payment/cancel<br><br>',
		);
		
		return $Data;
	}

	public function editPaymentmethodConfiguration ($Data)
	{
		$sql = 'DELETE FROM platnoscisettings WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->executeUpdate();
		
		$sql = 'INSERT INTO platnoscisettings SET 
					idpos=:idpos,
					firstmd5=:firstmd5,
					secondmd5=:secondmd5,
					authkey=:authkey,
					viewid = :viewid
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idpos', $Data['idpos']);
		$stmt->setString('firstmd5', $Data['firstmd5']);
		$stmt->setString('secondmd5', $Data['secondmd5']);
		$stmt->setString('authkey', $Data['authkey']);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			echo $e->getMessage();
			return false;
		}
		return true;
	}
}