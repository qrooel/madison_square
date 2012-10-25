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
 * $Revision: 689 $
 * $Author: gekosale $
 * $Date: 2012-09-01 19:55:28 +0200 (So, 01 wrz 2012) $
 * $Id: language.php 689 2012-09-01 17:55:28Z gekosale $
 */
class LanguageModel extends Model {

	public function getLanguages () {
		if (($Data = Cache::loadObject('languages')) === FALSE){
			$sql = 'SELECT 
					idlanguage AS id, 
					flag, 
					translation,
					viewid
				FROM language L
				INNER JOIN languageview LV ON LV.languageid = L.idlanguage AND LV.viewid = :viewid';
			$Data = Array();
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$Data[$rs->getInt('id')] = Array(
					'id' => $rs->getInt('id'),
					'flag' => $rs->getString('flag'),
					'weight' => $rs->getInt('id'),
					'icon' => $rs->getString('flag'),
					'name' => $this->registry->core->getMessage($rs->getString('translation'))
				);
			}
			Cache::saveObject('languages', $Data, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		
		return $Data;
	}

	public function changeAJAXLanguageAboutView ($id) {
		$objResponseChangeLanguage = new xajaxResponse();
		try{
			$checkId = $this->checkLanguageId($id);
			$this->registry->session->setActiveLanguageId($id);
			$this->registry->session->setActiveLanguage($checkId['name']);
			$shopCurrencyId = $this->registry->session->getActiveShopCurrencyId();
			if ($shopCurrencyId != $checkId['currencyid']){
				$this->changeAJAXCurrencyView($checkId['currencyid']);
			}
		}
		catch (FrontendException $fe){
			echo 'error';
		}
		$objResponseChangeLanguage->script('window.location.reload( false )');
		return $objResponseChangeLanguage;
	}

	public function checkLanguageId ($id) {
		$sql = 'SELECT languageid, name , currencyid
					FROM languageview 
					LEFT JOIN language L ON L.idlanguage = languageid
					WHERE viewid = :viewid AND languageid=:id';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'languageid' => $rs->getInt('languageid'),
				'currencyid' => $rs->getInt('currencyid'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function changeAJAXCurrencyView ($id) {
		$objResponse = new xajaxResponse();
		try{
			$shopCurrencyId = $this->registry->session->getActiveShopCurrencyId();
			$currencyData = $this->getCurrencySelectedData($id);
			if (is_array($currencyData) && ! empty($currencyData)){
				$this->registry->session->setActiveCurrencyId($currencyData['id']);
				$this->registry->session->setActiveCurrencySymbol($currencyData['symbol']);
				$rate = $this->changeCurrencyRate($shopCurrencyId, $currencyData['id']);
				$this->registry->session->setActiveCurrencyRate($rate);
				if ($rate > 0 || $rate != 1){
					$this->updateSessionNewPrices();
				}
			}
			$objResponse->script('window.location.reload( false )');
		}
		catch (FrontendException $fe){
			throw new FrontendException($e->getMessage());
		}
		return $objResponse;
	}

	public function getCurrencySelectedData ($currencyId) {
		$Data = Array();
		$sql = "SELECT C.idcurrency, C.currencysymbol
					FROM currency C
					WHERE C.idcurrency= :idcurrency";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idcurrency', $currencyId);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'id' => $rs->getInt('idcurrency'),
				'symbol' => $rs->getString('currencysymbol')
			);
		}
		return $Data;
	}

	public function getAllCurrenciesForView () {
		$shopCurrencyId = $this->registry->session->getActiveCurrencyId();
		if (($Data = Cache::loadObject('currencies')) === FALSE){
			$sql = "SELECT C.idcurrency, C.currencysymbol, C.currencyname
						FROM currency C
							LEFT JOIN currencyview CV ON CV.currencyid = C.idcurrency
						WHERE CV.viewid= :viewid";
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$id = $rs->getInt('idcurrency');
				$Data[$id] = Array(
					'id' => $id,
					'name' => $rs->getString('currencysymbol')
				);
			}
			Cache::saveObject('currencies', $Data, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
		$Data[$shopCurrencyId]['selected'] = 1;
		return $Data;
	}

	public function changeCurrencyRate ($currencyFromId, $currencyToId) {
		$exchangerate = 0.00;
		$sql = "SELECT exchangerate
					FROM currencyrates
					WHERE currencyfrom= :currencyfrom
					AND currencyto= :currencyto";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('currencyfrom', $currencyFromId);
		$stmt->setInt('currencyto', $currencyToId);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$exchangerate = $rs->getFloat('exchangerate');
		}
		return $exchangerate;
	}

	public function updateSessionNewPrices () {
		$cart = $this->registry->session->getActiveCart();
		if (isset($cart) && ! empty($cart)){
			App::getModel('cart')->setTempCartAfterCurrencyChange();
		}
	}
}