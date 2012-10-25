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
 * $Id: cssgenerator.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class CssgeneratorModel extends Model
{
	
	private $sizes = array(
		array(
			' 0px',
			' 0em',
			' 0%',
			' 0ex',
			' 0cm',
			' 0mm',
			' 0in',
			' 0pt',
			' 0pc'
		),
		array(
			':0px',
			':0em',
			':0%',
			':0ex',
			':0cm',
			':0mm',
			':0in',
			':0pt',
			':0pc'
		)
	);
	
	private $shortcuts = array(
		
		', ' => ',',
		' , ' => ',',
		';}' => '}',
		'; }' => '}',
		' ; }' => '}',
		' :' => ':',
		': ' => ':',
		' {' => '{',
		'; ' => ';',
		
		// kolory
		':black' => ':#000',
		':darkgrey' => ':#666',
		':fuchsia' => ':#F0F',
		':lightgrey' => ':#CCC',
		':orange' => ':#F60',
		':white' => ':#FFF',
		':yellow' => ':#FF0',
		
		':silver' => ':#C0C0C0',
		':gray' => ':#808080',
		':maroon' => ':#800000',
		':red' => ':#FF0000',
		':purple' => ':#800080',
		':green' => ':#008000',
		':lime' => ':#00FF00',
		':olive' => ':#808000',
		':navy' => ':#000080',
		':blue' => ':#0000FF',
		':teal' => ':#008080',
		':aqua' => ':#00FFFF'
	);
	
	private $font_weight_to_num = array(
		'lighter' => 100,
		'normal' => 400,
		'bold' => 700,
		'bolder' => 900
	);
	protected $currentPageSchemeId;
	protected $pageschemeCssValues;
	protected $layoutboxschemeCssValues;
	protected $layoutboxschemeCss;
	protected $layoutboxCssValues;
	protected $layoutboxCss;

	public function createPageSchemeStyleSheetDocument ()
	{
		
		$viewid = Helper::getViewId();
		
		if ($viewid > 0){
			$css_layout = $viewid . '.css';
		}
		else{
			$css_layout = 'scheme.css';
		}
		
		$filename = ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $css_layout;
		try{
			$pageScheme = $this->getPageSchemeStyleSheetContent();
			if (! empty($pageScheme)){
				$layoutBoxes = $this->getLayoutBoxesStyleSheetContent();
				$layoutBoxSchemes = $this->getLayoutBoxSchemeStyleSheetContent();
				$pageSchemeStyleSheet = $this->_preparePageSchemeCssContent($pageScheme);
				$file = @fopen($filename, "w+");
				$write = fwrite($file, $pageSchemeStyleSheet);
				foreach ($layoutBoxSchemes as $layoutBoxScheme){
					$layoutBoxSchemeStyleSheet = $this->_preparePageSchemeCssContent($layoutBoxScheme);
					$write = fwrite($file, $layoutBoxSchemeStyleSheet);
				}
				foreach ($layoutBoxes as $layoutBox){
					$layoutBoxStyleSheet = $this->_preparePageSchemeCssContent($layoutBox);
					$write = fwrite($file, $layoutBoxStyleSheet);
				}
				fclose($file);
				clearstatcache();
				$this->createMinifiedCSS();
			}
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
			clearstatcache();
		}
	}

	protected function createMinifiedCSS ()
	{
		$scheme = (Helper::getViewId() == 0) ? 'scheme.css' : Helper::getViewId() . '.css';
		$name = (Helper::getViewId() == 0) ? 'gekosale.css' : Helper::getViewId() . '.css';
		
		$cssFiles = Array(
			'static.css',
			'fancybox.css',
			$scheme,
			'scheme-new.css'
		);
		$code = '';
		$minifiedCSS = ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $name;
		foreach ($cssFiles as $cssFile){
			$filename = ROOTPATH . 'design' . DS . '_css_frontend' . DS . 'core' . DS . $cssFile;
			$code .= file_get_contents($filename);
		}
		$code = $this->cleanCode($code);
		$code = $this->compressCode($code);
		$file = @fopen($minifiedCSS, "wb");
		$write = fwrite($file, $code);
		fclose($file);
	}

	public function cleanCode ($code)
	{
		
		$code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', null, $code);
		$code = str_replace(array(
			"\r\n",
			"\r",
			"\n",
			"\t",
			'  ',
			'    '
		), null, $code);
		return $code;
	}

	public function compressCode ($code)
	{
		
		$code = str_replace($this->sizes[0], ' 0', $code);
		$code = str_replace($this->sizes[1], ':0', $code);
		$code = str_ireplace(array_keys($this->shortcuts), array_values($this->shortcuts), $code);
		$search = array(
			1 => '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i',
			2 => '/(font-weight|font):([a-z- ]*)(normal|bolder|bold|lighter)/ie'
		);
		
		$replace = array(
			1 => '$1#$2$3$4$5',
			2 => '"$1:$2" . $this->font_weight_to_num["$3"]'
		);
		
		$code = preg_replace($search, $replace, $code);
		return $code;
	
	}

	protected function clearMinifyFiles ()
	{
		$cachePath = ROOTPATH . 'cache';
		if ($dir = opendir($cachePath)){
			while (false !== ($file = readdir($dir))){
				if (substr($file, 0, 6) == 'minify'){
					unlink($cachePath . DS . $file);
				}
			}
		}
		closedir($dir);
	}

	protected function _preparePageSchemeCssContent ($schemeRules)
	{
		
		$gradientsPath = '../../_images_frontend/core/gradients/';
		$uploadsPath = '../../_images_frontend/upload/';
		
		$css = Array();
		foreach ($schemeRules as $rule){
			$attributes = Array();
			if (! strlen($rule['attribute'])){
				continue;
			}
			$value = $rule['value'];
			switch ($rule['attribute']) {
				
				case 'background':
					switch ($value['type']) {
						
						case 1: // Single colour
							if (isset($value['start'])){
								$attributes[] = "background: {$this->_formatCssColour($value['start'])};";
							}
							else{
								$attributes[] = "background: transparent;";
							}
							break;
						
						case 2: // Gradient
							if (isset($value['start']) && isset($value['end'])){
								if (! isset($value['gradient_height'])){
									$value['gradient_height'] = 32;
								}
								$attributes[] = "background: {$this->_formatCssColour($value['end'])};";
								$attributes[] = "background-image: -o-linear-gradient(top,{$this->_formatCssColour($value['start'])},{$this->_formatCssColour($value['end'])});";
								$attributes[] = "background: -webkit-gradient(linear, left top, left bottom, from({$this->_formatCssColour($value['start'])}), to({$this->_formatCssColour($value['end'])}));";
								$attributes[] = "background: -moz-linear-gradient(top,  {$this->_formatCssColour($value['start'])}, {$this->_formatCssColour($value['end'])});";
								$attributes[] = "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='{$this->_formatCssColour($value['start'])}', endColorstr='{$this->_formatCssColour($value['end'])}');";
							
							}
							else{
								$attributes[] = "background: transparent;";
							}
							break;
						
						case 3: // Picture
							! isset($value['start']) && ($value['start'] = 'transparent');
							if (isset($value['file'])){
								$attributes[] = "background: {$this->_formatCssColour($value['start'])} url('{$uploadsPath}{$value['file']}') {$value['position']} {$value['repeat']};";
							}
							else{
								$attributes[] = "background: {$this->_formatCssColour($value['start'])};";
							}
							break;
					}
					break;
				
				case 'icon':
					$attributes[] = "background: transparent url('{$uploadsPath}{$value['file']}') center center no-repeat;";
					break;
				
				case 'font':
					isset($value['colour']) && $attributes[] = "color: {$this->_formatCssColour($value['colour'])};";
					isset($value['family']) && $attributes[] = "font-family: {$value['family']};";
					isset($value['size']) && $attributes[] = "font-size: {$value['size']}px;";
					isset($value['bold']) && $attributes[] = 'font-weight: ' . ($value['bold'] ? 'bold' : 'normal') . ';';
					isset($value['italic']) && $attributes[] = 'font-style: ' . ($value['italic'] ? 'italic' : 'normal') . ';';
					isset($value['underline']) && $attributes[] = 'text-decoration: ' . ($value['underline'] ? 'underline' : 'none') . ';';
					isset($value['uppercase']) && $attributes[] = 'text-transform: ' . ($value['uppercase'] ? 'uppercase' : 'none') . ';';
					break;
				
				case 'border':
					$sides = Array(
						'top',
						'right',
						'bottom',
						'left'
					);
					foreach ($sides as $side){
						isset($value[$side]['size']) && $attributes[] = "border-{$side}-style: " . (($value[$side]['size'] > 0) ? 'solid' : 'none') . ';';
						isset($value[$side]['colour']) && $attributes[] = "border-{$side}-color: {$this->_formatCssColour($value[$side]['colour'])};";
						isset($value[$side]['size']) && $attributes[] = "border-{$side}-width: {$value[$side]['size']}px;";
					}
					break;
				
				case 'border-radius':
					$sides = Array(
						'top-right',
						'top-left',
						'bottom-right',
						'bottom-left'
					);
					
					foreach ($sides as $side){
						if (isset($value[$side]['value'])){
							$attributes[] = "border-{$side}-radius: {$value[$side]['value']};";
							$attributes[] = "-webkit-border-{$side}-radius: {$value[$side]['value']};";
							$attributes[] = "-khtml-border-{$side}-radius: {$value[$side]['value']};";
							$mozSide = str_replace('-', '', $side);
							$attributes[] = "-moz-border-radius-{$mozSide}: {$value[$side]['value']};";
						}
					}
					
					break;
				
				case 'line-height':
					$formattedValue = $value['value'];
					if (! preg_match('/(em|px|\%)$/', $formattedValue)){
						$formattedValue .= 'px';
					}
					$attributes[] = "line-height: {$formattedValue};";
					break;
				
				case 'width': 
					$attributes[] = "width: {$value['value']}px;";
					break;
				
				case 'height':
					$attributes[] = "height: {$value['value']}px;";
					break;
				
				default:
					if (isset($rule['attribute']) && isset($value['value'])){
						$attributes[] = "{$rule['attribute']}: {$value['value']};";
					}
					break;
			
			}
			$cssRuleString = $this->_formatCssRule($rule['selector'], $attributes);
			if ($cssRuleString){
				$css[] = $cssRuleString;
			}
		}
		$cssString = implode("\n\n", $css);
		return $cssString;
	}

	protected function _formatCssColour ($colour)
	{
		if (strlen($colour) == 6){
			return '#' . $colour;
		}
		if (empty($colour)){
			return 'transparent';
		}
		return $colour;
	}

	protected function _formatCssRule ($selector, $attributes)
	{
		if (! is_array($attributes) || ! count($attributes)){
			return false;
		}
		$attributesString = '';
		foreach ($attributes as $attribute){
			$attributesString .= "\t{$attribute}\n";
		}
		$selector = str_replace(', ', ",\n", $selector);
		return "{$selector} {\n{$attributesString}}";
	}

	public function getPageSchemeStyleSheetContent ()
	{
		$Data = Array();
		$sql = "SELECT 
					PS.idpagescheme, 
					PS.name, 
					PS.viewid, 
					PS.default
				FROM pagescheme PS
				WHERE PS.default = 1
				AND IF (:viewid >0, PS.viewid= :viewid, PS.viewid IS NULL)";
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$idPageScheme = $rs->getInt('idpagescheme');
			if ($idPageScheme > 0){
				$Data = $this->getPageSchemeCss($idPageScheme);
			}
		}
		return $Data;
	}

	public function getLayoutBoxSchemeStyleSheetContent ($id = NULL)
	{
		$Data = Array();
		if ($id === NULL){
			$sql = '
					SELECT
						LS.idlayoutboxscheme, LS.name, LS.viewid, LS.parentid
					FROM
						layoutboxscheme LS
					WHERE
						IF (:viewid >0, LS.viewid= :viewid, LS.viewid IS NULL)
				';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			while ($rs->next()){
				$idLayoutBoxScheme = $rs->getInt('idlayoutboxscheme');
				if ($idLayoutBoxScheme > 0){
					$Data[] = $this->getLayoutBoxSchemeCss($idLayoutBoxScheme);
				}
			}
		}
		else{
			$sql = '
					SELECT
						LS.idlayoutboxscheme, LS.name, LS.viewid, LS.parentid
					FROM
						layoutboxscheme LS
					WHERE
						LS.idlayoutboxscheme = :id
						AND IF (:viewid >0, LS.viewid= :viewid, LS.viewid IS NULL)
				';
			$stmt = $this->registry->db->prepareStatement($sql);
			$stmt->setInt('viewid', Helper::getViewId());
			$stmt->setInt('id', $id);
			$rs = $stmt->executeQuery();
			if ($rs->first()){
				$idLayoutBoxScheme = $rs->getInt('idlayoutboxscheme');
				if ($idLayoutBoxScheme > 0){
					$Data = $this->getLayoutBoxSchemeCss($idLayoutBoxScheme);
				}
			}
		}
		return $Data;
	}

	public function getLayoutBoxesStyleSheetContent ()
	{
		$boxes = $this->getLayoutBox();
		$Data = Array();
		foreach ($boxes as $box){
			$Data[] = $box['css'];
		}
		return $Data;
	}

	public function getLayoutBoxCssValues ($layoutBoxId)
	{
		$sql = 'SELECT LB.idlayoutbox, LB.name
					FROM layoutbox LB
					WHERE LB.idlayoutbox= :idlayoutbox';
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutbox', $layoutBoxId);
		$rs = $stmt->executeQuery();
		if ($rs->first()){
			$Data = Array(
				'idlayoutbox' => $rs->getInt('idlayoutbox'),
				'name' => $rs->getString('name'),
				'boxcss' => $this->getLayoutBoxCSS($IdLayoutBox)
			);
		}
		return $Data;
	}

	public function getLayoutBox ()
	{
		$sql = "SELECT LB.idlayoutbox
					FROM layoutbox LB";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$layoutboxid = $rs->getInt('idlayoutbox');
			$Data[$layoutboxid] = Array(
				'css' => $this->getLayoutBoxCSS($layoutboxid)
			);
		}
		return $Data;
	}

	public function getLayoutBoxCSS ($idlayoutbox)
	{
		$this->collectLayoutBoxCss();
		if (! isset($this->layoutboxCss[$idlayoutbox])){
			return Array();
		}
		return $this->layoutboxCss[$idlayoutbox];
	}

	public function getLayoutBoxCssValue ($id)
	{
		$this->collectLayoutBoxCssValues();
		if (! isset($this->layoutboxCssValues[$id])){
			return Array();
		}
		return $this->layoutboxCssValues[$id];
	}

	protected function collectLayoutBoxCss ()
	{
		if (is_array($this->layoutboxCss)){
			return;
		}
		$sql = '
				SELECT
					LBC.idlayoutboxcss,
					LBC.layoutboxid,
					LBC.selector,
					LBC.attribute
				FROM
					layoutboxcss LBC
					LEFT JOIN layoutbox LB ON LB.idlayoutbox = LBC.layoutboxid
			';
		if (Helper::getViewId() > 0){
			$sql .= '
					WHERE
						LB.viewid = :viewid
				';
		}
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('layoutboxid')][] = Array(
				'idlayoutboxschemecss' => $rs->getInt('idlayoutboxcss'),
				'selector' => $rs->getString('selector'),
				'attribute' => $rs->getString('attribute'),
				'value' => $this->getLayoutBoxCssValue($rs->getInt('idlayoutboxcss'))
			);
		}
		$this->layoutboxCss = $Data;
	}

	protected function collectLayoutBoxCssValues ()
	{
		if (is_array($this->layoutboxCssValues)){
			return;
		}
		$sql = '
				SELECT
					LBCV.layoutboxid,
					LBCV.layoutboxcssid,
					LBCV.name,
					LBCV.value,
					LBCV.2ndvalue
				FROM
					layoutboxcssvalue LBCV
					LEFT JOIN layoutbox LB ON LB.idlayoutbox = LBCV.layoutboxid
			';
		if (Helper::getViewId() > 0){
			$sql .= '
					WHERE
						LB.viewid = :viewid
				';
		}
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($rs->getString('2ndvalue') != NULL){
				$Data[$rs->getInt("layoutboxcssid")][$rs->getString('name')][$rs->getString('value')] = $rs->getString('2ndvalue');
			}
			else{
				$Data[$rs->getInt("layoutboxcssid")][$rs->getString('name')] = $rs->getString('value');
			}
		}
		$this->layoutboxCssValues = $Data;
	}

	public function getLayoutBoxJSValuesToEdit ($idLayoutBox)
	{
		$sql = "SELECT LBJV.idlayoutboxjsvalue, LBJV.variable, LBJV.value
					FROM layoutboxjsvalue LBJV
					WHERE  LBJV.layoutboxid= :idlayoutbox";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idlayoutbox', $idLayoutBox);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getString('variable')] = $rs->getString('value');
		}
		return $Data;
	}

	public function getLayoutBoxSchemeCss ($idLayoutBoxScheme)
	{
		$this->collectLayoutBoxSchemeCss();
		if (! isset($this->layoutboxschemeCss[$idLayoutBoxScheme])){
			return Array();
		}
		return $this->layoutboxschemeCss[$idLayoutBoxScheme];
	}

	public function getLayoutBoxSchemeCssValue ($id)
	{
		$this->collectLayoutBoxSchemeCssValues();
		if (! isset($this->layoutboxschemeCssValues[$id])){
			return Array();
		}
		return $this->layoutboxschemeCssValues[$id];
	}

	protected function collectLayoutBoxSchemeCss ()
	{
		if (is_array($this->layoutboxschemeCss)){
			return;
		}
		$sql = '
				SELECT
					LBSC.idlayoutboxschemecss,
					LBSC.layoutboxschemeid,
					LBSC.selector,
					LBSC.attribute
				FROM
					layoutboxschemecss LBSC
					LEFT JOIN layoutboxscheme LBS ON LBS.idlayoutboxscheme = LBSC.layoutboxschemeid
			';
		if (Helper::getViewId() > 0){
			$sql .= '
					WHERE
						LBS.viewid = :viewid
				';
		}
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			$Data[$rs->getInt('layoutboxschemeid')][] = Array(
				'idlayoutboxschemecss' => $rs->getInt('idlayoutboxschemecss'),
				'selector' => $rs->getString('selector'),
				'attribute' => $rs->getString('attribute'),
				'value' => $this->getLayoutBoxSchemeCssValue($rs->getInt('idlayoutboxschemecss'))
			);
		}
		$this->layoutboxschemeCss = $Data;
	}

	protected function collectLayoutBoxSchemeCssValues ()
	{
		if (is_array($this->layoutboxschemeCssValues)){
			return;
		}
		$sql = '
				SELECT
					LBSCV.layoutboxschemeid,
					LBSCV.layoutboxschemecssid,
					LBSCV.name,
					LBSCV.value,
					LBSCV.2ndvalue
				FROM
					layoutboxschemecssvalue LBSCV
					LEFT JOIN layoutboxscheme LBS ON LBS.idlayoutboxscheme = LBSCV.layoutboxschemeid
			';
		if (Helper::getViewId() > 0){
			$sql .= '
					WHERE
						LBS.viewid = :viewid
				';
		}
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($rs->getString('2ndvalue') != NULL){
				$Data[$rs->getInt("layoutboxschemecssid")][$rs->getString('name')][$rs->getString('value')] = $rs->getString('2ndvalue');
			}
			else{
				$Data[$rs->getInt("layoutboxschemecssid")][$rs->getString('name')] = $rs->getString('value');
			}
		}
		$this->layoutboxschemeCssValues = $Data;
	}

	public function getPageSchemeCss ($idPageScheme)
	{
		$sql = "SELECT PSC.idpageschemecss, PSC.class, PSC.selector, PSC.attribute
					FROM pageschemecss PSC
					WHERE PSC.pageschemeid = :idpagescheme";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('idpagescheme', $idPageScheme);
		$rs = $stmt->executeQuery();
		$this->currentPageSchemeId = $idPageScheme;
		while ($rs->next()){
			$Data[] = Array(
				'idpageschemecss' => $rs->getInt('idpageschemecss'),
				'class' => $rs->getString('class'),
				'selector' => $rs->getString('selector'),
				'attribute' => $rs->getString('attribute'),
				'value' => $this->getTemplateCssValue($rs->getInt('idpageschemecss'))
			);
		}
		$this->templateCssValues = null;
		return $Data;
	}

	protected function collectPageschemeCssValues ($id)
	{
		if (is_array($this->pageschemeCssValues)){
			return;
		}
		$sql = "
				SELECT
					PSCV.pageschemecssid,
					PSCV.name,
					PSCV.value,
					PSCV.2ndvalue
				FROM
					pageschemecssvalue PSCV
				WHERE
					PSCV.pageschemeid = :id
			";
		$Data = Array();
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setInt('id', $id);
		$rs = $stmt->executeQuery();
		while ($rs->next()){
			if ($rs->getString('2ndvalue') != NULL){
				$Data[$rs->getInt("pageschemecssid")][$rs->getString('name')][$rs->getString('value')] = $rs->getString('2ndvalue');
			}
			else{
				$Data[$rs->getInt("pageschemecssid")][$rs->getString('name')] = $rs->getString('value');
			}
		}
		$this->pageschemeCssValues = $Data;
	}

	public function getTemplateCssValue ($pageschemecssid)
	{
		$this->collectPageschemeCssValues($this->currentPageSchemeId);
		if (! isset($this->pageschemeCssValues[$pageschemecssid])){
			return Array();
		}
		return $this->pageschemeCssValues[$pageschemecssid];
	}

	public function prepareFieldName ($class = NULL, $selector, $attribute)
	{
		$fieldName = '';
		if ($selector != NULL && $attribute != NULL){
			if ($class !== NULL){
				$prepareName = $class . ',' . $selector . '_' . $attribute;
			}
			else{
				$prepareName = $selector . '_' . $attribute;
			}
			$fieldName = $prepareName;
		}
		return $fieldName;
	}
}