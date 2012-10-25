<?php /* Smarty version 2.6.19, created on 2012-10-08 22:00:13
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/layoutbox/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/layoutbox-list.png" alt=""/>Boksy</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt="Nowy boks"/>Nowy boks</span></a></li>
</ul>
<div class="block">
	<div id="list-layoutbox"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function editLayoutbox(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteLayoutbox(dg, id) {
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
			return xajax_doDeleteLayoutbox(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleLayoutbox(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteLayoutbox(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
		

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idlayoutbox\',
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
				align: GF_Datagrid.ALIGN_LEFT
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_title = new GF_Datagrid_Column({
			id: \'title\',
			caption: \''; ?>
Tytuł boksu<?php echo '\',
			appearance: {
				align: GF_Datagrid.ALIGN_LEFT
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_controller = new GF_Datagrid_Column({
			id: \'controller\',
			caption: \''; ?>
Kontroler<?php echo '\',
			appearance: {
				align: GF_Datagrid.ALIGN_LEFT
			},
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
			id: \'layoutbox\',
			mechanics: {
				key: \'idlayoutbox\',
				default_sorting: \'name\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllLayoutbox,
				delete_row: deleteLayoutbox,
				edit_row: editLayoutbox,
				delete_group: deleteMultipleLayoutbox,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editLayoutbox
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_title,
				column_controller,
				column_adddate,
				column_adduser,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-layoutbox\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>