<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/category-list.png" alt=""/>{trans}TXT_FILES_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/edit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_FILES{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-files"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
	 
	 function processFile(oRow) {
		if (oRow.thumb != '') {
			oRow.thumb = '<a href="' + oRow.thumb + '" class="show-thumb"><img src="{/literal}{$DESIGNPATH}{literal}_images_panel/icons/datagrid/details.png" style="vertical-align: middle;" alt="{/literal}{trans}TXT_VIEW_THUMB{/trans}{literal}"/></a>';
		}
		return oRow;
	 };
	 
	 function dataLoaded(dDg) {
		dDg.m_jBody.find('.show-thumb').mouseenter(GTooltip.ShowThumbForThis).mouseleave(GTooltip.HideThumbForThis);
	 };
   
   function viewFiles(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/view/' + id + '';
   };
   
   function editFiles(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + id + '';
   };
   
   function deleteFiles(dg, id){
   		var oRow = theDatagrid.GetRow(id);
   		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.filename +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteFiles(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	function deleteMultipleFiles(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteFiles(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
   
   $(document).ready(function() {
	  
	  var column_id = new GF_Datagrid_Column({
			id: 'idfile',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_thumb = new GF_Datagrid_Column({
			id: 'thumb',
			caption: '{/literal}{trans}TXT_THUMB{/trans}{literal}',
			appearance: {
				width: 30,
				no_title: true
			}
		});
		
		var column_filename = new GF_Datagrid_Column({
			id: 'filename',
			caption: '{/literal}{trans}TXT_NAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetFilenameSuggestions,
			}

		});
		
		var column_filetype = new GF_Datagrid_Column({
			id: 'filetype',
			caption: '{/literal}{trans}TXT_FILETYPE{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.filetype}{literal}
				],
			}
		});
		
		var column_fileextension = new GF_Datagrid_Column({
			id: 'fileextension',
			caption: '{/literal}{trans}TXT_FILEEXTENSION{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{/literal}{$datagrid_filter.fileextension}{literal}
				],
			}
		});
		
		var column_bind_count = new GF_Datagrid_Column({
			id: 'bindcount',
			caption: '{/literal}{trans}TXT_BIND_COUNT{/trans}{literal}',
			appearance: {
				width: 130
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var options = {
			id: 'files',
			mechanics: {
				key: 'idfile',
				default_sorting: 'filename',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllFiles,
				delete_row: deleteFiles,
				process: processFile,
				loaded: dataLoaded,
				delete_group: deleteMultipleFiles,
			},
			columns: [
				column_id,
				column_thumb,
				column_filename,
				column_fileextension,
				column_filetype,
				column_bind_count
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-files'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
