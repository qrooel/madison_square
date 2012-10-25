<?php /* Smarty version 2.6.19, created on 2012-10-08 09:39:52
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/paymentmethod/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/paymentmethod-list.png" alt=""/>Dostępne moduły płatności</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj metodę płatności</span></a></li>
</ul>

<div class="block">
	<div id="list-paymentmethod"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/

	 function editPaymentMethod(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deletePaymentMethod(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
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
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
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
		var title = \''; ?>
Wyłącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie wyłączenia<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
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
		var title = \''; ?>
Włącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie włączenia<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
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
			caption: \''; ?>
Włącz<?php echo '\',
			action: enablePaymentmethod,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/off.png<?php echo '\',
			condition: function(oR) { return oR[\'active\'] == \'0\'; }
		 });
		 
		 var action_disablePaymentmethod= new GF_Action({
			caption: \''; ?>
Wyłącz<?php echo '\',
			action: disablePaymentmethod,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/on.png<?php echo '\',
			condition: function(oR) { return oR[\'active\'] == \'1\'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: \'idpaymentmethod\',
			caption: \''; ?>
ID<?php echo '\',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_name = new GF_Datagrid_Column({
			id: \'name\',
			caption: \''; ?>
Nazwa<?php echo '\',
			appearance: {
				width: 440,
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_controller = new GF_Datagrid_Column({
			id: \'controller\',
			caption: \''; ?>
Nazwa modelu płatności<?php echo '\',
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
			id: \'hierarchy\',
			editable: true,
			appearance: {
				width: 80,
			},
			caption: \''; ?>
Kolejność<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

		
		var column_adddate = new GF_Datagrid_Column({
			id: \'adddate\',
			caption: \''; ?>
Data dodania<?php echo '\',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_adduser = new GF_Datagrid_Column({
			id: \'adduser\',
			caption: \''; ?>
Autor dodania<?php echo '\',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_editdate = new GF_Datagrid_Column({
			id: \'editdate\',
			caption: \''; ?>
Data modyfikacji<?php echo '\',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_edituser = new GF_Datagrid_Column({
			id: \'edituser\',
			caption: \''; ?>
Autor modyfikacji<?php echo '\',
			appearance: {
				width: 140,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

    var options = {
			id: \'paymentmethod\',
			mechanics: {
				key: \'idpaymentmethod\',
    			rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllPaymentMethod,
				delete_row: deletePaymentMethod,
				edit_row: editPaymentMethod,
				delete_group: deleteMultiplePaymentMethods,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editPaymentMethod,
				'; ?>
<?php endif; ?><?php echo '
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
    
    theDatagrid = new GF_Datagrid($(\'#list-paymentmethod\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>