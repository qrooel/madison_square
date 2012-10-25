<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/staticblocks-list.png" alt=""/>{trans}TXT_STATICBLOCKS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_STATICBLOCKS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-staticblocks"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteStaticBlocks(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteStaticBlocks(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	 
	 function deleteMultipleStaticBlocks(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteStaticBlocks(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editStaticBlocks(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	function enableStaticBlocks(dg, id) {
		xajax_enableStaticBlocks(dg, id);
	 };
	 
	 function disableStaticBlocks(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_PUBLISH{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableStaticBlocks(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableStaticBlocks(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_PUBLISH{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableStaticBlocks(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
   
    	var action_enableStaticBlocks = new GF_Action({
			caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
			action: enableStaticBlocks,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
			condition: function(oR) { return oR['publish'] != '1'; }
		 });
		 
		 var action_disableStaticBlocks = new GF_Action({
			caption: '{/literal}{trans}TXT_NOT_PUBLISH{/trans}{literal}',
			action: disableStaticBlocks,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
			condition: function(oR) { return oR['publish'] == '1'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idstaticcontent',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_topic = new GF_Datagrid_Column({
			id: 'topic',
			caption: '{/literal}{trans}TXT_TOPIC{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetTopicSuggestions,
			}
		});
		
		var column_name = new GF_Datagrid_Column({
			id: 'name',
			caption: '{/literal}{trans}TXT_CATEGORY{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.name}{literal}
				],
			}
		});

		var column_hierarchy = new GF_Datagrid_Column({
			id: 'hierarchy',
			editable: true,
			appearance: {
				width: 90,
			},
			caption: '{/literal}{trans}TXT_HIERARCHY{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
				

    	var options = {
			id: 'staticcontent',
			mechanics: {
				key: 'idstaticcontent',
				default_sorting: 'hierarchy',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllStaticBlocks,
				delete_row: deleteStaticBlocks,
				edit_row: editStaticBlocks,
				delete_group: deleteMultipleStaticBlocks,
				update_row: function(sId, oRow) {
					xajax_doAJAXUpdateStaticblocks(sId, oRow.hierarchy);
				},
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editStaticBlocks
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_topic,
				column_name,
				column_hierarchy
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableStaticBlocks,
				action_disableStaticBlocks,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableStaticBlocks,
				action_disableStaticBlocks,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-staticblocks'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
