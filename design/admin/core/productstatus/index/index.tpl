<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/status-list.png" alt=""/>{trans}TXT_PRODUCTSTATUS_LIST{/trans}</h2>


<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_PRODUCT_STATUS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-productstatus"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    function editProductStatus(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteProductStatus(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteProductStatus(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleProductStatuses(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteProductStatus(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
    var theDatagrid;
    
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idproductstatus',
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
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
	    var options = {
				id: 'productstatus',
				mechanics: {
					key: 'idproductstatus',
					rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
				},
				event_handlers: {
					load: xajax_LoadAllProductstatus,
					delete_row: deleteProductStatus,
					edit_row: editProductStatus,
					delete_group: deleteMultipleProductStatuses,
					{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
					click_row: editProductStatus
					{/literal}{/if}{literal}
				},
				columns: [
					column_id,
					column_name
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
    
   	  theDatagrid = new GF_Datagrid($('#list-productstatus'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
