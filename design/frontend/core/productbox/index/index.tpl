<div class="product-photos">
{if isset($product.mainphoto.normal.path)}
	{if $product.discountprice != NULL}
	<img class="promo" src="{$DESIGNPATH}_images_frontend/core/icons/product-promo.png" alt="{trans}TXT_PROMOTION{/trans}" title="{trans}TXT_PROMOTION{/trans}"/>
	{/if}
	{if $product.new == 1}
	<img class="promo" src="{$DESIGNPATH}_images_frontend/core/icons/product-novelty.png" alt="{trans}TXT_NEW{/trans}" title="{trans}TXT_NEW{/trans}"/>
	{/if}
	{if isset($product.photo[0].photoid) && $product.photo[0].photoid == 1}
		<img class="mainphoto" src="{$product.mainphoto.normal}" alt="{$product.productname}">
	{else}
		<a rel="product" href="{$product.mainphoto.orginal}" class="mainphotoContainer">
			<img class="mainphoto" src="{$product.mainphoto.normal}" alt="{$product.productname}">
		</a>
	{/if}
{/if}
	<div class="productThumbs"> 
	{section name=i loop=$product.otherphoto.small}
		<a rel="product" href="{$product.otherphoto.orginal[i]}"><img style="width:47px;" src="{$product.otherphoto.small[i]}" alt="{trans}TXT_ADDITIONALPHOTO{/trans}"></a> 
	{/section}
    </div> 
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
        		<legend><span>{trans}TXT_ADDITIONAL_OPTION{/trans}</span></legend>
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
			</fieldset>
		{/if}	
		{if count($product.staticattributes) > 0}
		<fieldset>
			{foreach from=$product.staticattributes item=group key=key}
			<div>
			<strong>{$group.name}:</strong> 
			{foreach from=$group.attributes key=v item=variant name=attributes}
				{if $smarty.foreach.attributes.last}
				{$variant.name}
				{else}
				{$variant.name},
				{/if}
			{/foreach}
       		</div>
        	{/foreach}
		</fieldset>	
		{/if}
			<fieldset>
				<div>
					{if $product.discountprice != NULL}
                    <span class="price">{trans}TXT_OLD_PRICE{/trans}: {price}{$product.price}{/price}</span><br /><br />
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
					<span class="price" id="changeprice" {if $product.discountprice != NULL}style="color: #900;"{/if}>
					{if $product.discountprice != NULL}
						{price}{$product.discountprice}{/price}
					{else}
						{price}{$product.price}{/price}
					{/if}
                    </span>
                    {if $product.discountprice != NULL}
                    <span class="price" style="color: #900;">(-{math equation="(1 - (x / y)) * 100" x=$product.discountprice y=$product.price format="%.2f"}%)</span><br /><br />
                    {/if}
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
                    	
						
					{if  isset($eraty.wariantsklepu) && ($eraty.wariantsklepu)>0 && isset($eraty.numersklepu) && $eraty.numersklepu > 0 && $product.price > 100}
		 				<div class="creditOptions">
                    		<span>Raty od <strong id="eratyvalue"></strong> {trans}TXT_MONTHLY{/trans}</span>
                    		<a id="eraty" href="#" class="button greenButton">{trans}TXT_CALCULATE_ZAGIEL{/trans}</a>
                   		</div>
					{/if}
                  
            </form>
            {else}
            <fieldset>
                <div class="toCart" id="available">
                    <a href="{$URL}{seo controller=contact}/{$product.idproduct}" class="button greenButton">{trans}TXT_REQUEST_QUOTE{/trans}</a>
				</div>
			</fieldset>
			
            {/if}	
                   	
                   <div class="addons">
                   	   {if $catalogmode == 0}
                   	   <ul> 
                           <li id="shippingCost">{trans}TXT_DELIVERYCOST{/trans}: <strong>{trans}TXT_FROM{/trans} {price}{$deliverymin}{/price}</strong></li> 
                           {if $product.trackstock == 1}<li id="availbility">{trans}TXT_STOCK{/trans}: <span id="stockavailablity" style="font-weight:bold">{$product.stock}</span> {trans}TXT_QTY{/trans}</li>{/if}
                       </ul> 
                       {/if}
                       <ul>
                       {if isset($clientdata)} 
	                       {if ($attset != NULL)}
	                        	<li id="toClip"><a href="#" onclick="xajax_addProductToWishList({$product.idproduct}, $('#attributevariants').val()); return true;">{trans}TXT_ADD_TO_WISHLIST{/trans}</a></li>
	                       {else}
	                        	<li id="toClip"><a href="#" onclick="xajax_addProductToWishList({$product.idproduct}, null); return true;">{trans}TXT_ADD_TO_WISHLIST{/trans}</a></li>
	                       {/if}
					   {/if}
                           <li id="print"><a href="{$URL}productprintbox/{$product.idproduct}">Drukuj stronę produktu</a></li>
                       </ul>
                       {if $product.producername <> ''}
                       <ul> 
                           <li id="showAll">{trans}TXT_PRODUCER{/trans}: <a href="{if $product.producerurl != ''}{$URL}{seo controller=producerlist}/{$product.producerurl}{else}#{/if}" class="producer-logo" title="{$product.producername}">{$product.producername}</a></li> 
                       </ul> 
                       {/if}
                   </div>       
               </div>
    
<div id="productTabs" class="layout-box withTabs" {if $tabbed == 0}style="border: none;"{/if}> 
	{if $tabbed == 1}      
	<ul class="tabs"> 
		{if $product.description != ''} 
    	<li><a href="#product-description"><h3>{trans}TXT_DESCRIPTION{/trans}</h3></a></li>
    	{/if}
   		{if $product.longdescription != ''} 
    	<li><a href="#product-longdescription"><h3>{trans}TXT_ADDITIONAL_INFO{/trans}</h3></a></li>
    	{/if} 
    	{if count($technicalData) > 0}
    	<li><a href="#product-technical-data"><h3>{trans}TXT_SPECIFICATIONS{/trans}</h3></a></li> 
    	{/if}
    	{if count($files) > 0}
    	<li><a href="#product-files"><h3>{trans}TXT_FILES{/trans}</h3></a></li> 
    	{/if}
    	{if $catalogmode == 0}
    	<li><a href="#product-delivery"><h3>{trans}TXT_COST_OF_DELIVERY{/trans}</h3></a></li>
    	{/if}
    	{if $enableopinions > 0} 
    	<li><a href="#product-opinions"><h3>{trans}TXT_OPINIONS{/trans}</h3></a></li>
    	{/if} 
    	{if $enabletags > 0} 
    	<li><a href="#product-tags"><h3>{trans}TXT_TAGS{/trans}</h3></a></li>
    	{/if} 
    	{if isset($tierpricing) && count($tierpricing) > 0} 
    	<li><a href="#tier-pricing"><h3>Dodatkowe rabaty</h3></a></li>
    	{/if} 
	</ul> 
	{/if}
	<div class="boxContent"> 
		{if $product.description != ''} 
		<div id="product-description" class="tabContent"> 
			{if $tabbed == 0}
			<fieldset class="listing">
	        	<legend><span>{trans}TXT_DESCRIPTION{/trans}</span></legend>
			{/if}
        	{$product.description}
        	{if $tabbed == 0}
			</fieldset>
			{/if}
        </div> 
        {/if}
        {if $product.longdescription != ''} 
        <div id="product-longdescription" class="tabContent"> 
        	{if $tabbed == 0}
			<fieldset class="listing">
	        	<legend><span>{trans}TXT_ADDITIONAL_INFO{/trans}</span></legend>
			{/if}
        	{$product.longdescription}
        	{if $tabbed == 0}
			</fieldset>
			{/if}
            
        </div> 
        {/if}
        {if $catalogmode == 0}
		<div id="product-delivery" class="tabContent"> 
			{if $tabbed == 0}
			<fieldset class="listing">
	        	<legend><span>{trans}TXT_COST_OF_DELIVERY{/trans}</span></legend>
			{/if}
				<dl> 
				{section name=d loop=$delivery}	
					<dt>{$delivery[d].name}</dt>
					<dd class="{cycle values="o,e"}">{price}{$delivery[d].dispatchmethodcost}{/price}</dd>
				{/section}	
				</dl> 
			{if $tabbed == 0}
				</fieldset>
			{/if}
        	
        </div> 
        {/if} 
        {if isset($tierpricing) && count($tierpricing) > 0} 
		<div id="tier-pricing" class="tabContent"> 
			{if $tabbed == 0}
			<fieldset class="listing">
	        	<legend><span>Dodatkowe rabaty</span></legend>
			{/if}
				<dl> 
				{section name=t loop=$tierpricing}	
					{if $smarty.section.t.first}
						{if $tierpricing[t].min == 0}
						<dt>do {$tierpricing[t].max} szt.</dt>
						{else}
						<dt>od {$tierpricing[t].min} do {$tierpricing[t].max} szt.</dt>
						{/if}
					{elseif $smarty.section.t.last}
						{if $tierpricing[t].max == 0}
							<dt>od {$tierpricing[t].min} szt.</dt>
						{else}
							<dt>od {$tierpricing[t].min} do {$tierpricing[t].max} szt.</dt>
						{/if}
					{else}
						<dt>od {$tierpricing[t].min} do {$tierpricing[t].max} szt.</dt>
					{/if}
					<dd class="{cycle values="o,e"}">{$tierpricing[t].discount}% rabatu</dd>
				{/section}	
				</dl> 
			{if $tabbed == 0}
				</fieldset>
			{/if}
        	
        </div> 
        {/if} 
         {if count($files) > 0}
        <div id="product-files" class="tabContent"> 
        	{if $tabbed == 0}
			<fieldset class="listing">
	        	<legend><span>{trans}TXT_FILES{/trans}</span></legend>
			{/if}
        	<dl> 
			{section name=f loop=$files}	
				<dt><a href="{$URL}redirect/view/{$files[f].idfile}">{$files[f].name}</a></dt>
				<dd class="{cycle values="o,e"}">&nbsp;</dd>
			{/section}	
			</dl> 
			{if $tabbed == 0}
				</fieldset>
			{/if}
        </div>  
        {/if}
        {if count($technicalData) > 0}
        <div id="product-technical-data" class="tabContent"> 
        	{section name=i loop=$technicalData}
            <fieldset class="listing">
            	<legend><span>{$technicalData[i].name}</span></legend>
                <dl> 
				{section name=a loop=$technicalData[i].attributes}	
					<dt>{$technicalData[i].attributes[a].name}</dt>
					{if $technicalData[i].attributes[a].type == 4}
						{if $technicalData[i].attributes[a].value == 1}
						<dd>{trans}TXT_YES{/trans}</dd>
					 	{else}
						<dd>{trans}TXT_NO{/trans}</dd>
					 	{/if}
					{else}
						<dd>{$technicalData[i].attributes[a].value}</dd>
					{/if}
				{/section}	
				</dl> 
			</fieldset>
            {/section}
		</div> 
        {/if}
        {if $enableopinions > 0} 
        <div id="product-opinions" class="tabContent"> 
        	{if count($range)}
        	<fieldset class="listing">
				<legend><span>{trans}TXT_AVERAGE_OPINION{/trans}</span></legend>
				<dl>
				{section name=i loop=$range}
					<dt>{$range[i].name}</dt>
					{if $range[i].mean > 0}
						<dd><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$range[i].mean}.png" alt="Ocena Klientów: {$range[i].mean}"/></dd>
					{else}
						<dd>&nbsp;<img src="{$DESIGNPATH}_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></dd>
					{/if}
				{/section}	
				</dl>
			</fieldset>
			{/if}
			{if count($productreview)>0}
				{section name=r loop=$productreview}
				<fieldset class="listing">
					<legend><span>{$productreview[r].firstname} ({$productreview[r].adddate})</span></legend>
					<p style="padding:10px;">{$productreview[r].review}</p>
					<dl>
					{section name=g loop=$productreview[r].ranges}
						<dt>{$productreview[r].ranges[g].name}</dt>
						<dd><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$productreview[r].ranges[g].value}.png" alt="Ocena Klienta: {$productreview[r].ranges[g].value}"/></dd>
					{/section}	
					</dl>
				</fieldset>
				{/section}
			{/if}
			{if isset($clientdata)} 
			<fieldset class="listing">
				<legend><span>{trans}TXT_ADD_REVIEW{/trans}</span></legend>
				<form id="review" method="post" action="#">
				{section name=i loop=$range}
					<div class="field-select"> 
						<label>{$range[i].name}</label> 
					    <span class="field"> 
						    <select name="{$range[i].id}" id="range-{$range[i].id}"> 
							    <option value="0">{trans}TXT_CHOOSE_RANGE_VALUE{/trans}</option> 
							    {section name=j loop=$range[i].values}
							    <option value="{$range[i].values[j]}">{$range[i].values[j]}</option> 
								{/section}
						    </select> 
					    </span> 
					</div> 
				{/section}
					<div class="field-textarea"> 
       					<span class="field"><textarea id="htmlopinion" name="htmlopinion" rows="10" cols="60"></textarea></span>      
    				 </div>  
					<div class="field-buttons" style="height: 30px;">
						<a id="add-review" class="button" href="#"><span>{trans}TXT_SEND{/trans}</span></a>
					</div>
				</form>
			</fieldset>
			{else}
				<p>{trans}TXT_LOGIN_TO_ADD_REVIEW{/trans}. <a href="{$URL}{seo controller=clientlogin}">{trans}TXT_LOGIN{/trans}</a></p>
			{/if}
		</div> 
        {/if}
        {if $enabletags > 0} 
        <div id="product-tags" class="tabContent"> 
	    	{if isset($clientdata)} 
	        <fieldset class="listing">
	        	<legend><span>{trans}TXT_ADD_TAG{/trans}</span></legend>
	            <form id="tags">
			        <div class="field-text">
			        	<span class="field">
			            	<input type="text" id="htmltag" size="20" maxlength="20" value="" />
			            </span>
			        </div>
			        <div class="field-buttons">
						<span class="button"><span><input type="submit" name="save" value="{trans}TXT_ADD{/trans}" onClick="xajax_addProductTags({$product.idproduct}, $('#htmltag').val());return false; "/></span></span>
					</div>
				</form>
			</fieldset>
			{/if}
			<div id="tags-cloud">
				{$tags}
			</div>
		</div>
		{/if}
		
       
	</div> 
</div>
<script type="text/javascript">
{literal}
$('#add-review').unbind('click').bind('click',function(){
	var params = {};
	var form = $('form#review').serializeArray();
	$.each(form, function(index,value) {
		params[value.name] = value.value;
	});
	return xajax_addOpinion({/literal}{$product.idproduct}{literal}, params);
});

$('#eraty').click(function(){
	
	$.fancybox({
		'overlayShow'	:	true,
		'autoScale' 	:	true,
		'type'			: 'iframe',
		'width'			: 630,
		'height'			: 500,
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'href' : 'https://www.eraty.pl/symulator/oblicz.php?numerSklepu={/literal}{$eraty.numersklepu}&wariantSklepu={$eraty.wariantsklepu}{literal}&typProduktu=0&wartoscTowarow=' + parseFloat($('#changeprice').text())
	});
});

$(document).ready(function(){

	$(".show-form").fancybox({
		'overlayShow'	:	true,
		'width'			:   280,
		'height'	    :   100,
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'scrolling'		: 	'no',
	});
	
	var liczbarat = 10;
	var oprocentowanie = 0.13;
	var rata = 0;
	var wartosc = parseFloat($('#changeprice').text());
	rata = ((((wartosc + 12) * (1 + oprocentowanie))) * 1.01) / liczbarat;
	rata = Math.round(rata * 100) / 100;
	$('#eratyvalue').text(rata);
	
	var producttrackstock = {/literal}{$product.trackstock}{literal};

	$('#width, #height').change(function(){
		var measure = ($('#width').val() * $('#height').val()) / 10000;
		$('#product-qty').val(measure);
	});
	
	$('#add-cart').unbind('click').bind('click', function(){
		if(producttrackstock == 1){
			if($('#availablestock').val() > 0){
				return xajax_addProductToCart({/literal}{$product.idproduct}{literal}, $('#attributevariants').val(), $('#product-qty').val(), $('#availablestock').val() +','+ $('#variantprice').val(), {/literal}{$product.trackstock}{literal});
			}else{
				GError('{/literal}{trans}ERR_SHORTAGE_OF_STOCK{/trans}{literal}');
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