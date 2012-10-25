<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/orders-edit.png" alt=""/>{trans}TXT_EDIT_ORDER{/trans} {$order.order_id} ({$order.view}) z dnia {$order.order_date}</h2>
<ul class="possibilities">
	<li><a href="{$URL}order/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_ORDER_LIST{/trans}" alt="{trans}TXT_ORDER_LIST{/trans}"/></span></a></li>
	{if $order.previous > 0}<li><a href="{$URL}order/edit/{$order.previous}" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-green.png" title="{trans}TXT_PREV_ORDER{/trans}" alt="{trans}TXT_PREV_ORDER{/trans}"/>{trans}TXT_PREV_ORDER{/trans}</span></a></li>{/if}
	{if $order.next > 0}<li><a href="{$URL}order/edit/{$order.next}" class="button"><span><img class="right "src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-right-green.png" title="{trans}TXT_NEXT_ORDER{/trans}" alt="{trans}TXT_NEXT_ORDER{/trans}"/>{trans}TXT_NEXT_ORDER{/trans}</span></a></li>{/if}
	<li><a href="{$URL}order/confirm/{$order.order_id}" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/print.png" title="{trans}TXT_NEXT_ORDER{/trans}" alt="{trans}TXT_NEXT_ORDER{/trans}"/>{trans}TXT_PRINT{/trans}</span></a></li>
	<li><a href="#edit_order" id="save_order" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>

{fe_form form=$form render_mode="JS"}

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
				$('#total_delivery + .waiting, #total_total + .waiting').remove();
				$('#total_delivery, #total_total').after('<img class="waiting" src="{/literal}{$DESIGNPATH}{literal}_images_panel/icons/loading/indicator.gif" alt=""/>');
				$('#total_net_total').text(fNetValue.toFixed(2));
				$('#total_vat_value').text(fVatValue.toFixed(2));
				$('#pricenetto').val(fNetValue.toFixed(2));
				$('#pricebrutto').val((fNetValue + fVatValue).toFixed(2));
				
				if ((bWithDeliveryMethodsUpdate != undefined) && bWithDeliveryMethodsUpdate) {					
					xajax_GetDispatchMethodForPrice({
						net_total: (fNetValue).toFixed(2),
						gross_total: (fNetValue + fVatValue).toFixed(2),
						idorder: $('#idorder').attr('value'),
						currency: $('#currencyid').attr('value'),
						weight_total: (fWeight).toFixed(2),
					}, GCallback(function(oResponse) {
						$('#edit_order').get(0).GetField('delivery_method').ExchangeOptions(oResponse.options);
						$('#additional_data__delivery_data__delivery_method').change(RecalculateOrder);
						$('#additional_data__rulescart_data__rules_cart').change(RecalculateOrder);
					}));
				}
				xajax_CalculateDeliveryCost({
					products: aoProducts,
					idorder: $('#idorder').attr('value'),
					weight: parseFloat($('.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span').text()),
					price_for_deliverers: $('#pricebrutto').attr('value'),
					net_total: $('#pricenetto').attr('value'),
					delivery_method: $('#additional_data__delivery_data__delivery_method option:selected').attr('value'),
					rules_cart: $('#additional_data__rulescart_data__rules_cart option:selected').attr('value'),
					currency: $('#currencyid').attr('value')
				}, GCallback(function(oResponse) {
					var fDeliveryValue = parseFloat(oResponse.cost);
					fDeliveryValue = isNaN(fDeliveryValue) ? 0 : fDeliveryValue;
					$('#total_delivery').text(fDeliveryValue.toFixed(2));
					$('#dispatchmethodprice').val(fDeliveryValue.toFixed(2));
					if(oResponse.rulesCart.discount != undefined) {
						var sSymbol =  oResponse.rulesCart.symbol;
						var fDiscount = parseFloat(oResponse.rulesCart.discount);
						var fOldTotal = parseFloat(fNetValue + fVatValue + fDeliveryValue);
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
						$('#total_total').html('<small>{/literal}{trans}TXT_DISCOUNT{/trans}{literal}'+ ' ' 
								+ fDiscount + '' + sSymbol+ '<br/><s style="color: black;">' +  (fOldTotal).toFixed(2)+ '</s></small><br/>'
								+ '<font style="color: red;">' + (fNewTotal).toFixed(2) + '</font>');
					} else {
						$('#total_total').text((fNetValue + fVatValue + fDeliveryValue).toFixed(2));
					}
					$('#total_delivery').text(fDeliveryValue.toFixed(2));
					$('#dispatchmethodprice').val(fDeliveryValue.toFixed(2));
					$('#hash').text((fNetValue + fVatValue + fDeliveryValue).toFixed(2));
					$('#total_delivery + .waiting, #total_total + .waiting').remove();
				}));
			};
			
			var OnProductListChanged = GEventHandler(function(eEvent) {
				var gSelectedDatagrid = $('.field-order-editor').get(0).gNode.m_gSelectedDatagrid;
				if(gSelectedDatagrid.m_aoRows.length){
					RecalculateOrder(eEvent, true);
				}
			});
			
			var RecalculateCurrency = function(fOnRecalculate) {
				xajax_RecalculateCurrency({
					sOrderId: '{/literal}{$order.id}{literal}'
				}, GCallback(fOnRecalculate));
			};
			
			var OnRecalculateCurrency = GEventHandler(function(eEvent) {
				var gOrderEditor = $('.field-order-editor').get(0).gNode;
				gOrderEditor.m_gForm.m_oOptions.oValues['products_data']['products'] = eEvent.aoProducts;
				gOrderEditor.Populate(eEvent.aoProducts);
				$('.currency-warning').slideUp(500, function() {
					$(this).remove();
				});
			});
			
			var OnRecalculateCurrencyRequest = GEventHandler(function(eEvent) {
				if (bCurrencyRecalculated) {
					return true;
				}
				GWarning(
					'Aktualizacja kursu waluty',
					'Aktualizacja kursu jest bezpowrotna, ale konieczna każdorazowo gdy chcesz dokonać zmiany w liście zamówionych produktów. Czy chcesz kontynuować?',
					{
						bAutoExpand: true,
						aoPossibilities: [
							{
								mLink: GEventHandler(function(eEvent) {
									GAlert.DestroyThis.apply(this, [eEvent]);
									RecalculateCurrency(OnRecalculateCurrency);
									bCurrencyRecalculated = true;
								}),
								sCaption: 'Tak, przelicz zamówienie'
							},
							{
								mLink: GAlert.DestroyThis,
								sCaption: 'Anuluj zmiany'
							}
						]
					}
				);
				return false;
			});
			
			var OnProductListAboutToChange = GEventHandler(function(eEvent) {
				if (bCurrencyRecalculated) {
					return true;
				}
				GWarning(
					'Zamówienie złożone w obcej walucie',
					'Wprowadzenie zmian do zamówienia spowoduje przeliczenie jego wartości zgodnie z dzisiejszym kursem. Zmiana będzie bezpowrotna. Czy chcesz kontynuować?',
					{
						bAutoExpand: true,
						aoPossibilities: [
							{
								mLink: GEventHandler(function(eEvent) {
									GAlert.DestroyThis.apply(this, [eEvent]);
									RecalculateCurrency(OnRecalculateCurrency);
								}),
								sCaption: 'Tak, przelicz zamówienie'
							},
							{
								mLink: GAlert.DestroyThis,
								sCaption: 'Anuluj zmiany'
							}
						]
					}
				);
				return false;
			});
			
			GCore.OnLoad(function() {
				$("<input />").attr({type:'hidden',name:'idorder',id:'idorder',value:'{/literal}{$order.id}{literal}'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'dispatchmethodprice',id:'dispatchmethodprice'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricebrutto',id:'pricebrutto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'pricenetto',id:'pricenetto'}).appendTo($("#edit_order"));
				$("<input />").attr({type:'hidden',name:'currencyid',id:'currencyid',value:'{/literal}{$currencyid}{literal}'}).appendTo($("#edit_order"));
				$('#additional_data__delivery_data__delivery_method').change(RecalculateOrder);
				$('#additional_data__rulescart_data__rules_cart').change(RecalculateOrder);
				$('.client-activity').GClientActivity({
					fSource: xajax_GetClientActivity,
					jClientId: $('.field-client-history > input'),
					gProducts: $('.field-order-editor').get(0).gNode
				});
				$('#save_order').click(function(){
					$('#save_order_trigger').val(1);
				});
				$('#recalculate-currency').click(OnRecalculateCurrencyRequest);
			});	

			var before = {/literal}{$order.totalnetto}{literal};
			window.onbeforeunload = function () {
				var after = $('#pricenetto').val();
				if((before != after) && ($('#save_order_trigger').val() == 0)){
   					return '{/literal}{trans}TXT_CONFIRM_LEAVING_ORDER{/trans}{literal}';
				}
			};
		/*]]>*/
	{/literal}
</script>