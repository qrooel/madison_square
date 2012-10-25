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
 * $Id: confirmation.php 655 2012-04-24 08:51:44Z gekosale $
 */

class confirmationModel extends Model
{

	public function getURLParamToValidOrderLink ($orderLink)
	{
		$sql = "SELECT 
					idorder, 
					orderstatusid, 
					paymentmethodid, 
					activelink
				FROM `order` 
				WHERE activelink= :orderlink";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('orderlink', $orderLink);
		$rs = $stmt->executeQuery();
		$Data = Array();
		try{
			if ($rs->first()){
				$Data = Array(
					'idorder' => $rs->getInt('idorder'),
					'paymentmethodid' => $rs->getInt('paymentmethodid'),
					'orderstatusid' => $rs->getInt('orderstatusid'),
					'activelink' => $rs->getString('activelink')
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_CLIENT_NO_EXIST'));
		}
		return $Data;
	}

	public function changeOrderStatus ($idorder)
	{
		$upateOrder = 0;
		$sql = "UPDATE `order` SET 
						activelink = 1, 
						orderstatusid = :orderstatusid
					WHERE idorder = :idorder";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setInt('orderstatusid', $this->layer['confirmorderstatusid']);
		try{
			$rs = $stmt->executeQuery();
			$upateOrder = 1;
		}
		catch (Exception $e){
			throw new FrontendException('Error while executing query (update order)- confirmation model');
		}
		
		$sql = 'INSERT INTO orderhistory(content, orderstatusid, orderid, inform, addid)
							VALUES (:content, :orderstatusid, :orderid, :inform, :addid)';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('content', $this->registry->core->getMessage('TXT_CONFIRMED_ORDER'));
		$stmt->setInt('orderstatusid', $this->layer['confirmorderstatusid']);
		$stmt->setInt('orderid', $idorder);
		$stmt->setInt('inform', 0);
		$stmt->setInt('addid', 1);
		try{
			$stmt->executeUpdate();
		}
		catch (Exception $e){
			throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
		}
		return $upateOrder;
	}

}