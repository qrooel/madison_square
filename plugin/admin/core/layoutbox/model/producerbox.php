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

class ProducerBoxModel extends Model
{

	public function _addFieldsContentTypeProducerBox ($form, $boxContent)
	{
		$ct_ProducerBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_ProducerBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));

		$ct_ProducerBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('ProducerBox')));
		
		$ct_ProducerBox->AddChild(new FE_Select(Array(
			'name' => 'view',
			'label' => 'Domyślny widok',
			'options' => Array(
				new FE_Option('0', 'Lista'),
				new FE_Option('1', 'Select')
			)
		)));
		
		$ct_ProducerBox->AddChild(new FE_MultiSelect(Array(
			'name' => 'producers',
			'label' => $this->registry->core->getMessage('TXT_AVAILABLE_PRODUCERS'),
			'options' => FE_Option::Make(App::getModel('producer')->getProducerToSelect())
		)));
	}

	public function _populateFieldsContentTypeProducerBox (&$populate, $ctValues = Array())
	{
		$populate['ct_ProducerBox']['view'] = '0';
		$populate['ct_ProducerBox']['producers'] = Array();
		isset($ctValues['view']) and $populate['ct_ProducerBox']['view'] = $ctValues['view'];
		isset($ctValues['producers']) and $populate['ct_ProducerBox']['producers'] = explode(',', $ctValues['producers']);
		return $populate;
	}
}