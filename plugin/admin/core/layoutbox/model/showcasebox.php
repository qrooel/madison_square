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

class ShowcaseBoxModel extends Model
{

	public function _addFieldsContentTypeShowcaseBox ($form, $boxContent)
	{
		
		$ct_ShowcaseBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_ShowcaseBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		$ct_ShowcaseBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('ShowcaseBox')));
		
		$ct_ShowcaseBox->AddChild(new FE_TextField(Array(
			'name' => 'productsCount',
			'label' => 'Maks. liczba produktów',
			'comment' => 'Domyślnie 0 (bez ograniczenia)'
		)));
		
		$ct_ShowcaseBoxOrderBy = $ct_ShowcaseBox->AddChild(new FE_Select(Array(
			'name' => 'ct_ShowcaseBox_orderBy',
			'label' => 'Domyślne sortowanie',
			'options' => Array(
				new FE_Option('id', 'ID produktu'),
				new FE_Option('name', 'Nazwa'),
				new FE_Option('price', 'Cena'),
				new FE_Option('rating', 'Ocena klientów'),
				new FE_Option('opinions', 'Ilość recenzji'),
				new FE_Option('adddate', 'Data dodania'),
				new FE_Option('random', 'Losowo')
			)
		)));
		
		$ct_ShowcaseBox->AddChild(new FE_Select(Array(
			'name' => 'ct_ShowcaseBox_orderDir',
			'label' => 'Kolejność sortowania',
			'options' => Array(
				new FE_Option('asc', 'Rosnąco'),
				new FE_Option('desc', 'Malejąco')
			),
			'dependencies' => Array(
				new FE_Dependency(FE_Dependency::SHOW, $ct_ShowcaseBoxOrderBy, new FE_ConditionNot(new FE_ConditionEquals('random')))
			)
		)));
		
		$ct_ShowcaseBox->AddChild(new FE_Select(Array(
			'name' => 'statusId',
			'label' => 'Status',
			'comment' => 'Będą wyświetlone tylko produkty o tych statusach',
			'options' => FE_Option::Make(App::getModel('productstatus')->getProductstatusAll(false))
		)));
	
	}

	public function _populateFieldsContentTypeShowcaseBox (&$populate, $ctValues = Array())
	{
		$populate['ct_ShowcaseBox']['productsCount'] = '0';
		$populate['ct_ShowcaseBox']['view'] = '0';
		$populate['ct_ShowcaseBox']['ct_ShowcaseBox_orderBy'] = 'id';
		$populate['ct_ShowcaseBox']['ct_ShowcaseBox_orderDir'] = 'asc';
		$populate['ct_ShowcaseBox']['statusId'] = '0';
		isset($ctValues['productsCount']) and $populate['ct_ShowcaseBox']['productsCount'] = $ctValues['productsCount'];
		isset($ctValues['view']) and $populate['ct_ShowcaseBox']['view'] = $ctValues['view'];
		isset($ctValues['orderBy']) and $populate['ct_ShowcaseBox']['ct_ShowcaseBox_orderBy'] = $ctValues['orderBy'];
		isset($ctValues['orderDir']) and $populate['ct_ShowcaseBox']['ct_ShowcaseBox_orderDir'] = $ctValues['orderDir'];
		isset($ctValues['statusId']) and $populate['ct_ShowcaseBox']['statusId'] = $ctValues['statusId'];
		return $populate;
	}
}