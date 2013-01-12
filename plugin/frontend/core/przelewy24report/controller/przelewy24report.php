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

class Przelewy24ReportController extends Controller
{

	public function index ()
	{
		$this->disableLayout();
		
		if (isset($_POST['p24_session_id']) && $_POST['p24_session_id'] != ''){
			
			$sessionid = base64_decode($_POST['p24_session_id']);
			$sql = 'SELECT 
						*
					FROM `order`
					WHERE sessionid = :crc';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('crc', base64_decode($_POST['p24_session_id']));
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$sql2 = 'SELECT 
							idsprzedawcy,
							crc,
							positiveorderstatusid,
							negativeorderstatusid
						FROM przelewy24settings WHERE viewid = :viewid';
				$stmt2 = $this->registry->db->prepareStatement($sql2);
				$stmt2->setInt('viewid', $rs->getInt('viewid'));
				$rs2 = $stmt2->executeQuery();
				if ($rs2->first()){
					
					$p24_session_id = $_POST["p24_session_id"];
					$p24_order_id = $_POST["p24_order_id"];
					$p24_kwota = number_format($rs->getString('globalprice') * 100, 0,'','');
					
					$P = array();
					$RET = array();
					$url = "https://secure.przelewy24.pl/transakcja.php";
					$P[] = "p24_id_sprzedawcy=" . $rs2->getInt('idsprzedawcy');
					$P[] = "p24_session_id=" . $p24_session_id;
					$P[] = "p24_order_id=" . $p24_order_id;
					$P[] = "p24_kwota=" . $p24_kwota;
					$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, join("&", $P));
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					$result = curl_exec($ch);
					curl_close($ch);
					$T = explode(chr(13) . chr(10), $result);
					$res = false;
					foreach ($T as $line){
						$line = ereg_replace("[\n\r]", "", $line);
						if ($line != "RESULT" and ! $res)
							continue;
						if ($res)
							$RET[] = $line;
						else
							$res = true;
					}
					if ($RET[0] == 'TRUE'){
						$sql = 'INSERT INTO orderhistory(content, orderstatusid, orderid, inform, addid)
							VALUES (:content, :orderstatusid, :orderid, :inform, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('content', 'Transakcja zakoñczona sukcesem.');
						$stmt->setInt('orderstatusid', $rs2->getInt('positiveorderstatusid'));
						$stmt->setInt('orderid', $rs->getInt('idorder'));
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
						$stmt->setInt('orderstatusid', $rs2->getInt('positiveorderstatusid'));
						$stmt->setInt('orderid', $rs->getInt('idorder'));
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
						}
						if ($this->registry->session->getActivePaymentData() != NULL){
							$url = App::getRegistry()->core->getControllerNameForSeo('payment') . '/confirm';
							App::redirect($url);
						}
					}
					else{
						$sql = 'INSERT INTO orderhistory(content, orderstatusid, orderid, inform, addid)
								VALUES (:content, :orderstatusid, :orderid, :inform, :addid)';
						$stmt = $this->registry->db->prepareStatement($sql);
						$stmt->setString('content', 'Transakcja zakoñczona b³êdem:' . $RET[1]);
						$stmt->setInt('orderstatusid', $rs2->getInt('negativeorderstatusid'));
						$stmt->setInt('orderid', $rs->getInt('idorder'));
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
						$stmt->setInt('orderstatusid', $rs2->getInt('negativeorderstatusid'));
						$stmt->setInt('orderid', $rs->getInt('idorder'));
						try{
							$stmt->executeUpdate();
						}
						catch (Exception $e){
							throw new Exception($this->registry->core->getMessage('ERR_ORDER_HISTORY_ADD'));
						}
						if ($this->registry->session->getActivePaymentData() != NULL){
							$url = App::getRegistry()->core->getControllerNameForSeo('payment') . '/cancel';
							App::redirect($url);
						}
					}
				}
			
			}
		}
	
	}
}
