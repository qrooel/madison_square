<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/mostsearch-list.png" alt=""/>{trans}TXT_SPY{/trans}</h2>

<div class="block">
	<div id="list-spy"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    var theDatagrid;
    
   $(document).ready(function() {
   
   		function editClient(dg, id) {
    		location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 	};
   
  		var column_id = new GF_Datagrid_Column({
			id: 'id',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
		});
		
		var column_client = new GF_Datagrid_Column({
			id: 'client',
			caption: '{/literal}{trans}TXT_CLIENT{/trans}{literal}',
		});
		
		var column_session = new GF_Datagrid_Column({
			id: 'client_session',
			caption: '{/literal}{trans}TXT_SESSION{/trans}{literal}',
			appearance: {
				visible: false
			},
		});
		
		var column_lastaddress = new GF_Datagrid_Column({
			id: 'lastaddress',
			caption: '{/literal}{trans}TXT_SPY_LAST_ADDRESS{/trans}{literal}',
		});
		
		var column_cart = new GF_Datagrid_Column({
			id: 'cart',
			caption: '{/literal}{trans}TXT_CART{/trans}{literal}',
			appearance: {
				width: 110,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_ipaddress = new GF_Datagrid_Column({
			id: 'ipaddress',
			caption: 'IP',
			appearance: {
				width: 110,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_isbot = new GF_Datagrid_Column({
			id: 'isbot',
			caption: '{/literal}{trans}TXT_SPY_ISBOT{/trans}{literal}',
			appearance: {
				width: 70,
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.isbot}{literal}
				],
			}
		});
		var column_ismobile = new GF_Datagrid_Column({
			id: 'ismobile',
			caption: '{/literal}{trans}TXT_SPY_ISMOBILE{/trans}{literal}',
			appearance: {
				width: 70,
			},
			filter: {
			type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.ismobile}{literal}
				],
			}
		});
		var column_browser = new GF_Datagrid_Column({
			id: 'browser',
			caption: '{/literal}{trans}TXT_SPY_BROWSER{/trans}{literal}',
			appearance: {
				width: 100,
			},
			filter: {
			type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.browser}{literal}
				],
			}
		});
		var column_platform = new GF_Datagrid_Column({
			id: 'platform',
			caption: '{/literal}{trans}TXT_SPY_PLATFORM{/trans}{literal}',
			appearance: {
				width: 90,
			},
			filter: {
			type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.platform}{literal}
				],
			}
		});
		
    var options = {
			id: 'spy',
			mechanics: {
				key: 'client_session',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllSpy,
				edit_row: editClient,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editClient
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_client,
				column_lastaddress,
				column_ipaddress,
				column_session,
				column_cart,
				column_isbot,
				column_ismobile,
				column_browser,
				column_platform,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT
			]
			
			
    };
    
    theDatagrid = new GF_Datagrid($('#list-spy'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
</script>
