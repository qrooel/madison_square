<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/orders-edit.png" alt=""/>{trans}TXT_SPY{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}spy/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_SPY_LIST{/trans}" alt="{trans}TXT_SPY_LIST{/trans}"/></span></a></li>
</ul>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			GCore.OnLoad(function() {
				
				$('.view-order').GTabs();
				
			});
		/*]]>*/
	{/literal}
</script>

<div class="view-order GForm">

	<fieldset>
		<legend><span>{trans}TXT_CLIENT{/trans}</span></legend>
			<ul class="changes-detailed">
				<li>
				{if !isset($clientData.firstname)}
					<p>{trans}TXT_GUEST{/trans}</p>
				{else}
					<p>{trans}TXT_FIRSTNAME{/trans}:  <strong>{$clientData.firstname}</strong></p>
					<p>{trans}TXT_SURNAME{/trans}: <strong>{$clientData.surname}</strong></p>
				</li>
				{/if}
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>{trans}TXT_CART{/trans}</span></legend>
			<ul class="changes-detailed">
			{if !isset($cart)}
				<p>{trans}ERR_EMPTY_PRODUCT_LIST{/trans}</p>
			{else}
				{foreach from=$cart item=product}
					<li>
						<p>{trans}TXT_PRODUCT{/trans}:  <strong>{$product.name}</strong></p>
						<p>{trans}TXT_QUANTITY{/trans}: <strong>{$product.qty}</strong> {trans}TXT_QTY{/trans}</p>
						<p>{trans}TXT_PRICE{/trans}: <strong>{$product.qtyprice}</strong> {trans}TXT_CURRENCY{/trans}</p>
					</li>
				{/foreach}
			{/if}
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>Historia zamówień klienta</span></legend>
			{if !isset($clientData.firstname)}
				<p>{trans}TXT_GUEST{/trans}</p>
			{else}
			<ul class="changes-detailed">
				{if isset($clientOrderHistory[0].adddate)}
				{foreach from=$clientOrderHistory item=clientorderhistory}
					<li>
						<h4><span>{$clientorderhistory.adddate}</span></h4>
						<p>Nr. zamówienia:  <strong><a href="{$URL}order/edit/{$clientorderhistory.idorder}">#{$clientorderhistory.idorder}</a></strong></p>
						<p class="author">Wartość zamówienia : <strong>{$clientorderhistory.globalprice}</strong>{trans}TXT_CURRENCY{/trans}</p>
					</li>
				{/foreach}
				{else}
				Brak zamówień
				{/if}
			</ul>
			{/if}
	</fieldset>