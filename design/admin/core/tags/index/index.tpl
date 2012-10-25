<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/tags-list.png" alt=""/>{trans}TXT_TAGS_LIST{/trans}</h2>

<div class="block">
	<div id="list-tags"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteTags(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteTags(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	function deleteMultipleTags(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteTags(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idtags',
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
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.name}{literal}
				],
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
		
		var column_productname = new GF_Datagrid_Column({
			id: 'productname',
			caption: '{/literal}{trans}TXT_PRODUCT{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [{id:'',caption:''},{/literal}{$datagrid_filter.productname}{literal}]
			}
		});
		
		var column_client = new GF_Datagrid_Column({
			id: 'client',
			caption: '{/literal}{trans}TXT_CLIENT{/trans}{literal}',
			appearance: {
				width: 200
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_view = new GF_Datagrid_Column({
			id: 'view',
			caption: '{/literal}{trans}TXT_LAYER{/trans}{literal}',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.view}{literal}
				],
			}
		});
		
		
    var options = {
			id: 'tags',
			mechanics: {
				key: 'idtags',
				default_sorting: 'textcount',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllTags,
				delete_row: deleteTags,
				delete_group: deleteMultipleTags
			},
			columns: [
				column_id,
				column_name,
				column_textcount,
				column_productname,
				column_client,
				column_view
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-tags'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
