<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/orders-edit.png" alt=""/>{trans}TXT_EDIT_ORDER{/trans} {$order.order_id} ({$order.view}) z dnia {$order.order_date}</h2>
<ul class="possibilities">
	<li><a href="{$URL}order/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_ORDER_LIST{/trans}" alt="{trans}TXT_ORDER_LIST{/trans}"/></span></a></li>
	{if $order.previous > 0}<li><a href="{$URL}order/edit/{$order.previous}" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-green.png" title="{trans}TXT_PREV_ORDER{/trans}" alt="{trans}TXT_PREV_ORDER{/trans}"/>{trans}TXT_PREV_ORDER{/trans}</span></a></li>{/if}
	{if $order.next > 0}<li><a href="{$URL}order/edit/{$order.next}" class="button"><span><img class="right "src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-right-green.png" title="{trans}TXT_NEXT_ORDER{/trans}" alt="{trans}TXT_NEXT_ORDER{/trans}"/>{trans}TXT_NEXT_ORDER{/trans}</span></a></li>{/if}
	<li><a href="{$URL}order/confirm/{$order.order_id}" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/print.png" title="{trans}TXT_NEXT_ORDER{/trans}" alt="{trans}TXT_NEXT_ORDER{/trans}"/>{trans}TXT_PRINT{/trans}</span></a></li>
	<li><a href="#edit_order" id="save_order" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
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
		<legend><span>{trans}TXT_VIEW_ORDER_BASIC_DATA{/trans}</span></legend>
		{fe_form form=$form render_mode="JS"}
		
		<div class="layout-two-columns">
		 	
			<div class="column">
				<h3><span><strong>Zmień status</strong></span></h3>
				{fe_form form=$statusChange render_mode="JS"}
			</div>
		 	
			<div class="column">
				<h3><span><strong>Dodaj notkę</strong></span></h3>
				{fe_form form=$addNotes render_mode="JS"}
			</div>
			
		</div>
		
		
	</fieldset>
	

	
	<fieldset>
		<legend><span>{trans}TXT_VIEW_ORDER_INVOICES{/trans}</span></legend>
		
		<ul class="changes-detailed">
		{section name=n loop=$order.invoices}
		<li>
			<h4><span>{$order.invoices[n].symbol} - <em>{$order.invoices[n].invoicedate}</em> <a href="{$URL}invoice/view/{$order.invoices[n].idinvoice},0">ORYGINAŁ</a> | <a href="{$URL}invoice/view/{$order.invoices[n].idinvoice},1">KOPIA</a></span></h4>
			{if $order.invoices[n].comment !=''}<p>{trans}TXT_COMMENT{/trans}: <strong>{$order.invoices[n].comment}</strong></p>{/if}
			<p>{trans}TXT_MATURITY{/trans}: <strong>{$order.invoices[n].paymentduedate}</strong></p>
			<p>{trans}TXT_SALES_PERSON{/trans}: <strong>{$order.invoices[n].salesperson}</strong></p>
			<p>{trans}TXT_TOTAL_PAYED{/trans}: <strong>{$order.invoices[n].totalpayed}</strong></p>
		</li>
		{/section}
		</ul>
		<p class="information">
		<a href="{$URL}invoice/add/{$order.order_id},1" class="button"><span>{trans}TXT_ADD_INVOICE_PRO{/trans}</span></a>
		<a href="{$URL}invoice/add/{$order.order_id},2" class="button"><span>{trans}TXT_ADD_INVOICE_VAT{/trans}</span></a>
		</p>
		<br />
		
	</fieldset>
	
	<fieldset>
		<legend><span>{trans}TXT_VIEW_ORDER_HISTORY{/trans}</span></legend>
			{if count($order.order_history) > 0}
				<ul class="changes-detailed">
					{foreach from=$order.order_history item=change}
						<li>
							<h4><span>{$change.date} - <em>{if $change.inform}{trans}TXT_VIEW_ORDER_CLIENT_INFORMED{/trans}{else}{trans}TXT_VIEW_ORDER_CLIENT_NOT_INFORMED{/trans}{/if}</em></span></h4>
							{if isset($change.orderstatusname)}<p>status: <strong>{$change.orderstatusname}</strong></p>{/if}
							{if isset($change.content)}<p>Komentarz: <strong>{$change.content}</strong></p>{/if}
							<p class="author">{trans}TXT_VIEW_ORDER_CHANGE_AUTHOR{/trans}: <strong>{$change.firstname} {$change.surname}</strong></p>
						</li>
					{/foreach}
				</ul>
			{else}
				<p class="information">{trans}TXT_VIEW_ORDER_NO_RECORDED_HISTORY{/trans}</p>
			{/if}
	</fieldset>
	{if count($order.order_files) > 0}
	<fieldset>
		<legend><span>{trans}TXT_VIEW_ORDER_FILES{/trans}</span></legend>
			{section name=f loop=$order.order_files}
				<p class="information"><a href="{$order.order_files[f].path}" target="_blank">{$order.order_files[f].path}</a></p>
			{/section}
	</fieldset>
	{/if}
	
	<fieldset>
		<legend><span>Notatka do zamowienia</span></legend>
			<ul class="changes-detailed">
				{foreach from=$orderNotes item=ordernotes}
					<li>
						<h4><span>{$ordernotes.adddate}</span></h4>
						{if isset($ordernotes.content)}<p>Komentarz: <strong>{$ordernotes.content}</strong></p>{/if}
						<p class="author">{trans}TXT_VIEW_ORDER_CHANGE_AUTHOR{/trans}: <strong>{$ordernotes.firstname} {$ordernotes.surname}</strong></p>
					</li>
				{/foreach}
			</ul>
	</fieldset>
	
	<!--<fieldset>
		<legend><span>Notatka do produktu</span></legend>
			<ul class="changes-detailed">
				Do zrobienia
			</ul>
	</fieldset>-->
	
	<fieldset>
		<legend><span>Notatka o kliencie</span></legend>
			<ul class="changes-detailed">
				{foreach from=$clientNotes item=clientnotes}
					<li>
						<h4><span>{$clientnotes.adddate}</span></h4>
						{if isset($clientnotes.content)}<p>Komentarz: <strong>{$clientnotes.content}</strong></p>{/if}
						<p class="author">{trans}TXT_VIEW_ORDER_CHANGE_AUTHOR{/trans}: <strong>{$clientnotes.clientname} {$clientnotes.clientsurname}</strong></p>
					</li>
				{/foreach}
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>Historia zamówień klienta</span></legend>
			<ul class="changes-detailed">
				{foreach from=$clientOrderHistory item=clientorderhistory}
					<li>
						<h4><span>{$clientorderhistory.adddate}</span></h4>
						<p>Nr. zamówienia:  <strong><a href="{$URL}order/edit/{$clientorderhistory.idorder}">#{$clientorderhistory.idorder}</a></strong></p>
						<p class="author">Wartość zamówienia : <strong>{$clientorderhistory.globalprice}</strong>{$currencysymbol}</p>
					</li>
				{/foreach}
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>Komentarz klienta</span></legend>
		{if isset($order.customeropinion)}
			<p class="information">{$order.customeropinion}</p>
		{else}
			<p class="information">{trans}TXT_CUSTOMER_OPINION_NO_EXIST{/trans}</p>
		{/if}
	</fieldset>
</div>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			
			var RecalculateOrder = function(eEvent, bWithDeliveryMethodsUpdate) {
				var fNetValue = parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_net_subsum span').text());
				var fVatValue = parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_vat_value span').text());
				var fWeight = parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span').text());
				fNetValue = isNaN(fNetValue) ? 0 : fNetValue;
				fVatValue = isNaN(fVatValue) ? 0 : fVatValue;
				fWeight = isNaN(fWeight) ? 0 : fWeight;
				var gSelectedDatagrid = $('.field-order-editor').get(0).gNode.m_gSelectedDatagrid;
				var aoProducts = [];
				for (var i in gSelectedDatagrid.m_aoRows) {
					aoProducts.push({
						id: gSelectedDatagrid.m_aoRows[i].idproduct,
						variant: gSelectedDatagrid.m_aoRows[i].variant,
						quantity: gSelectedDatagrid.m_aoRows[i].quantity,
						price: gSelectedDatagrid.m_aoRows[i].price
					});
				};
				$('#additional_data__summary_data__total_net_total').val(fNetValue.toFixed(2));
				$('#additional_data__summary_data__total_vat_value').val(fVatValue.toFixed(2));
				$('#pricenetto').val(fNetValue.toFixed(2));
				$('#pricebrutto').val((fNetValue + fVatValue).toFixed(2));
				
				if ((bWithDeliveryMethodsUpdate != undefined) && bWithDeliveryMethodsUpdate) {					
					xajax_GetDispatchMethodForPrice({
						products: aoProducts,
						idorder: {/literal}{$order.id}{literal},
						net_total: (fNetValue).toFixed(2),
						gross_total: (fNetValue + fVatValue).toFixed(2),
						weight_total: (fWeight).toFixed(2),
					}, GCallback(function(oResponse) {
						$('#edit_order').get(0).GetField('delivery_method').ExchangeOptions(oResponse.options);
					}));
				}
				xajax_CalculateDeliveryCost({
					products: aoProducts,
					idorder: {/literal}{$order.id}{literal},
					weight: parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span').text()),
					price_for_deliverers: $('#pricebrutto').val(),
					net_total: $('#pricenetto').val(),
					delivery_method: $('#additional_data__payment_data__delivery_method').val(),
					rules_cart: $('#additional_data__payment_data__rules_cart').val(),
					currency: $('#currencyid').val()
				}, GCallback(function(oResponse) {
					var fDeliveryValue = parseFloat(oResponse.cost);
					fDeliveryValue = isNaN(fDeliveryValue) ? 0 : fDeliveryValue;
					var fCouponValue = parseFloat(oResponse.coupon);
					fCouponValue = isNaN(fCouponValue) ? 0 : fCouponValue;
					$('#additional_data__summary_data__total_delivery').val(fDeliveryValue.toFixed(2));
					$('#dispatchmethodprice').val(fDeliveryValue.toFixed(2));
					if(oResponse.rulesCart.discount != undefined) {
						var sSymbol =  oResponse.rulesCart.symbol;
						var fDiscount = parseFloat(oResponse.rulesCart.discount);
						var fOldTotal = parseFloat(fNetValue + fVatValue + fDeliveryValue - fCouponValue);
						switch (sSymbol) {
							case '%':
								fNewTotal = fOldTotal * (fDiscount / 100);
								break;
							case '+':
								fNewTotal = fOldTotal + fDiscount;
								break;
							case '-':
								fNewTotal = fOldTotal - fDiscount;
								break;
							case '=':
								fNewTotal = fDiscount;
								break;
						}
						$('#additional_data__summary_data__total_total').val((fNewTotal).toFixed(2));
					} else {
						$('#additional_data__summary_data__total_total').val((fNetValue + fVatValue + fDeliveryValue - fCouponValue).toFixed(2));
					}
					
					$('#additional_data__summary_data__total_delivery').val(fDeliveryValue.toFixed(2));
					$('#coupon').val(fCouponValue.toFixed(2));
					$('#dispatchmethodprice').val(fDeliveryValue.toFixed(2));
				}));
			};
			
			var OnProductListChanged = GEventHandler(function(eEvent) {
				var gSelectedDatagrid = $('.field-order-editor').get(0).gNode.m_gSelectedDatagrid;
				if(gSelectedDatagrid.m_aoRows.length){
					RecalculateOrder(eEvent, true);
				}
			});
			
			$(document).ready(function() {
				$('#additional_data__payment_data__delivery_method').live('change',RecalculateOrder);
				$('#additional_data__payment_data__rules_cart').change(RecalculateOrder);
				$("<input />").attr({type:'hidden',name:'coupon',id:'coupon',value:'0'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'dispatchmethodprice',id:'dispatchmethodprice'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricebrutto',id:'pricebrutto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricenetto',id:'pricenetto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'currencyid',id:'currencyid',value:'{/literal}{$currencyid}{literal}'}).appendTo($("#edit_order"));
			});	

		/*]]>*/
	{/literal}
</script>