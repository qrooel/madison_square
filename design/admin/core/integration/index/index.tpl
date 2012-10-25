<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/rulescart-list.png" alt=""/>{trans}TXT_INTEGRATION{/trans}</h2>

<div class="block">
	<div id="list-integration"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   	 function editIntegration(dg, id) {
   		location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	 
	 var theDatagrid;
	  
   $(document).ready(function() {
   
		var column_id = new GF_Datagrid_Column({
			id: 'idintegration',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_name = new GF_Datagrid_Column({
			id: 'name',
			caption: '{/literal}{trans}TXT_INTEGRATION_NAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_symbol = new GF_Datagrid_Column({
			id: 'symbol',
			caption: '{/literal}{trans}TXT_INTEGRATION_SYMBOL{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		
		
		

    var options = {
			id: 'integration',
			mechanics: {
				key: 'idintegration',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllIntegration,
				edit_row: editIntegration,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editIntegration
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_symbol
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT
			],
			group_actions: [
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-integration'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
