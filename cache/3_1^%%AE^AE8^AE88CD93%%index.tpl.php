<?php /* Smarty version 2.6.19, created on 2012-10-08 09:39:06
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/client/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/client-list.png" alt=""/>Lista klientów</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj klienta</span></a></li>
</ul>

<div class="block">
	<div id="list-clients"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function editClient(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.firstname + \' \'+ oRow.surname +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteClient(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleClients(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteClient(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function enableClient(dg, id) {
		xajax_enableClient(dg, id);
	 };
	 
	 function disableClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Wyłącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie wyłączenia<?php echo ' <strong>\' + oRow.firstname + \' \'+ oRow.surname +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableClient(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableClient(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Włącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie włączenia<?php echo ' <strong>\' + oRow.firstname + \' \'+ oRow.surname +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableClient(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
   
   $(document).ready(function() {
		
		 var action_enableClient = new GF_Action({
			caption: \''; ?>
Aktywuj klienta<?php echo '\',
			action: enableClient,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/deactivate.png<?php echo '\',
			condition: function(oR) { return oR[\'disable\'] != \'0\'; }
		 });
		 
		 var action_disableClient = new GF_Action({
			caption: \''; ?>
Zablokuj klienta<?php echo '\',
			action: disableClient,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/activate.png<?php echo '\',
			condition: function(oR) { return oR[\'disable\'] == \'0\'; }
		 });
		 
		var column_id = new GF_Datagrid_Column({
			id: \'idclient\',
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
		
		var column_clientorder = new GF_Datagrid_Column({
			id: \'clientorder\',
			caption: \''; ?>
Wartość zamówień<?php echo '\',
			appearance: {
				width: 40,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_firstname = new GF_Datagrid_Column({
			id: \'firstname\',
			caption: \''; ?>
Imię<?php echo '\',
			appearance: {
				width: 200
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetFirstnameSuggestions,
			}
		});
		
		var column_surname = new GF_Datagrid_Column({
			id: \'surname\',
			caption: \''; ?>
Nazwisko<?php echo '\',
			appearance: {
				width: 200
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetSurnameSuggestions,
			}
		});
		
		var column_email = new GF_Datagrid_Column({
			id: \'email\',
			caption: \''; ?>
E-mail<?php echo '\',
			appearance: {
				width: 180,
				visible: false
			}
		});
		
		var column_phone = new GF_Datagrid_Column({
			id: \'phone\',
			caption: \''; ?>
Telefon<?php echo '\',
			appearance: {
				width: 110,
				visible: false
			}
		});
		
		var column_group = new GF_Datagrid_Column({
			id: \'groupname\',
			caption: \''; ?>
Nazwa grupy<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['groupname']; ?>
<?php echo '
				],
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
		
		var column_view = new GF_Datagrid_Column({
			id: \'view\',
			caption: \''; ?>
Widok<?php echo '\',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['view']; ?>
<?php echo '
				],
			}
		});
		

    var options = {
			id: \'client\',
			mechanics: {
				key: \'idclient\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllClient,
				delete_row: deleteClient,
				edit_row: editClient,
				delete_group: deleteMultipleClients,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editClient
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_surname,
				column_firstname,
				column_group,
				column_email,
				column_phone,
				column_adddate,
				column_editdate,
				column_clientorder,
				column_view
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableClient,
				action_disableClient,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableClient,
				action_disableClient,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-clients\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>