<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/atributes-list.png" alt=""/>{trans}TXT_ATTRIBUTE_PRODUCTS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_ATTRIBUTE_PRODUCT{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-attributeproducts"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
	 
	 function editAttribute(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteStaticAttributes(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteStaticAttributes(p.dg, p.id);
		};
    	new GF_Alert(title, msg, func, true, params);
    	return false;
	 };
	 
	 function deleteMultipleStaticAttributes(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteStaticAttributes(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idstaticgroup',
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
			appearance: {
				width: 130
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
    var options = {
			id: 'attributeproduct',
			mechanics: {
				key: 'idstaticgroup',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllStaticAttributes,
				delete_row: deleteStaticAttributes,
				edit_row: editAttribute,
				delete_group: deleteMultipleStaticAttributes,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editAttribute
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    	theDatagrid = new GF_Datagrid($('#list-attributeproducts'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
