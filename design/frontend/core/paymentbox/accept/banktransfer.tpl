<fieldset class="listing">
	<h2><span>{trans}TXT_BANKTRANSFER_INFO{/trans}:</span></h2>
	{if isset($content) && !empty($content)}
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
			<dt>{trans}TXT_BANK_DATA{/trans}:</dt><dd><strong>{$content.banknr} - {$content.bankname}</strong></dd>
		</dl>
		<dl>
			<dt>{trans}TXT_TITLE{/trans}:</dt><dd>{$orderData.clientdata.firstname} {$orderData.clientdata.surname}, {trans}TXT_ORDER_NUMER{/trans}: {$orderId}</dd>
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
	{else}
		<div>
			<p>{trans}EMPTY_BANKTRNASFER_INFO_ADMIN_CONTACT{/trans}</p>
		</div>
	{/if}
</fieldset>
								
<div class="buttons">
	<a href="{$URL}{seo controller=mainside}/" class="button"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
</div>					