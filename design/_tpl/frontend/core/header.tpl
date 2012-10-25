<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
<head>
	<title>{if $metadata.keyword_title != ''}{$metadata.keyword_title}{/if}{if $CURRENT_CONTROLLER != 'mainside'} - {$SHOP_NAME}{/if}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Author" content="Gekosale; http://www.gekosale.pl"/>
	<meta name="description" content="{$metadata.keyword_description}"/>
	<meta name="keywords" content="{$metadata.keyword}"/>
	<meta name="robots" content="all" />
	<meta name="revisit-after" content="1 Day" />
	<meta http-equiv="content-language" content="{$languageCode|substr:0:2}"/>
	<link rel="shortcut icon" href="{$DESIGNPATH}_images_frontend/core/logos/{$FAVICON}"/>
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<!--[if !(lt IE 7)]><!-->
	<link rel="stylesheet" href="{css_layout}" type="text/css"/>
	<link rel="stylesheet" href="{css_namespace css_file="style.css" mode="frontend"}" type="text/css"/>
	<style>
	{$logoCSS}
	</style>
	<!--<![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" href="{css_namespace css_file="ie6style.css" mode="frontend"}" type="text/css"/>
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" href="{css_namespace css_file="ie7style.css" mode="frontend"}" type="text/css"/>
	<![endif]-->
	<script type="text/javascript" src="{$DESIGNPATH}_js_libs/gekosale.libs.min.js"></script>
	<script type="text/javascript" src="{$DESIGNPATH}_js_frontend/core/gekosale.js"></script>
	<script type="text/javascript" src="{$DESIGNPATH}_js_frontend/core/init.js"></script>
	{if $gacode != ''}
	<script type="text/javascript">
	{literal}
	    var _gaq = _gaq || [];
	    _gaq.push(['_setAccount', '{/literal}{$gacode}{literal}']);
	    _gaq.push(['_trackPageview']);
	    _gaq.push(['_trackPageLoadTime']);
	{/literal}
	</script>
	{/if}
	<script type="text/javascript">
	{literal}
	/*<![CDATA[*/
		new GCore({
			iCookieLifetime: 30,
			sDesignPath: '{/literal}{$DESIGNPATH}{literal}',
			sController: '{/literal}{$CURRENT_CONTROLLER}{literal}',
			sCartRedirect: '{/literal}{$cartredirect}{literal}'
		});

		$(document).ready(function(){
			$('#product-search').submit(function(){
				var query = Base64.encode($('#product-search-phrase').val());
				var url = '{/literal}{$URL}{seo controller=productsearch}{literal}/' + query;
				window.location.href = url;
				return false;
			});
		});
	/*]]>*/
	{/literal}
	</script>
	{$xajax}
	{if isset($error)}
	<script type="text/javascript">
	{literal}
		$(document).ready(function(){
			GError('{/literal}{$error}{literal}');
		});
	{/literal}
	</script>
	{/if}
</head>
{php}flush(){/php}
<body class="body">
	<div id="message-bar"></div>
	<div id="main-container">
		<div id="header">
			{if $CURRENT_CONTROLLER == 'mainside'}
			<h1><a href="{$URL}" class="logo" title="{$SHOP_NAME}">{$SHOP_NAME}</a></h1>
			{else}
			<a href="{$URL}" class="logo" title="{$SHOP_NAME}">{$SHOP_NAME}</a>
			{/if}
				
			{if $catalogmode == 0}
			<div id="header-cart-summary">
			{if isset($clientdata)}
				<p>{trans}TXT_WELCOME{/trans}, <strong><a href="{$URL}{seo controller=clientsettings}/">{if isset($client.firstname)}{$client.firstname} {$client.surname}{else}{$clientdata.firstname} {$clientdata.surname}{/if}</a></strong> (<a href="logout;">{trans}TXT_LOGOUT{/trans}</a>)</p>
			{else}
				<p><span class="{seo_js controller=clientlogin}">{trans}TXT_LOGIN_PROCESS{/trans}</span> | <span class="{seo_js controller=registrationcart}">{trans}TXT_REGISTRATION{/trans}</span></p>
			{/if}
				<p id="cart-preview">{$cartpreview}</p>
			</div>
			{/if}
		</div>

		<div id="horizontal-navigation">
			<div>
				<ul>
					<li {if $CURRENT_CONTROLLER == 'mainside'}class="active"{/if}><a href="{$URL}">{trans}TXT_MAINSIDE{/trans}</a></li>
					<li {if $CURRENT_CONTROLLER == 'productpromotion'}class="active"{/if}><a href="{$URL}{seo controller=productpromotion}/">{trans}TXT_PROMOTIONS{/trans}</a></li>
					<li {if $CURRENT_CONTROLLER == 'productnews'}class="active"{/if}><a href="{$URL}{seo controller=productnews}/">{trans}TXT_NEW_PRODUCTS{/trans}</a></li>
					{if isset($contentcategory)}
					{section name=cat loop=$contentcategory}
					{if $contentcategory[cat].header == 1}
					<li {if $CURRENT_CONTROLLER == 'staticcontent' && $CURRENT_PARAM == $contentcategory[cat].id}class="active"{/if}><a href="{$URL}{seo controller=staticcontent}/{$contentcategory[cat].id}/{$contentcategory[cat].seo}">{$contentcategory[cat].name}</a>
					{if count($contentcategory[cat].children) > 0}
						<ul>
						{section name=under loop=$contentcategory[cat].children}
						{if $contentcategory[cat].children[under].header == 1}
							<li><a class="active{if $CURRENT_CONTROLLER == 'staticcontent' && $CURRENT_PARAM == $contentcategory[cat].children[under].id} current{/if}" href="{$URL}{seo controller=staticcontent}/{$contentcategory[cat].children[under].id}/{$contentcategory[cat].children[under].seo}">{$contentcategory[cat].children[under].name}</a></li>
						{/if}
						{/section}
						</ul>
					{/if}
					</li>
					{/if}
					{/section}
					{/if}
					<li {if $CURRENT_CONTROLLER == 'contact'}class="active"{/if}><a href="{$URL}{seo controller=contact}/">{trans}TXT_CONTACT{/trans}</a></li>
				</ul>
			</div>
			<form id="product-search" action="{$URL}{seo controller=productsearch}" method="post">
				<input id="product-search-phrase" name="q" type="text" value="Szukaj produktu..." />
			</form>
		</div>
		<div class="subheader">
			<div id="breadcrumbs">
			    <strong>{trans}TXT_YOU_ARE_HERE{/trans}:</strong>
			    <ul>
			    {section name=b loop=$breadcrumb}
					<li><a href="{$URL}{$breadcrumb[b].link}" title="{$breadcrumb[b].title}">{$breadcrumb[b].title}</a></li>
				{/section}
			    </ul>
			</div>
			{if isset($currencies) && !empty($currencies) && count($currencies) > 1}
			<div id="currencies-selector">
				<div class="field-select"> 
					<label for="currencies">{trans}TXT_KIND_OF_CURRENCY{/trans}:</label>
					<span class="field"> 
						<select id="currencies" onchange="xajax_changeCurrency(this.value);">
					 	{foreach from=$currencies item=currency}
							{if isset($currency.selected) && $currency.selected == 1}
					 		<option value="{$currency.id}" selected="selected">{$currency.name}</option>
							{else}
							<option value="{$currency.id}">{$currency.name}</option>
							{/if}
						{/foreach}
						</select>
					</span> 
                </div>
			</div>
			{/if}
			{if isset($languageFlag) && !empty($languageFlag) && count($languageFlag) > 1}
			<div id="languages-selector">
           		<div class="field-select"> 
					<label for="languages">{trans}TXT_LANGUAGE{/trans}:</label>
					<span class="field"> 
						<select id="languages" onchange="xajax_changeLanguage(this.value);" >
					 	{foreach from=$languageFlag item=languages}
					 		{if isset($language) && $languages.id == $language}
				 			<option value="{$languages.id}" selected="selected">{trans}{$languages.name}{/trans}</option>
					 		{else}
				 			<option value="{$languages.id}">{trans}{$languages.name}{/trans}</option>
					 		{/if}
						{/foreach}
						</select>
					</span> 
           		</div> 
			</div>
			{/if}
		</div>