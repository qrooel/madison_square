<?php /* Smarty version 2.6.19, created on 2012-10-08 20:07:45
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/productstatus/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/status-list.png" alt=""/>Statusy produktów</h2>


<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj nowy status produktu</span></a></li>
</ul>

<div class="block">
	<div id="list-productstatus"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
    function editProductStatus(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteProductStatus(dg, id) {
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
			return xajax_doDeleteProductStatus(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleProductStatuses(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteProductStatus(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
    var theDatagrid;
    
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idproductstatus\',
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
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
	    var options = {
				id: \'productstatus\',
				mechanics: {
					key: \'idproductstatus\',
					rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
				},
				event_handlers: {
					load: xajax_LoadAllProductstatus,
					delete_row: deleteProductStatus,
					edit_row: editProductStatus,
					delete_group: deleteMultipleProductStatuses,
					'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
					click_row: editProductStatus
					'; ?>
<?php endif; ?><?php echo '
				},
				columns: [
					column_id,
					column_name
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
    
   	  theDatagrid = new GF_Datagrid($(\'#list-productstatus\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>