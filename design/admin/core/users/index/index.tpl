<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/users-list.png" alt=""/>{trans}TXT_USERS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_USER{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-users"></div>
</div>

<script type="text/javascript">

	{literal}

		/*<![CDATA[*/

			function editUser(dg, id) {
				location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
			};

			function deleteUser(dg, id) {
				var oRow = theDatagrid.GetRow(id);
				var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
				var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
				var params = {
					dg: dg,
					id: id
				};
				var func = function(p) {
					return xajax_doDeleteUser(p.id, p.dg);
				};
				new GF_Alert(title, msg, func, true, params);
			};

			function deleteMultipleUser(dg, ids) {
				var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
				var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
				var params = {
					dg: dg,
					ids: ids
				};
				var func = function(p) {
					return xajax_doDeleteUser(p.ids, p.dg);
				};
				new GF_Alert(title, msg, func, true, params);
			};

			function enableUser(dg, id) {
				xajax_enableUser(dg, id);
			};

			function disableUser(dg, id) {
				var oRow = theDatagrid.GetRow(id);
				var title = '{/literal}{trans}TXT_DISABLE{/trans}{literal}';
				var msg = '{/literal}{trans}TXT_DISABLE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
				var params = {
					dg: dg,
					id: id
				};
				var func = function(p) {
					return xajax_disableUser(p.dg, p.id);
				};
				new GF_Alert(title, msg, func, true, params);
			};	

			function enableUser(dg, id) {
				var oRow = theDatagrid.GetRow(id);
				var title = '{/literal}{trans}TXT_ENABLE{/trans}{literal}';
				var msg = '{/literal}{trans}TXT_ENABLE_CONFIRM{/trans}{literal} <strong>' + oRow.firstname + ' '+ oRow.surname +'</strong> ?';
				var params = {
					dg: dg,
					id: id
				};
				var func = function(p) {
					return xajax_enableUser(p.dg, p.id);
				};
				new GF_Alert(title, msg, func, true, params);
			};
			var theDatagrid;
			GCore.OnLoad(function() {

				var action_enableUser = new GF_Action({
					caption: '{/literal}{trans}TXT_ENABLE_USER{/trans}{literal}',
					action: enableUser,
					img: '{/literal}{$DESIGNPATH}_images_panel/datagrid/user-unactive.png{literal}',
					condition: function(oR) { return oR['active'] != '1'; }
				});

				var action_disableUser = new GF_Action({
					caption: '{/literal}{trans}TXT_DISABLE_USER{/trans}{literal}',
					action: disableUser,
					img: '{/literal}{$DESIGNPATH}_images_panel/datagrid/user-active.png{literal}',
					condition: function(oR) { return oR['active'] == '1'; }
				});

				var column_id = new GF_Datagrid_Column({
					id: 'iduser',
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
						width: 140
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
						width: 140
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
						width: 180
					},
					filter: {
						type: GF_Datagrid.FILTER_AUTOSUGGEST,
						source: xajax_GetEmailSuggestions,
					}
				});

				var column_group = new GF_Datagrid_Column({
					id: 'groupname',
					caption: '{/literal}{trans}TXT_GROUP_NAME{/trans}{literal}',
					appearance: {
						width: 120
					},
					filter: {
						type: GF_Datagrid.FILTER_SELECT,
						options: [
							{/literal}{$datagrid_filter.groupnames}{literal}
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
					id: 'user',
					mechanics: {
						key: 'iduser',
						rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
					},
					event_handlers: {
						load: xajax_LoadAllUser,
						delete_row: deleteUser,
						edit_row: editUser,
						delete_group: deleteMultipleUser,
						{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
						click_row: editUser
						{/literal}{/if}{literal}
					},
					columns: [
						column_id,
						column_surname,
						column_firstname,
						column_group,
						column_email,
						column_adddate,
						column_adduser,
						column_editdate,
						column_edituser
					],
					row_actions: [
						GF_Datagrid.ACTION_EDIT,
						GF_Datagrid.ACTION_DELETE,
						action_enableUser,
						action_disableUser
					],
					group_actions: [
						GF_Datagrid.ACTION_DELETE
					],
					context_actions: [
						GF_Datagrid.ACTION_EDIT,
						GF_Datagrid.ACTION_DELETE,
						action_enableUser,
						action_disableUser
					]
				};

				theDatagrid = new GF_Datagrid($('#list-users'), options);

			});

		/*]]>*/

	{/literal}

</script>