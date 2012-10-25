<?php /* Smarty version 2.6.19, created on 2012-10-08 09:40:19
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/view/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/view.png" alt=""/>Lista sklepów</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj sklep</span></a></li>
</ul>

<div class="block">
	<div id="list-shop-view"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function editView(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteView(dg, id) {
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
			return xajax_doDeleteView(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleView(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteView(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idview\',
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
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
			}
		});
		
		var column_namespace = new GF_Datagrid_Column({
			id: \'namespace\',
			caption: \''; ?>
Namespace<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
			}
		});

		var column_url = new GF_Datagrid_Column({
			id: \'url\',
			caption: \''; ?>
Adres WWW<?php echo '\',
			appearance: {
				width: 150
			}
		});
		
		var column_store = new GF_Datagrid_Column({
			id: \'store\',
			caption: \''; ?>
Firmy<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
			}
		});

    	var options = {
			id: \'view\',
			mechanics: {
				key: \'idview\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllView,
				delete_row: deleteView,
				edit_row: editView,
				delete_group: deleteMultipleView,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editView
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_namespace,
				column_url,
				column_store
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
				GF_Datagrid.ACTION_DELETE,
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-shop-view\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>