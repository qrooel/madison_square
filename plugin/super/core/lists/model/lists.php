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
 * $Id: lists.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class listsModel extends Model
{

	// PODATEK VAT //
	public function getVAT ()
	{
		$sql = 'SELECT V.idvat as idvat, V.`value` as vatval
					FROM vat V';
		$results = $this->registry->db->executeQuery($sql);
		return $results->getAllRows();
	}

	public function getVATForSelect ()
	{
		$results = $this->getVAT();
		$Data = Array();
		
		foreach ($results as $value){
			$Data[$value['idvat']] = $this->registry->core->getMessage($value['vatval']);
		}
		return $Data;
	}

	public function getCountries ()
	{
		$sql = 'SELECT 
					C.idcountry as countryid, 
					C.name
				FROM country C';
		$results = $this->registry->db->executeQuery($sql);
		return $results->getAllRows();
	}

	public function getCountryForSelect ()
	{
		$results = $this->getCountries();
		$Data = Array();
		
		foreach ($results as $value){
			$Data[$value['countryid']] = $value['name'];
		}
		return $Data;
	}

}