<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/contentcategory-list.png" alt=""/>{trans}TXT_CONTENTCATEGORY_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_CONTENTCATEGORY{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-contentcategory"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   
   function deleteContentCategory(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteContentCategory(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	function deleteMultipleContentCategorys(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteContentCategory(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editContentCategory(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function processUrl(oRow) {
		oRow.url = '<a href="' + oRow.url + '" class="url" target="_blank">' + oRow.url + '</a>';
		return oRow;
	 };

	 function dataLoaded(dDg) {
	 	dDg.m_jBody.find('.url').click(function(){
			window.open($(this).attr('href'));
			return false;
		});
	 };
	 
	var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idcontentcategory',
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
				width: 120,
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_url = new GF_Datagrid_Column({
			id: 'url',
			caption: '{/literal}{trans}TXT_URL{/trans}{literal}',
			appearance: {
				width: 180,
			},
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

		var column_hierarchy = new GF_Datagrid_Column({
			id: 'hierarchy',
			editable: true,
			appearance: {
				width: 40,
			},
			caption: '{/literal}{trans}TXT_HIERARCHY{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

    	var options = {
			id: 'contentcategory',
			mechanics: {
				key: 'idcontentcategory',
				default_sorting: 'hierarchy',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllContentCategory,
				delete_row: deleteContentCategory,
				edit_row: editContentCategory,
				delete_group: deleteMultipleContentCategorys,
				update_row: function(sId, oRow) {
					xajax_doAJAXUpdateContentCategory(sId, oRow.hierarchy);
				},
				process: processUrl,
				loaded: dataLoaded,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editContentCategory
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_url,
				column_adddate,
				column_hierarchy
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-contentcategory'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
