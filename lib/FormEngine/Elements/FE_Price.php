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

class FE_Price extends FE_TextField {
	
	public function __construct($attributes) {
		parent::__construct($attributes);
		if (isset($this->_attributes['vat_field']) && ($this->_attributes['vat_field'] instanceof FE_Field)) {
			$this->_attributes['vat_field_name'] = $this->_attributes['vat_field']->GetName();
		}
		$this->_attributes['vat_values'] = App::getModel('vat/vat')->getVATValuesAll();
		$this->_attributes['prefixes'] = Array(
			App::getRegistry()->core->getMessage('TXT_PRICE_NET'),
			App::getRegistry()->core->getMessage('TXT_PRICE_GROSS')
		);
	}
	
	protected function _PrepareAttributes_JS() {
		$attributes = Array(
			$this->_FormatAttribute_JS('name', 'sName'),
			$this->_FormatAttribute_JS('label', 'sLabel'),
			$this->_FormatAttribute_JS('comment', 'sComment'),
			$this->_FormatAttribute_JS('suffix', 'sSuffix'),
			$this->_FormatAttribute_JS('prefixes', 'asPrefixes'),
			$this->_FormatAttribute_JS('error', 'sError'),
			$this->_FormatAttribute_JS('vat_field_name', 'sVatField'),
			$this->_FormatAttribute_JS('vat_values', 'aoVatValues', FE::TYPE_OBJECT),
			$this->_FormatRules_JS(),
			$this->_FormatDependency_JS(),
			$this->_FormatDefaults_JS(),
		);
		return $attributes;
	}
	
}
