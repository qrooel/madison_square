<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/similarproduct-list.png" alt=""/>{trans}TXT_BUY_ALSO_LIST{/trans}</h2>

<div class="block">
	<div id="list-buyalso"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    function viewBuyalso(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/view/{literal}' + id + '';
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'productid',
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
			caption: '{/literal}{trans}TXT_NAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		 
    	var options = {
			id: 'productid',
			mechanics: {
				key: 'productid',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllBuyalso,
				edit_row: viewBuyalso,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: viewBuyalso
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-buyalso'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
</script>