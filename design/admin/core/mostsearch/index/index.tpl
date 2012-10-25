<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/mostsearch-list.png" alt=""/>{trans}TXT_MOST_SEARCH_LIST{/trans}</h2>

<div class="block">
	<div id="list-mostsearch"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteMostSearch(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteMostSearch(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
   
   function deleteMultipleMostSearchs(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteMostSearch(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idmostsearch',
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
		
		
		var column_textcount = new GF_Datagrid_Column({
			id: 'textcount',
			caption: '{/literal}{trans}TXT_QUANTITY{/trans}{literal}',
			appearance: {
				width: 130
			},
			sorting: {
				default_order: 'desc'
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    var options = {
			id: 'mostsearch',
			mechanics: {
				key: 'idmostsearch',
				default_sorting: 'textcount',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllMostSearch,
				delete_row: deleteMostSearch,
				delete_group: deleteMultipleMostSearchs,
			},
			columns: [
				column_id,
				column_name,
				column_textcount,
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
     theDatagrid = new GF_Datagrid($('#list-mostsearch'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
