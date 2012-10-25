<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/userhistorylog-list.png" alt=""/>{trans}TXT_ADMIN_HISTORYLOGS_LIST{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/delete" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_CLEAR{/trans}</span></a></li>
</ul>
<div class="block">
	<div id="list-userhistorylog"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    var theDatagrid;
    
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'iduserhistorylog',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			}
		});
		
		var column_surname = new GF_Datagrid_Column({
			id: 'surname',
			caption: '{/literal}{trans}TXT_SURNAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetSurnameSuggestions,
			}
		});
		
		var column_firstname = new GF_Datagrid_Column({
			id: 'firstname',
			caption: '{/literal}{trans}TXT_FIRSTNAME{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetFirstnameSuggestions,
			}
		});
		
		var column_address = new GF_Datagrid_Column({
			id: 'address',
			caption: '{/literal}{trans}TXT_ADDRESS{/trans}{literal}'
		});
		
		var column_sessionid = new GF_Datagrid_Column({
			id: 'sessionid',
			caption: '{/literal}{trans}TXT_SESSION{/trans}{literal}'
		});
		
		var column_adddate = new GF_Datagrid_Column({
			id: 'adddate',
			caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

    var options = {
			id: 'userhistorylog',
			appearance: {
				column_select: false,
				column_options: false
			},
			mechanics: {
				key: 'iduserhistorylog',
				no_column_modification: true,
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllUserhistorylog
			},
			columns: [
				column_id,
				column_surname,
				column_firstname,
				column_sessionid,
				column_address,
				column_adddate
			],
			row_actions: [
			],
			group_actions: [
			],
			context_actions: [
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-userhistorylog'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
