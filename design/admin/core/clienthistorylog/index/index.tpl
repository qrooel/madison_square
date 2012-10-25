<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/userhistorylog-list.png" alt=""/>{trans}TXT_CLIENT_HISTORY_LOGS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/delete" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_CLEAR{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-clienthistorylog"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    var theDatagrid;
   
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idclienthistorylog',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			}
		});
		
		var column_clientid = new GF_Datagrid_Column({
			id: 'clientid',
			caption: '{/literal}{trans}TXT_CLIENTID{/trans}{literal}',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_url = new GF_Datagrid_Column({
			id: 'url',
			caption: '{/literal}{trans}TXT_URL{/trans}{literal}',
			appearance: {
				width: 300
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
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
			id: 'clienthistorylog',
			appearance: {
				column_select: false,
				column_options: false
			},
			mechanics: {
				key: 'idclienthistorylog',
				no_column_modification: true,
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllClienthistorylog
			},
			columns: [
				column_id,
				column_clientid,
				column_url,
				column_sessionid,
				column_adddate
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-clienthistorylog'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
