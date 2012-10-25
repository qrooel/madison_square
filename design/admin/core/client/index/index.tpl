<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/client-list.png" alt=""/>{trans}TXT_CLIENTS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_CLIENT{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-clients"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
	 function editClient(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteClient(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleClients(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteClient(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function enableClient(dg, id) {
		xajax_enableClient(dg, id);
	 };
	 
	 function disableClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DISABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableClient(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_ENABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableClient(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
   
   $(document).ready(function() {
		
		 var action_enableClient = new GF_Action({
			caption: '{/literal}{trans}TXT_ENABLE_CLIENT{/trans}{literal}',
			action: enableClient,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/deactivate.png{literal}',
			condition: function(oR) { return oR['disable'] != '0'; }
		 });
		 
		 var action_disableClient = new GF_Action({
			caption: '{/literal}{trans}TXT_DISABLE_CLIENT{/trans}{literal}',
			action: disableClient,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/activate.png{literal}',
			condition: function(oR) { return oR['disable'] == '0'; }
		 });
		 
		var column_id = new GF_Datagrid_Column({
			id: 'idclient',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_clientorder = new GF_Datagrid_Column({
			id: 'clientorder',
			caption: '{/literal}{trans}TXT_CLIENTORDER_VALUE{/trans}{literal}',
			appearance: {
				width: 40,
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
				width: 200
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
				width: 200
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetSurnameSuggestions,
			}
		});
		
		var column_email = new GF_Datagrid_Column({
			id: 'email',
			caption: '{/literal}{trans}TXT_EMAIL{/trans}{literal}',
			appearance: {
				width: 180,
				visible: false
			}
		});
		
		var column_phone = new GF_Datagrid_Column({
			id: 'phone',
			caption: '{/literal}{trans}TXT_PHONE{/trans}{literal}',
			appearance: {
				width: 110,
				visible: false
			}
		});
		
		var column_group = new GF_Datagrid_Column({
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
		
		var column_editdate = new GF_Datagrid_Column({
			id: 'editdate',
			caption: '{/literal}{trans}TXT_EDITDATE{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
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
			id: 'client',
			mechanics: {
				key: 'idclient',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllClient,
				delete_row: deleteClient,
				edit_row: editClient,
				delete_group: deleteMultipleClients,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editClient
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_surname,
				column_firstname,
				column_group,
				column_email,
				column_phone,
				column_adddate,
				column_editdate,
				column_clientorder,
				column_view
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableClient,
				action_disableClient,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableClient,
				action_disableClient,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-clients'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
