<?php
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009 Gekosale
 *
 * This library is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version. 
 * 
 */

class PlatnosciReportController extends Controller
{

	public function index ()
	{
		$Data = $_POST;
		$this->disableLayout();
		$sql = 'SELECT 
					*
				FROM platnoscisettings
				WHERE viewid = :viewid';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$sig = md5($Data['pos_id'] . $Data['session_id'] . $Data['ts'] . $rs->getString('secondmd5'));
			if ($Data['sig'] != $sig){
				die('ERROR: WRONG SIGNATURE');
			}
			
			$ts = time();
			$sig = md5($rs->getString('idpos') . $Data['session_id'] . $ts . $rs->getString('firstmd5'));
			
			$server = 'https://www.platnosci.pl';
			$server_script = '/paygw/UTF/Payment/get/';
			$parameters = "?pos_id=" . $rs->getString('idpos') . "&session_id=" . $Data['session_id'] . "&ts=" . $ts . "&sig=" . $sig;
			
			$url = $server . $server_script . $parameters;
			
			$url = str_replace("&amp;", "&", urldecode(trim($url)));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_ENCODING, "");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			$content = curl_exec($ch);
			$response = curl_getinfo($ch);
			curl_close($ch);
			
			$str = simplexml_load_string($content);
			
			foreach ($str->trans as $trans){
				if ($trans->status == 99 || ($trans->status > 0 && $trans->status <= 7)){
					$idstatus = (int) $trans->status;
					$idorder = (int) $trans->order_id;
					
					$sql = 'SELECT idstatus FROM `platnoscistatus` WHERE idplatnosci = :idplatnosci';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('idplatnosci', $idstatus);
					$rs = $stmt->executeQuery();
					if ($rs->first()){
						$statusId = $rs->getInt('idstatus');
					}
					
					$statusMap = Array(
						1 => 'Platnosci.pl [nowa]',
						4 => 'Platnosci.pl [rozpoczeta]',
						5 => 'Platnosci.pl [oczekuje na odbior]',
						2 => 'Platnosci.pl [anulowana]',
						3 => 'Platnosci.pl [odrzucona]',
						6 => 'Platnosci.pl [autoryzacja odmowna]',
						7 => 'Platnosci.pl [srodki odrzucone]',
						99 => 'Platnosci.pl [zakonczona]'
					);
					
					$sql = 'INSERT INTO orderhistory(content, orderstatusid, orderid, inform, addid)
							VALUES (:content, :orderstatusid, :orderid, :inform, :addid)';
					$stmt = $this->registry->db->prepareStatement($sql);
					if (isset($statusMap[$idstatus])){
						$stmt->setString('content', $statusMap[$idstatus]);
					}
					else{
						$stmt->setString('content', 'Brak informacji o statusie');
					}
					$stmt->setInt('orderstatusid', $statusId);
					$stmt->setInt('orderid', $idorder);
					$stmt->setInt('inform', 0);
					$stmt->setInt('addid', 1);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
					}
					
					$sql = 'UPDATE `order` SET orderstatusid = :orderstatusid WHERE idorder = :orderid';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('orderstatusid', $statusId);
					$stmt->setInt('orderid', $idorder);
					try{
						$stmt->executeUpdate();
					}
					catch (Exception $e){
						throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
					}
					echo 'OK';
				}
				else{
					echo "ERROR";
				}
			}
		
		}
	}
}
