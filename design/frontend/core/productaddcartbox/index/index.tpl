<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
	<head>
		<!-- begin: Meta information -->
			<title>{if $metadata.keyword_title != ''}{$metadata.keyword_title} : {/if}{$SHOP_NAME}</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta http-equiv="Author" content="Verison; http://verison.pl"/>
			<meta name="description" content="{$metadata.keyword_description}"/>
			<meta name="keywords" content="{$metadata.keyword}"/>
			<meta name="robots" content="all" />
			<meta name="revisit-after" content="1 Day" />
			<meta http-equiv="content-language" content="{$languageCode|substr:0:2}"/>
			<link rel="shortcut icon" href="{$DESIGNPATH}_images_frontend/core/logos/{$FAVICON}"/>
			<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<!-- end: Meta information -->
		<!-- begin: Stylesheets -->
			<!--[if !(lt IE 7)]><!-->
			<link rel="stylesheet" href="{css_namespace css_file="gekosale.css" mode="frontend"}" type="text/css"/>
			<link rel="stylesheet" href="{css_namespace css_file="style.css" mode="frontend"}" type="text/css"/>
			<!--<![endif]-->
			<!--[if lt IE 7]>
				<link rel="stylesheet" href="{css_namespace css_file="ie6style.css" mode="frontend"}" type="text/css"/>
			<![endif]-->
			<!--[if IE 7]>
			 	<link rel="stylesheet" href="{css_namespace css_file="ie7style.css" mode="frontend"}" type="text/css"/>
			<![endif]-->
		<!-- end: Stylesheets -->
		<script type="text/javascript" src="{$DESIGNPATH}_js_libs/gekosale.libs.min.js"></script>
		<script type="text/javascript" src="{$DESIGNPATH}_js_frontend/core/gekosale.js"></script>
		<script type="text/javascript" src="{$DESIGNPATH}_js_frontend/core/init.js"></script>
		<!-- begin: JS libraries and scripts inclusion -->
		<!-- end: JS libraries and scripts inclusion -->
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
		<!-- begin: JS variables binding -->
			<script type="text/javascript">
				{literal}
					/*<![CDATA[*/
						new GCore({
							iCookieLifetime: 30,
							sDesignPath: '{/literal}{$DESIGNPATH}{literal}',
							sController: '{/literal}{$CURRENT_CONTROLLER}{literal}',
							sCartRedirect: '{/literal}{$cartredirect}{literal}'
						});
					/*]]>*/
				{/literal}
			</script>
		<!-- end: JS variables binding -->
		{$xajax}
	</head>
	{php}flush(){/php}
	<body style="background: #fff;">
	<div class="product-photos">
		{if isset($product.mainphoto.normal.path)}
		<img class="mainphoto" style="margin-right: 10px;height: 300px;" src="{$product.mainphoto.normal}" alt="{trans}TXT_MAIN_PHOTO{/trans}">
		{/if}
	</div>
	
	
	<div class="product-details">
    	{if $catalogmode == 0 && $product.pricewithoutvat > 0}
    	<form action="" method="">
	    	<input type="hidden" id="preventaddcart" value="0" />
			<input type="hidden" id="attributevariants" value="0" />
			<input type="hidden" id="availablestock" value="{$product.stock}" />
			<input type="hidden" id="variantprice" value="{$product.price}" />	
	        {if ($attset != NULL)}
        	<fieldset class="options">		
        		{foreach from=$attributes item=attributesgroup key=grid}
        		<div class="field-select">
					{if count($attributesgroup.attributes) == 1}
						<label><strong>{$attributesgroup.name}:</strong> 
						{foreach from=$attributesgroup.attributes key=v item=variant}
							{$variant}
						{/foreach}
						</label>
					{else}
						<label>{$attributesgroup.name}</label>
					{/if}
					<span class="field" {if count($attributesgroup.attributes) == 1}style="position: absolute; left: -10000px"{/if}>
	        		<select id="{$grid}" name="{$grid}" class="attributes">
	        		{foreach from=$attributesgroup.attributes key=v item=variant}
	        			<option value="{$v}">{$variant}</option>
	        		{/foreach}
	        		</select>
        			</span>
        		</div>
        		{/foreach}
        		{foreach from=$product.staticattributes item=group key=key}
        		<div class="field-select">
					<label><strong>{$group.name}:</strong> 
						{foreach from=$group.attributes key=v item=variant name=attributes}
							{if $smarty.foreach.attributes.last}
								{$variant.name}
								{else}
								{$variant.name},
								{/if}
						{/foreach}
					</label>
        		</div>
        		{/foreach}
			</fieldset>
		{/if}	
			
			<fieldset>
				<div>
					{if $product.discountprice != NULL}
                    <span class="price" style="color: #900;">{trans}TXT_PROMOTION{/trans} {math equation="(1 - (x / y)) * 100" x=$product.discountprice y=$product.price format="%.2f"}%</span><br /><br />
                    {/if}
                    {if $showtax == 0}
                    <span class="price">{trans}TXT_NET_PRICE{/trans}: 
                    <strong id="changeprice-netto">
                    {if $product.discountprice != NULL}
						{price}{$product.discountpricenetto}{/price}
					{else}
						{price}{$product.pricewithoutvat}{/price}
					{/if}
                    </strong>
                    {/if}
                    
                    {if $showtax == 1}
					<span class="price">{trans}TXT_PRICE{/trans}:</span>
					<span class="price" id="changeprice">
					{if $product.discountprice != NULL}
						{price}{$product.discountprice}{/price}
					{else}
						{price}{$product.price}{/price}
					{/if}
                    </span>
                    {/if}
                    
                    {if $showtax == 2}
					<span class="price">{trans}TXT_PRICE{/trans}:</span>
					<span class="price" id="changeprice">
					{if $product.discountprice != NULL}
						{price}{$product.discountprice}{/price}
					{else}
						{price}{$product.price}{/price}
					{/if}
                    </span><br />
                    <span class="netto">{trans}TXT_NET_PRICE{/trans}: 
                    <strong id="changeprice-netto">
                    {if $product.discountprice != NULL}
						{price}{$product.discountpricenetto}{/price}
					{else}
						{price}{$product.pricewithoutvat}{/price}
					{/if}
                    </strong>
                    {/if}
                </div>
               
                <div class="toCart" id="available">
					{if $product.unit == 2}
					<input id="product-qty" type="hidden" value="1">
					<div class="field-text">
                    	<span>{trans}TXT_WIDTH{/trans}</span>
                    	<span class="field">
                      		<label for="quantity">cm</label>
                           <input id="width" type="text" value="1">
                        </span>
                    </div>
                    <div class="field-text">
                    	<span>{trans}TXT_HEIGHT{/trans}</span>
                    	<span class="field">
                      		<label for="quantity">cm</label>
                            <input id="height" type="text" value="1">
                        </span>
                    </div>
                    <a href="#" id="add-cart" class="button redButton" style="margin-top: 15px;">{trans}TXT_ADD_TO_CART{/trans}</a>
					{else}
					<div class="field-text">
                    	<span class="field">
                      		<label for="quantity">{trans}TXT_QTY{/trans}</label>
                            <input id="product-qty" type="text" value="1">
                        </span>
                    </div>
                    <a href="#" id="add-cart" class="button redButton">{trans}TXT_ADD_TO_CART{/trans}</a>
					{/if}
                    
				</div>
				<div class="toCart" id="noavailable" style="display:none;height: 42px;">
                	<h3>{trans}ERR_SHORTAGE_OF_STOCK{/trans}</h3>
				</div>
			</fieldset>   
            </form>
            {else}
            <fieldset class="cartOpt">
            	<legend>{trans}TXT_CART{/trans}</legend>
                <div class="cartOptionsContent">
                	<div class="cartOptions">
                    	<div class="toCart">
                    		<strong class="price" id="changeprice">{trans}TXT_REQUEST_QUOTE{/trans}</strong>
                            <a href="{$URL}{seo controller=contact}/{$product.idproduct}" class="button greenButton">{trans}TXT_SEND_YOUR_QUERY{/trans}</a>
						</div>
					</div>
				</div>
			</fieldset>  
            {/if}	
                  
               </div>
               
           <div class="description">
				<p>{$product.shortdescription|strip_tags:false|truncate:350}<br /><br /><a class="read-more" href="{$URL}{seo controller=productcart}/{$product.seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
			</div>

<script type="text/javascript">
{literal}
$('.read-more').click(function(){
	parent.location.href = $(this).attr('href');
});
$(document).ready(function(){

	var liczbarat = 10;
	var oprocentowanie = 0.13;
	var rata = 0;
	var wartosc = parseFloat($('#changeprice').text());
	rata = ((((wartosc + 12) * (1 + oprocentowanie))) * 1.01) / liczbarat;
	rata = Math.round(rata * 100) / 100;
	$('#eratyvalue').text(rata);
	
	var producttrackstock = {/literal}{$product.trackstock}{literal};
	
	$('#add-cart').unbind('click').bind('click', function(){
		if(producttrackstock == 1){
			if($('#availablestock').val() > 0){
				return xajax_addProductToCart({/literal}{$product.idproduct}{literal}, $('#attributevariants').val(), $('#product-qty').val(), $('#availablestock').val() +','+ $('#variantprice').val(), {/literal}{$product.trackstock}{literal});
			}else{
				parent.GError('{/literal}{trans}ERR_SHORTAGE_OF_STOCK{/trans}{literal}');
				return false;
			}
		}else{
			return xajax_addProductToCart({/literal}{$product.idproduct}{literal}, $('#attributevariants').val(), $('#product-qty').val(), $('#availablestock').val() +','+ $('#variantprice').val(), {/literal}{$product.trackstock}{literal});
		}
	});
	{/literal}
	{if ($attset != NULL)}
	{literal}
		GProductAttributes({
			aoVariants: {/literal}{$variants}{literal},
			bTrackStock: producttrackstock
		});
	{/literal}
	{else}
	{literal}
		if(producttrackstock == 1 && ($('#availablestock').val() == 0)){
			$('#available').hide();
			$('#noavailable').show();
		}else{
			$('#available').show();
			$('#noavailable').hide();
		}
	{/literal}
	{/if}
	{literal}
});
{/literal}
</script>
</body> 
