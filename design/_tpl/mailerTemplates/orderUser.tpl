<tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br />
	<table>
		<thead>
			<tr>
				<th class="name">{trans}TXT_PRODUCT_NAME{/trans}:</th>
				<th class="price">{trans}TXT_PRODUCT_PRICE{/trans}:</th>
				<th class="quantity">{trans}TXT_QUANTITY{/trans}:</th>
				<th class="subtotal">{trans}TXT_VALUE{/trans}:</th>
			</tr>
		</thead>
		<tbody>
		{foreach name=outer item=product from=$order.cart} 
			{if isset($product.standard)}
			<tr>
				<th>{$product.name}</th>
				<td>{price}{$product.newprice}{/price}</td>
				<td>{$product.qty} {trans}TXT_QTY{/trans}</td>
				<td>{price}{$product.qtyprice}{/price}</td>
			</tr>
			{/if}
			{foreach name=inner item=attributes from=$product.attributes}
				<tr>
					<th>{$attributes.name}<br />
					{foreach name=f item=features from=$attributes.features} <small>
					{$features.attributename}&nbsp;&nbsp;</small> {/foreach}</th>
					<td>{price}{$attributes.newprice}{/price}</td>
					<td>{$attributes.qty} {trans}TXT_QTY{/trans}</td>
					<td>{price}{$attributes.qtyprice}{/price}</td>
				</tr>
			{/foreach} 
		{/foreach}
		</tbody>
	</table>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}: </b></font><br />
	{if isset($order.rulescart)}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.rulescart}: <strong>{$order.rulescartmessage}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_VIEW_ORDER_TOTAL{/trans}: <strong>{price}{$order.priceWithDispatchMethodPromo}{/price}</strong></p>
	{else}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_ALL_ORDERS_PRICE_GROSS{/trans}: <strong>{price}{$order.priceWithDispatchMethod}{/price}</strong></p>
	{/if}
	<p>{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}</p>
	<p>{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}</p>
	</td>
</tr>
<!--<tr>-->
<!--	<td>-->
<!--	<p>{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />-->
<!--	<a href="{$URL}confirmation/index/{$orderlink}">{$URL}confirmation/index/{$orderlink}</a>-->
<!--	</p>-->
<!--	</td>-->
<!--</tr>-->
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />
	{if $order.clientaddress.companyname !=
	''}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname}
	<br>{/if} {if $order.clientaddress.nip != ''}{trans}TXT_NIP{/trans} :
	{$order.clientaddress.nip} 
	
	
	<br>{/if} {trans}TXT_FIRSTNAME{/trans} :
	{$order.clientaddress.firstname} 
	
	
	<br> {trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.clientaddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />
	{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>
	{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br />
	<p>{$order.customeropinion}</p>
	</td>
</tr>
