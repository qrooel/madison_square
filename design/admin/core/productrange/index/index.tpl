<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/productrange-list.png" alt=""/>{trans}TXT_PRODUCTRANGES_LIST{/trans}</h2>

<div class="block">
	<div id="list-productranges"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/

	 function deleteProductRange(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteProductRange(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleProductRange(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteProductRange(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idproductreview',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_firstname = new GF_Datagrid_Column({
			id: 'firstname',
			caption: '{/literal}{trans}TXT_FIRSTNAME{/trans}{literal}',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetFirstnameSuggestions,
			}
		});
		
		var column_surname = new GF_Datagrid_Column({
			id: 'surname',
			caption: '{/literal}{trans}TXT_SURNAME{/trans}{literal}',
			appearance: {
				width: 120
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetSurnameSuggestions,
			}
		});
		
		var column_productname = new GF_Datagrid_Column({
			id: 'productname',
			caption: '{/literal}{trans}TXT_NAME{/trans}{literal}',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_rating = new GF_Datagrid_Column({
			id: 'rating',
			caption: '{/literal}{trans}TXT_AVERAGE_OPINION{/trans}{literal}',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

    var options = {
			id: 'productrange',
			mechanics: {
				key: 'idproductreview',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllProductRange,
				delete_row: deleteProductRange,
				delete_group: deleteMultipleProductRange,
			},
			columns: [
				column_id,
				column_firstname,
				column_surname,
				column_productname,
				column_rating
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-productranges'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
