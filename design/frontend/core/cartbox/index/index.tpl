<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.scrollTo.min.js"></script>
{if isset($paymenterror)}
<script>
{literal}
GError('{/literal}{$paymenterror}{literal}','');
{/literal}
</script>
{/if} 
{if isset($dispatcherror)}
<script>
{literal}
GError('{/literal}{$dispatcherror}{literal}','');
{/literal}
</script>
{/if} 
{if ($count) > 0}
<table class="cart" cellspacing="0">
	<thead>
		<tr>
			<th class="name">{trans}TXT_PRODUCT_NAME{/trans}</th>
			<th class="price">{trans}TXT_PRICE{/trans}</th>
			<th class="quantity">{trans}TXT_PRODUCT_QUANTITY{/trans}</th>
			<th class="subtotal">{trans}TXT_PRODUCT_SUBTOTAL{/trans}</th>
			<th class="delete">{trans}TXT_DELETE{/trans}</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$productCart item=product key=key}	
		{if ($productCart[$key].standard == 1)}
		<tr class="{cycle values="o,e"}">	
			<th scope="row" class="name">
				<a href="{$URL}{seo controller=productcart}/{$productCart[$key].seo}">
					<span class="picture">
						<img src="{$productCart[$key].smallphoto}" alt="{trans}TXT_MAIN_PHOTO{/trans}"/>
					</span>
					<strong>{$productCart[$key].name}</strong>
				</a>
			</th>
			<th class="price">
				{price}{$productCart[$key].newprice}{/price}
			</th>
			<th class="quantity">
				<img style="cursor: pointer" src="{$DESIGNPATH}/_images_frontend/core/buttons/minus-2.png" alt="({trans}TXT_LESS{/trans})" onClick="xajax_checkQuantityDec({$productCart[$key].idproduct}, null); return false;"/>
				<input class="product-quantity" type="text" title="{$productCart[$key].qty}" name="quantity[{$productCart[$key].idproduct}]" value="{$productCart[$key].qty}" onchange="xajax_changeQuantity({$productCart[$key].idproduct},null,this.value);"/>
				<img style="cursor: pointer" src="{$DESIGNPATH}/_images_frontend/core/buttons/plus-2.png" alt="({trans}TXT_LESS{/trans})" onClick="xajax_checkQuantityInc({$productCart[$key].idproduct}, null); return false;"/>
			</th>
			<td class="subtotal">
				{price}{$productCart[$key].qtyprice}{/price}
			</td>	
			<td class="delete">
				<input type="submit" name="delete[{$productCart[$key].idproduct}]" value="{trans}TXT_DELETE{/trans}" onClick="xajax_deleteProductFromCart({$productCart[$key].idproduct}, null); return false;" />
			</td>
		</tr>
		{/if}
		{if ($productCart[$key].attributes <> NULL)}
		{foreach from=$productCart[$key].attributes item=attribprod key=key}
		<tr class="{cycle values="o,e"}">
			<th scope="row" class="name">
				<a href="{$URL}{seo controller=productcart}/{$attribprod.seo}">
					<span class="picture">
						<img src="{$attribprod.smallphoto}" alt="{trans}TXT_MAIN_PHOTO{/trans}"/>
					</span>
					<strong>{$attribprod.name}</strong>
				</a>
				{foreach from=$attribprod.features item=feature key=key}
					{$feature.group}: {$feature.attributename}<br />
				{/foreach}
		</th>
		<th class="price">
			{price}{$attribprod.newprice}{/price}
		</th>
		<th class="quantity">
			<img style="cursor: pointer" src="{$DESIGNPATH}/_images_frontend/core/buttons/minus-2.png" alt="({trans}TXT_LESS{/trans})"  onClick="xajax_checkQuantityDec({$attribprod.idproduct},{$attribprod.attr}); return false;"/>
			<input class="product-quantity" type="text" title="{$attribprod.qty}" name="quantity[{$attribprod.idproduct}]" value="{$attribprod.qty}" onchange="xajax_changeQuantity({$attribprod.idproduct},{$attribprod.attr},this.value);" />
			<img style="cursor: pointer" src="{$DESIGNPATH}/_images_frontend/core/buttons/plus-2.png" alt="({trans}TXT_LESS{/trans})" onClick="xajax_checkQuantityInc({$attribprod.idproduct},{$attribprod.attr}); return false;"/>
		</th>
		<th class="subtotal">
			{price}{$attribprod.qtyprice}{/price}
		</th>
		<th class="delete">
			<input type="submit" name="delete[{$attribprod.idproduct}]" value="{trans}TXT_DELETE{/trans}" onClick="xajax_deleteProductFromCart({$attribprod.idproduct}, {$attribprod.attr}); return false;"/>
		</th>
		{/foreach}
		{/if}
	{/foreach}	
	</tbody>
</table>
{if $guestcheckout == 0}
<p style="text-align: center;">{trans}TXT_GUEST_CHECKOUT_DISABLED{/trans}</p>
<p style="text-align: center;"><span class="{seo_js controller=clientlogin}">{trans}TXT_LOGIN_PROCESS{/trans}</span> {trans}TXT_OR{/trans} <span class="{seo_js controller=registrationcart}">{trans}TXT_REGISTRATION{/trans}</span></p>
{else}

{if isset($coupons)}
	{fe_form form=$coupons render_mode="JS"}
{/if} 	

{if $minimumordervalue > 0}
<fieldset class="listing">
	<legend><span>{trans}TXT_MINIMUM_ORDER_VALUE{/trans}</span></legend>
	<p>{trans}TXT_MINIMUM_ORDER_VALUE_REQUIRED{/trans} <strong>{price}{$minimumordervalue}{/price}</strong></p>
</fieldset>
{else}
{if (isset($checkRulesCart))}

{foreach from=$checkRulesCart item=rule key=key}
{if $checkRulesCart[$key].type == 0}
<fieldset class="listing">
	<legend><span>{trans}TXT_MEET_CONDITION_FOR_DISCOUNT{/trans} {if (isset($checkRulesCart[$key].discount))}{$checkRulesCart[$key].discount}{/if}</span></legend>
	<img src="{$DESIGNPATH}_images_frontend/core/icons/gifts.png" style="float: left;margin-right: 10px;"/>
	{foreach from=$checkRulesCart[$key].conditions item=condition key=k}
		<p>{$checkRulesCart[$key].conditions[$k]}</p>
	{/foreach}
</fieldset>
{/if}
{/foreach}
{/if}

<div class="layout-two-columns">
	<div class="column">
		<fieldset class="listing" id="delivery">
			<legend><span>{trans}TXT_DELIVERY_TYPE{/trans}</span></legend>
			<dl>
				{foreach from=$deliverymethods item=delivery key=key}
					<dt>
					<label>
					<input type="radio" name="del" id="delivery-{$delivery.dispatchmethodid}" value="{$delivery.dispatchmethodid}" {if ($delivery.dispatchmethodid == $checkedDelivery.dispatchmethodid)}checked="checked"{/if} onclick="xajax_setDispatchmethodChecked({$delivery.dispatchmethodid}); return false;" />
					{$delivery.name} {if $delivery.description <> ''}<a href="#delivery-help-{$delivery.dispatchmethodid}" class="show-tooltip" ><img src="{$DESIGNPATH}_images_frontend/core/icons/help-2.png" /></a>{/if}
					</label>
					</dt>
					<dd>
					{price}{$delivery.dispatchmethodcost}{/price}
					</dd>
					<div style="display:none;">
						<div style="width:380px;" id="delivery-help-{$delivery.dispatchmethodid}">{if $delivery.photo != NULL}<img src="{$delivery.photo}" />{/if}{$delivery.description}</div>
					</div>
				{/foreach}
			</dl> 
		</fieldset>
	</div>
	<div class="column">
		<div id="payment">
		{$payment}
		</div>
	</div>
</div>
<div id="finalization">
{$finalization}
</div>
{fe_form form=$form render_mode="JS"} 
{if $uploadSettings.uploaderenabled == 1 && $uploadSettings.uploadmaxfilesize > 0 && $uploadSettings.uploadchunksize > 0 && !empty($uploadSettings.uploadextensions)}
<fieldset class="listing">
	<legend><span>{trans}TXT_ORDER_FILES_UPLOAD{/trans}</span></legend>
	<div id="container" style="padding-top: 0px;">
		<div id="filelist">{trans}TXT_ORDER_FILES_UPLOAD_HELP{/trans}</div>
		<br />
		<a id="pickfiles" href="#" class="button-red">{trans}TXT_SELECT_FILES{/trans}</a>
		<a id="uploadfiles" href="#" class="button-red">{trans}TXT_UPLOAD_FILES{/trans}</a>
	</div>
</fieldset>
{/if}
{if count($deliverymethods) > 0}
<div class="buttons" style="width: 100%">
	<a href="#" class="button" style="float: left; margin-left: 10px;" onclick="history.back();return false;"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
	<a href="#" class="submit-order" style="float: right" onclick="$('#order').submit();return false; ">{trans}TXT_PLACE_ORDER{/trans}</a>
</div>
{else}
	<p style="font-size: 18px;color: #990000;text-align: center;">{trans}TXT_NO_SHIPPING_FOR_THIS_COUNTRY{/trans}</p>
{/if}


{/if}

{/if}

{else}
	<span style="text-align: center; margin : 10px;"> 
		<h4>{trans}TXT_CART_IS_EMPTY{/trans}</h4>
	</span>
{/if}	

<script type="text/javascript">
{literal}
function restoreQty(){
	$('.product-quantity').each(function(){
		$(this).val($(this).attr('title'));
	});
}

$(document).ready(function() {
	
	$(".show-tooltip").fancybox({
		'overlayShow'	:	true,
		'width'			: 280,
		'height'			: 100,
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'scrolling'		: 	'no',
	});

	$('#billing_data__billing_address_columns__right_billing__country, #shipping_data__shipping_address_columns__right_shipping__country').change(function(){
		if($('#shipping_data__copy').val() == 1){
			return xajax_setAjaxShippingCountryId ($(this).val());
		}
	});

	$('form').submit(function(){
		var errors = $('.invalid').size();
		if(errors > 0){
			$.scrollTo($('#billing_data'));
		}
	});
	
});
{/literal}
</script>
{if $uploadSettings.uploaderenabled == 1 && $uploadSettings.uploadmaxfilesize > 0 && $uploadSettings.uploadchunksize > 0 && !empty($uploadSettings.uploadextensions)}
<script type="text/javascript" src="{$DESIGNPATH}_js_libs/plupload/plupload.full.js"></script>
<script type="text/javascript">
{literal}
$(function() {
	var uploader = new plupload.Uploader({
		runtimes : 'flash',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '{/literal}{$uploadSettings.uploadmaxfilesize}{literal}mb',
		chunk_size : '{/literal}{$uploadSettings.uploadchunksize}{literal}kb',
		unique_names: true,
		url : '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/add',
		flash_swf_url : '{/literal}{$DESIGNPATH}{literal}_js_libs/plupload/plupload.flash.swf',
		filters : [
			{title : "{/literal}{$uploadSettings.uploadextensions}{literal}", extensions : "{/literal}{$uploadSettings.uploadextensions}{literal}"},
		],
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append(
				'<div id="' + file.id + '">' +
				file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
			'</div>');
		});
		up.refresh();
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);
		up.refresh(); 
	});

	uploader.bind('FileUploaded', function(up, file) {
		$('#' + file.id + " b").html("100%");
	});
});
{/literal}
</script>
{/if}