<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/translation-list.png" alt=""/>{trans}TXT_SITEMAPS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_SITEMAPS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-sitemaps"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteSitemaps(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteSitemaps(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	 
	function deleteMultipleSitemaps(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteSitemaps(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editSitemaps(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	 
	function refreshSitemaps(dg, id) {
		xajax_refreshSitemaps(dg, id);
	};
	 
	 
   var theDatagrid;
   
   $(document).ready(function() {
   
  	 	var action_refreshSitemaps = new GF_Action({
			caption: '{/literal}{trans}TXT_REFRESH{/trans}{literal}',
			action: refreshSitemaps,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/refresh.png{literal}'
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idsitemaps',
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
				width: 60
			}
		});
		
		var column_pingserver = new GF_Datagrid_Column({
			id: 'pingserver',
			caption: '{/literal}{trans}TXT_SITEMAPS_PINGSERVER{/trans}{literal}',
			appearance: {
				width: 160,
				visible: false
			},
		});
		
		var column_lastupdate = new GF_Datagrid_Column({
			id: 'lastupdate',
			caption: '{/literal}{trans}TXT_SITEMAPS_LASTUPDATE{/trans}{literal}',
			appearance: {
				width: 50
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
			}
		});
		
    	var options = {
			id: 'sitemaps',
			mechanics: {
				key: 'idsitemaps',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllSitemaps,
				delete_row: deleteSitemaps,
				edit_row: editSitemaps,
				delete_group: deleteMultipleSitemaps,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editSitemaps
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_pingserver,
				column_lastupdate
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_refreshSitemaps,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_refreshSitemaps,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-sitemaps'), options);
	
	});
   
   /*]]>*/
   
   {/literal}
   
</script>
