<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/view.png" alt=""/>{trans}TXT_CURRENCIES_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_CURRENCIES{/trans}</span></a></li>
	<li><a href="{$URL}{$CURRENT_CONTROLLER}#refresh" id="refresh" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/datagrid/refresh.png" alt=""/>{trans}TXT_REFRESH_CURRENCIES{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-currencieslist"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/

   function processCurrency(row) {
   	
	return {
		id: row.id,
		name: row.name,
		currencysymbol: row.currencysymbol,
		currencyto: row.exchangerate
	};
};

    function updateCurrency(dg, id) {
    	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_UPDATE_EXCHANGE_RATES{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_UPDATE_EXCHANGE_RATES_HELP{/trans}{literal} <strong>' + oRow.currencysymbol +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doUpdateCurrency(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editCurrencieslist(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	
	 function calculateCurrencieslist(dg, id) {

		return xajax_doDeleteCurrencieslist(id);
		
	 };
	 
	 function deleteCurrencieslist(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.currencysymbol +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteCurrencieslist(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	
	function deleteMultipleCurrencieslist(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteCurrencieslist(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 

	var theDatagrid;
	
	var action_updateCurrency = new GF_Action({
			caption: '{/literal}{trans}TXT_UPDATE{/trans}{literal}',
			action: updateCurrency,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/refresh.png{literal}'
			
	});
			
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'id',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_name = new GF_Datagrid_Column({
			id: 'name',
			caption: '{/literal}{trans}TXT_CURRENCY_NAME{/trans}{literal}',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
			}
		});
		
		var column_currencysymbol = new GF_Datagrid_Column({
			id: 'currencysymbol',
			caption: '{/literal}{trans}TXT_CURRENCY_SYMBOL{/trans}{literal}',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
				
			}
		});
		
		var column_currencyto = new GF_Datagrid_Column({
			id: 'currencyto',
			caption: '{/literal}{trans}TXT_EXCHANGE_RATE{/trans}{literal}',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.currencyto}{literal}
				],
			}
		});
		
    	var options = {
			id: 'currencieslist',
			mechanics: {
				key: 'id',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllCurrencieslist,
				edit_row: editCurrencieslist,
				delete_row: deleteCurrencieslist,
				delete_group: deleteMultipleCurrencieslist,
				process: processCurrency,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editCurrencieslist
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_currencysymbol,
				column_currencyto
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_updateCurrency,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_updateCurrency,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-currencieslist'), options);
		
	 });
   
	 $('#refresh').click(function(){
	 	return xajax_refreshAllCurrencies();
	 });
   /*]]>*/
   
   {/literal}
   
  </script>
