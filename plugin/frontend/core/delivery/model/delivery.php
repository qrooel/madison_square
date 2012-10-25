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
 * $Revision: 689 $
 * $Author: gekosale $
 * $Date: 2012-09-01 19:55:28 +0200 (So, 01 wrz 2012) $
 * $Id: delivery.php 689 2012-09-01 17:55:28Z gekosale $
 */

class DeliveryModel extends Model
{
	
	protected $priceFormat = '%01.2f';

	public function priceFormatParser ($price)
	{
		return sprintf($this->priceFormat, $price);
	}

	public function getDispatchmethod ()
	{
		$sql = "SELECT 
					DP.name, 
					DP.iddispatchmethod
				FROM dispatchmethod DP 
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = iddispatchmethod
				WHERE DV.viewid=:viewid
				ORDER BY DP.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		try{
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$Data[] = Array(
					'name' => $this->registry->core->getMessage($rs->getString('name')),
					'iddispatchmethod' => $rs->getInt('iddispatchmethod')
				);
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while doing sql query- getDispatchmethod- deliveryModel');
		}
		return $Data;
	}

	public function getDispatchmethodPrice ()
	{
		if ($this->registry->session->getActiveShippingCountryId() == NULL){
			$shippingCountryId = $this->layer['countryid'];
		}
		else{
			$shippingCountryId = $this->registry->session->getActiveShippingCountryId();
		}
		$cartData = $this->registry->session->getActiveCartForDelivery();
		$globalprice = isset($cartData['price']) ? $cartData['price'] : 0;
		$globalweight = isset($cartData['weight']) ? $cartData['weight'] : 0;
		$shippingcost = isset($cartData['shippingcost']) ? $cartData['shippingcost'] : 0;
		
		$rate = $this->registry->session->getActiveCurrencyRate();
		$shopCurrency = $this->registry->session->getActiveShopCurrencyId();
		$currentCurrency = $this->registry->session->getActiveCurrencyId();
		if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
			$globalprice = $globalprice * $rate;
		}
		$Data = Array();
		
		$sql = "SELECT 
					DP.dispatchmethodid, 
					DP.`from`, 
					DP.`to`, 
					V.value, 
					DP.iddispatchmethodprice,
					IF(DP.vat IS NOT NULL, ROUND((DP.dispatchmethodcost + (DP.dispatchmethodcost*(V.`value`/100))) * CR.exchangerate,4), DP.dispatchmethodcost* CR.exchangerate) as dispatchmethodcost,
					IF(DP.vat IS NOT NULL, ROUND(:shippingcost+(:shippingcost*(V.`value`/100)) * CR.exchangerate,4), :shippingcost) as shippingcost,
					CASE
  						WHEN (`from` <> 0 AND `from`<:globalprice AND `to`= 0 AND DP.dispatchmethodcost =0) THEN D.name
 					 	WHEN (:globalprice BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from` < :globalprice AND DP.dispatchmethodcost <> 0) THEN D.name
  						WHEN (`from`= 0 AND `to`= 0 AND DP.dispatchmethodcost = 0) THEN D.name
					END as name,
					D.description,
					D.photo,
					D.countryids
				FROM dispatchmethodprice DP
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN currencyrates CR ON CR.currencyfrom = D.currencyid AND CR.currencyto = :currencyto
				LEFT JOIN vat V ON V.idvat = DP.vat
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE DV.viewid= :viewid AND IF(D.maximumweight IS NOT NULL, D.maximumweight >= :globalweight, 1) AND D.type = 1
				ORDER BY D.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('globalprice', $globalprice);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setFloat('shippingcost', $shippingcost);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$dispatchmethodid = $rs->getInt('dispatchmethodid');
			if ($rs->getString('name') != NULL){
				if ($globalprice > 0 || $globalweight > 0){
					$dispatchmethodcost = $rs->getFloat('dispatchmethodcost') + $rs->getFloat('shippingcost');
				}
				else{
					$dispatchmethodcost = $rs->getFloat('shippingcost');
				}
				if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
					$dispatchmethodcost = $rate * $dispatchmethodcost;
				}
				$countryIds = explode(',', $rs->getString('countryids'));
				if (in_array($shippingCountryId, $countryIds) || $rs->getString('countryids') == ''){
					$Data[$dispatchmethodid] = Array(
						'dispatchmethodid' => $dispatchmethodid,
						'name' => $this->registry->core->getMessage($rs->getString('name')),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'photoid' => $rs->getInt('photo'),
						'countryids' => $countryIds,
						'vatvalue' => $rs->getFloat('value'),
						'dispatchmethodcost' => $this->priceFormatParser($dispatchmethodcost),
						'description' => $rs->getString('description'),
						'photo' => ($rs->getInt('photo') > 0) ? App::getModel('gallery')->getImagePath(App::getModel('gallery')->getMediumImageById($rs->getInt('photo'))) : NULL
					);
				}
			}
		}
		
		$sql = "SELECT
					DW.dispatchmethodid, 
					DW.`from`, 
					DW.`to`, 
					DW.iddispatchmethodweight,
					IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)) * CR.exchangerate,4), DW.cost* CR.exchangerate) as dispatchmethodcost, 
					IF(DW.vat IS NOT NULL, ROUND(:shippingcost+(:shippingcost*(V.`value`/100)) * CR.exchangerate,4), :shippingcost) as shippingcost, 
					D.freedelivery,
					CASE
  						WHEN (`from`<>0 AND `from`<:globalweight AND `to`=0 AND DW.cost =0) THEN D.name
 					 	WHEN (:globalweight BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`<:globalweight AND DW.cost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DW.cost = 0) THEN D.name
					END as name,
					D.description,
					D.photo,
					V.value,
					D.countryids
				FROM dispatchmethodweight DW
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN currencyrates CR ON CR.currencyfrom = D.currencyid AND CR.currencyto = :currencyto
				LEFT JOIN vat V ON V.idvat = DW.vat
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE DV.viewid= :viewid AND D.type = 2
				ORDER BY D.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('globalprice', $globalprice);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setFloat('shippingcost', $shippingcost);
		$stmt->setInt('currencyto', $this->registry->session->getActiveCurrencyId());
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$dispatchmethodid = $rs->getInt('dispatchmethodid');
			if ($rs->getString('name') != NULL){
				if (($rs->getFloat('freedelivery') > 0) && ($rs->getFloat('freedelivery') <= $globalprice)){
					$dispatchmethodcost = 0.00;
				}
				else{
					$dispatchmethodcost = $rs->getFloat('dispatchmethodcost');
				}
				
				if ($globalprice > 0 || $globalweight > 0){
					$dispatchmethodcost = $dispatchmethodcost + $rs->getFloat('shippingcost');
				}
				else{
					$dispatchmethodcost = $rs->getFloat('shippingcost');
				}
				
				if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
					$dispatchmethodcost = $rate * $dispatchmethodcost;
				}
				$countryIds = explode(',', $rs->getString('countryids'));
				if (in_array($shippingCountryId, $countryIds) || $rs->getString('countryids') == ''){
					$Data[$dispatchmethodid] = Array(
						'dispatchmethodid' => $dispatchmethodid,
						'name' => $this->registry->core->getMessage($rs->getString('name')),
						'from' => $rs->getFloat('from'),
						'to' => $rs->getFloat('to'),
						'photoid' => $rs->getInt('photo'),
						'countryids' => $countryIds,
						'vatvalue' => $rs->getFloat('value'),
						'dispatchmethodcost' => $this->priceFormatParser($dispatchmethodcost),
						'description' => $rs->getString('description'),
						'photo' => ($rs->getInt('photo') > 0) ? App::getModel('gallery')->getImagePath(App::getModel('gallery')->getMediumImageById($rs->getInt('photo'))) : NULL
					);
				}
			}
		}
		return $Data;
	}

	public function getDispatchmethodPriceForProduct ($globalprice, $globalweight)
	{
		$rate = $this->registry->session->getActiveCurrencyRate();
		$shopCurrency = $this->registry->session->getActiveShopCurrencyId();
		$currentCurrency = $this->registry->session->getActiveCurrencyId();
		if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
			$globalprice = $globalprice * $rate;
		}
		$Data = Array();
		
		$sql = "SELECT 
					DP.dispatchmethodid,
					DP.`from`, 
					DP.`to`, 
					V.value, 
					DP.iddispatchmethodprice,
					IF(DP.vat IS NOT NULL, ROUND(DP.dispatchmethodcost+(DP.dispatchmethodcost*(V.`value`/100)),4), DP.dispatchmethodcost) as dispatchmethodcost,
					CASE
  						WHEN (`from`<>0 AND `from`<:globalprice AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
 					 	WHEN (:globalprice BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`<:globalprice AND DP.dispatchmethodcost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DP.dispatchmethodcost =0) THEN D.name
					END as name
				FROM dispatchmethodprice DP
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN vat V ON V.idvat = DP.vat
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE DV.viewid= :viewid AND IF(D.maximumweight IS NOT NULL, D.maximumweight >= :globalweight, 1) AND D.type = 1
				ORDER BY D.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('globalprice', $globalprice);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$dispatchmethodid = $rs->getInt('dispatchmethodid');
			if ($rs->getString('name') != NULL){
				$dispatchmethodcost = $rs->getFloat('dispatchmethodcost');
				if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
					$dispatchmethodcost = $rate * $dispatchmethodcost;
				}
				$Data[] = Array(
					'dispatchmethodid' => $dispatchmethodid,
					'name' => $this->registry->core->getMessage($rs->getString('name')),
					'from' => $rs->getFloat('from'),
					'to' => $rs->getFloat('to'),
					'vatvalue' => $rs->getFloat('value'),
					'dispatchmethodcost' => $this->priceFormatParser($dispatchmethodcost)
				);
			}
		}
		
		$sql = "SELECT
					DW.dispatchmethodid, 
					DW.`from`, 
					DW.`to`, 
					V.value, 
					DW.iddispatchmethodweight,
					IF(DW.vat IS NOT NULL, ROUND(DW.cost+(DW.cost*(V.`value`/100)),4), DW.cost) as dispatchmethodcost, 
					D.freedelivery,
					CASE
  						WHEN (`from`<>0 AND `from`<:globalweight AND `to`=0 AND DW.cost =0) THEN D.name
 					 	WHEN (:globalweight BETWEEN `from` AND `to`) THEN D.name
  						WHEN (`to` = 0 AND `from`<:globalweight AND DW.cost <> 0) THEN D.name
  						WHEN (`from`=0 AND `to`=0 AND DW.cost = 0) THEN D.name
					END as name
				FROM dispatchmethodweight DW
				LEFT JOIN vat V ON V.idvat = DW.vat
				LEFT JOIN dispatchmethod D ON D.iddispatchmethod = dispatchmethodid
				LEFT JOIN dispatchmethodview DV ON DV.dispatchmethodid = D.iddispatchmethod
				WHERE DV.viewid= :viewid AND D.type = 2
				ORDER BY D.hierarchy ASC";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('globalprice', $globalprice);
		$stmt->setFloat('globalweight', $globalweight);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$dispatchmethodid = $rs->getInt('dispatchmethodid');
			if ($rs->getString('name') != NULL){
				if (($rs->getFloat('freedelivery') > 0) && ($rs->getFloat('freedelivery') <= $globalprice)){
					$dispatchmethodcost = 0.00;
				}
				else{
					$dispatchmethodcost = $rs->getFloat('dispatchmethodcost');
				}
				
				if ($shopCurrency !== $currentCurrency && ! empty($rate) && $rate > 0){
					$dispatchmethodcost = $rate * $dispatchmethodcost;
				}
				$Data[] = Array(
					'dispatchmethodid' => $dispatchmethodid,
					'name' => $this->registry->core->getMessage($rs->getString('name')),
					'from' => $rs->getFloat('from'),
					'to' => $rs->getFloat('to'),
					'vatvalue' => $rs->getFloat('value'),
					'dispatchmethodcost' => $this->priceFormatParser($dispatchmethodcost)
				);
			}
		}
		return $Data;
	}

	public function setAJAXDispatchmethodChecked ($dispatchmethodid)
	{
		$objResponseDispatchmethod = new xajaxResponse();
		try{
			$this->setDispatchmethodChecked($dispatchmethodid);
			$this->registry->session->setActivePaymentMethodChecked(0);
			$this->registry->session->setActivePaymentData(NULL);
			
			$objResponseDispatchmethod->assign('delivery-' . $dispatchmethodid, "checked", "checked");
			$objResponseDispatchmethod->script('xajax_refreshPaymentMethod();');
			$objResponseDispatchmethod->script('xajax_refreshFinalization();');
			$settings = $this->registry->core->loadModuleSettings('inpost', Helper::getViewId());
			if(isset($settings['inpostdispatchmethod']) && $settings['inpostdispatchmethod'] == $dispatchmethodid){
				$objResponseDispatchmethod->script('$("#inpostenabled").val(1).trigger("change");');
			}else{
				$objResponseDispatchmethod->script('$("#inpostenabled").val(0).trigger("change");');
			}
		}
		catch (FrontendException $e){
			throw new FrontendException('Error while checking deliver: getAJAXDelivererChecked- dispatchmethodModel');
		}
		return $objResponseDispatchmethod;
	}

	public function setDispatchmethodChecked ($dispatchmethodid)
	{
		$this->registry->session->setActiveDispatchmethodChecked(0);
		$this->registry->session->setActiveglobalPriceWithDispatchmethod($this->registry->session->getActiveGlobalPrice());
		$this->registry->session->setActiveglobalPriceWithDispatchmethodNetto($this->registry->session->getActiveGlobalPriceWithoutVat());
		if ($dispatchmethodid != NULL){
			$dispatchmethods = $this->getDispatchmethodPrice();
			
			$dispatchmethodcost = $dispatchmethods[$dispatchmethodid]['dispatchmethodcost'];
			
			$activeDispatchmethod = Array(
				'dispatchmethodid' => $dispatchmethodid,
				'dispatchmethodcost' => $this->priceFormatParser($dispatchmethodcost),
				'dispatchmethodcostnetto' => $this->priceFormatParser($dispatchmethodcost),
				'dispatchmethodname' => $dispatchmethods[$dispatchmethodid]['name']
			);
			
			$this->registry->session->setActiveDispatchmethodChecked($activeDispatchmethod);
			$this->registry->session->setActiveglobalPriceWithDispatchmethod($this->registry->session->getActiveGlobalPrice() + $dispatchmethodcost);
			$this->registry->session->setActiveglobalPriceWithDispatchmethodNetto($this->registry->session->getActiveGlobalPriceWithoutVat() + $dispatchmethodcost);
		}
	}

	public function setAjaxShippingCountryId ($countryid)
	{
		$objResponse = new xajaxResponse();
		$this->registry->session->setActiveDispatchmethodChecked(NULL);
		$this->registry->session->setActiveShippingCountryId($countryid);
		$objResponse->script('window.location.reload(false);');
		return $objResponse;
	}

}