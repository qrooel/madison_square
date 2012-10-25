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

class ProducerListBoxModel extends Model
{

	public function _addFieldsContentTypeProducerListBox ($form, $boxContent)
	{
		
		$ct_ProducerListBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_ProducerListBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		$ct_ProducerListBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('ProducerListBox')));
		
		$ct_ProducerListBox->AddChild(new FE_TextField(Array(
			'name' => 'productsCount',
			'label' => 'Maks. liczba produktów na stronę',
			'comment' => 'Domyślnie 0 (wyświetla wszystkie produkty)'
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Checkbox(Array(
			'name' => 'pagination',
			'label' => $this->registry->core->getMessage('TXT_PAGINATION')
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Checkbox(Array(
			'name' => 'showphoto',
			'label' => $this->registry->core->getMessage('TXT_SHOW_PRODUCER_PHOTO')
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Checkbox(Array(
			'name' => 'showdescription',
			'label' => $this->registry->core->getMessage('TXT_SHOW_PRODUCER_DESCRIPTION')
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Select(Array(
			'name' => 'view',
			'label' => 'Domyślny widok',
			'options' => Array(
				new FE_Option('0', 'Siatka'),
				new FE_Option('1', 'Lista')
			)
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Select(Array(
			'name' => 'orderBy',
			'label' => 'Domyślne sortowanie',
			'options' => Array(
				new FE_Option('id', 'ID produktu'),
				new FE_Option('name', 'Nazwa'),
				new FE_Option('price', 'Cena'),
				new FE_Option('rating', 'Ocena klientów'),
				new FE_Option('opinions', 'Ilość recenzji'),
				new FE_Option('adddate', 'Data dodania')
			)
		)));
		
		$ct_ProducerListBox->AddChild(new FE_Select(Array(
			'name' => 'orderDir',
			'label' => 'Kolejność sortowania',
			'options' => Array(
				new FE_Option('asc', 'Rosnąco'),
				new FE_Option('desc', 'Malejąco')
			)
		)));
	
	}

	public function _populateFieldsContentTypeProducerListBox (&$populate, $ctValues = Array())
	{
		$populate['ct_ProducerListBox']['productsCount'] = '0';
		$populate['ct_ProducerListBox']['pagination'] = false;
		$populate['ct_ProducerListBox']['showphoto'] = true;
		$populate['ct_ProducerListBox']['showdescription'] = true;
		$populate['ct_ProducerListBox']['view'] = '0';
		$populate['ct_ProducerListBox']['orderBy'] = 'id';
		$populate['ct_ProducerListBox']['orderDir'] = 'asc';
		isset($ctValues['productsCount']) and $populate['ct_ProducerListBox']['productsCount'] = $ctValues['productsCount'];
		isset($ctValues['pagination']) and $populate['ct_ProducerListBox']['pagination'] = (bool) $ctValues['pagination'];
		isset($ctValues['showphoto']) and $populate['ct_ProducerListBox']['showphoto'] = (bool) $ctValues['showphoto'];
		isset($ctValues['showdescription']) and $populate['ct_ProducerListBox']['showdescription'] = (bool) $ctValues['showdescription'];
		isset($ctValues['view']) and $populate['ct_ProducerListBox']['view'] = $ctValues['view'];
		isset($ctValues['orderBy']) and $populate['ct_ProducerListBox']['orderBy'] = $ctValues['orderBy'];
		isset($ctValues['orderDir']) and $populate['ct_ProducerListBox']['orderDir'] = $ctValues['orderDir'];
		return $populate;
	}
}