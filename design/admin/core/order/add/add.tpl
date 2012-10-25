<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/orders-edit.png" alt=""/>{trans}TXT_EDIT_ORDER{/trans} {$order.order_id} ({$order.view}) z dnia {$order.order_date}</h2>
<ul class="possibilities">
	<li><a href="{$URL}order/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_ORDER_LIST{/trans}" alt="{trans}TXT_ORDER_LIST{/trans}"/></span></a></li>
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
						net_total: (fNetValue).toFixed(2),
						gross_total: (fNetValue + fVatValue).toFixed(2),
						currency: $('#currencyid').val(),
						weight_total: (fWeight).toFixed(2),
					}, GCallback(function(oResponse) {
						$('#edit_order').get(0).GetField('delivery_method').ExchangeOptions(oResponse.options);
					}));
				}
				xajax_CalculateDeliveryCost({
					products: aoProducts,
					weight: parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span').text()),
					price_for_deliverers: $('#pricebrutto').val(),
					net_total: $('#pricenetto').val(),
					delivery_method: $('#additional_data__payment_data__delivery_method').val(),
					rules_cart: $('#additional_data__payment_data__rules_cart').val(),
					currency: $('#currencyid').val()
				}, GCallback(function(oResponse) {
					var fDeliveryValue = parseFloat(oResponse.cost);
					fDeliveryValue = isNaN(fDeliveryValue) ? 0 : fDeliveryValue;
					$('#additional_data__summary_data__total_delivery').val(fDeliveryValue.toFixed(2));
					$('#dispatchmethodprice').val(fDeliveryValue.toFixed(2));
					$('#additional_data__summary_data__total_total').val((fNetValue + fVatValue + fDeliveryValue).toFixed(2));
					
					$('#additional_data__summary_data__total_delivery').val(fDeliveryValue.toFixed(2));
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
				$("<input />").attr({type:'hidden',name:'dispatchmethodprice',id:'dispatchmethodprice'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricebrutto',id:'pricebrutto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricenetto',id:'pricenetto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'currencyid',id:'currencyid',value:'{/literal}{$currencyid}{literal}'}).appendTo($("#edit_order"));
			});	

		/*]]>*/
	{/literal}
</script>