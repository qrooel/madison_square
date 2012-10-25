<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/promotion-list.png" alt=""/>{trans}TXT_PRODUCT_PROMOTION_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_PROMOTION{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-productnews"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/


   function editProductPromotion (dg, id) {
   	location.href = '{/literal}{$URL}product/edit/{literal}' + id + '';
   };
   
   function deleteProductPromotion(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteProductPromotion(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleProductPromotion(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteProductPromotion(p.dg,p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 

	 var theDatagrid;
	 
   $(document).ready(function() {
   
		var column_id = new GF_Datagrid_Column({
			id: 'idproduct',
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
			caption: '{/literal}{trans}TXT_PRODUCT{/trans}{literal}',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
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
		
		var column_enddate = new GF_Datagrid_Column({
			id: 'enddate',
			caption: '{/literal}{trans}TXT_END_DATE{/trans}{literal}',
			appearance: {
				width: 140,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    	var options = {
			id: 'productnew',
			mechanics: {
				key: 'idproduct',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllProductPromotion,
				edit_row: editProductPromotion,
				delete_row: deleteProductPromotion,
				delete_group: deleteMultipleProductPromotion,
			},
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
			],
			columns: [
				column_id,
				column_name,
				column_category,
				column_enddate
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-productnews'), options);
		
	 });
   
   
   
   {/literal}
   
  </script>
