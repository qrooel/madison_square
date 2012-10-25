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
 * $Revision: 441 $
 * $Author: gekosale $
 * $Date: 2011-08-27 13:53:40 +0200 (So, 27 sie 2011) $
 * $Id: template.class.php 441 2011-08-27 11:53:40Z gekosale $ 
 */

class Template extends Smarty
{
	
	/*
	* @the registry
	* @access private
	*/
	protected $registry;
	
	protected $savedStates = Array();
	
	protected $compileid = null;

	/**
	 *
	 * @constructor
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function __construct ($registry)
	{
		$this->registry = $registry;
		$this->compile_dir = ROOTPATH . 'cache';
		$this->config_dir = ROOTPATH . 'config';
		$this->cache_dir = ROOTPATH . 'cache';
		$this->security = false;
		$this->use_sub_dirs = false;
		$this->force_compile = false;
		$this->debugging = false;
		$this->caching = false;
		$this->register_block('trans', Array(
			$this,
			'do_translate'
		));
		
		$this->register_block('transjs', Array(
			$this,
			'do_translate_js'
		));
		
		$this->register_block('price', Array(
			$this,
			'do_parse_price'
		));
		
		$this->register_prefilter(Array(
			$this,
			'process_translations'
		));
		
		if (is_object($this->registry)){
			$this->messages = $this->registry->core->getTranslations();
			$this->layerData = $this->registry->loader->getCurrentLayer();
			$this->compileid = Helper::getViewId() . '_' . Helper::getLanguageId();
		}
	}

	function process_translations ($tpl_source, &$smarty)
	{
		return preg_replace_callback('/{trans}(.+?){\/trans}/', Array(
			$this,
			'getTrans'
		), $tpl_source);
	}

	public function getTrans ($trans)
	{
		$name = $trans[1];
		if (! is_null($name) && ($name != '') && (isset($this->messages[$name]))){
			return $this->messages[$name];
		}
		else{
			return $name;
		}
	}

	public function do_translate ($params, $content)
	{
		$name = $content;
		if (! is_null($name) && ($name != '') && (isset($this->messages[$name]))){
			return $this->messages[$name];
		}
		else{
			return $name;
		}
	}

	public function do_parse_price ($params, $price)
	{
		if ($price < 0){
			return ($this->layerData['negativepreffix'] . number_format(abs($price), $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . $this->layerData['negativesuffix']);
		}
		return ($this->layerData['positivepreffix'] . number_format($price, $this->layerData['decimalcount'], $this->layerData['decimalseparator'], $this->layerData['thousandseparator']) . $this->layerData['positivesuffix']);
	}

	public function do_translate_js ($params, $content)
	{
		return addslashes($this->registry->core->getMessage($content));
	}

	/*
	* saveState
	*
	* Pushes the current template's variable bindings onto stack. Allows for multiple usage
	* of a single Template object with different template files.
	*
	* @return int The current stack's size
	* @see reloadState
	*/
	public function saveState ()
	{
		return array_push($this->savedStates, $this->get_template_vars());
	}

	/*
	* reloadState
	*
	* Pops the template's variable bindings off the stack and returns to the state before
	* the last saveState's usage.
	*
	* @see saveState
	*/
	public function reloadState ()
	{
		$this->clear_all_assign();
		$this->assign(array_pop($this->savedStates));
	}

	public function fetch ($resource_name, $cache_id = null, $compile_id = null, $display = false)
	{
		$compile_id = $this->compileid;
		return parent::fetch($resource_name, $cache_id, $compile_id, $display);
	}

	public function display ($resource_name, $cache_id = null, $compile_id = null)
	{
		$compile_id = $this->compileid;
		return parent::display($resource_name, $cache_id, $compile_id);
	}

	public function fetchAdminHeader ()
	{
		$appVersion = $this->registry->session->getActiveAppVersion();
		$settings = $this->registry->session->getActiveGlobalSettings();
		
		$content = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
		<head>
			<title>{$SHOP_NAME} Admin</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta http-equiv="Author" content="Gekosale; http://www.gekosale.pl"/>
			<meta http-equiv="Description" content="Panel administracyjny systemu sklepowego Gekosale."/>
			<meta name="robots" content="noindex, nofollow"/>
			<link rel="shortcut icon" href="'.DESIGNPATH.'_images_panel/icons/favicon.ico"/>
			<link rel="stylesheet" href="'.DESIGNPATH . '_css_panel/core/style.css?v='.$appVersion.'" type="text/css"/>
			<link rel="stylesheet" href="'.DESIGNPATH . '_css_panel/core/wide.css?v='.$appVersion.'" type="text/css"/>
			<link rel="stylesheet" href="'.DESIGNPATH.'_js_libs/daterangepicker/css/ui.daterangepicker.css?v='.$appVersion.'" type="text/css"/>
			<link rel="stylesheet" href="'.DESIGNPATH.'_js_libs/daterangepicker/css/redmond/jquery-ui-1.7.1.custom.css?v='.$appVersion.'" type="text/css"/>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery-1.4.2.min.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/ckeditor/ckeditor.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/xajax/xajax_core.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery-ui-1.7.2.custom.min.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.dimensions.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.gradient.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.checkboxes.pack.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.resize.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/swfobject.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.swfobject.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/colorpicker.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/swfupload.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/swfupload.queue.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.swfupload.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/json2.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/base64.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/jquery.onkeyup.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_libs/daterangepicker/js/daterangepicker.jQuery.js?v='.$appVersion.'"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_panel/core/gekosale.js?v='.$appVersion.'"></script>
			<script type="text/javascript">
				{literal}
					/*<![CDATA[*/
						new GCore({
							iCookieLifetime: 30,
							sDesignPath: \'{/literal}'.DESIGNPATH.'{literal}\',
							iActiveView: \'{/literal}{$view}{literal}\',
							aoViews: {/literal}{$views}{literal},
							iActiveLanguage: \'{/literal}{$language}{literal}\',
							aoLanguages: {/literal}{$languages}{literal},
							sUrl: \'{/literal}{$URL}{literal}\',
							sCurrentController: \'{/literal}{$CURRENT_CONTROLLER}{literal}\',
							sCurrentAction: \'{/literal}{$CURRENT_ACTION}{literal}\',
						});
						$(document).ready(function(){
							$(\'#search\').GSearch(); 
						});
					/*]]>*/
				{/literal}
			</script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_panel/core/init.js"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_panel/core/gf.js"></script>
			<script type="text/javascript" src="'.DESIGNPATH.'_js_panel/core/pl_PL.js"></script>
			
			<script type="text/javascript">
				GF_Debug.s_iLevel = GF_Debug.LEVEL_ALL;
			</script>
			
		<!-- end: GexoFramework -->
		
		{$xajax}
		
		{if isset($error)}
			<script type="text/javascript">
				{literal}
					$(document).ready(function(){
						GError(\'{/literal}{trans}TXT_ERROR_OCCURED{/trans}{literal}\', \'{/literal}{$error}{literal}\');
					});
				{/literal}
			</script>
		{/if}
	</head>
	<body>

		<!-- begin: Header -->
			<div id="header">

				<div class="layout-container">

					<h1><a href="{$URL}mainside" accesskey="0" title="{trans}TXT_RETURN_TO_DESKTOP{/trans}"><img src="data:image/png;base64,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" /></a></h1> 

						<div id="appversion">
							<h3>{trans}TXT_VERSION{/trans}: {$appversion}</h3>
						</div>
					<!-- begin: Quick Access -->
						<div id="quick-access">
							<h3>{trans}TXT_QUICK_ACCESS{/trans}:</h3>
							<ul>
								<li><a href="{$URL}products/add">{trans}TXT_ADD_PRODUCT{/trans}</a></li>
							</ul>
						</div>

						<div id="livesearch">
							<h3>{trans}TXT_SEARCH{/trans}: <input type="text" name="search" id="search" /></h3>
						</div>
						
						<script type="text/javascript">
							{literal}
								/*<![CDATA[*/
									var aoQuickAccessPossibilites = [
										{mLink: \'{/literal}{$URL}{literal}order\', sCaption: \'{/literal}{trans}TXT_ORDER_LIST{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}product/add\', sCaption: \'{/literal}{trans}TXT_ADD_PRODUCT{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}product\', sCaption: \'{/literal}{trans}TXT_PRODUCT_LIST{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}category/add\', sCaption: \'{/literal}{trans}TXT_ADD_CATEGORY{/trans}{literal}\'},
										{mLink: \'{/literal}{$URL}{literal}client/add\', sCaption: \'{/literal}{trans}TXT_ADD_CLIENT{/trans}{literal}\'},
										{mLink: \'{/literal}{$URL}{literal}category\', sCaption: \'{/literal}{trans}TXT_CATEGORY_LIST{/trans}{literal}\'},
										{mLink: \'{/literal}{$URL}{literal}client\', sCaption: \'{/literal}{trans}TXT_CLIENT_LIST{/trans}{literal}\'},
										{mLink: \'{/literal}{$URL}{literal}statssales\', sCaption: \'{/literal}{trans}TXT_STATSSALES{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}statsclients\', sCaption: \'{/literal}{trans}TXT_STATSCLIENTS{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}statsproducts\', sCaption: \'{/literal}{trans}TXT_STATSPRODUCTS{/trans}{literal}\', bDefault: true},
										{mLink: \'{/literal}{$URL}{literal}productpromotion\', sCaption: \'{/literal}{trans}TXT_PROMOTIONS_LIST{/trans}{literal}\'}
									];
								/*]]>*/
							{/literal}
						</script>
						<div id="top-menu">
							<ul>
								<li>
									<a href="{$URL}users/edit/{$user_id}" class="icon-person"><strong>{$user_name}</strong></a>
								</li>
								<li>
									<a href="{$URL}logout;" class="icon-logout">{trans}TXT_LOGOUT{/trans}</a>
								</li>
								<li>
									<a href="{$FRONTEND_URL}" target="_blank" >{trans}TXT_HOME_PAGE{/trans}</a>
								</li>
							</ul>
						</div>
				</div>
			</div>
			<div id="navigation-bar">
				<div class="layout-container">
				
					<div id="selectors" style="float: right; margin-top: 8px;"></div>
					
					<ul id="navigation">
						{if isset($menu)}
							{section name=block loop=$menu}
								<li>
									{if isset($menu[block].elements)}
										<a href="{$URL}{$menu[block].elements[0].link}">{trans}{$menu[block].name}{/trans}</a>
									{else}
										{if $menu[block].link == \'mainside\'}
											<a href="{$URL}{$menu[block].link}">{trans}{$menu[block].name}{/trans}</a>
										{/if}
									{/if}
									{if isset($menu[block].elements)}
										<ul>
											{section name=element loop=$menu[block].elements}
												<li {if $CURRENT_CONTROLLER == $menu[block].elements[element].link}class="active"{/if}>
													<a href="{$URL}{$menu[block].elements[element].link}">{$menu[block].elements[element].name}</a>
													{if isset($menu[block].elements[element].subelement)}
														<ul>
															{section name=sub loop=$menu[block].elements[element].subelement}
																<li {if $CURRENT_CONTROLLER == $menu[block].elements[element].subelement[sub].link}class="active"{/if}>
																	<a href="{$URL}{$menu[block].elements[element].subelement[sub].link}">{$menu[block].elements[element].subelement[sub].name}</a>
																</li>	
															{/section}
														</ul>
													{/if}
												</li>	
											{/section}
										</ul>
									{/if}
								</li>	
							{/section}
						{/if}
					</ul>
				</div>
			</div>
			<div id="message-bar">
				<h2 class="aural">{trans}TXT_NEWS{/trans}</h2>
			</div>
			<div id="content" class="layout-container">
		';
		return $this->fetch('text:'.$content);
	}

	public function fetchAdminFooter ()
	{
//		return "</div><div style=\"display: block;height: 50px;width: 100%;background: #3D4041 url('".DESIGNPATH."_images_panel/backgrounds/header.png') 0 bottom repeat-x;\">Oprogramowanie GekoSale</div>";
		return "</div>";
	}
}