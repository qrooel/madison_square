<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/status-list.png" alt=""/>{trans}TXT_ORDERSTATUS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_ORDERSTATUS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-orderstatus"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
	 function editOrderstatus(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteOrderstatus(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteOrderstatus(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleOrderstatuses(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteOrderstatus(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 function setDefault(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DEFAULT{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setDefault(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};	

	
	 var theDatagrid;
	 
   $(document).ready(function() {
		
   		var action_setDefault = new GF_Action({
		   caption: '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal}',
		   action: setDefault,
	   		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
	   		condition: function(oR) { return oR['def'] == '0'; }
		   
	   });
	   
		var column_id = new GF_Datagrid_Column({
			id: 'idorderstatus',
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
		
		var column_groupname = new GF_Datagrid_Column({
			id: 'groupname',
			caption: '{/literal}{trans}TXT_GROUP_NAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.groupname}{literal}
				],
			}
		});

		var column_adddate = new GF_Datagrid_Column({
			id: 'adddate',
			caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		

    var options = {
			id: 'orderstatus',
			mechanics: {
				key: 'idorderstatus',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllOrderstatus,
				delete_row: deleteOrderstatus,
				edit_row: editOrderstatus,
				delete_group: deleteMultipleOrderstatuses,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editOrderstatus
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_groupname,
				column_adddate
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_setDefault
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_setDefault
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-orderstatus'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
