{if isset($orderId) && $orderId>0}
<div>
	<p>Dzi�kujemy za dokonanie p�atno�ci poprzez system Przelewy24.pl</p>
	<p>Tw�j numer zam�wienia: <strong> {$orderId} </strong></p>
</div>	
{/if}
<div class="buttons">
	<a href="{$URL}{seo controller=mainside}/" class="button"><span>{trans}TXT_BACK_TO_SHOPPING{/trans}</span></a>
</div>		