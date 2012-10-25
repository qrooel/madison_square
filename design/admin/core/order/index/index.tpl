<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/order-list.png" alt=""/>{trans}TXT_ORDERS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" id="add_order" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_ORDERS{/trans}</span></a></li>
	<li><a href="{$URL}exchange/view/4" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/xls.png" alt=""/>{trans}TXT_EXPORT_ORDERS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-orders"></div>
</div>

{if $view == 0}
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#add_order').click(function(){
		var title = '{/literal}{trans}TXT_SELECT_VIEW_FROM_ORDER{/trans}{literal}';
		var msg = '';
		var params = {};
   	 	new GF_Alert(title, msg, true, true, params);
      	return false;
	});
});
{/literal}
</script>
{/if}

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
     function viewOrder(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/view/{literal}' + id + '';
   };
	 
	 function editOrder(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteOrder(dg, id) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + id + '?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteOrder(p.id, p.dg);
		};
   	 new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleOrders(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteOrder(p.ids, p.dg);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	function changeStatus(dg, id, status) {
	return xajax_doChangeOrderStatus(id, dg, status);
};

function changeStatusMulti(dg, ids, status) {
	return xajax_doChangeOrderStatus(ids, dg, status);
};

	 var theDatagrid;
   
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idorder',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 30
			},
			sorting: {
				default_order: GF_Datagrid.SORT_DIR_DESC
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_client = new GF_Datagrid_Column({
			id: 'client',
			caption: '{/literal}{trans}TXT_CLIENT{/trans}{literal}',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetClientSuggestions,
			}
		});
		
		var column_price = new GF_Datagrid_Column({
			id: 'price',
			caption: '{/literal}{trans}TXT_ORDER_BASE_VALUE{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_products = new GF_Datagrid_Column({
			id: 'products',
			caption: '{/literal}{trans}TXT_PRODUCTS{/trans}{literal}',
			appearance: {
				width: 190,
				align: GF_Datagrid.ALIGN_LEFT
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_globalprice = new GF_Datagrid_Column({
			id: 'globalprice',
			caption: '{/literal}{trans}TXT_TOTAL_ORDER_VALUE{/trans}{literal}',
			appearance: {
				width: 80
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_dispatchmethodprice = new GF_Datagrid_Column({
			id: 'dispatchmethodprice',
			caption: '{/literal}{trans}TXT_DISPATCHMETHODPRICE{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_orderstatusname = new GF_Datagrid_Column({
			id: 'orderstatusname',
			caption: '{/literal}{trans}TXT_CURRENT_ORDER_STATUS{/trans}{literal}',
			appearance: {
				width: 190,
			},
			filter: {
				type: GF_Datagrid.FILTER_TREE,
				filtered_column: 'orderstatusid',
				options: {/literal}{$datagrid_filter.orderstatusid}{literal},
			}
		});
		
		var column_dispatchmethodname = new GF_Datagrid_Column({
			id: 'dispatchmethodname',
			caption: '{/literal}{trans}TXT_DISPATCH_METHOD{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.dispatchmethodname}{literal}
				],
			}
		});
		
		var column_paymentmethodname = new GF_Datagrid_Column({
			id: 'paymentmethodname',
			caption: '{/literal}{trans}TXT_PAYMENT_METHOD{/trans}{literal}',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.paymentmethodname}{literal}
				],
			}
		});
		
		var column_clientid = new GF_Datagrid_Column({
			id: 'clientid',
			caption: '{/literal}{trans}TXT_CLIENT{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.clientid}{literal}
				],
			}
		});
		
		var column_adddate = new GF_Datagrid_Column({
			id: 'adddate',
			caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
			appearance: {
				width: 110
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

		var action_changeStatus = new GF_Action({
			img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/datagrid/change-status.png',
			caption: '{/literal}{trans}TXT_CHANGE_STATUS{/trans}{literal}',
			action: changeStatus,
			values: {/literal}{$order_statuses}{literal}
		});
	
		var action_changeStatusMulti = new GF_Action({
			img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/datagrid/change-status.png',
			caption: '{/literal}{trans}TXT_CHANGE_STATUS{/trans}{literal}',
			action: changeStatusMulti,
			values: {/literal}{$order_statuses}{literal}
		});
	
    var options = {
			id: 'order',
			mechanics: {
				key: 'idorder',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllOrder,
				view_row: viewOrder,
				delete_row: deleteOrder,
				edit_row: editOrder,
				delete_group: deleteMultipleOrders,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editOrder
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_client,
				column_products,
				column_price,
				column_dispatchmethodprice,
				column_orderstatusname,
				column_dispatchmethodname,
				column_paymentmethodname,
				column_clientid,
				column_globalprice,
				column_adddate,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_changeStatus,
				GF_Datagrid.ACTION_DELETE,
//				GF_Datagrid.ACTION_VIEW,
			],
			context_actions: [
				action_changeStatus,
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				action_changeStatus,
				GF_Datagrid.ACTION_DELETE
			],
		
    };
    
    theDatagrid = new GF_Datagrid($('#list-orders'), options);
    
    $('.GF_Datagrid_Col_adddate .GF_Datagrid_filter_between .from,.GF_Datagrid_Col_adddate .GF_Datagrid_filter_between .to').datepicker({dateFormat: 'yy-mm-dd'});

	});
   
   /*]]>*/
   
   {/literal}
   
  </script>
