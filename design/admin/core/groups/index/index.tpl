<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/clientgroup-list.png" alt=""/>{trans}TXT_GROUPS_USERS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_GROUP{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-groups"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
	 
	 function editGroup(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteGroup(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteGroup(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleGroups(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteGroup(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
   
   function displayUsers(dg, id) {
    location.href = '{/literal}{$URL}{literal}users/index,groupname=' + GF_Datagrid.ReturnInstance(dg).GetRow(id).name + '';
   };
		
	 var action_displayUsers = new GF_Action({
		caption: '{/literal}{trans}TXT_DISPLAY_GROUP_USERS{/trans}{literal}',
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/activate.png{literal}',
		action: displayUsers
	 });

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idgroup',
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
				width: 460
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_user_count = new GF_Datagrid_Column({
			id: 'usercount',
			caption: '{/literal}{trans}TXT_USER_COUNT{/trans}{literal}',
			appearance: {
				width: 80
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
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
		
		var column_adduser = new GF_Datagrid_Column({
			id: 'adduser',
			caption: '{/literal}{trans}TXT_ADDUSER{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
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
		
		var column_edituser = new GF_Datagrid_Column({
			id: 'edituser',
			caption: '{/literal}{trans}TXT_EDITUSER{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

    var options = {
			id: 'group',
			mechanics: {
				key: 'idgroup',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllGroups,
				delete_row: deleteGroup,
				edit_row: editGroup,
				delete_group: deleteMultipleGroups,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editGroup
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_user_count,
				column_adddate,
				column_adduser,
				column_editdate,
				column_edituser
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
				GF_Datagrid.ACTION_DELETE,
				action_displayUsers
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-groups'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
