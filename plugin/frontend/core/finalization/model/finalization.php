<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie
 * informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 *
 * $Revision: 687 $
 * $Author: gekosale $
 * $Date: 2012-09-01 14:02:47 +0200 (So, 01 wrz 2012) $
 * $Id: finalization.php 687 2012-09-01 12:02:47Z gekosale $
 */

class finalizationModel extends Model {

	public function saveOrder ($Data) {
		try{
			$order = $this->setClientOrder($Data);
			if ($order != NULL){
				if (count($order['cart']) == 0){
					App::redirect($this->registry->core->getControllerNameForSeo('cart'));
				}
				$saveOrder = App::getModel('order')->saveOrder($order);
				$clientid = $this->registry->session->getActiveClientid();
				$this->registry->session->setActiveorderid($saveOrder);
				$email = ($order['deliveryAddress']['email'] != '') ? $order['deliveryAddress']['email'] : $order['clientaddress']['email'];
				$orderid = $this->registry->session->getActiveorderid();
				$orderlink = App::getModel('order')->generateOrderLink($orderid);
				if ($orderlink != NULL && $orderid != NULL){
					App::getModel('order')->changeOrderLink($orderid, $orderlink);
					
					$files = $this->registry->session->getActiveOrderUploadedFiles();
					if (count($files) > 0){
						$this->addOrderFiles($files, $orderid);
						$this->registry->template->assign('orderfiles', $files);
					}
					
					$this->registry->template->assign('order', $order);
					$this->registry->template->assign('orderId', $orderid);
					$this->registry->template->assign('orderlink', $orderlink);
					$mailer = new Mailer($this->registry);
					$mailer->loadContentToBody('orderClient');
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->FromName = $this->registry->session->getActiveShopName();
					$mailer->setSubject($this->registry->core->getMessage('TXT_ORDER_CLIENT') . ': ' . $orderid);
					$mailer->addAddress($email);
					try{
						$mailer->Send();
						$mailer->ClearAddresses();
						unset($mailer);
					}
					catch (phpmailerException $e){
						throw $e;
					}
					
					$mailer = new Mailer($this->registry);
					$mailer->loadContentToBody('orderUser');
					$mailer->AddEmbeddedImage('./design/_images_frontend/core/logos/' . $this->layer['photoid'], 'logo', $this->layer['photoid']);
					$mailer->FromName = $this->registry->session->getActiveShopName();
					$mailer->AddReplyTo($email);
					$mailer->setSubject($this->registry->core->getMessage('TXT_ORDER_CLIENT') . ': ' . $orderid);
					$mailer->addAddress($this->registry->session->getActiveShopEmail());
					try{
						$mailer->Send();
						$mailer->ClearAddresses();
					}
					catch (phpmailerException $e){
						throw $e;
					}
					$this->registry->session->unsetActiveCart();
					$this->registry->session->unsetActiveglobalPriceWithDispatchmethod();
					$this->registry->session->unsetActiveglobalPriceWithDispatchmethodNetto();
					$this->registry->session->unsetActiveDispatchmethodChecked();
					$this->registry->session->unsetActivePaymentMethodChecked();
					$this->registry->session->unsetActiveGlobalPrice();
					
					$paymentMethodData = Array(
						'orderId' => $this->registry->session->getActiveorderid(),
						'orderData' => $this->registry->session->getActiveClientOrder()
					);
					
					$this->registry->session->setActivePaymentData($paymentMethodData);
					$this->registry->session->unsetActiveorderid();
					$this->registry->session->unsetActiveClientOrder();
					
					App::getModel('dataset')->flushCache();
					App::redirect($this->registry->core->getControllerNameForSeo('payment') . '/accept');
				}
			}
		}
		catch (FrontendException $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_ORDER_SAVE'), $e->getMessage());
		}
	}

	protected function addOrderFiles ($Data, $orderId) {
		foreach ($Data as $key => $filename){
			$sql = 'INSERT INTO orderfiles SET
					path = :path,
					orderid = :orderid
			';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('path', App::getURLAdress() . 'upload/order/' . $filename);
			$stmt->setInt('orderid', $orderId);
			try{
				$stmt->executeQuery();
			}
			catch (Exception $e){
				throw new Exception($e->getMessage());
			}
		}
		$this->registry->session->setActiveOrderUploadedFiles(Array());
	
	}

	public function setClientOrder ($Data = Array()) {
		$clientOrder = Array();
		$clientModel = App::getModel('client');
		$clientdata = $clientModel->getClient();
		if (empty($clientdata) && isset($Data['clientaddress'])){
			$clientdata = $Data['clientaddress'];
		}
		$globalPrice = 0;
		
		$event = new sfEvent($this, 'frontend.finalization.setActiveClientOrder', Array(
			'dispatchmethod' => $this->registry->session->getActiveDispatchmethodChecked(),
			'cart' => $this->registry->session->getActiveCart()
		));
		$this->registry->dispatcher->notify($event);
		
		$clientOrder = Array(
			'cart' => $this->registry->session->getActiveCart(),
			'globalPrice' => App::getModel('cart')->getGlobalPrice(),
			'globalPriceWithoutVat' => App::getModel('cart')->getGlobalPriceWithoutVat(),
			'priceWithDispatchMethod' => $this->registry->session->getActiveglobalPriceWithDispatchmethod(),
			'priceWithDispatchMethodNetto' => $this->registry->session->getActiveglobalPriceWithDispatchmethodNetto(),
			'count' => App::getModel('cart/cart')->getProductAllCount(),
			'clientdata' => $clientdata,
			'clientaddress' => (isset($Data['clientaddress'])) ? $Data['clientaddress'] : Array(),
			'deliveryAddress' => (isset($Data['deliveryAddress'])) ? $Data['deliveryAddress'] : Array(),
			'dispatchmethod' => $this->registry->session->getActiveDispatchmethodChecked(),
			'payment' => $this->registry->session->getActivePaymentMethodChecked(),
			'clientid' => $this->registry->session->getActiveClientid(),
			'customeropinion' => (isset($Data['comments'])) ? $Data['comments'] : Array()
		);
		
		$eratyCheck = App::getModel('payment')->checkEraty($clientOrder['payment']['idpaymentmethod']);
		if ($eratyCheck == 0){
			$rulesDiscount = $this->getRulesCart($clientOrder);
			if (is_array($rulesDiscount) && count($rulesDiscount) > 0 && $clientOrder['dispatchmethod'] > 0 && $clientOrder['payment'] > 0){
				if ($rulesDiscount['symbol'] == '+'){
					$globalPricePromo = sprintf('%01.2f', $clientOrder['globalPrice'] + $rulesDiscount['discount']);
					$globalPriceWithoutVatPromo = sprintf('%01.2f', $clientOrder['globalPriceWithoutVat'] + $rulesDiscount['discount']);
					$priceWithDispatchMethodPromo = sprintf('%01.2f', $clientOrder['priceWithDispatchMethod'] + $rulesDiscount['discount']);
					$priceWithDispatchMethodNettoPromo = sprintf('%01.2f', $clientOrder['priceWithDispatchMethodNetto'] + $rulesDiscount['discount']);
					$message = $rulesDiscount['symbol'] . $this->registry->core->processPrice($rulesDiscount['discount']);
				}
				elseif ($rulesDiscount['symbol'] == '-'){
					$globalPricePromo = sprintf('%01.2f', $clientOrder['globalPrice'] - $rulesDiscount['discount']);
					$globalPriceWithoutVatPromo = sprintf('%01.2f', $clientOrder['globalPriceWithoutVat'] - $rulesDiscount['discount']);
					$priceWithDispatchMethodPromo = sprintf('%01.2f', $clientOrder['priceWithDispatchMethod'] - $rulesDiscount['discount']);
					$priceWithDispatchMethodNettoPromo = sprintf('%01.2f', $clientOrder['priceWithDispatchMethodNetto'] - $rulesDiscount['discount']);
					$message = $rulesDiscount['symbol'] . $this->registry->core->processPrice($rulesDiscount['discount']);
				}
				elseif ($rulesDiscount['symbol'] == '%'){
					$globalPricePromo = sprintf('%01.2f', abs($clientOrder['globalPrice'] * ($rulesDiscount['discount']) / 100));
					$globalPriceWithoutVatPromo = sprintf('%01.2f', abs($clientOrder['globalPriceWithoutVat'] * ($rulesDiscount['discount']) / 100));
					$priceWithDispatchMethodPromo = sprintf('%01.2f', abs($clientOrder['priceWithDispatchMethod'] * ($rulesDiscount['discount']) / 100));
					$priceWithDispatchMethodNettoPromo = sprintf('%01.2f', abs($clientOrder['priceWithDispatchMethodNetto'] * ($rulesDiscount['discount']) / 100));
					$message = ((($rulesDiscount['discount'] - 100) > 0) ? '+' : '-') . abs($rulesDiscount['discount'] - 100) . $rulesDiscount['symbol'];
				}
				if ($globalPricePromo > 0 && $globalPriceWithoutVatPromo > 0 && $priceWithDispatchMethodPromo > 0 && $priceWithDispatchMethodNettoPromo > 0){
					$clientOrder['globalPricePromo'] = $globalPricePromo;
					$clientOrder['globalPriceWithoutVatPromo'] = $globalPriceWithoutVatPromo;
					$clientOrder['priceWithDispatchMethodPromo'] = $priceWithDispatchMethodPromo;
					$clientOrder['priceWithDispatchMethodNettoPromo'] = $priceWithDispatchMethodNettoPromo;
					$clientOrder['rulescart'] = $this->getRulesCartName($rulesDiscount['rulescartid']);
					$clientOrder['rulescartid'] = $rulesDiscount['rulescartid'];
					$clientOrder['rulescartmessage'] = $message;
				}
			}
		}
		$this->registry->session->setActiveClientOrder($clientOrder);
		return $this->getClientOrder();
	}

	public function getClientOrder () {
		$Data = Array();
		$Data = $this->registry->session->getActiveClientOrder();
		return $Data;
	}

	public function getOrderSummary () {
		$order = $this->getClientOrder();
		$Summary = Array();
		$Summary[] = Array(
			'label' => $this->registry->core->getMessage('TXT_SUM_PRICE'),
			'value' => $this->registry->core->processPrice(App::getModel('cart')->getGlobalPrice())
		);
		$Summary[] = Array(
			'label' => $this->registry->core->getMessage('TXT_COST_OF_DELIVERY'),
			'value' => $this->registry->core->processPrice($order['dispatchmethod']['dispatchmethodcost'])
		);
		$coupon = $this->registry->session->getActiveCoupon();
		if (isset($order['rulescart']) && $order['rulescart'] != NULL){
			$Summary[] = Array(
				'label' => $order['rulescart'],
				'value' => $order['rulescartmessage']
			);
			if ($coupon != NULL && ! empty($coupon)){
				$Summary[] = Array(
					'label' => $coupon['code'],
					'value' => '<strong>-' . $this->registry->core->processPrice($this->registry->session->getActiveCouponValue()) . '</strong>'
				);
			}
			$Summary[] = Array(
				'label' => $this->registry->core->getMessage('TXT_ALL_ORDERS_PRICE_NETTO'),
				'value' => $this->registry->core->processPrice($order['priceWithDispatchMethodNettoPromo'])
			);
			$Summary[] = Array(
				'label' => $this->registry->core->getMessage('TXT_ALL_ORDERS_PRICE_GROSS'),
				'value' => $this->registry->core->processPrice($order['priceWithDispatchMethodPromo'])
			);
		}
		else{
			$Summary[] = Array(
				'label' => $this->registry->core->getMessage('TXT_ALL_ORDERS_PRICE_NETTO'),
				'value' => $this->registry->core->processPrice($order['priceWithDispatchMethodNetto'])
			);
			if ($coupon != NULL && ! empty($coupon)){
				$Summary[] = Array(
					'label' => $coupon['code'],
					'value' => '<strong>-' . $this->registry->core->processPrice($this->registry->session->getActiveCouponValue()) . '</strong>'
				);
			}
			$Summary[] = Array(
				'label' => $this->registry->core->getMessage('TXT_ALL_ORDERS_PRICE_GROSS'),
				'value' => $this->registry->core->processPrice($order['priceWithDispatchMethod'])
			);
		}
		return $Summary;
	}

	/**
	 * Chcek cart rules.
	 * If there is any rule, reduce global price. Otherwise return 0.
	 *
	 * @param
	 *       	 array client order
	 * @return array discount (rulescartid, suffixtypeid, symbol, discount) or 0
	 * @access public
	 */
	public function getRulesCart ($clientOrder) {
		$clientGroupId = $this->registry->session->getActiveClientGroupid();
		$Data = Array();
		$discount = Array();
		$check = true;
		$allRules = $this->getAllCartRules();
		if (is_array($allRules) && ! empty($allRules)){
			foreach ($allRules as $rule){
				$ruleid = $rule['idrulescart'];
				if ($clientGroupId > 0){
					$sql = "SELECT 
								RCCG.rulescartid, 
								RCR.ruleid, 
								RCR.pkid, 
								RCR.pricefrom, 
								RCR.priceto,
								RCCG.suffixtypeid, 
								RCCG.discount, 
								S.symbol,
								RCCG.clientgroupid
							FROM rulescartclientgroup RCCG
							LEFT JOIN rulescart RC ON RCCG.rulescartid = RC.idrulescart
							LEFT JOIN rulescartrule RCR ON RCR.rulescartid = RC.idrulescart
							LEFT JOIN rulescartview RCV ON RCV.rulescartid = RC.idrulescart
							LEFT JOIN suffixtype S ON RCCG.suffixtypeid = S.idsuffixtype
							WHERE
						    	RCR.rulescartid = :ruleid
						          AND RCV.viewid= :viewid
									AND RCCG.clientgroupid= :clientgroupid
									AND IF(RC.datefrom is not null, (cast(RC.datefrom as date) <= curdate()), 1)
									AND IF(RC.dateto is not null, (cast(RC.dateto as date)>= curdate()),1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setString('clientgroupid', $clientGroupId);
					$stmt->setInt('viewid', Helper::getViewId());
					$stmt->setInt('ruleid', $ruleid);
				}
				else{
					$sql = "SELECT RCR.rulescartid, RCR.ruleid, RCR.pkid, RCR.pricefrom, RCR.priceto,
									RC.suffixtypeid, RC.discount, S.symbol,
									'clientgroupid'=NULL as clientgroupid
								FROM  rulescart RC
									LEFT JOIN rulescartrule RCR ON RCR.rulescartid = RC.idrulescart
									LEFT JOIN rulescartview RCV ON RCV.rulescartid = RC.idrulescart
									LEFT JOIN suffixtype S ON RC.suffixtypeid = S.idsuffixtype
		      					WHERE
		                 			 RCR.rulescartid = :ruleid
		                 			AND RC.discountforall =1
		          					AND RCV.viewid= :viewid
		          					AND IF(RC.datefrom is not null, (cast(RC.datefrom as date) <= curdate()), 1)
									AND IF(RC.dateto is not null, (cast(RC.dateto as date)>= curdate()),1)";
					$stmt = $this->registry->db->prepareStatement($sql);
					$stmt->setInt('viewid', Helper::getViewId());
					$stmt->setInt('ruleid', $ruleid);
				}
				try{
					$rs = $stmt->executeQuery();
					while ($rs->next()){
						$ruleid = $rs->getInt('ruleid');
						$rulescartid = $rs->getInt('rulescartid');
						switch ($ruleid) {
							case 9: // delivery (dispatchmethod)
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if (isset($clientOrder['dispatchmethod']['dispatchmethodid']) && $clientOrder['dispatchmethod']['dispatchmethodid'] == $rs->getInt('pkid')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
							case 10: // paymentmethod
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if (isset($clientOrder['payment']['idpaymentmethod']) && $clientOrder['payment']['idpaymentmethod'] == $rs->getInt('pkid')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
							case 11: // final cart price
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if (isset($clientOrder['globalPrice']) && $clientOrder['globalPrice'] >= $rs->getFloat('pricefrom')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
							case 12: // final cart price
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if (isset($clientOrder['globalPrice']) && $clientOrder['globalPrice'] <= $rs->getFloat('priceto')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
							case 13: // final cart price with dispatch method
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if (isset($clientOrder['priceWithDispatchMethod']) && $clientOrder['priceWithDispatchMethod'] >= $rs->getFloat('pricefrom')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
							case 14: // final cart price with dispatch method
								if ((isset($Data[$rulescartid][$ruleid]) && $Data[$rulescartid][$ruleid] == 0) || ! isset($Data[$rulescartid][$ruleid])){
									if ($clientOrder['priceWithDispatchMethod'] <= $rs->getFloat('priceto')){
										$Data[$rulescartid][$ruleid] = 1;
									}
									else{
										$Data[$rulescartid][$ruleid] = 0;
									}
								}
								break;
						}
						$discountValue = $rs->getFloat('discount');
						$discountSymbol = $rs->getString('symbol');
						if ($discountSymbol !== '%' && $discountValue > 0){
							$shopCurrency = $this->registry->session->getActiveShopCurrencyId();
							$currentCurrency = $this->registry->session->getActiveCurrencyId();
							if ($shopCurrency != $currentCurrency){
								$rate = $this->registry->session->getActiveCurrencyRate();
								if (! empty($rate) && $rate > 0){
									$discountValue = $rate * $discountValue;
								}
							}
						}
						$discount[$rs->getInt('rulescartid')]['rulescartid'] = $rs->getInt('rulescartid');
						$discount[$rs->getInt('rulescartid')]['suffixtypeid'] = $rs->getInt('suffixtypeid');
						$discount[$rs->getInt('rulescartid')]['discount'] = $discountValue;
						$discount[$rs->getInt('rulescartid')]['symbol'] = $discountSymbol;
					}
					
					//
				}
				catch (Exception $e){
					throw new FrontendException($this->registry->core->getMessage('ERR_RULES_CART'));
				}
			} // end foreach
		} // end if is_array rulesAll
		
		if (count($Data) > 0){
			foreach ($Data as $rulescart => $rules){
				foreach ($rules as $rule){
					if ($rule == 0){
						unset($Data[$rulescart]);
					}
				}
			}
		}
		foreach ($Data as $rulescart => $rules){
			return $discount[$rulescart];
		}
	}

	public function getAllCartRules () {
		$rules = Array();
		$sql = "SELECT RC.idrulescart
					FROM rulescart RC
						LEFT JOIN rulescartview RCV ON RCV.rulescartid = RC.idrulescart
					WHERE RCV.viewid= :viewid
						AND IF(RC.datefrom is not null, (cast(RC.datefrom as date) <= curdate()), 1)
						AND IF(RC.dateto is not null, (cast(RC.dateto as date)>= curdate()),1)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$rules[] = Array(
					'idrulescart' => $rs->getInt('idrulescart')
				);
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_RULES_CART_SELECT'));
		}
		return $rules;
	}

	public function getRulesCartName ($rulescartid) {
		$rule = "";
		$sql = "SELECT RC.name
					FROM  rulescart RC
					WHERE idrulescart= :rulescartid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('rulescartid', $rulescartid);
		try{
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$rule = $rs->getString('name');
			}
		}
		catch (Exception $e){
			throw new FrontendException($this->registry->core->getMessage('ERR_RULES_CART_NAME'));
		}
		return $rule;
	}

}