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
 * $Id: controller.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class controllerModel extends Model
{

	public function getControllerSimpleList ()
	{
		$rs = $this->registry->db->executeQuery('SELECT idcontroller AS id, name FROM controller WHERE mode = 1');
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name')
			);
		}
		return $Data;
	}

	public function getControllerSimpleListToSelect ()
	{
		$Data = $this->getControllerSimpleList();
		$tmp = Array();
		foreach ($Data as $key){
			$tmp[$Data['id']] = $Data['name'];
		}
		return $tmp;
	}

	public function getControllers ()
	{
		$rs = $this->registry->db->executeQuery('SELECT idcontroller AS id, name, 
				enable, version, description FROM controller WHERE mode = 1');
		$Data = Array();
		while ($rs->next()){
			$Data[] = Array(
				'id' => $rs->getInt('id'),
				'name' => $rs->getString('name'),
				'version' => $rs->getString('version'),
				'enable' => $rs->getInt('enable'),
				'description' => $rs->getString('description')
			);
		}
		return $Data;
	}
}