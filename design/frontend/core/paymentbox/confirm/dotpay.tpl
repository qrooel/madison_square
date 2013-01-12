{if isset($orderId) && $orderId>0}
<div>
	<p>Dziękujemy za dokonanie płatności poprzez system Dotpay.pl</p>
	<p>Twój numer zamówienia: <strong> {$orderId} </strong></p>
</div>	
{/if}
<div class="buttons">
	<a href="{$URL}{seo controller=mainside}/"><img src="{$DESIGNPATH}/_images_frontend/buttons/wroc-do-zakupow.png" alt="{trans}TXT_BACK_TO_SHOPPING{/trans}"/></a>
</div>		