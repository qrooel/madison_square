<ol class="order-stage">
	<li class="settings"><a href="{$URL}{seo controller=clientsettings}/">{trans}TXT_SETTINGS{/trans}</a></li>
	<li class="orders active"><a href="{$URL}{seo controller=clientorder}/">{trans}TXT_ORDERS{/trans}</a></li>
	<li class="address"><a href="{$URL}{seo controller=clientaddress}/">{trans}TXT_CLIENT_ADDRESS{/trans}</a></li>
</ol>
						
{if !empty($orderproductlist)}
<fieldset class="listing">
	<legend><span>{trans}TXT_ORDERS_NR{/trans}: {$order.idorder}</span></legend>
		<dl>
			<dt>{trans}TXT_ALL_ORDERS_PRICE{/trans}</dt><dd>{$order.globalprice} {$order.currencysymbol}</dd>
			<dt>{trans}TXT_STATUS{/trans}</dt><dd>{$order.orderstatusname}</dd>
			<dt>{trans}TXT_DATE{/trans}</dt><dd>{$order.orderdate}</dd>
			<dt>{trans}TXT_PAYMENT{/trans}</dt><dd>{$order.paymentmethodname}</dd>
			<dt>{trans}TXT_DISPATCH{/trans}</dt><dd>{$order.dispatchmethodname} ({$order.dispatchmethodprice} {$order.currencysymbol})</dd>
		</dl> 
</fieldset>
<fieldset class="listing">
	<legend><span>{trans}TXT_VIEW_ORDER_PRODUCTS{/trans}</span></legend>
	<table class="cart" cellspacing="0">
		<thead>
			<tr>
				<th class="name">{trans}TXT_PRODUCT_NAME{/trans}:</th>
				<th class="price">{trans}TXT_PRODUCT_PRICE{/trans}:</th>
				<th class="quantity">{trans}TXT_QUANTITY{/trans}:</th>
				<th class="subtotal">{trans}TXT_VALUE{/trans}:</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$orderproductlist}	
			<tr class="{cycle values="o,e"}">
				<th scope="row" class="name">
				{if isset($orderproductlist[i].idproduct) && ($orderproductlist[i].idproduct) > 0}
					<a href="{$URL}{seo controller=productcart}/{$orderproductlist[i].seo}">
						<strong>{$orderproductlist[i].productname}</strong>
						<small>								
						{section name=k loop=$orderproductlist[i].attributes}	
							{$orderproductlist[i].attributes[k].attributename}
						{/section}
						</small>
					</a>
				</th>
				{else}
					<strong>{$orderproductlist[i].productname}</strong>
					<small>								
					{section name=k loop=$orderproductlist[i].attributes}	
						{$orderproductlist[i].attributes[k].attributename}
					{/section}
					</small>
				{/if}
				<td class="price">
					{$orderproductlist[i].price} {trans}TXT_CURRENCY{/trans}
				</td>
				<td class="quantity">
					{$orderproductlist[i].qty}
				</td>
				<td class="subtotal">
					{$orderproductlist[i].qtyprice}
				</td>
			</tr>
			{/section}	
		</tbody>
	</table>
</fieldset>					
<div class="layout-two-columns">
	<div class="column">
		<fieldset class="listing">
			<legend><span>{trans}TXT_EDIT_ORDER_BILLING_DATA{/trans}:</span></legend>
			<p>{$order.billingaddress.firstname} {$order.billingaddress.surname}</p>
			<p>{$order.billingaddress.street} {$order.billingaddress.streetno} / {$order.billingaddress.placeno}</p>
			<p>{$order.billingaddress.postcode} {$order.billingaddress.placename}</p>
			<p>{$order.billingaddress.phone}</p>
			<p>{$order.billingaddress.email}</p>
		</fieldset>
	</div>
	<div class="column">
		<fieldset class="listing">
			<legend><span>{trans}TXT_EDIT_ORDER_SHIPPING_DATA{/trans}:</span></legend>
			<p>{$order.shippingaddress.firstname} {$order.shippingaddress.surname}</p>
			<p>{$order.shippingaddress.street} {$order.shippingaddress.streetno} / {$order.shippingaddress.placeno}</p>
			<p>{$order.shippingaddress.postcode} {$order.shippingaddress.placename}</p>
			<p>{$order.shippingaddress.phone}</p>
			<p>{$order.shippingaddress.email}</p>
		</fieldset>
	</div>
</div>
{if count($order.invoices) > 0}
<fieldset>
	<legend><span>{trans}TXT_VIEW_ORDER_INVOICES{/trans}</span></legend>
	<dl>
	{section name=n loop=$order.invoices}
		<dt><a href="{$URL}invoice/{$order.invoices[n].idinvoice}">{$order.invoices[n].symbol}</a></dt>
		<dd class="{cycle values="e,o"}">{$order.invoices[n].invoicedate}</dd>
	{/section}
	</dl> 
</fieldset>			
{/if}
{/if}
<fieldset class="listing">
	<legend><span>{trans}TXT_HISTORY_ORDERS{/trans}</span></legend>
		<dl>
		{section name=i loop=$orderlist}
			<dt><a href="{$URL}{seo controller=clientorder}/{$orderlist[i].idorder}">{trans}TXT_ORDERS_NR{/trans}: {$orderlist[i].idorder}</a></dt>
			<dd class="{cycle values="e,o"}">{trans}TXT_ORDERS_SUBMITTED{/trans} {$orderlist[i].orderdate}</dd>
		{/section}
		</dl> 
</fieldset>
