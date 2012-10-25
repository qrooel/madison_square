<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/subpagelayout-list.png" alt=""/>{trans}TXT_SUBPAGE_LAYOUT{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt="{trans}TXT_SUBPAGE_LAYOUT_ADD{/trans}"/>{trans}TXT_SUBPAGE_LAYOUT_ADD{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-subpagelayout"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
	 function editSubpageLayout(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idsubpagelayout',
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
			caption: '{/literal}{trans}TXT_SUBPAGE_NAME{/trans}{literal}',
			appearance: {
				width: 220,
				align: GF_Datagrid.ALIGN_LEFT
			}
		});
		
		var column_description = new GF_Datagrid_Column({
			id: 'description',
			caption: '{/literal}{trans}TXT_SUBPAGE_LAYOUT_DESCRIPTION{/trans}{literal}',
			appearance: {
				align: GF_Datagrid.ALIGN_LEFT
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
			id: 'subpagelayout',
			mechanics: {
				key: 'idsubpagelayout',
				default_sorting: 'name',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllSubpageLayout,
				edit_row: editSubpageLayout,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editSubpageLayout
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_description,
				column_adddate,
				column_adduser,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-subpagelayout'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>