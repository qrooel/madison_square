<?php defined('ROOTPATH') OR die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2009-2011 Gekosale
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU General Public License Version 3, 29 June 2007 as published by the Free Software
 * Foundation (http://opensource.org/licenses/gpl-3.0.html).
 * If you did not receive a copy of the license and are unable to obtain it through the 
 * world-wide-web, please send an email to license@verison.pl so we can send you a copy immediately.
 */

class FE_ListItem {
	
	public $value;
	public $label;
	
	public function __construct($label, $value) {
		$this->value = $value;
		$this->label = $label;
	}
	
	public static function Make($array, $default = '') {
		$result = Array();
		if ($default and is_array($default)) {
			$result[] = new FE_ListItem($default[0], '');
		}
		foreach ($array as $key => $value) {
			$result[] = new FE_ListItem($key, $value);
		}
		return $result;
	}
	
}
