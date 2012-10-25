<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/paymentmethod-list.png" alt=""/>{trans}TXT_PAYMENTMETHOD_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_PAYMENTMETHOD{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-paymentmethod"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/

	 function editPaymentMethod(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deletePaymentMethod(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeletePaymentMethod(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultiplePaymentMethods(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeletePaymentMethod(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 function enablePaymentmethod(dg, id) {
		xajax_enablePaymentmethod(dg, id);
	 };
   	 
	 function disablePaymentmethod(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DISABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disablePaymentmethod(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enablePaymentmethod(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_ENABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enablePaymentmethod(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {

	   var action_enablePaymentmethod= new GF_Action({
			caption: '{/literal}{trans}TXT_ENABLE{/trans}{literal}',
			action: enablePaymentmethod,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
			condition: function(oR) { return oR['active'] == '0'; }
		 });
		 
		 var action_disablePaymentmethod= new GF_Action({
			caption: '{/literal}{trans}TXT_DISABLE{/trans}{literal}',
			action: disablePaymentmethod,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
			condition: function(oR) { return oR['active'] == '1'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idpaymentmethod',
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
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_controller = new GF_Datagrid_Column({
			id: 'controller',
			caption: '{/literal}{trans}TXT_PAYMENT_CONTROLLER{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetControllerSuggestions,
			}
		});

		var column_hierarchy = new GF_Datagrid_Column({
			id: 'hierarchy',
			editable: true,
			appearance: {
				width: 80,
			},
			caption: '{/literal}{trans}TXT_HIERARCHY{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
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
		
		var column_editdate = new GF_Datagrid_Column({
			id: 'editdate',
			caption: '{/literal}{trans}TXT_EDITDATE{/trans}{literal}',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
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
			id: 'paymentmethod',
			mechanics: {
				key: 'idpaymentmethod',
    			rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllPaymentMethod,
				delete_row: deletePaymentMethod,
				edit_row: editPaymentMethod,
				delete_group: deleteMultiplePaymentMethods,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editPaymentMethod,
				{/literal}{/if}{literal}
				update_row: function(sId, oRow) {
					xajax_doAJAXUpdateMethod(sId, oRow.hierarchy);
				},
			},
			columns: [
				column_id,
				column_name,
				column_controller,
				column_hierarchy,
				column_adddate,
				column_adduser,
				column_editdate,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_enablePaymentmethod,
				action_disablePaymentmethod
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_enablePaymentmethod,
				action_disablePaymentmethod
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-paymentmethod'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
