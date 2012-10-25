<?php /* Smarty version 2.6.19, created on 2012-10-09 07:42:57
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/subpagelayout/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/subpagelayout-list.png" alt=""/>Układ podstron</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt="Dodanie układu podstrony"/>Dodanie układu podstrony</span></a></li>
</ul>

<div class="block">
	<div id="list-subpagelayout"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
	 function editSubpageLayout(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idsubpagelayout\',
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
Nazwa podstrony<?php echo '\',
			appearance: {
				width: 220,
				align: GF_Datagrid.ALIGN_LEFT
			}
		});
		
		var column_description = new GF_Datagrid_Column({
			id: \'description\',
			caption: \''; ?>
Opis<?php echo '\',
			appearance: {
				align: GF_Datagrid.ALIGN_LEFT
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
			id: \'subpagelayout\',
			mechanics: {
				key: \'idsubpagelayout\',
				default_sorting: \'name\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllSubpageLayout,
				edit_row: editSubpageLayout,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editSubpageLayout
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_description,
				column_adddate,
				column_adduser,
				column_edituser
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-subpagelayout\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>