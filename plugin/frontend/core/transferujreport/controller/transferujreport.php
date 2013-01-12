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

class TransferujReportController extends Controller
{

	public function index ()
	{
		$this->disableLayout();
		if ($_SERVER['REMOTE_ADDR'] == '195.149.229.109' && ! empty($_POST)){
			$id_sprzedawcy = $_POST['id'];
			$status_transakcji = $_POST['tr_status'];
			$id_transakcji = $_POST['tr_id'];
			$kwota_transakcji = $_POST['tr_amount'];
			$kwota_zaplacona = $_POST['tr_paid'];
			$blad = $_POST['tr_error'];
			$data_transakcji = $_POST['tr_date'];
			$opis_transackji = $_POST['tr_desc'];
			$ciag_pomocniczy = $_POST['tr_crc'];
			$email_klienta = $_POST['tr_email'];
			$suma_kontrolna = $_POST['md5sum'];
			// sprawdzenie stanu transakcji
			if ($status_transakcji == 'TRUE' && $blad == 'none'){
				$sql = 'SELECT 
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :crc';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('crc', base64_decode($ciag_pomocniczy));
				try{
					$rs = $stmt->executeQuery();
				}
				catch (Exception $e){
				}
				if ($rs->first()){
					$sql = "UPDATE `order` SET 
								orderstatusid = (SELECT positiveorderstatusid FROM transferujsettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid'));
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
					}
					
					$sql = 'INSERT INTO orderhistory SET
								content = :content, 
								orderstatusid = (SELECT positiveorderstatusid FROM transferujsettings WHERE viewid = :viewid),
								orderid = :idorder, 
								inform = 0, 
								addid = 1';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('content', 'Płatność zakończona sukcesem');
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid'));
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
					}
				
				}
			}
			else{
				$sql = 'SELECT 
							viewid,
							idorder
						FROM `order`
						WHERE sessionid = :crc';
				$stmt = $this->registry->db->prepareStatement($sql);
				$stmt->setString('crc', base64_decode($ciag_pomocniczy));
				$rs = $stmt->executeQuery();
				if ($rs->first()){
					$sql = "UPDATE `order` SET 
								orderstatusid = (SELECT negativeorderstatusid FROM transferujsettings WHERE viewid = :viewid)
							WHERE idorder = :idorder";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid'));
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
					}
					
					$sql = 'INSERT INTO orderhistory SET
								content = :content, 
								orderstatusid = (SELECT negativeorderstatusid FROM transferujsettings WHERE viewid = :viewid),
								orderid = :idorder, 
								inform = 0, 
								addid = 1';
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('content', 'Płatność zakończona niepowodzeniem');
					$stmt->setInt('idorder', $rs->getInt('idorder'));
					$stmt->setInt('viewid', $rs->getInt('viewid'));
					try{
						$stmt->executeQuery();
					}
					catch (Exception $e){
					}
				
				}
			}
			echo 'TRUE';
		}
	
	}
}
