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
 * $Id: tagsbox.php 655 2012-04-24 08:51:44Z gekosale $
 */

class TagsBoxModel extends Model
{

	public function getAllTags ()
	{
		$sql = "SELECT name, textcount, idtags, viewid 
				FROM tags
				WHERE viewid = :viewid";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'idtags' => $rs->getInt('idtags'),
				'name' => $rs->getString('name'),
				'textcount' => $rs->getInt('textcount'),
				'viewid' => $rs->getInt('viewid'),
				'percentage' => 0
			);
		}
		return $Data;
	}

}