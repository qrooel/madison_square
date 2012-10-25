<fieldset class="listing">
	<legend><span>{trans}TXT_ONDELIVERY_INFO{/trans} {trans}TXT_ONDELIVERY_ADDRESS{/trans}:</span></legend>
		<dl>
			<dt>{trans}TXT_CLIENT{/trans}:</dt><dd>{$orderData.deliveryAddress.firstname} {$orderData.deliveryAddress.surname}</dd>
		</dl>
		{if $orderData.deliveryAddress.companyname != '' && $orderData.deliveryAddress.nip != ''}
		<dl>
			<dt>{trans}TXT_COMPANY_NAME{/trans}:</dt><dd>{$orderData.deliveryAddress.companyname}</dd>
		</dl>
		<dl>
			<dt>{trans}TXT_NIP{/trans}:</dt><dd>{$orderData.deliveryAddress.nip}</dd>
		</dl>
		{/if}
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