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

class NewsListBoxModel extends Model
{

	public function _addFieldsContentTypeNewsListBox ($form, $boxContent)
	{
		
		$ct_NewsListBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_NewsListBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		
		$ct_NewsListBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('NewsListBox')));
		
		$ct_NewsListBox->AddChild(new FE_TextField(Array(
			'name' => 'newsCount',
			'label' => 'Maks. liczba newsów',
			'comment' => 'Domyślnie 0 (wyświetla wszystkie newsy)'
		)));
		
	}

	public function _populateFieldsContentTypeNewsListBox (&$populate, $ctValues = Array())
	{
		$populate['ct_NewsListBox']['newsCount'] = '0';
		isset($ctValues['newsCount']) and $populate['ct_NewsListBox']['newsCount'] = $ctValues['newsCount'];
		return $populate;
	}
}