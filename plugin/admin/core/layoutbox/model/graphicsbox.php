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

class GraphicsBoxModel extends Model
{

	public function _addFieldsContentTypeGraphicsBox ($form, $boxContent)
	{
		
		$ct_GraphicsBox = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'ct_GraphicsBox',
			'label' => $this->registry->core->getMessage('TXT_LAYOUT_BOX_CONTENT_SETTINGS')
		)));
		$ct_GraphicsBox->AddDependency(new FE_Dependency(FE_Dependency::SHOW, $boxContent, new FE_ConditionEquals('GraphicsBox')));
		
		$ct_GraphicsBox->AddChild(new FE_LocalFile(Array(
			'name' => 'image',
			'label' => 'Obraz',
			'file_source' => 'design/_images_frontend/upload/'
		)));
		
		$ct_GraphicsBox->AddChild(new FE_Select(Array(
			'name' => 'align',
			'label' => 'Wyrównanie obrazu',
			'options' => Array(
				new FE_Option('center center', 'Środek'),
				new FE_Option('left center', 'Do lewej'),
				new FE_Option('right center', 'Do prawej')
			)
		)));
		
		$ct_GraphicsBox->AddChild(new FE_TextField(Array(
			'name' => 'url',
			'label' => 'Adres strony po kliknięciu',
			'comment' => 'Linkując wewnątrz sklepu możesz podawać samą nazwę kontrolera np. kontakt, promocje.'
		)));
	}

	public function _populateFieldsContentTypeGraphicsBox (&$populate, $ctValues = Array())
	{
		$populate['ct_GraphicsBox']['align'] = 'center';
		$populate['ct_GraphicsBox']['align'] = 'url';
		$populate['ct_GraphicsBox']['image'] = '';
		isset($ctValues['align']) and $populate['ct_GraphicsBox']['align'] = $ctValues['align'];
		isset($ctValues['url']) and $populate['ct_GraphicsBox']['url'] = $ctValues['url'];
		//FIXME: Ponizsze nalezy uzaleznic od rzeczywistej sciezki do katalogu 'upload'.
		isset($ctValues['image']) and $populate['ct_GraphicsBox']['image'] = Array(
			'file' => substr($ctValues['image'], strlen('design/_images_frontend/upload/'))
		);
		return $populate;
	}
}