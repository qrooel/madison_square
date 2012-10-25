<?php /* Smarty version 2.6.19, created on 2012-10-08 20:11:55
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/sitemaps/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/translation-list.png" alt=""/>Lista map strony</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj mapę strony</span></a></li>
</ul>

<div class="block">
	<div id="list-sitemaps"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
   function deleteSitemaps(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteSitemaps(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	 
	function deleteMultipleSitemaps(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteSitemaps(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editSitemaps(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	 
	function refreshSitemaps(dg, id) {
		xajax_refreshSitemaps(dg, id);
	};
	 
	 
   var theDatagrid;
   
   $(document).ready(function() {
   
  	 	var action_refreshSitemaps = new GF_Action({
			caption: \''; ?>
Odśwież<?php echo '\',
			action: refreshSitemaps,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/refresh.png<?php echo '\'
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: \'idsitemaps\',
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
				width: 60
			}
		});
		
		var column_pingserver = new GF_Datagrid_Column({
			id: \'pingserver\',
			caption: \''; ?>
Pingserver<?php echo '\',
			appearance: {
				width: 160,
				visible: false
			},
		});
		
		var column_lastupdate = new GF_Datagrid_Column({
			id: \'lastupdate\',
			caption: \''; ?>
Ostatni ping<?php echo '\',
			appearance: {
				width: 50
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
			}
		});
		
    	var options = {
			id: \'sitemaps\',
			mechanics: {
				key: \'idsitemaps\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllSitemaps,
				delete_row: deleteSitemaps,
				edit_row: editSitemaps,
				delete_group: deleteMultipleSitemaps,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editSitemaps
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_pingserver,
				column_lastupdate
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_refreshSitemaps,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_refreshSitemaps,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-sitemaps\'), options);
	
	});
   
   /*]]>*/
   
   '; ?>

   
</script>