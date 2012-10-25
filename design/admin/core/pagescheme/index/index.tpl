<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/pagescheme-list.png" alt=""/>{trans}TXT_PAGE_SCHEME{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_PAGE_SCHEME_ADD{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-pagescheme"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
	 function editPagescheme(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deletePagescheme(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeletePagescheme(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultiplePageschemes(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeletePagescheme(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
		
	 function setDefaultPagescheme(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DEFAULT{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setDefaultPagescheme(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};	
		 
	 function setNoDefaultPagescheme(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_NO_DEFAULT{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_CHANGE_DEFAULT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setNoDefaultPagescheme(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};

	 var theDatagrid;
	 
   $(document).ready(function() {
	   
	   var action_setDefaultPagescheme = new GF_Action({
		   caption: '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal}',
		   action: setDefaultPagescheme,
	   		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/activate.png{literal}',
	   		condition: function(oR) { return oR['def'] == '0'; }
		   
	   });

	   var action_removableScheme = new GF_Action({
			caption: '{/literal}{trans}TXT_DELETE{/trans}{literal}',
			action: deletePagescheme,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/delete.png{literal}',
			condition: function(oR) { return oR['def'] != '1'; }
		});
		
		var column_id = new GF_Datagrid_Column({
			id: 'idpagescheme',
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
				width: 440,
			},
		});
		
		var column_default = new GF_Datagrid_Column({
			id: 'def',
			caption: '{/literal}{trans}TXT_DEFAULT{/trans}{literal}',
			appearance: {
				width: 20,
				visible: true
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
			id: 'pagescheme',
			mechanics: {
				key: 'idpagescheme',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllPagescheme,
				delete_row: deletePagescheme,
				edit_row: editPagescheme,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editPagescheme
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_default,
				column_adddate,
				column_adduser,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_removableScheme,
				action_setDefaultPagescheme
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_removableScheme,
				action_setDefaultPagescheme
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-pagescheme'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>