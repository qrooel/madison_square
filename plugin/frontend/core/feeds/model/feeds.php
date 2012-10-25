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
 * $Id: feeds.php 655 2012-04-24 08:51:44Z gekosale $
 */

class feedsModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getClientsList ()
	{
		$sql = 'SELECT 
					CD.clientid as id, 
					CD.phone, 
					CD.description, 
					CD.email, 
					CD.firstname, 
					CD.surname,	
					CA.nip, 
					CA.street, 
					CA.streetno, 
					CA.placeno, 
					CA.companyname, 
					CG.name AS clientgroup, 
					V.value AS vat
				FROM clientdata CD
				LEFT JOIN client C ON CD.clientid= C.idclient
				LEFT JOIN clientgrouptranslation CG ON CG.clientgroupid = CD.clientgroupid AND CG.languageid= :languageid
				LEFT JOIN clientaddress CA ON CA.clientid = C.idclient
				LEFT JOIN vat V ON V.idvat = CA.vatid
		';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'email' => $rs->getString('email'),
				'phone' => $rs->getString('phone'),
				'description' => $rs->getString('description'),
				'nip' => $rs->getInt('nip'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getInt('streetno'),
				'placeno' => $rs->getInt('placeno'),
				'vat' => $rs->getString('vat'),
				'clientgroup' => $rs->getString('clientgroup'),
				'companyname' => $rs->getString('companyname'),
			);
			return $Data;
		}
		throw new CoreException($this->registry->core->getMessage('ERR_CLIENT_NO_EXIST'));
	}

	public function getOrderList ()
	{
		$sql = "SELECT O.idorder as id, O.price, O.adddate as date, O.globalprice, O.dispatchmethodprice,
						O.dispatchmethodname, O.paymentmethodname,
						OST.name as orderstatusname,
						OCDelivery.firstname, OCDelivery.surname, OCDelivery.street, OCDelivery.streetno,
						OCDelivery.companyname, OCDelivery.NIP, OCDelivery.placeno, OCDelivery.postcode,
						OCDelivery.place, OCDelivery.phone, OCDelivery.email
					FROM `order` O
					LEFT JOIN orderstatus OS ON OS.idorderstatus=O.orderstatusid
          			LEFT JOIN orderstatustranslation OST ON OST.orderstatusid = OS.idorderstatus
              			AND OST.languageid= :languageid
					LEFT JOIN orderclientdeliverydata OCDelivery ON OCDelivery.orderid= O.idorder";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('languageid', Helper::getLanguageId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'price' => $rs->getFloat('price'),
				'orderdate' => $rs->getString('date'),
				'globalprice' => $rs->getFloat('globalprice'),
				'dispatchmethodprice' => $rs->getFloat('dispatchmethodprice'),
				'dispatchmethodname' => $rs->getString('dispatchmethodname'),
				'paymentmethodname' => $rs->getString('paymentmethodname'),
				'orderstatusname' => $rs->getString('orderstatusname'),
				'firstname' => $rs->getString('firstname'),
				'surname' => $rs->getString('surname'),
				'street' => $rs->getString('street'),
				'streetno' => $rs->getString('streetno'),
				'companyname' => $rs->getString('companyname'),
				'NIP' => $rs->getString('nip'),
				'placeno' => $rs->getString('placeno'),
				'postcode' => $rs->getString('postcode'),
				'place' => $rs->getString('place'),
				'phone' => $rs->getString('phone'),
				'email' => $rs->getString('email')
			);
		}
		return $Data;
	}

	public function getProductForOrder ($id)
	{
		$sql = "SELECT O.idorder as id, OP.name as productname, OP.price, OP.qty, OPA.name as attributename
					FROM `order` O
						LEFT JOIN orderproduct OP ON OP.orderid= O.idorder
						LEFT JOIN orderproductattribute OPA ON OPA.orderproductid= OP.idorderproduct
					WHERE O.idorder= :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'productname' => $rs->getString('productname'),
				'attributename' => $rs->getString('attributename'),
				'price' => $rs->getFloat('price'),
				'qty' => $rs->getInt('qty')
			);
		}
		return $Data;
	}
}
?>