<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/opinion-list.png" alt=""/>{trans}TXT_RANGETYPES_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_RANGETYPE{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-rangetype"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function editRangeType(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	 
	function deleteRangeType(dg, id) {
		var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteRangeType(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleRangeTypes(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteRangeType(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idrangetype',
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
			caption: '{/literal}{trans}TXT_RANGETYPE{/trans}{literal}',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.name}{literal}
				],
			}
		});
		
		var column_category = new GF_Datagrid_Column({
			id: 'categoriesname',
			caption: '{/literal}{trans}TXT_CATEGORY{/trans}{literal}',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_TREE,
				filtered_column: 'ancestorcategoryid',
				options: {/literal}{$datagrid_filter.categoryid}{literal},
				load_children: xajax_LoadCategoryChildren
			}
		});
		
    var options = {
			id: 'rangetype',
			mechanics: {
				key: 'idrangetype',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllRangeType,
				edit_row: editRangeType,
				delete_row: deleteRangeType,
				delete_group: deleteMultipleRangeTypes,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editRangeType
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_category
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-rangetype'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
