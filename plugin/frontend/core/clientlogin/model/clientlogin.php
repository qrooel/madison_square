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
 * $Id: clientlogin.php 655 2012-04-24 08:51:44Z gekosale $
 */

class clientloginModel extends Model
{

	public function authProccess ($login, $password)
	{
		$login = App::getModel('formprotection')->cropDangerousCode($login);
		$password = App::getModel('formprotection')->cropDangerousCode($password);
		$sql = 'SELECT idclient,disable FROM client WHERE login = :login AND password = :password AND viewid=:viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('login', $login);
		$stmt->setString('password', $password);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if ($rs->getInt('disable') == 0){
				return $rs->getInt('idclient');
			}
			else{
				return - 1;
			}
		}
		else{
			return 0;
		}
	}

	public function facebookAuthProccess ($facebookId)
	{
		$sql = 'SELECT idclient,disable FROM client WHERE facebookid = :facebookid AND viewid=:viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('facebookid', $facebookId);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			if ($rs->getInt('disable') == 0){
				return $rs->getInt('idclient');
			}
			else{
				return - 1;
			}
		}
		else{
			return 0;
		}
	}

	public function setTimeInterval ()
	{
		$sql = "SELECT V.periodid, P.timeinterval FROM view V
					LEFT JOIN period P ON P.idperiod = V.periodid 
					WHERE V.idview=:viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Array = Array();
		if ($rs->first()){
			$Array = Array(
				'timeinterval' => $rs->getString('timeinterval')
			);
		}
		$date = new DateTime();
		$date->setDate(date("Y"), date("m"), date("d"));
		$date->modify($Array['timeinterval']);
		return $date->format("Y-m-d");
	}

	public function checkClientGroup ()
	{
		$sql = "SELECT CD.clientgroupid
					FROM clientdata CD
					LEFT JOIN assigntogroup AG ON AG.clientgroupid = CD.clientgroupid
					LEFT JOIN client C ON C.idclient = CD.clientid
					WHERE clientid=:clientid AND C.viewid=:viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$rs = $stmt->executeQuery();
		$group = 0;
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'clientgroupid' => $rs->getInt('clientgroupid')
			);
		}
		if (isset($Data['clientgroupid']) && ($Data['clientgroupid'] > 0)){
			$group = $this->checkActiveClientGroup();
		}
		if ($group !== 0){
			return $group;
		}
		else{
			return $Data['clientgroupid'];
		}
	}

	public function checkActiveClientGroup ()
	{
		$period = $this->setTimeInterval();
		$sql = 'SELECT O.clientid, O.idorder, SUM(price) as price, O.orderstatusid, O.viewid, O.`adddate`
					FROM `order` O
						LEFT JOIN orderstatusorderstatusgroups OSOSG ON OSOSG.orderstatusid = O.orderstatusid
					WHERE orderstatusgroupsid=4 
						AND O.viewid=:viewid 
						AND O.clientid=:clientid 
						AND O.adddate > :period
					GROUP BY O.clientid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		
		$stmt->setString('period', $period);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$price = 0;
		$group = 0;
		$Datagroup = 0;
		if ($rs->first()){
			$price = $rs->getFloat('price');
			if ($price == NULL){
				$price = 0.00;
			}
			
			$sql2 = 'SELECT ATG.`from`, ATG.`to`, ATG.clientgroupid, CASE
						  	WHEN (`from`<>0 AND `from` <= :price AND `to`=0) THEN clientgroupid
						  	WHEN (:price BETWEEN `from` AND `to`) THEN clientgroupid
						  	WHEN (`to` = 0 AND `from` <= :price) THEN clientgroupid
						  	WHEN (`from`=0 AND `to`=0) THEN clientgroupid
						END as groupclient
						FROM assigntogroup ATG
						WHERE viewid=:viewid';
			$stmt = $this->registry->db->prepareStatement($sql2);
			$stmt->setFloat('price', $price);
			$stmt->setInt('viewid', Helper::getViewId());
			
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Datagroup = $rs->getInt('groupclient');
				if ($Datagroup > 0){
					$group = $Datagroup;
				}
			}
			if ($group > 0){
				$this->autoAssigntoGroup($group);
			}
		}
		return $group;
	}

	public function autoAssigntoGroup ($Datagroup)
	{
		$sql = "UPDATE clientdata SET clientgroupid = :clientgroupid
					WHERE clientid=:clientid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('clientid', $this->registry->session->getActiveClientid());
		$stmt->setInt('clientgroupid', $Datagroup);
		try{
			$stmt->executeQuery();
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_AUTO_ASSIGN_TO_GROUP'));
		}
	}
}