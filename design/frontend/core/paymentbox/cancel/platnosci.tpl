{if isset($orderId) && $orderId > 0}
<div>
	<p>{trans}TXT_PLATNOSCI_CANCELLED{/trans}</p>
	<p>Twój numer zamówienia: <strong> {$orderId} </strong></p>
</div>	
{/if}
<div class="buttons">
	<a href="{$URL}{seo controller=mainside}/" class="button"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
</div>	