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
 * $Id: eraty.php 655 2012-04-24 08:51:44Z gekosale $
 */

class EratyModel extends Model
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	public function updateOrderEratyBackAccept ($idorder, $proposal)
	{
		$sql = "UPDATE `order`
					SET 
						orderstatusid = (SELECT orderstatusid FROM orderstatustranslation WHERE name LIKE 'Żagiel [Zapisany]'),
						eratyproposal = :eratyproposal,
						eratyaccept = 1
					WHERE idorder = :idorder";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		$stmt->setString('eratyproposal', $proposal);
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_CHANGE_ORDER_STATUS_ERATY'));
		}
	}

	public function updateOrderEratyBackCancel ($idorder)
	{
		$sql = "UPDATE `order`
					SET 
						orderstatusid = (SELECT orderstatusid FROM orderstatustranslation WHERE name LIKE 'Żagiel [Anulowany]'),
						eratycancel = 1
					WHERE idorder = :idorder";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		try{
			$stmt->executeUpdate();
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_CHANGE_ORDER_STATUS_ERATY'));
		}
	}

	public function checkOrderidAcceptLink ($idorder)
	{
		$sql = "SELECT O.idorder
					FROM `order` O
					WHERE O.idorder = :idorder
						AND O.eratyproposal IS NULL
						AND O.eratycancel IS NULL
						AND O.eratyaccept IS NULL
						AND O.paymentmethodid = (SELECT idpaymentmethod FROM paymentmethod WHERE controller = 'eraty')";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$orderid = $rs->getInt('idorder');
				return $orderid;
			}
			return 0;
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_CHECK_ERATY_LINK'));
		}
	}

	public function checkOrderidCanceledLink ($idorder)
	{
		$sql = "SELECT O.idorder
					FROM `order` O
					WHERE O.idorder = :idorder
						AND O.eratyproposal IS NULL
						AND O.eratycancel IS NULL
						AND O.eratyaccept IS NULL
						AND O.paymentmethodid = (SELECT idpaymentmethod FROM paymentmethod WHERE controller = 'eraty')";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idorder', $idorder);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$orderid = $rs->getInt('idorder');
				return $orderid;
			}
			return 0;
		}
		catch (FrontendException $fe){
			throw new FrontendException($this->registry->core->getMessage('ERR_CHECK_ERATY_LINK'));
		}
	}

	public function checkEraty ($idpaymentmethod)
	{
		$sql = "SELECT
					ES.wariantsklepu, 
					ES.numersklepu, 
					ES.`char`
				FROM eratysettings ES
				LEFT JOIN paymentmethodview PV ON  ES.paymentmethodid  = PV.paymentmethodid
				WHERE PV.viewid = :viewid
				AND ES.paymentmethodid = :idpaymentmethod";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('idpaymentmethod', $idpaymentmethod);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'wariantsklepu' => $rs->getInt('wariantsklepu'),
				'numersklepu' => $rs->getString('numersklepu'),
				'char' => $rs->getString('char')
			);
			return $Data;
		}
		return 0;
	}

	public function getData ($Data)
	{
		$eraty = $this->checkEraty($Data['orderData']['payment']['idpaymentmethod']);
		return Array(
			'eraty' => $eraty
		);
	}

	public function confirmPayment ($Data, $params)
	{
		$idorder = $Data['orderId'];
		if (! empty($params)){
			$param = preg_split('/&/i', $params);
			if (isset($param[0]) && isset($param[1])){
				$idzamowienie = preg_replace('/[a-zA-Z_=]/i', '', $param[1]);
				$idwniosku = preg_replace('/[a-zA-Z_=]/i', '', $param[2]);
				if (is_numeric($idzamowienie) && ! empty($idwniosku) && ($idzamowienie == $idorder)){
					$idorder = App::getModel('eraty')->checkOrderidAcceptLink($idzamowienie);
					if ($idorder > 0){
						App::getModel('eraty')->updateOrderEratyBackAccept($idzamowienie, $idwniosku);
						$this->registry->template->assign('idorder', $idorder);
						$clientOrder = App::getModel('order')->getOrderInfoForEraty($idorder);
						if (! empty($clientOrder) && $idorder > 0){
							$this->registry->template->assign('clientOrder', $clientOrder);
							
							$mailer = new Mailer($this->registry);
							$mailer->loadContentToBody('eratyAccept');
							$mailer->addAddress($clientOrder['email']);
							$mailer->addBCC($this->registry->session->getActiveShopEmail());
							$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
							$mailer->setSubject($this->registry->core->getMessage('TXT_ZAGIEL_PROPOSAL_ACCEPT'));
							try{
								$mailer->Send();
							}
							catch (phpmailerException $e){
							
							}
						}
					}
					else{
						$this->registry->template->assign('errLink', 1);
					}
				}
				else{
					$this->registry->template->assign('errLink', 1);
				}
			}
			else{
				$this->registry->template->assign('error', 1);
			}
		}
		else{
			$this->registry->template->assign('error', 1);
		}
	}

	public function cancelPayment ($Data, $params)
	{
		
		$idorder = $Data['orderId'];
		$param = $this->registry->core->getParam();
		if (! empty($param)){
			$idzamowienie = preg_replace('/[a-zA-Z_=&]/i', '', $param);
			if (is_numeric($idzamowienie) && ($idzamowienie == $idorder)){
				$order = App::getModel('eraty')->checkOrderidCanceledLink($idzamowienie);
				if (! empty($order) && $order > 0){
					App::getModel('eraty')->updateOrderEratyBackCancel($idzamowienie);
					$this->registry->template->assign('idorder', $order);
					$clientOrder = App::getModel('order')->getOrderInfoForEraty($order);
					if (! empty($clientOrder) && $order > 0){
						$this->registry->template->assign('clientOrder', $clientOrder);
						
						$mailer = new Mailer($this->registry);
						$mailer->loadContentToBody('eratyCancel');
						$mailer->addAddress($clientOrder['email']);
						$mailer->addBCC($this->registry->session->getActiveShopEmail());
						$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
						$mailer->IsHTML(true);
						$mailer->setSubject($this->registry->core->getMessage('TXT_ZAGIEL_PROPOSAL_CANCEL'));
						try{
							$mailer->Send();
						}
						catch (phpmailerException $e){
						
						}
					}
				}
				else{
					$this->registry->template->assign('errLink', 1);
				}
			}
			else{
				$this->registry->template->assign('error', 1);
			}
			//błędnie wprowadzony url
		}
		else{
			$this->registry->template->assign('error', 1);
		}
	}
}