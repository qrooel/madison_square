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
 * $Id: pickup.php 655 2012-04-24 08:51:44Z gekosale $
 */

class PickupModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function getData ()
	{
		$sql = "SELECT
						V.name AS shopname, 
						S.companyname,
						S.postcode, 
						S.street, 
						S.streetno, 
						S.placeno, 
						S.placename, 
						S.province, 
						S.nip,
						S.bankname, 
						S.banknr
					FROM store S
					LEFT JOIN view V ON V.storeid = S.idstore
					WHERE V.idview = :id";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			if ($rs->first()){
				$Data = Array(
					'shopname' => $rs->getString('shopname'),
					'companyname' => $rs->getString('companyname'),
					'postcode' => $rs->getString('postcode'),
					'placename' => $rs->getString('placename'),
					'street' => $rs->getString('street'),
					'streetno' => $rs->getString('streetno'),
					'placeno' => $rs->getString('placeno'),
					'bankname' => $rs->getString('bankname'),
					'banknr' => $rs->getString('banknr')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while doing sql query- getInfoToBanktransfer- banktransferModel.');
		}
		return $Data;
	}
}