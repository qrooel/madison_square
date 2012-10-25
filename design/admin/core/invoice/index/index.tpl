<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/invoice-list.png" alt=""/>{trans}TXT_VIEW_ORDER_INVOICES{/trans}</h2>
<ul class="possibilities">
	<li><a href="#" id="export" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/save.png" alt="" />{trans}TXT_EXPORT_SELECTED{/trans}</span></a></li>
</ul>
<div class="block">
	<div id="list-invoice"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
    var theDatagrid;

    function viewOrder(dg, id) {
        location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/view/{literal}' + id + '';
	};

	function deleteOrder(dg, id) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + id + '?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteInvoice(p.id, p.dg);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idinvoice',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_invoicedate = new GF_Datagrid_Column({
			id: 'invoicedate',
			caption: '{/literal}{trans}TXT_DATE{/trans}{literal}',
			appearance: {
				width: 140,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_symbol = new GF_Datagrid_Column({
			id: 'symbol',
			caption: '{/literal}{trans}TXT_INVOICE{/trans}{literal}',
			appearance: {
				width: GF_Datagrid.WIDTH_AUTO,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_orderid = new GF_Datagrid_Column({
			id: 'orderid',
			caption: '{/literal}{trans}TXT_ORDER{/trans}{literal}',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    	var options = {
			id: 'invoice',
			mechanics: {
				key: 'idinvoice'
			},
			event_handlers: {
				load: xajax_LoadAllInvoice,
				view_row: viewOrder,
				delete_row: deleteOrder,
			},
			columns: [
				column_id,
				column_invoicedate,
				column_symbol,
				column_orderid,
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE,
				GF_Datagrid.ACTION_VIEW,
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-invoice'), options);

    $('#export').click(function(){
    	var selected = theDatagrid.GetSelected();
    	if(selected.length){
			location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/confirm/{literal}'+ Base64.encode(JSON.stringify(selected));
    	}else{
    		var title = '{/literal}{trans}TXT_INVOICE_EXPORT{/trans}{literal}';
			var msg = '{/literal}{trans}ERR_EMPTY_INVOICES_SELECTED_LIST{/trans}{literal}';
			var params = {};
			var func = function(p) {

			};
	    	new GMessage(title, msg);
    	}
		return false;
    });
    
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
