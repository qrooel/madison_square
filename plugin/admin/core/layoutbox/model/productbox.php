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
 * $Revision: 520 $
 * $Author: gekosale $
 * $Date: 2011-09-08 13:37:54 +0200 (Cz, 08 wrz 2011) $
 * $Id: layoutbox.php 520 2011-09-08 11:37:54Z gekosale $ 
 */

class ProductBoxModel extends Model
{

	public function _addFieldsContentTypeProductBox ($form, $boxContent)
	{
		
		$ct_GraphicsBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_ProductBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		$ct_GraphicsBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('ProductBox')));
		
		$ct_GraphicsBox->AddChild(new FE_Select(Array(
			'name' => 'tabbed',
			'label' => 'Używaj zakładek w karcie produktu',
			'options' => Array(
				new FE_Option(1, 'Tak'),
				new FE_Option(0, 'Nie')
			)
		)));
	}

	public function _populateFieldsContentTypeProductBox (&$populate, $ctValues = Array())
	{
		$populate['ct_ProductBox']['tabbed'] = '1';
		isset($ctValues['tabbed']) and $populate['ct_ProductBox']['tabbed'] = $ctValues['tabbed'];
		return $populate;
	}
}