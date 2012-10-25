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

class SitemapBoxModel extends Model
{

	public function _addFieldsContentTypeSitemapBox ($form, $boxContent)
	{
		
		$ct_SitemapBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_SitemapBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		$ct_SitemapBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('SitemapBox')));
		
		$ct_SitemapBox->AddChild(new FE_TextField(Array(
			'name' => 'categoryTreeLevels',
			'label' => 'Maks. liczba poziomów kategorii',
			'comment' => 'Domyślnie 0 (bez ograniczenia)'
		)));
	
	}

	public function _populateFieldsContentTypeSitemapBox (&$populate, $ctValues = Array())
	{
		$populate['ct_SitemapBox']['categoryTreeLevels'] = '0';
		isset($ctValues['categoryTreeLevels']) and $populate['ct_SitemapBox']['categoryTreeLevels'] = $ctValues['categoryTreeLevels'];
		return $populate;
	}
}