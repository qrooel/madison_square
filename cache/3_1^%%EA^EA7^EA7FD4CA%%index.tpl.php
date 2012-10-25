<?php /* Smarty version 2.6.19, created on 2012-10-08 21:56:33
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/groups/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/clientgroup-list.png" alt=""/>Lista grup użytkowników</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj grupę użytkowników</span></a></li>
</ul>

<div class="block">
	<div id="list-groups"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
	 
	 function editGroup(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteGroup(dg, id) {
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
			return xajax_doDeleteGroup(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleGroups(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteGroup(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
   
   function displayUsers(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'users/index,groupname=\' + GF_Datagrid.ReturnInstance(dg).GetRow(id).name + \'\';
   };
		
	 var action_displayUsers = new GF_Action({
		caption: \''; ?>
Wyświetl użytkowników należących do tej grupy<?php echo '\',
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/activate.png<?php echo '\',
		action: displayUsers
	 });

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idgroup\',
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
				width: 460
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_user_count = new GF_Datagrid_Column({
			id: \'usercount\',
			caption: \''; ?>
Liczba użytkowników<?php echo '\',
			appearance: {
				width: 80
			},
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
			id: \'group\',
			mechanics: {
				key: \'idgroup\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllGroups,
				delete_row: deleteGroup,
				edit_row: editGroup,
				delete_group: deleteMultipleGroups,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editGroup
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_user_count,
				column_adddate,
				column_adduser,
				column_editdate,
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
				GF_Datagrid.ACTION_DELETE,
				action_displayUsers
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-groups\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>