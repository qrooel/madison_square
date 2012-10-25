<div>
	<table border="0">	
		<tr>
			<td align="left"><font size="25">{trans}TXT_ORDER{/trans}: {$order.order_id}<br /><small>{trans}TXT_SALE_DATE{/trans}: {$order.order_date}</small></font></td>
		</tr>
	</table>
</div>
<div>
	<table border="0">
		<tr>
			<td align="left">
				<table border="0">
					<tr>
						<th align="left">
							<font size="12"><b>{trans}TXT_SELLER{/trans}: </b></font><br />
							<font color="grey">
								{$companyaddress.shopname}<br />
								{$companyaddress.street} {$companyaddress.streetno} / {$companyaddress.placeno}<br />
								{$companyaddress.postcode} {$companyaddress.placename}<br />
								{trans}TXT_NIP{/trans}: {$companyaddress.nip}
							</font>
						</th>
						<th>
							<font size="12"><b>{trans}TXT_TRANSFEREE{/trans}: </b></font><br />
							<font color="grey">
								{if $order.billing_address.companyname != ''}
									{$order.billing_address.companyname}<br />
								{else}
									{$order.billing_address.firstname} {$order.billing_address.surname}<br />
								{/if}
								{if $order.billing_address.nip !=''}
									{trans}TXT_NIP{/trans}: {$order.billing_address.nip}
								{/if}
								{$order.billing_address.street} {$order.billing_address.streetno} / {$order.billing_address.placeno}<br />
								{$order.billing_address.postcode} {$order.billing_address.city}<br />
								{trans}TXT_PHONE{/trans}: {$order.billing_address.phone}<br />
								{$order.billing_address.email}<br />
							</font>
						</th>
						<th>
							<font size="12"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />
							<font color="grey">
								{if $order.delivery_address.companyname != ''}
									{$order.delivery_address.companyname}<br />
								{else}
									{$order.delivery_address.firstname} {$order.delivery_address.surname}<br />
								{/if}
								{if $order.delivery_address.nip !=''}
									{trans}TXT_NIP{/trans}: {$order.delivery_address.nip}
								{/if}
								{$order.delivery_address.street} {$order.delivery_address.streetno} / {$order.delivery_address.placeno}<br />
								{$order.delivery_address.postcode} {$order.delivery_address.city}<br />
								{trans}TXT_PHONE{/trans}: {$order.delivery_address.phone}<br />
								{$order.delivery_address.email}<br />
								{$order.delivery_method.deliverername}
							</font>
						</th>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<div id="invoicedata">
	<table border="0">
		<tr>
			<td align="left">
				<table border="1">
					<tr>
						<td align="center" width="5%"><small><b>Lp.</b></small></td>
						<td align="left" width="20%"><small><b>{trans}TXT_PRODUCT_NAME{/trans}</b></small></td>
						<td align="left" width="10%"><small><b>{trans}TXT_EAN{/trans}</b></small></td>
						<td align="center" width="5%"><small><b>{trans}TXT_UNIT_MEASURE{/trans}</b></small></td>
						<td align="center" width="5%"><small><b>{trans}TXT_PRODUCT_QUANTITY{/trans}</b></small></td>
						<td align="center" width="10%"><small><b>{trans}TXT_JS_RANGE_EDITOR_VAT{/trans}</b></small></td>
						<td align="center" width="10%"><small><b>{trans}TXT_JS_PRODUCT_SELECT_NET_SUBSUM{/trans}</b></small></td>
						<td align="center" width="15%"><small><b>{trans}TXT_VAT_AMOUNT{/trans}</b></small></td>
						<td align="center" width="20%"><small><b>{trans}TXT_JS_PRODUCT_SELECT_SUBSUM{/trans}</b></small></td>
					</tr>
					{section name=i loop=$order.products}
					<tr>
						<td align="center" width="5%"><font color="grey"><small>{$order.products[i].lp}</small></font></td>
						<td align="left" width="20%"><font color="grey"><small>{$order.products[i].name}
						{if count($order.products[i].attributes) > 0}
							<br />{$order.products[i].attributes.name}
						{/if}
						</small></font></td>
						<td align="center" width="10%"><font color="grey"><small>{$order.products[i].ean}</small></font></td>
						<td align="center" width="5%"><font color="grey"><small>szt.</small></font></td>
						<td align="center" width="5%"><font color="grey"><small>{$order.products[i].quantity}</small></font></td>
						<td align="center" width="10%"><font color="grey"><small>{$order.products[i].vat} %</small></font></td>
						<td align="center" width="10%"><font color="grey"><small>{$order.products[i].net_subtotal}</small></font></td>
						<td align="center" width="15%"><font color="grey"><small>{$order.products[i].vat_value}</small></font></td>
						<td align="center" width="20%"><font color="grey"><small>{$order.products[i].subtotal}</small></font></td>
					</tr>	
					{/section}	
					<tr><td colspan="9"><br /></td></tr>
					<tr>
						<td align="right" width="45%" colspan="5"><font color="grey">{trans}TXT_TOGETHER{/trans}</font></td>
						<td align="center" width="10%"><font color="grey"><small>X</small></font></td>
						<td align="center" width="15%"><font color="grey"><small>{price}{$total.netto}{/price}</small></font></td>
						<td align="center" width="10%"><font color="grey"><small>{price}{$total.vatvalue}{/price}</small></font></td>
						<td align="center" width="20%"><font color="grey"><small>{price}{$total.brutto}{/price}</small></font></td>
					</tr>
					{foreach from=$summary item=summary}
					<tr>
						<td align="right" width="45%" colspan="5"><font color="grey">{trans}TXT_CONTAIN{/trans}</font></td>
						<td align="center" width="10%"><font color="grey"><small>{$summary.vat} %</small></font></td>
						<td align="center" width="15%"><font color="grey"><small>{price}{$summary.netto}{/price}</small></font></td>
						<td align="center" width="10%"><font color="grey"><small>{price}{$summary.vatvalue}{/price}</small></font></td>
						<td align="center" width="20%"><font color="grey"><small>{price}{$summary.brutto}{/price}</small></font></td>
					</tr>	
					{/foreach}
				</table>
			</td>
		</tr>
	</table>
</div>
			
<div id="pricesumary" align="right">
	<table border="0">	
		<tr >
			<td align="left"><font size="8" color="grey">{trans}TXT_PAYMENT_METHOD{/trans}:<br />{trans}{$order.payment_method.paymentname}{/trans}</font></td>
		</tr>
		<tr >
			<td align="left"><font size="8" color="grey">{trans}TXT_ORDER_STATUS{/trans}:<br />{$order.current_status}</font></td>
		</tr>
	</table>
</div>
<div id="pricesumary" align="right">
	<table border="0" align="right">
		<tr>
			<td align="left"><font size="8" color="grey">{trans}TXT_COMMENT{/trans}: {$order.customeropinion} </font></td>
		</tr>
	</table>
</div>
