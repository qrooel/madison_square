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
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: loader.class.php 438 2011-08-27 09:29:36Z gekosale $
 */

class Loader
{
	
	const SYSTEM_NAMESPACE = 'core';
	
	protected $registry;
	protected $events;
	protected $layer = Array();
	protected $namespace = 'core';

	public function __construct (&$registry)
	{
		$this->registry = $registry;
		$this->loadView();
	}

	public function loadView ()
	{
		$sql = 'SELECT
					V.idview,
					V.name as shopname,
					V.namespace,
					C.idcurrency, 
					C.currencysymbol,
					C.decimalseparator,
					C.decimalcount,
					C.thousandseparator,
					C.positivepreffix,
					C.positivesuffix,
					C.negativepreffix,
					C.negativesuffix,
					S.countryid,
					S.defaultphotoid,
					V.taxes,
					V.showtax,
					V.offline,
					gacode,
					gapages,
					gatransactions,
					cartredirect,
					minimumordervalue,
					photoid,
					favicon,
					enableopinions,
					enabletags,
					enablerss,
					catalogmode,
					forcelogin,
					apikey,
					faceboookappid,
					faceboooksecret,
					watermark,
					confirmregistration,
					enableregistration,
					confirmorder,
					confirmorderstatusid,
					guestcheckout,
					ordernotifyaddresses
				FROM view V
				LEFT JOIN viewurl VU ON VU.viewid = V.idview
				LEFT JOIN viewcategory VC ON VC.viewid = V.idview
				LEFT JOIN store S ON V.storeid = S.idstore ';
		if ($this->registry->session->getActiveCurrencyId() == NULL){
			$sql .= 'LEFT JOIN currency C ON S.currencyid = C.idcurrency ';
		}
		else{
			$sql .= 'LEFT JOIN currency C ON C.idcurrency = :currencyid ';
		}
		$sql .= 'WHERE VU.url = :url';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('url', App::getHost());
		$stmt->setInt('currencyid', $this->registry->session->getActiveCurrencyId());
		$stmt->setString('packagename', 'Gekosale');
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$this->layer = Array(
				'idview' => $rs->getInt('idview'),
				'defaultphotoid' => $rs->getInt('defaultphotoid'),
				'namespace' => $rs->getString('namespace'),
				'cartredirect' => $rs->getString('cartredirect'),
				'faceboookappid' => $rs->getString('faceboookappid'),
				'faceboooksecret' => $rs->getString('faceboooksecret'),
				'gacode' => $rs->getString('gacode'),
				'gapages' => $rs->getString('gapages'),
				'gatransactions' => $rs->getString('gatransactions'),
				'offline' => $rs->getInt('offline'),
				'taxes' => $rs->getInt('taxes'),
				'showtax' => $rs->getInt('showtax'),
				'shopname' => $rs->getString('shopname'),
				'photoid' => $rs->getString('photoid'),
				'favicon' => $rs->getString('favicon'),
				'watermark' => $rs->getString('watermark'),
				'idcurrency' => $rs->getInt('idcurrency'),
				'currencysymbol' => $rs->getString('currencysymbol'),
				'decimalseparator' => $rs->getString('decimalseparator'),
				'decimalcount' => $rs->getInt('decimalcount'),
				'thousandseparator' => $rs->getString('thousandseparator'),
				'positivepreffix' => $rs->getString('positivepreffix'),
				'positivesuffix' => $rs->getString('positivesuffix'),
				'negativepreffix' => $rs->getString('negativepreffix'),
				'negativesuffix' => $rs->getString('negativesuffix'),
				'countryid' => $rs->getInt('countryid'),
				'enablerating' => $rs->getInt('enableopinions'),
				'enableopinions' => $rs->getInt('enableopinions'),
				'enabletags' => $rs->getInt('enabletags'),
				'enablerss' => $rs->getInt('enablerss'),
				'catalogmode' => $rs->getInt('catalogmode'),
				'forcelogin' => $rs->getInt('forcelogin'),
				'confirmregistration' => $rs->getInt('confirmregistration'),
				'enableregistration' => $rs->getInt('enableregistration'),
				'confirmorder' => $rs->getInt('confirmorder'),
				'confirmorderstatusid' => $rs->getInt('confirmorderstatusid'),
				'guestcheckout' => $rs->getInt('guestcheckout'),
				'minimumordervalue' => $rs->getFloat('minimumordervalue'),
				'apikey' => $rs->getString('apikey'),
				'ordernotifyaddresses' => explode(',', $rs->getString('ordernotifyaddresses'))
			);
			$this->registry->session->setActiveShopOffline($this->layer['offline']);
			$this->registry->session->setActiveShowTax($this->layer['showtax']);
			$this->registry->session->setActiveShopName($this->layer['shopname']);
			if (is_null($this->layer['photoid'])){
				$this->layer['photoid'] = 'logo.png';
			}
			if (is_null($this->layer['favicon'])){
				$this->layer['favicon'] = 'favicon.ico';
			}
			$this->registry->session->setActiveShopCurrencyId($this->layer['idcurrency']);
			$this->registry->session->setActiveForceLogin($this->layer['forcelogin']);
			
			if ($this->registry->session->getActiveBrowserData() == NULL){
				$browser = new Browser();
				$Data = Array(
					'browser' => $browser->getBrowser(),
					'platform' => $browser->getPlatform(),
					'ismobile' => $browser->isMobile(),
					'isbot' => $browser->isRobot()
				);
				$this->registry->session->setActiveBrowserData($Data);
			}
		}
	}

	public function getCurrentLayer ()
	{
		return $this->layer;
	}

	public function getLayerViewId ()
	{
		return (isset($this->layer['idview'])) ? $this->layer['idview'] : 0;
	}

	public function getCurrentNamespace ()
	{
		return (isset($this->layer['namespace'])) ? $this->layer['namespace'] : 'core';
	}

	public function getSystemNamespace ()
	{
		return self::SYSTEM_NAMESPACE;
	}

	public function getNamespaces ()
	{
		if (isset($this->layer['namespace'])){
			return Array(
				$this->layer['namespace'],
				self::SYSTEM_NAMESPACE
			);
		}
		return Array(
			self::SYSTEM_NAMESPACE
		);
	}

	public function registerEvents ()
	{
		if (empty($this->events)){
			$this->loadEvents();
		}
		foreach ($this->events as $key => $event){
			$this->registry->dispatcher->connect($event['name'], array(
				App::getModel($event['model']),
				$event['method']
			));
		
		}
	}

	protected function loadEvents ()
	{
		$sql = 'SELECT * FROM event';
		$rs = $this->registry->db->executeQuery($sql);
		while ($rs->next()){
			$this->events[] = Array(
				'name' => $rs->getString('name'),
				'model' => $rs->getString('model'),
				'method' => $rs->getString('method')
			);
		}
	
	}
}