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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: core.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

class Core
{
	
	protected $_boxParams;
	
	protected $registry;
	protected $storeid;
	protected $viewid;

	public function __construct ($registry)
	{
		$this->registry = $registry;
		$this->setEnvironmentVariables();
		$this->layerData = $this->registry->loader->getCurrentLayer();
	}

	public function processPrice ($price, $withSymbol = true)
	{
		if (! is_null($price)){
			if ($price < 0){
				return ($this->layerData['negativepreffix'] . number_format(abs($price), $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . $this->layerData['negativesuffix']);
			}
			return ($this->layerData['positivepreffix'] . number_format($price, $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . (($withSymbol == true) ? $this->layerData['positivesuffix'] : ''));
		}
		return NULL;
	}

	public function setTranslations ()
	{
		if (($this->messages = Cache::loadObject('translations')) === FALSE){
			$sql = 'SELECT T.name,TD.translation FROM translation T
						LEFT JOIN translationdata TD ON T.idtranslation = TD.translationid
						WHERE TD.languageid = :languageid
						';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$this->messages[$rs->getString('name')] = $rs->getString('translation');
			}
			Cache::saveObject('translations', $this->messages, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
	}

	public function getTranslations ()
	{
		return $this->messages;
	}

	public function setUrlMap ()
	{
		if (($this->urlmap = Cache::loadObject('urlmap')) === FALSE){
			$sql = 'SELECT controller, params, pkid, url FROM urlmap';
			$stmt = $this->registry->db->prepareStatement($sql);
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$this->urlmap[$rs->getString('controller')][$rs->getInt('pkid')] = $rs->getString('url');
			}
			Cache::saveObject('urlmap', $this->urlmap, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
	
	}

	public function setSeoNames ()
	{
		if (($this->controllerseo = Cache::loadObject('controllerseo')) === FALSE){
			$sql = 'SELECT 
						C.name as name, 
						IF(CS.name IS NOT NULL, CS.name, C.name) as alias 
					FROM controller C
					LEFT JOIN controllerseo CS ON CS.controllerid = C.idcontroller
					WHERE CS.languageid = :languageid';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('languageid', Helper::getLanguageId());
			$rs = $stmt->executeQuery();
			$Data = Array();
			while ($rs->next()){
				$this->controllerseo[$rs->getString('name')] = $rs->getString('alias');
			}
			Cache::saveObject('controllerseo', $this->controllerseo, Array(
				Cache::SESSION => 0,
				Cache::FILE => 1
			));
		}
	
	}

	public function getMessage ($name)
	{
		if (! is_null($name) && ($name != '') && (isset($this->messages[$name]))){
			return $this->messages[$name];
		}
		else{
			return $name;
		}
	}

	public function setLanguage ()
	{
		$Data = Array();
		$browserLanguage = $this->getBrowserFirstLanguage();
		if ($this->registry->session->getActiveLanguage() == NULL){
			$sql = 'SELECT 
						L.idlanguage,
						L.name,
						C.idcurrency, 
						C.currencysymbol
					FROM language L
					LEFT JOIN languageview LV ON L.idlanguage = LV.languageid
					LEFT JOIN currency C ON C.idcurrency = L.currencyid
					WHERE LV.viewid = :viewid ORDER BY L.idlanguage';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			$set = false;
			while ($rs->next()){
				$Data[substr($rs->getString('name'), 0, 2)] = Array(
					'id' => $rs->getInt('idlanguage'),
					'name' => $rs->getString('name'),
					'currencyid' => $rs->getInt('idcurrency'),
					'currencysymbol' => $rs->getString('currencysymbol')
				);
			}
			foreach ($Data as $language => $val){
				if ($language == $browserLanguage){
					$this->registry->session->setActiveLanguage($val['name']);
					$this->registry->session->setActiveLanguageId($val['id']);
					$this->registry->session->setActiveCurrencyId($val['currencyid']);
					$this->registry->session->setActiveCurrencySymbol($val['currencysymbol']);
					break;
				}
			}
			if ($this->registry->session->getActiveLanguage() == NULL){
				
				if (strtolower($browserLanguage) != 'pl' && isset($Data['en'])){
					$val = $Data['en'];
					$this->registry->session->setActiveLanguage($val['name']);
					$this->registry->session->setActiveLanguageId($val['id']);
					$this->registry->session->setActiveCurrencyId($val['currencyid']);
					$this->registry->session->setActiveCurrencySymbol($val['currencysymbol']);
				}
				
				if (strtolower($browserLanguage) != 'pl' && ! isset($Data['en'])){
					$val = $Data['pl'];
					$this->registry->session->setActiveLanguage($val['name']);
					$this->registry->session->setActiveLanguageId($val['id']);
					$this->registry->session->setActiveCurrencyId($val['currencyid']);
					$this->registry->session->setActiveCurrencySymbol($val['currencysymbol']);
				}
			
			}
		
		}
	}

	public function getBrowserFirstLanguage ()
	{
		if (! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			return ('');
		}
		
		$browserLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$browserLanguagesSize = sizeof($browserLanguages);
		for ($i = 0; $i < $browserLanguagesSize; $i ++){
			$browserLanguage = explode(';', $browserLanguages[$i]);
			$browserLanguages[$i] = substr($browserLanguage[0], 0, 2);
		}
		
		if (isset($browserLanguages[0]))
			return ($browserLanguages[0]);
		
		return ('');
	}

	public function setAdminStoreConfig ()
	{
		$sql = 'SELECT
					(SELECT version FROM updatehistory WHERE packagename = :packagename ORDER BY idupdatehistory DESC LIMIT 1) AS appversion,
					(SELECT url FROM viewurl WHERE viewid = :viewid LIMIT 1) AS shopurl
		';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('packagename', 'Gekosale');
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->registry->session->setActiveAppVersion($rs->getString('appversion'));
			$this->registry->session->setActiveShopUrl($rs->getString('shopurl'));
		}
		if ($this->registry->session->getActiveGlobalSettings() == NULL){
			$settingsData = App::getModel('globalsettings')->getSettings();
			$this->registry->session->setActiveGlobalSettings($settingsData);
		}
	}

	public function getParam ($index = 0)
	{
		$clean = explode('?', $this->registry->router->getParams());
		if (count($clean) > 0){
			$url = $clean[0];
		}
		else{
			$url = $this->registry->router->getParams();
		}
		$params = explode(',', str_replace('/', ',', $url));
		if (isset($params[$index])){
			return $params[$index];
		}
		else{
			return false;
		}
	}

	public function getParams ()
	{
		$params = explode(',', $this->registry->router->getParams());
		return $params;
	}

	public function getParamFromUrl ($paramType)
	{
		$params = $this->getParams();
		
		if ($paramType == 'price'){
			foreach ($params as $param){
				if (preg_match('(od[\d+]{1,9}do[\d+]{1,9})', $param)){
					return $param;
				}
			}
		}
		
		if ($paramType == 'attributes'){
			$attributes = Array();
			foreach ($params as $param){
				if (preg_match('(g[\d+]{1,3}-[\d+]{1,4})', $param)){
					$attributes[$param] = $param;
				}
			}
			return $attributes;
		}
		
		if ($paramType == 'staticattributes'){
			$attributes = Array();
			foreach ($params as $param){
				if (preg_match('(s[\d+]{1,3}-[\d+]{1,4})', $param)){
					$attributes[$param] = $param;
				}
			}
			return $attributes;
		}
		
		if ($paramType == 'producer'){
			$rawProducers = App::getModel('product')->getProducerAll();
			$producers = Array();
			foreach ($rawProducers as $producer){
				$producers[$producer['seo']] = $producer['id'];
			}
			$prod = array_keys($producers);
			$producersFromUrl = Array();
			foreach ($params as $param){
				if (in_array($param, $prod)){
					$producersFromUrl[$producers[$param]] = $param;
				}
			}
			return $producersFromUrl;
		}
		return '';
	}

	public function getPriceRangeFromUrl ()
	{
		$params = $this->getParams();
		$priceFrom = 0;
		$priceTo = 999999;
		foreach ($params as $param){
			if (strpos($param, 'od') == 0 && strpos($param, 'do') > 0){
				
				$priceFrom = substr($param, strpos($param, 'od') + 2, strpos($param, 'do') - 2);
				$priceTo = substr($param, strpos($param, 'do') + 2);
			}
		}
		return Array(
			'priceFrom' => (int) $priceFrom,
			'priceTo' => (int) $priceTo
		);
	}

	public function getParamsForBox ($boxId)
	{
		$this->getBoxParams();
		if (isset($this->_boxParams[$boxId])){
			return $this->_boxParams[$boxId];
		}
		return Array();
	}

	public function getBoxParams ()
	{
		if (! isset($this->_boxParams) or ! is_array($this->_boxParams)){
			$this->_boxParams = Array();
			$params = $this->getParams();
			foreach ($params as $param){
				if (substr($param, 0, 2) == 'p='){
					$this->_boxParams = json_decode(base64_decode(substr($param, 2)), true);
					break;
				}
			}
		}
		return $this->_boxParams;
	}

	public function getDefaultValueToSelect ()
	{
		return Array(
			$this->getMessage('TXT_CHOOSE_SELECT')
		);
	}

	public function setEnvironmentVariables ()
	{
		$this->setLayerVariables();
		$this->setLanguage();
		if (App::getRegistry()->router->getMode() == 0){
			$this->setSeoNames();
			//			$this->setUrlMap();
		}
		$this->setTranslations();
	}

	public function setLayerVariables ()
	{
		if (App::getRegistry()->router->getMode() == 0){
			$this->registry->session->setActiveMainsideViewId($this->registry->loader->getLayerViewId());
		}
		else{
			if ($this->registry->session->getActiveViewId() !== NULL){
				$viewid = $this->registry->session->getActiveViewId();
			}
			else{
				$viewid = 0;
			}
			$this->registry->session->setActiveViewId($viewid);
		}
	}

	public static function passwordGenerate ()
	{
		$passwdlen = 8;
		$passwd = NULL;
		$length = 74;
		$collection = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^*_-+=?:';
		for ($x = 0; $x < $passwdlen; $x ++){
			$passwd .= $collection{rand(0, $length)};
		}
		return $passwd;
	}

	public function getFrontendControllerNameForSeo ($controller)
	{
		return $this->controllerseo[$controller];
	}

	public function getControllerNameForSeo ($controller)
	{
		if (App::getRegistry()->router->getMode() == 0){
			if (! is_null($controller) && ($controller != '') && (isset($this->controllerseo[$controller]))){
				return $this->controllerseo[$controller];
			}
			else{
				return $controller;
			}
		}
		else{
			return $controller;
		}
	}

	public function setDefaultPhoto ()
	{
		
		if (Helper::getStoreId() > 0){
			
			$sql = 'SELECT 
						defaultphotoid
					FROM store WHERE idstore = :id 
					AND defaultphotoid IS NOT NULL';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('id', Helper::getStoreId());
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$Data = $rs->getInt('defaultphotoid');
			}
			else{
				$Data = 1;
			}
		}
		else{
			$Data = 1;
		}
		
		return $Data;
	}

	public static function toString ($data, $terminate = true)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		if ($terminate)
			die();
	}

	public static function clearUTF ($string)
	{
		$a = array(
			'À',
			'Á',
			'Â',
			'Ã',
			'Ä',
			'Å',
			'Æ',
			'Ç',
			'È',
			'É',
			'Ê',
			'Ë',
			'Ì',
			'Í',
			'Î',
			'Ï',
			'Ð',
			'Ñ',
			'Ò',
			'Ó',
			'Ô',
			'Õ',
			'Ö',
			'Ø',
			'Ù',
			'Ú',
			'Û',
			'Ü',
			'Ý',
			'ß',
			'à',
			'á',
			'â',
			'ã',
			'ä',
			'å',
			'æ',
			'ç',
			'è',
			'é',
			'ê',
			'ë',
			'ì',
			'í',
			'î',
			'ï',
			'ñ',
			'ò',
			'ó',
			'ô',
			'õ',
			'ö',
			'ø',
			'ù',
			'ú',
			'û',
			'ü',
			'ý',
			'ÿ',
			'Ā',
			'ā',
			'Ă',
			'ă',
			'Ą',
			'ą',
			'Ć',
			'ć',
			'Ĉ',
			'ĉ',
			'Ċ',
			'ċ',
			'Č',
			'č',
			'Ď',
			'ď',
			'Đ',
			'đ',
			'Ē',
			'ē',
			'Ĕ',
			'ĕ',
			'Ė',
			'ė',
			'Ę',
			'ę',
			'Ě',
			'ě',
			'Ĝ',
			'ĝ',
			'Ğ',
			'ğ',
			'Ġ',
			'ġ',
			'Ģ',
			'ģ',
			'Ĥ',
			'ĥ',
			'Ħ',
			'ħ',
			'Ĩ',
			'ĩ',
			'Ī',
			'ī',
			'Ĭ',
			'ĭ',
			'Į',
			'į',
			'İ',
			'ı',
			'Ĳ',
			'ĳ',
			'Ĵ',
			'ĵ',
			'Ķ',
			'ķ',
			'Ĺ',
			'ĺ',
			'Ļ',
			'ļ',
			'Ľ',
			'ľ',
			'Ŀ',
			'ŀ',
			'Ł',
			'ł',
			'Ń',
			'ń',
			'Ņ',
			'ņ',
			'Ň',
			'ň',
			'ŉ',
			'Ō',
			'ō',
			'Ŏ',
			'ŏ',
			'Ő',
			'ő',
			'Œ',
			'œ',
			'Ŕ',
			'ŕ',
			'Ŗ',
			'ŗ',
			'Ř',
			'ř',
			'Ś',
			'ś',
			'Ŝ',
			'ŝ',
			'Ş',
			'ş',
			'Š',
			'š',
			'Ţ',
			'ţ',
			'Ť',
			'ť',
			'Ŧ',
			'ŧ',
			'Ũ',
			'ũ',
			'Ū',
			'ū',
			'Ŭ',
			'ŭ',
			'Ů',
			'ů',
			'Ű',
			'ű',
			'Ų',
			'ų',
			'Ŵ',
			'ŵ',
			'Ŷ',
			'ŷ',
			'Ÿ',
			'Ź',
			'ź',
			'Ż',
			'ż',
			'Ž',
			'ž',
			'ſ',
			'ƒ',
			'Ơ',
			'ơ',
			'Ư',
			'ư',
			'Ǎ',
			'ǎ',
			'Ǐ',
			'ǐ',
			'Ǒ',
			'ǒ',
			'Ǔ',
			'ǔ',
			'Ǖ',
			'ǖ',
			'Ǘ',
			'ǘ',
			'Ǚ',
			'ǚ',
			'Ǜ',
			'ǜ',
			'Ǻ',
			'ǻ',
			'Ǽ',
			'ǽ',
			'Ǿ',
			'ǿ',
			'Ą',
			'Ć',
			'Ę',
			'Ł',
			'Ń',
			'Ó',
			'Ś',
			'Ź',
			'Ż',
			'ą',
			'ć',
			'ę',
			'ł',
			'ń',
			'ó',
			'ś',
			'ź',
			'ż',
			' ',
			',',
			'_',
			'.',
			'?'
		);
		$b = array(
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'AE',
			'C',
			'E',
			'E',
			'E',
			'E',
			'I',
			'I',
			'I',
			'I',
			'D',
			'N',
			'O',
			'O',
			'O',
			'O',
			'O',
			'O',
			'U',
			'U',
			'U',
			'U',
			'Y',
			's',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'ae',
			'c',
			'e',
			'e',
			'e',
			'e',
			'i',
			'i',
			'i',
			'i',
			'n',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'u',
			'u',
			'u',
			'u',
			'y',
			'y',
			'A',
			'a',
			'A',
			'a',
			'A',
			'a',
			'C',
			'c',
			'C',
			'c',
			'C',
			'c',
			'C',
			'c',
			'D',
			'd',
			'D',
			'd',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'E',
			'e',
			'G',
			'g',
			'G',
			'g',
			'G',
			'g',
			'G',
			'g',
			'H',
			'h',
			'H',
			'h',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'I',
			'i',
			'IJ',
			'ij',
			'J',
			'j',
			'K',
			'k',
			'L',
			'l',
			'L',
			'l',
			'L',
			'l',
			'L',
			'l',
			'l',
			'l',
			'N',
			'n',
			'N',
			'n',
			'N',
			'n',
			'n',
			'O',
			'o',
			'O',
			'o',
			'O',
			'o',
			'OE',
			'oe',
			'R',
			'r',
			'R',
			'r',
			'R',
			'r',
			'S',
			's',
			'S',
			's',
			'S',
			's',
			'S',
			's',
			'T',
			't',
			'T',
			't',
			'T',
			't',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'W',
			'w',
			'Y',
			'y',
			'Y',
			'Z',
			'z',
			'Z',
			'z',
			'Z',
			'z',
			's',
			'f',
			'O',
			'o',
			'U',
			'u',
			'A',
			'a',
			'I',
			'i',
			'O',
			'o',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'U',
			'u',
			'A',
			'a',
			'AE',
			'ae',
			'O',
			'o',
			'A',
			'C',
			'E',
			'L',
			'N',
			'O',
			'S',
			'Z',
			'Z',
			'a',
			'c',
			'e',
			'l',
			'n',
			'o',
			's',
			'z',
			'z',
			'-',
			'',
			'-',
			'-',
			''
		);
		return str_replace($a, $b, $string);
	}

	public static function clearNonAlpha ($string)
	{
		$a = Array(
			"!",
			"@",
			"#",
			"$",
			"%",
			"^",
			"*",
			"(",
			")",
			",",
			".",
			":",
			"\"",
			"/",
			"\\",
			"<",
			">",
			"|"
		);
		return str_replace($a, '', strip_tags($string));
	}

	public static function truncate ($s, $l, $e = '...', $isHTML = true)
	{
		$i = 0;
		$tags = array();
		if ($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach ($m as $o){
				if ($o[0][1] - $i >= $l)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				if ($t[0] != '/')
					$tags[] = $t;
				elseif (end($tags) == substr($t, 1))
					array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}
		return substr($s, 0, $l = min(strlen($s), $l + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	}

	public function __call ($name, $params)
	{
		$url = '';
		if (substr($name, 0, 9) == "getUrlMap" && strlen($name) > 9){
			$controller = strtolower(preg_replace('/getUrlMap?/', '', $name));
			$pkid = (isset($params[0]) && $params[0] > 0) ? $params[0] : 0;
			$url = isset($this->urlmap[$controller][$pkid]) ? $this->urlmap[$controller][$pkid] : '';
		}
		return $url;
	}

	public function loadModuleSettings ($module, $viewid = 0)
	{
		$sql = 'SELECT * FROM modulesettings WHERE module = :module';
		if ($viewid > 0){
			$sql .= ' AND viewid = :viewid';
		}
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('module', $module);
		$stmt->setInt('viewid', $viewid);
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[$rs->getString('param')] = $rs->getString('value');
		}
		return $Data;
	}

	public function saveModuleSettings ($module, $Data, $viewid = 0)
	{
		foreach ($Data as $param => $value){
			$sql = 'INSERT INTO modulesettings SET
						param = :param,
						module = :module,
						viewid = :viewid,
						value = :value
					ON DUPLICATE KEY UPDATE
						value = :value';
			
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setString('param', $param);
			$stmt->setString('module', $module);
			$stmt->setInt('viewid', $viewid);
			$stmt->setString('value', $value);
			$rs = $stmt->executeQuery();
		}
	}

}
