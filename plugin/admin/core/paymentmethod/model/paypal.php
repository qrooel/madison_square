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
 * $Id: paypal.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class PaypalModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getConfigurationFields ()
	{
		$sql = 'SELECT 
					business, 
					apiusername, 
					apipassword, 
					apisignature, 
					sandbox,
					positiveorderstatusid,
					negativeorderstatusid
				FROM paypalsettings';
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		$Values = array(
			'business' => null,
			'apiusername' => null,
			'apipassword' => null,
			'apisignature' => null,
			'sandbox' => null,
			'positiveorderstatusid' => null,
			'negativeorderstatusid' => null
		);
		while ($rs->next()){
			$Values = array(
				'business' => $rs->getString('business'),
				'apiusername' => $rs->getString('apiusername'),
				'apipassword' => $rs->getString('apipassword'),
				'apisignature' => $rs->getString('apisignature'),
				'sandbox' => $rs->getInt('sandbox'),
				'positiveorderstatusid' => $rs->getInt('positiveorderstatusid'),
				'negativeorderstatusid' => $rs->getInt('negativeorderstatusid')
			);
		}
		
		$Data = array();
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'business',
			'label' => 'Adres email',
			'comment' => '',
			'help' => 'Adres email',
			'value' => $Values['business']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'apiusername',
			'label' => 'Nazwa użytkownika API',
			'comment' => '',
			'help' => 'Wprowadź nazwę użytkownika API',
			'value' => $Values['apiusername']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'apipassword',
			'label' => 'Hasło użytkownika API',
			'comment' => '',
			'help' => 'Wprowadź hasło użytkownika API',
			'value' => $Values['apipassword']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_TextField',
			'name' => 'apisignature',
			'label' => 'Sygnatura API',
			'comment' => '',
			'help' => 'Wprowadź sygnaturę API',
			'value' => $Values['apisignature']
		);
		
		$Data[] = Array(
			'fe_element' => 'FE_Select',
			'name' => 'sandbox',
			'label' => 'Sandbox',
			'comment' => '',
			'help' => 'Sandbox',
			'options' => Array(
				'0' => 'Nie (korzystaj z wersji Live)',
				'1' => 'Tak (korzystaj z Sandbox)'
			),
			'value' => $Values['sandbox']
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

	public function editPaymentmethodConfiguration ($Data)
	{
		$sql = 'DELETE FROM paypalsettings WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->executeUpdate();
		
		$sql = 'INSERT INTO paypalsettings SET 
					sandbox = :sandbox,
					apisignature = :apisignature,
					apipassword = :apipassword,
					apiusername = :apiusername,
					business = :business,
					viewid = :viewid,
					positiveorderstatusid = :positiveorderstatusid,
					negativeorderstatusid = :negativeorderstatusid
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('sandbox', $Data['sandbox']);
		$stmt->setString('apisignature', $Data['apisignature']);
		$stmt->setString('apipassword', $Data['apipassword']);
		$stmt->setString('apiusername', $Data['apiusername']);
		$stmt->setString('business', $Data['business']);
		$stmt->setInt('positiveorderstatusid', $Data['positiveorderstatusid']);
		$stmt->setInt('negativeorderstatusid', $Data['negativeorderstatusid']);
		$stmt->setInt('viewid', Helper::getViewId());
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