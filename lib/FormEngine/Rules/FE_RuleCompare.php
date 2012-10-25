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
class FE_RuleCompare extends FE_Rule {
	
	protected $_compareWith;
	
	public function __construct($errorMsg, FE_Field $compareWith) {
		parent::__construct($errorMsg);
		$this->_compareWith = $compareWith;
	}
	
	protected function _Check($value) {
		return ($value == $this->_compareWith->GetValue());
	}
	
	public function Render() {
		$errorMsg = addslashes($this->_errorMsg);
		$field = addslashes($this->_compareWith->getName());
		return "{sType: '{$this->GetType()}', sErrorMessage: '{$errorMsg}', sFieldToCompare: '{$field}'}";
	}
	
}