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
 * $Id: suffix.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class SuffixModel extends Model
{

	public function getSuffixTypes ()
	{
		$sql = 'SELECT idsuffixtype AS id, name, symbol FROM suffixtype';
		$rs = $this->registry->db->executeQuery($sql);
		return $rs->getAllRows();
	}

	public function getSuffixTypesForSelect ()
	{
		$res = $this->getSuffixTypes();
		$Data = Array();
		foreach ($res as $value){
			$Data[$value['id']] = $value['symbol'];
		}
		return $Data;
	}

	public function getRulesSuffixTypesForSelect ()
	{
		$res = $this->getSuffixTypes();
		$Data = Array();
		foreach ($res as $value){
			if ($value['symbol'] != '='){
				$Data[$value['id']] = $value['symbol'];
			}
		}
		return $Data;
	}

	public function getPrice ()
	{
		$Data = array(
			'0' => 'Netto',
			'1' => 'Brutto',
			'2' => 'Netto + Brutto'
		);
		return $Data;
	}
}