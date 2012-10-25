<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/newsletter-list.png" alt=""/>{trans}TXT_TRANSMAILS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_TEMPLATE{/trans}</span></a></li>
	<li><a href="#" id="refresh" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/datagrid/refresh.png" alt=""/>{trans}TXT_REFRESH_TRANSMAIL{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-transmails"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    function editTransmail(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
   
	 function deleteTransmail(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteTransmail(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleTransmail(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteTransmail(p.dg, p.ids);
		};
		new GF_Alert(title, msg, func, true, params);
	 };
	 
	 
	 function setDefaultTransMailTemplate(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DEFAULT{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setDefaultTransMailTemplate(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};	
	
	var theDatagrid;
	 
   $(document).ready(function() {
	   
	   var action_setDefaultTransMailTemplate = new GF_Action({
		   caption: '{/literal}{trans}TXT_SET_DEFAULT{/trans}{literal}',
		   action: setDefaultTransMailTemplate,
	   		img:'{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
	   		condition: function(oR) { return oR['active'] == '0'; }
		   
	   });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idtransmail',
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
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_active = new GF_Datagrid_Column({
			id: 'active',
			caption: '{/literal}{trans}TXT_DEFAULT{/trans}{literal}',
			appearance: {
				width: 20,
				visible: true
			}
		});
		
		
		var column_action = new GF_Datagrid_Column({
			id: 'action',
			caption: '{/literal}{trans}TXT_ACTION{/trans}{literal}'
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
		
    var options = {
			id: 'transmail',
			mechanics: {
				key: 'idtransmail',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllTransmail,
				delete_row: deleteTransmail,
				edit_row: editTransmail,
				delete_group: deleteMultipleTransmail,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editTransmail
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_active,
				column_action,
				column_adddate
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_setDefaultTransMailTemplate
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_setDefaultTransMailTemplate
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-transmails'), options);
    
   });

   $(document).ready(function() {
		$('#refresh').click(function(){
			var title = '{/literal}{trans}TXT_REFRESH_TRANSMAIL{/trans}{literal}';
			var msg = '{/literal}{trans}TXT_REFRESH_TRANSMAIL_HELP{/trans}{literal}?';
			var params = {};
			var func = function(p) {
				return xajax_doRefreshTransmail();
			};
			new GF_Alert(title, msg, func, true, params);
		});
	});
   /*]]>*/
   
   {/literal}
   
  </script>
