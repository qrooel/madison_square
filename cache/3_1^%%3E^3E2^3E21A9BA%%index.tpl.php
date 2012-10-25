<?php /* Smarty version 2.6.19, created on 2012-10-08 20:11:33
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/clientnewsletter/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/clientnewsletter-list.png" alt=""/>Lista klientów zapisanych do Newsletter</h2>

<div class="block">
	<div id="list-clientnewsletters"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function deleteClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.email +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteClientNewsletter(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleClientNewsletter(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteClientNewsletter(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
   
   	function enableClientNewsletter(dg, id) {
		xajax_enableClientNewsletter(dg, id);
	 };
	 
	 function disableClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Wyłącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie wyłączenia<?php echo ' <strong>\' + oRow.email +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableClientNewsletter(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Włącz<?php echo '\';
		var msg = \''; ?>
Potwierdzenie włączenia<?php echo ' <strong>\' + oRow.email +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableClientNewsletter(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
   
  		 var action_enableClientNewsletter = new GF_Action({
			caption: \''; ?>
Aktywuj klienta<?php echo '\',
			action: enableClientNewsletter,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/deactivate.png<?php echo '\',
			condition: function(oR) { return oR[\'active\'] != \''; ?>
Aktywna<?php echo '\'; }
		 });
		 
		 var action_disableClientNewsletter = new GF_Action({
			caption: \''; ?>
Zablokuj klienta<?php echo '\',
			action: disableClientNewsletter,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/activate.png<?php echo '\',
			condition: function(oR) { return oR[\'active\'] == \''; ?>
Aktywna<?php echo '\'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: \'idclientnewsletter\',
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
		
		var column_email = new GF_Datagrid_Column({
			id: \'email\',
			caption: \''; ?>
E-mail<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		var column_adddate = new GF_Datagrid_Column({
			id: \'adddate\',
			caption: \''; ?>
Data dodania<?php echo '\',
			appearance: {
				width: 140,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

    	var options = {
			id: \'clientnewsletter\',
			mechanics: {
				key: \'idclientnewsletter\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllClientNewsletter,
				delete_row: deleteClientNewsletter,
				delete_group: deleteMultipleClientNewsletter,
			},
			columns: [
				column_id,
				column_email,
				column_adddate
			],
			row_actions: [
				action_enableClientNewsletter,
				action_disableClientNewsletter,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
  				action_enableClientNewsletter,
				action_disableClientNewsletter,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-clientnewsletters\'), options);
    
   });
   
   /*]]>*/
   
   '; ?>

   
  </script>