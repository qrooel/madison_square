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
 * $Id: tagsbox.php 438 2011-08-27 09:29:36Z gekosale $
 */

class MostSearchedBoxModel extends Model
{

	public function getAllMostSearched ()
	{
		$sql = "SELECT 
					idmostsearch,
					name, 
					textcount
				FROM mostsearch
				WHERE viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idmostsearch' => $rs->getInt('idmostsearch'),
				'name' => $rs->getString('name'),
				'phrase' => base64_encode($rs->getString('name')),
				'textcount' => $rs->getInt('textcount'),
				'percentage' => 0
			);
		}
		return $Data;
	}

}