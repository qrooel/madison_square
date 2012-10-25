<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/updater.png"	alt="" />{trans}TXT_GEKOLAB{/trans}</h2>
{if isset($channelError)}
<div class="block">
<div id="list-update"><p>{$channelError}</p></div>
</div>
{else}
<div class="block">
<div id="list-update"></div>
</div>
<script type="text/javascript">

	{literal}
		
		/*<![CDATA[*/

		var dataProvider;
		GCore.OnLoad(function() {
			
		

			function installPackage(dg, column_uniquename) {
				var oPackage = GF_Datagrid.ReturnInstance(dg).GetRow(column_uniquename);
				location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/add/' + oPackage.package + '';
			};
			
			function updatePackage(dg, column_uniquename) {
				var oPackage = GF_Datagrid.ReturnInstance(dg).GetRow(column_uniquename);
				location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + oPackage.package + '';
			};
			
			function uninstallPackage(dg, column_uniquename) {
				var oPackage = GF_Datagrid.ReturnInstance(dg).GetRow(column_uniquename);
				location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/delete/' + oPackage.package + '';
			};
			   
			var action_updatePackage = new GF_Action({
				caption: '{/literal}{trans}TXT_UPDATE{/trans}{literal}',
				action: updatePackage,
				img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/update.png{literal}',
				condition: function(oR) { return oR['upgrade'] == '1'; }
			});

			var action_installPackage = new GF_Action({
				caption: '{/literal}{trans}TXT_INSTALL{/trans}{literal}',
				action: installPackage,
				img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/add.png{literal}',
				condition: function(oR) { return oR['install'] == '1'; }
			});

			var action_uninstallPackage = new GF_Action({
				caption: '{/literal}{trans}TXT_UNINSTALL{/trans}{literal}',
				action: uninstallPackage,
				img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/delete.png{literal}',
				condition: function(oR) { return oR['uninstall'] == '1'; }
			});
			
			var column_package = new GF_Datagrid_Column({
				id: 'package',
				caption: '{/literal}{trans}TXT_PACKAGE{/trans}{literal}',
				filter: {
					type: GF_Datagrid.FILTER_INPUT,
				},
				appearance: {
					width: GF_Datagrid.WIDTH_AUTO,
				}
			});
			
			var column_name = new GF_Datagrid_Column({
				id: 'name',
				caption: '{/literal}{trans}TXT_NAME{/trans}{literal}',
				filter: {
					type: GF_Datagrid.FILTER_INPUT,
				},
				appearance: {
					width: GF_Datagrid.WIDTH_AUTO,
				}
			});
			
			var column_server_version = new GF_Datagrid_Column({
				id: 'server_version',
				caption: '{/literal}{trans}TXT_SERVER_VERSION{/trans}{literal}',
				appearance: {
					width: GF_Datagrid.WIDTH_AUTO,
				}
			});
			
			var column_local_version = new GF_Datagrid_Column({
				id: 'local_version',
				caption: '{/literal}{trans}TXT_LOCAL_VERSION{/trans}{literal}',
				appearance: {
					width: GF_Datagrid.WIDTH_AUTO,
				}
			});

			dataProvider = new GF_Datagrid_Data_Provider({
				key: 'package',
			}, {/literal}{$packages}{literal});
			
			var options = {
				id: 'gekolab',
				mechanics: {
					key: 'package',
					rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal},
					right_click_menu: false
				},
				event_handlers: {
					load: function(a, b) {dataProvider.Load(a, b);}
				},
				columns: [
					column_package,
					column_name,
					column_server_version,
					column_local_version,
				],
				row_actions: [
					action_updatePackage,
					action_installPackage,
					action_uninstallPackage
				],
				context_actions: [
					action_updatePackage,
					action_installPackage,
					action_uninstallPackage
				]
			};
			
			var theDatagrid = new GF_Datagrid($('#list-update'), options);
			
		});

		/*]]>*/

	{/literal}

</script>
{/if}