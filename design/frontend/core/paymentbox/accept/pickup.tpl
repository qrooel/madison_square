<fieldset class="listing">
	<legend><span>{trans}TXT_PICKUP_RECEIPT{/trans}:</span></legend>
		<dl>
			<dt>{trans}TXT_COMPANYNAME{/trans}:</dt><dd>{$content.companyname}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_SHOPNAME{/trans}:</dt><dd>{$content.shopname}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_STREET{/trans}:</dt><dd>{$content.street} {$content.streetno} {if $content.placeno != ''} / {$content.placeno}{/if}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_CITY{/trans}:</dt><dd>{$content.postcode} {$content.placename}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_CLIENT{/trans}:</dt><dd>{$orderData.deliveryAddress.firstname} {$orderData.deliveryAddress.surname}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_STREET{/trans}:</dt><dd>{$orderData.deliveryAddress.street} {$orderData.deliveryAddress.streetno} {if $orderData.deliveryAddress.placeno != ''} / {$orderData.deliveryAddress.placeno}{/if}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_CITY{/trans}:</dt><dd>{$orderData.deliveryAddress.postcode} {$orderData.deliveryAddress.placename}</dd>
		</dl>
		{if isset($orderData.priceWithDispatchMethodPromo) && $orderData.priceWithDispatchMethodPromo >0}
		<dl>
			<dt>{trans}TXT_PRICE_WITH_DISPATCHMETHOD{/trans}:</dt><dd>{price}{$orderData.priceWithDispatchMethodPromo}{/price}</dd>
		</dl>
		{else}
		<dl>
			<dt>{trans}TXT_PRICE_WITH_DISPATCHMETHOD{/trans}:</dt><dd>{price}{$orderData.priceWithDispatchMethod}{/price}</dd>
		</dl>
		{/if}
</fieldset>
								
<div class="buttons">
	<a href="{$URL}{seo controller=mainside}/" class="button"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
</div>					