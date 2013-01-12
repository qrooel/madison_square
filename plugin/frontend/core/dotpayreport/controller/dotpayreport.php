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

class DotpayReportController extends Controller{

	public function index()
	{
		$this->disableLayout();
		
		if(in_array($_SERVER['REMOTE_ADDR'],Array('195.150.9.37','217.17.41.5')) && !empty($_POST)){
			$config = App::getModel('payment/dotpay')->getConfig();
			$id_sprzedawcy = $_POST['id'];
			
			$m5 = $config['pin'] . ':' . $config['idsprzedawcy'] . ':' . $_POST['control'] . ':' . $_POST['t_id'] .
    		':' . $_POST['amount'] . ':' . $_POST['email'] . ':' . $_POST['service'] . ':' . $_POST['code'] . ':' . $_POST['username'] .
			':' . $_POST['password'] . ':' . $_POST['t_status'];
			$status_transakcji = $_POST['t_status'];
			$id_transakcji = $_POST['t_id'];
			$kwota_transakcji = $_POST['amount'];
			$email_klienta = $_POST['email'];
			$suma_kontrolna = $_POST['md5'];

			if($_POST['t_status'] == 2){
				$sql = 'SELECT 
							globalprice,
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :crc';		
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('crc', base64_decode($_POST['control']));
				$rs = $stmt->executeQuery();
				if($rs->first()){
					$sql2 = "UPDATE `order` SET 
								orderstatusid = (SELECT positiveorderstatusid FROM dotpaysettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt2 = $this->registry->db->prepareStatement($sql2);
					$stmt2->setInt('idorder', $rs->getInt('idorder'));
					$stmt2->setInt('viewid', $rs->getInt('viewid'));
					$stmt2->executeQuery();
				}
			}
			elseif($_POST['t_status'] == 3)
			{
				$sql = 'SELECT 
							globalprice,
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :crc';		
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('crc', base64_decode($_POST['control']));
				$rs = $stmt->executeQuery();
				if($rs->first()){
					$sql2 = "UPDATE `order` SET 
								orderstatusid = (SELECT negativeorderstatusid FROM dotpaysettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt2 = $this->registry->db->prepareStatement($sql2);
					$stmt2->setInt('idorder', $rs->getInt('idorder'));
					$stmt2->setInt('viewid', $rs->getInt('viewid'));
					$stmt2->executeQuery();
				}
			}
			print "OK";
			exit;
		}

	}
}