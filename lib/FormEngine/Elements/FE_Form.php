<?php
defined('ROOTPATH') or die('No direct access allowed.');
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

class FE_Form extends FE_Container
{
	
	const FORMAT_GROUPED = 0;
	const FORMAT_FLAT = 1;
	const TABS_VERTICAL = 0;
	const TABS_HORIZONTAL = 1;
	
	public $fields;
	
	protected $_values;
	protected $_globalvalues;
	protected $_flags;
	protected $_populatingWholeForm;

	public function __construct ($attributes)
	{
		parent::__construct($attributes);
		$this->_populatingWholeForm = false;
		$this->fields = Array();
		$this->_values = Array();
		$this->_globalvalues = Array();
		$this->_flags = Array();
		$this->form = $this;
		
		if (! isset($this->_attributes['class'])){
			$this->_attributes['class'] = '';
		}
		if (! isset($this->_attributes['tabs'])){
			$this->_attributes['tabs'] = self::TABS_VERTICAL;
		}
	}

	public function Render_JS ()
	{
		if (is_object(App::getRegistry()->router) && App::getRegistry()->router->getMode() == 0 && ! in_array(App::getRegistry()->router->getCurrentController(), Array(
			'login',
			'forgotlogin'
		))){
			$render = '<script type="text/javascript" src="' . DESIGNPATH . '_js_frontend/core/gform.js"></script>';
		}
		else{
			$render = '';
		}
		$render .= "
			<form id=\"{$this->_attributes['name']}\" method=\"{$this->_attributes['method']}\" action=\"{$this->_attributes['action']}\">
				<input type=\"hidden\" name=\"{$this->_attributes['name']}_submitted\" value=\"1\"/>
			</form>
			<script type=\"text/javascript\">
				/*<![CDATA[*/
					GCore.OnLoad(function() {
						$('#{$this->_attributes['name']}').GForm({
							sFormName: '{$this->_attributes['name']}',
							sClass: '{$this->_attributes['class']}',
							iTabs: " . (($this->_attributes['tabs'] == self::TABS_VERTICAL) ? 'GForm.TABS_VERTICAL' : 'GForm.TABS_HORIZONTAL') . ",
							aoFields: [
								{$this->_RenderChildren()}
							],
							oValues: " . json_encode($this->GetValues()) . ",
							oErrors: " . json_encode($this->GetErrors()) . "
						});
					});
				/*]]>*/
			</script>
		";
		return $render;
	}

	public function Render_Static ()
	{
	}

	public function getSubmitValues ($flags = 0)
	{
		return $this->GetValues($flags);
	}

	public function getElementValue ($element)
	{
		return $this->GetValue($element);
	}

	public function GetValues ($flags = 0)
	{
		if ($flags & FE_Form::FORMAT_FLAT){
			$values = Array();
			foreach ($this->fields as $field){
				if (is_array($field)){
					foreach ($field as $fieldInstance){
						if ($fieldInstance instanceof FE_Field){
							$values = array_merge_recursive($values, Array(
								$fieldInstance->GetName() => $fieldInstance->GetValue()
							));
						}
					}
				}
				else 
					if ($field instanceof FE_Field){
						$values = array_merge_recursive($values, Array(
							$field->GetName() => $field->GetValue()
						));
					}
			}
			return $values;
		}
		else{
			return $this->_Harvest(Array(
				$this,
				'_HarvestValues'
			));
		}
		return Array();
	}

	public function GetErrors ()
	{
		return $this->_Harvest(Array(
			$this,
			'_HarvestErrors'
		));
	}

	public function GetValue ($element)
	{
		foreach ($this->fields as $field){
			if (($field instanceof FE_Field) and ($field->GetName() == $element)){
				return $field->GetValue();
			}
		}
	}

	public function GetFlags ()
	{
		
		return $this->_flags;
	
	}

	public function Populate ($value, $flags = 0)
	{
		if ($flags & FE_Form::FORMAT_FLAT){
			return;
		}
		else{
			$this->_values = $this->_values + $value;
		}
		$this->_populatingWholeForm = true;
		parent::Populate($value);
		$this->_populatingWholeForm = false;
	}

	public function Validate ($values)
	{
		if (! isset($values[$this->_attributes['name'] . '_submitted']) or ! $values[$this->_attributes['name'] . '_submitted']){
			return false;
		}
		$this->Populate($values);
		return parent::Validate();
	}

}
