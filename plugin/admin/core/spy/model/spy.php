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
 * $Id: spy.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SpyModel extends ModelWithDatagrid
{

	public function __construct ($registry, $modelFile)
	{
		parent::__construct($registry, $modelFile);
	}

	protected function initDatagrid ($datagrid)
	{
		$datagrid->setTableData('sessionhandler', Array(
			'id' => Array(
				'source' => 'SH.idsessionhandler'
			),
			'sessionid' => Array(
				'source' => 'SH.sessionid'
			),
			'client' => Array(
				'source' => 'CONCAT(AES_DECRYPT(CD.firstname,:encryptionkey),\' \',AES_DECRYPT(CD.surname,:encryptionkey))'
			),
			'client_session' => Array(
				'source' => 'SH.sessionid'
			),
			'ipaddress' => Array(
				'source' => 'SH.ipaddress'
			),
			'lastaddress' => Array(
				'source' => 'SH.url'
			),
			'client_status' => Array(
				'source' => 'IF( SH.expiredate >= NOW(), \'TXT_ACTIVE\', \'TXT_INACTIVE\')',
				'prepareForSelect' => true,
				'processLanguage' => true
			),
			'cart' => Array(
				'source' => 'CONCAT(SH.globalprice,\' \',SH.cartcurrency)'
			),
			'browser' => Array(
				'source' => 'SH.browser',
				'prepareForSelect' => true
			),
			'platform' => Array(
				'source' => 'SH.platform',
				'prepareForSelect' => true
			),
			'isbot' => Array(
				'source' => 'IF( SH.isbot = 1, \'TXT_YES\', \'TXT_NO\')',
				'prepareForSelect' => true,
				'processLanguage' => true
			),
			'ismobile' => Array(
				'source' => 'IF( SH.ismobile = 1, \'TXT_YES\', \'TXT_NO\')',
				'prepareForSelect' => true,
				'processLanguage' => true
			)
		));
		$datagrid->setFrom('
			sessionhandler SH
			LEFT JOIN clientdata CD ON CD.clientid = SH.clientid
		');
		
		$datagrid->setGroupBy('
			SH.sessionid
		');
		
		$datagrid->setAdditionalWhere('
			SH.viewid IN (:viewids) 
		');
	}

	public function getDatagridFilterData ()
	{
		return $this->getDatagrid()->getFilterData();
	}

	public function getSpyForAjax ($request, $processFunction)
	{
		return $this->getDatagrid()->getData($request, $processFunction);
	}

	public function utf16_2_utf8 ($nowytekst)
	{
		$nowytekst = str_replace('u0104', 'Ą', $nowytekst); //Ą
		$nowytekst = str_replace('u0106', 'Ć', $nowytekst); //Ć
		$nowytekst = str_replace('u0118', 'Ę', $nowytekst); //Ę
		$nowytekst = str_replace('u0141', 'Ł', $nowytekst); //Ł
		$nowytekst = str_replace('u0143', 'Ń', $nowytekst); //Ń
		$nowytekst = str_replace('u00D3', 'Ó', $nowytekst); //Ó
		$nowytekst = str_replace('u015A', 'Ś', $nowytekst); //Ś
		$nowytekst = str_replace('u0179', 'Ź', $nowytekst); //Ź
		$nowytekst = str_replace('u017B', 'Ż', $nowytekst); //Ż
		

		$nowytekst = str_replace('u0105', 'ą', $nowytekst); //ą
		$nowytekst = str_replace('u0107', 'ć', $nowytekst); //ć
		$nowytekst = str_replace('u0119', 'ę', $nowytekst); //ę
		$nowytekst = str_replace('u0142', 'ł', $nowytekst); //ł
		$nowytekst = str_replace('u0144', 'ń', $nowytekst); //ń
		$nowytekst = str_replace('u00F3', 'ó', $nowytekst); //ó
		$nowytekst = str_replace('u015B', 'ś', $nowytekst); //ś
		$nowytekst = str_replace('u017A', 'ź', $nowytekst); //ź
		$nowytekst = str_replace('u017C', 'ż', $nowytekst); //ż
		$nowytekst = str_replace('u017c', 'ż', $nowytekst); //ż
		return ($nowytekst);
	}

	public function getSessionData ($id)
	{
		$sql = 'SELECT clientid, cart FROM sessionhandler WHERE sessionid = :id';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('id', $id);
		$rs = $stmt->executeQuery();
		$Data = Array();
		if ($rs->first()){
			$Data = Array(
				'clientid' => $rs->getInt('clientid'),
				'cart' => json_decode($rs->getString('cart'), true)
			);
		}
		if (isset($Data['cart'])){
			foreach ($Data['cart'] as $key => $product){
				$Data['cart'][$key]['name'] = $this->utf16_2_utf8($product['name']);
			}
		}
		return $Data;
	
	}

}