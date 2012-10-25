<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/productnews-list.png" alt=""/>{trans}TXT_PRODUCT_NEWS_LIST{/trans}</h2>

<div class="block">
	<div id="list-productnews"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteProductNews(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteProductNews(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleroductNews(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteProductNews(p.dg,p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function disableProductNews(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DISABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableProductNews(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableProductNews(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_ENABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableProductNews(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
   
  		 var action_enableProductNews = new GF_Action({
			caption: '{/literal}{trans}TXT_ENABLE_PRODUCT_NEWS{/trans}{literal}',
			action: enableProductNews,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
			condition: function(oR) { return oR['active'] != '1'; }
		 });
		 
		 var action_disableProductNews = new GF_Action({
			caption: '{/literal}{trans}TXT_DISABLE_PRODUCT_NEWS{/trans}{literal}',
			action: disableProductNews,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
			condition: function(oR) { return oR['active'] == '1'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idproductnew',
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
				key: 'idproductnew',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllProductNews,
				delete_row: deleteProductNews,
				delete_group: xajax_doDeleteProductNews,
			},
			row_actions: [
				GF_Datagrid.ACTION_DELETE,
				action_enableProductNews,
				action_disableProductNews
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
				GF_Datagrid.ACTION_DELETE,
				action_enableProductNews,
				action_disableProductNews
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-productnews'), options);
		
	 });
   
   
   
   {/literal}
   
  </script>
