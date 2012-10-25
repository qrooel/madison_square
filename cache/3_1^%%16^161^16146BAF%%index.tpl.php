<?php /* Smarty version 2.6.19, created on 2012-10-08 21:55:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/pagescheme/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/pagescheme-list.png" alt=""/>Szablon stylów sklepu</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Nowy szablon stylów sklepu</span></a></li>
</ul>

<div class="block">
	<div id="list-pagescheme"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function editPagescheme(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deletePagescheme(dg, id) {
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
			return xajax_doDeletePagescheme(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultiplePageschemes(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeletePagescheme(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
		
	 function setDefaultPagescheme(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Domyślny<?php echo '\';
		var msg = \''; ?>
Ustaw jako domyślny<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setDefaultPagescheme(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};	
		 
	 function setNoDefaultPagescheme(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
TXT_NO_DEFAULT<?php echo '\';
		var msg = \''; ?>
TXT_CHANGE_DEFAULT<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_setNoDefaultPagescheme(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	};

	 var theDatagrid;
	 
   $(document).ready(function() {
	   
	   var action_setDefaultPagescheme = new GF_Action({
		   caption: \''; ?>
Ustaw jako domyślny<?php echo '\',
		   action: setDefaultPagescheme,
	   		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/activate.png<?php echo '\',
	   		condition: function(oR) { return oR[\'def\'] == \'0\'; }
		   
	   });

	   var action_removableScheme = new GF_Action({
			caption: \''; ?>
Usuń<?php echo '\',
			action: deletePagescheme,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/delete.png<?php echo '\',
			condition: function(oR) { return oR[\'def\'] != \'1\'; }
		});
		
		var column_id = new GF_Datagrid_Column({
			id: \'idpagescheme\',
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
		});
		
		var column_default = new GF_Datagrid_Column({
			id: \'def\',
			caption: \''; ?>
Domyślny<?php echo '\',
			appearance: {
				width: 20,
				visible: true
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
			id: \'pagescheme\',
			mechanics: {
				key: \'idpagescheme\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllPagescheme,
				delete_row: deletePagescheme,
				edit_row: editPagescheme,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editPagescheme
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_default,
				column_adddate,
				column_adduser,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_removableScheme,
				action_setDefaultPagescheme
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_removableScheme,
				action_setDefaultPagescheme
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-pagescheme\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>