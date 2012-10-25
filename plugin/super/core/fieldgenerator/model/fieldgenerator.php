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
 * $Revision: 655 $
 * $Author: gekosale $
 * $Date: 2012-04-24 10:51:44 +0200 (Wt, 24 kwi 2012) $
 * $Id: fieldgenerator.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class FieldGeneratorModel extends Model
{
	
	protected $xml;
	protected $fieldsSpecifier;
	protected $nextFieldId = 0;
	protected $container;
	protected $currentContainer;
	protected $layoutBoxSelector;

	public function LoadSchemeFields ($layoutBoxSelector = '.layout-box', $fieldsSpecifier = null)
	{
		$this->layoutBoxSelector = $layoutBoxSelector;
		$this->fieldsSpecifier = $fieldsSpecifier;
		$xmlParser = new xmlParser();
		$this->xml = $xmlParser->parseFast('config/scheme_fields.xml');
		return $this;
	}

	public function AddFields (FE_Container $container)
	{
		$this->container = $container;
		$this->currentContainer = $container;
		$this->walk(Array(
			$this,
			'addField'
		), Array(
			$this,
			'addFieldset'
		));
		return $this;
	}

	protected function addField ($item)
	{
		$funcName = 'addField' . $this->getFieldTypeSuffix($item);
		if (! is_callable(Array(
			$this,
			$funcName
		))){
			//throw new CoreException("Unknown field type: '{$item['type']}'.");
			return;
		}
		call_user_func(Array(
			$this,
			$funcName
		), $item);
	}

	protected function addFieldset ($item)
	{
		$this->currentContainer = $this->container->AddChild(new FE_Fieldset(Array(
			'name' => (string) $item['name'],
			'label' => (string) $item['label']
		)));
		if ((string) $item['name'] == 'layoutbox'){
			$this->addFieldPreview();
		}
	}

	/*
		* ADD FIELD DLA ROZNYCH TYPOW POL
		*/
	
	protected function addFieldPreview ()
	{
		$this->currentContainer->AddChild(new FE_LayoutBoxSchemePreview(Array(
			'triggers' => $this->GetFieldNames('layoutbox'),
			'stylesheets' => Array(
				DESIGNPATH . '_css_frontend/core/static.css',
				DESIGNPATH . '_css_frontend/core/scheme.css'
			),
			'layout_box_tpl' => ROOTPATH . 'design/_tpl/frontend/core/layoutbox.tpl'
		)));
	}

	protected function addFieldBorder ($item)
	{
		$this->currentContainer->AddChild(new FE_Border($this->getFieldAttributes($item) + Array()));
	}

	protected function addFieldBorderRadius ($item)
	{
		$this->currentContainer->AddChild(new FE_Select($this->getFieldAttributes($item) + Array(
			'css_attribute' => 'border-radius',
			'options' => Array(
				new FE_Option('0', 'brak'),
				new FE_Option('3px', '3 piksele'),
				new FE_Option('4px', '4 piksele'),
				new FE_Option('5px', '5 pikseli'),
				new FE_Option('6px', '6 pikseli'),
				new FE_Option('7px', '7 pikseli')
			)
		)));
	}

	protected function addFieldTextAlign ($item)
	{
		$this->currentContainer->AddChild(new FE_Select($this->getFieldAttributes($item) + Array(
			'css_attribute' => 'text-align',
			'options' => Array(
				new FE_Option('left', 'Do lewej'),
				new FE_Option('center', 'Do środka'),
				new FE_Option('right', 'Do prawej'),
				new FE_Option('justify', 'Wyjustowanie')
			)
		)));
	}

	protected function addFieldBackground ($item)
	{
		$this->currentContainer->AddChild(new FE_ColourSchemePicker($this->getFieldAttributes($item) + Array(
			'gradient_height' => (string) $item->height,
			'file_source' => 'design/_images_frontend/upload/',
			'type_colour' => (boolean) $item->type['colour'],
			'type_gradient' => (boolean) $item->type['gradient'],
			'type_image' => (boolean) $item->type['image']
		)));
	}

	protected function addFieldFont ($item)
	{
		$this->currentContainer->AddChild(new FE_FontStyle($this->getFieldAttributes($item) + Array()));
	}

	protected function addFieldIcon ($item)
	{
		$this->currentContainer->AddChild(new FE_LocalFile($this->getFieldAttributes($item) + Array(
			'file_source' => 'design/_images_frontend/upload/'
		)));
	}

	protected function addFieldLineHeight ($item)
	{
		if ((string) $item->mode == 'select'){
			$this->currentContainer->AddChild(new FE_Select($this->getFieldAttributes($item) + Array(
				'css_attribute' => 'line-height',
				'options' => Array(
					new FE_Option('1.0em', '100%'),
					new FE_Option('1.1em', '110%'),
					new FE_Option('1.2em', '120%'),
					new FE_Option('1.3em', '130%'),
					new FE_Option('1.4em', '140%'),
					new FE_Option('1.5em', '150%'),
					new FE_Option('1.6em', '160%'),
					new FE_Option('1.7em', '170%')
				)
			)));
		}
		else{
			$this->currentContainer->AddChild(new FE_TextField($this->getFieldAttributes($item) + Array(
				'suffix' => 'px',
				'size' => FE_TextField::SIZE_SHORT,
				'css_attribute' => 'line-height'
			)));
		}
	}

	protected function addFieldMarginBottom ($item)
	{
		if ((string) $item->mode == 'select'){
			$this->currentContainer->AddChild(new FE_Select($this->getFieldAttributes($item) + Array(
				'css_attribute' => 'margin-bottom',
				'options' => Array(
					new FE_Option('0', 'brak'),
					new FE_Option('2px', '2px'),
					new FE_Option('5px', '5px'),
					new FE_Option('10px', '10px'),
					new FE_Option('12px', '12px'),
					new FE_Option('15px', '15px')
				)
			)));
		}
		else{
			$this->currentContainer->AddChild(new FE_TextField($this->getFieldAttributes($item) + Array(
				'size' => FE_TextField::SIZE_SHORT,
				'css_attribute' => 'margin-bottom'
			)));
		}
	}

	protected function addFieldWidth ($item)
	{
		$this->currentContainer->AddChild(new FE_TextField($this->getFieldAttributes($item) + Array(
			'suffix' => 'px',
			'size' => FE_TextField::SIZE_SHORT,
			'css_attribute' => 'width'
		)));
	}

	protected function addFieldHeight ($item)
	{
		$this->currentContainer->AddChild(new FE_TextField($this->getFieldAttributes($item) + Array(
			'suffix' => 'px',
			'size' => FE_TextField::SIZE_SHORT,
			'css_attribute' => 'height'
		)));
	}

	public function GetFieldNames ($fieldsSpecifier = '')
	{
		$oldSpecifier = $this->fieldsSpecifier;
		if ($fieldsSpecifier != ''){
			$this->fieldsSpecifier = $fieldsSpecifier;
		}
		$names = array_values($this->walk(Array(
			$this,
			'getFieldName'
		)));
		$this->fieldsSpecifier = $oldSpecifier;
		return $names;
	}

	public function GetDefaultValues ()
	{
		return $this->walk(Array(
			$this,
			'getDefaultValue'
		));
	}

	protected function getDefaultValue ($node)
	{
		if (count($node->{'default'}->children()) < 1){
			return (string) ($node->{'default'});
		}
		$value = $this->getAsArray($node->{'default'});
		return $value['default'];
	}

	protected function getAsArray ($node)
	{
		if (count($node->children())){
			$array = Array();
			foreach ($node as $child){
				$array[$child->getName()] = $this->getAsArray($child);
			}
			return $array;
		}
		return (string) $node;
	}

	protected function walk ($callbackForField, $callbackForFieldset = null)
	{
		$node = $this->xml;
		if ($this->fieldsSpecifier != ''){
			$node = array_pop($node->xpath("fieldset[@name='{$this->fieldsSpecifier}']"))->children();
		}
		return $this->walkSubtree($node, $callbackForField, $callbackForFieldset);
	}

	protected function walkSubtree ($node, $callbackForField, $callbackForFieldset = null)
	{
		$array = Array();
		foreach ($node as $item){
			if ($item->getName() == 'fieldset'){
				if (is_callable($callbackForFieldset)){
					$array[(string) $item['name']] = call_user_func($callbackForFieldset, $item);
					$this->walkSubtree($item, $callbackForField, $callbackForFieldset);
				}
				else{
					$array[(string) $item['name']] = $this->walkSubtree($item, $callbackForField, $callbackForFieldset);
				}
			}
			elseif ($item->getName() == 'field'){
				$array[$this->getFieldName($item)] = call_user_func($callbackForField, $item);
			}
		}
		return $array;
	}

	protected function getFieldAttributes ($item)
	{
		return Array(
			'name' => $this->getFieldName($item),
			'label' => $this->getFieldLabel($item),
			'comment' => $this->getFieldComment($item),
			'selector' => $this->getFieldSelector($item)
		);
	}

	protected function getFieldName ($item)
	{
		if (empty($item['name'])){
			$item->addAttribute('name', 'auto_field_' . ($this->nextFieldId ++));
		}
		$prefix = $item['name'];
		return $prefix . '_' . $item['type'];
	}

	protected function getFieldSelector ($item)
	{
		return str_replace('<layout-box/>', $this->layoutBoxSelector, substr($item->selector->asXML(), 2 + strlen('selector'), - 3 - strlen('selector')));
	}

	protected function getFieldTypeSuffix ($item)
	{
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $item['type'])));
	}

	protected function getFieldLabel ($item)
	{
		return $item->label;
	}

	protected function getFieldComment ($item)
	{
		return $item->comment;
	}

	public function PopulateFormWithValues ($form, $cssValues, $selectorFunc = null)
	{
		
		$populate = Array();
		
		foreach ($form->fields as $fieldName => $field){
			
			if (! is_object($field) or $field->selector == null){
				continue;
			}
			
			$fieldType = substr(strrchr($fieldName, '_'), 1);
			if (is_callable($selectorFunc)){
				$selector = call_user_func($selectorFunc, (string) $field->selector);
			}
			else{
				$selector = (string) $field->selector;
			}
			
			if (! isset($cssValues[$selector][$fieldType])){
				continue;
			}
			
			$value = $cssValues[$selector][$fieldType];
			
			switch ($fieldType) {
				
				case 'border':
					$sides = Array(
						'top',
						'right',
						'bottom',
						'left'
					);
					foreach ($sides as $side){
						isset($value[$side]['size']) and $populate[$field->parent->name][$fieldName][$side]['size'] = $value[$side]['size'];
						isset($value[$side]['colour']) and $populate[$field->parent->name][$fieldName][$side]['colour'] = $value[$side]['colour'];
					}
					break;
				
				case 'font':
					$parameters = Array(
						'family',
						'colour',
						'bold',
						'italic',
						'underline',
						'uppercase',
						'size'
					);
					foreach ($parameters as $parameter){
						isset($value[$parameter]) and $populate[$field->parent->name][$fieldName][$parameter] = $value[$parameter];
					}
					break;
				
				case 'background':
					if (isset($value['type'])){
						isset($value['type']) and $populate[$field->parent->name][$fieldName]['type'] = $value['type'];
						switch ($value['type']) {
							
							case 1: // Single colour
								isset($value['start']) and $populate[$field->parent->name][$fieldName]['start'] = $value['start'];
								break;
							
							case 2: // Gradient
								isset($value['start']) and $populate[$field->parent->name][$fieldName]['start'] = $value['start'];
								isset($value['end']) and $populate[$field->parent->name][$fieldName]['end'] = $value['end'];
								break;
							
							case 3: // Picture
								isset($value['start']) and $populate[$field->parent->name][$fieldName]['start'] = $value['start'];
								isset($value['file']) and $populate[$field->parent->name][$fieldName]['file'] = $value['file'];
								isset($value['position']) and $populate[$field->parent->name][$fieldName]['position'] = $value['position'];
								isset($value['repeat']) and $populate[$field->parent->name][$fieldName]['repeat'] = $value['repeat'];
								break;
						
						}
					}
					break;
				
				case 'icon':
					isset($value['file']) and $populate[$field->parent->name][$fieldName]['file'] = $value['file'];
					break;
				
				default:
					isset($value['value']) and $populate[$field->parent->name][$fieldName] = $value['value'];
					break;
			}
		
		}
		
		return $populate;
	
	}

	public function SaveCSSValues ($id, $submitedData, $getSelector, $saveAttribute, $saveValue, $saveComplexValue)
	{
		
		foreach ($submitedData as $fieldName => $field){
			if (is_string($field) || ! isset($field['selector']) || empty($field['selector'])){
				// Field doesn't carry any information important from the stylesheet's point of view
				continue;
			}
			
			if (is_callable($getSelector)){
				$selector = call_user_func($getSelector, $field['selector']);
			}
			else{
				$selector = $field['selector'];
			}
			
			$fieldType = substr(strrchr($fieldName, '_'), 1);
			
			$idNewCssAttribute = call_user_func($saveAttribute, $id, $fieldType, $selector);
			
			switch ($fieldType) {
				
				case 'border':
					$sides = Array(
						'top',
						'right',
						'bottom',
						'left'
					);
					foreach ($sides as $side){
						isset($field[$side]['size']) && call_user_func($saveComplexValue, $id, $idNewCssAttribute, $side, 'size', $field[$side]['size']);
						isset($field[$side]['colour']) && call_user_func($saveComplexValue, $id, $idNewCssAttribute, $side, 'colour', $field[$side]['colour']);
					}
					break;
				
				case 'border-radius':
					$sides = Array(
						'top-right',
						'top-left',
						'bottom-right',
						'bottom-left'
					);
					if (isset($field['value'])){ // To allow artificial, separate corner definitions without adding aditional selects to the form
						call_user_func($saveValue, $id, $idNewCssAttribute, 'value', $field['value']);
						foreach ($sides as $side){
							$field[$side]['value'] = $field['value'];
						}
					}
					foreach ($sides as $side){
						isset($field[$side]['value']) && call_user_func($saveComplexValue, $id, $idNewCssAttribute, $side, 'value', $field[$side]['value']);
					}
					break;
				
				case 'font':
					$parameters = Array(
						'family',
						'colour',
						'bold',
						'italic',
						'underline',
						'uppercase',
						'size'
					);
					foreach ($parameters as $parameter){
						isset($field[$parameter]) && call_user_func($saveValue, $id, $idNewCssAttribute, $parameter, $field[$parameter]);
					}
					break;
				
				case 'icon':
					isset($field['file']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'file', $field['file']);
					break;
				
				case 'background':
					if (isset($field['type'])){
						call_user_func($saveValue, $id, $idNewCssAttribute, 'type', $field['type']);
						switch ($field['type']) {
							
							case 1: // Single colour
								isset($field['start']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'start', $field['start']);
								break;
							
							case 2: // Gradient
								isset($field['start']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'start', $field['start']);
								isset($field['end']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'end', $field['end']);
								isset($field['gradient_height']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'gradient_height', $field['gradient_height']);
								call_user_func($saveValue, $id, $idNewCssAttribute, 'position', '0 0');
								call_user_func($saveValue, $id, $idNewCssAttribute, 'repeat', 'repeat-x');
								break;
							
							case 3: // Picture
								isset($field['start']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'start', $field['start']);
								isset($field['file']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'file', $field['file']);
								isset($field['position']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'position', $field['position']);
								isset($field['repeat']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'repeat', $field['repeat']);
								break;
						
						}
					}
					break;
				
				default:
					isset($field['value']) && call_user_func($saveValue, $id, $idNewCssAttribute, 'value', $field['value']);
					break;
			}
		}
		
		return true;
	
	}

}