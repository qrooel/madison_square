<?php /* Smarty version 2.6.19, created on 2012-10-08 20:11:52
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/integration/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/rulescart-list.png" alt=""/>Integracje</h2>

<div class="block">
	<div id="list-integration"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
   	 function editIntegration(dg, id) {
   		location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	 
	 var theDatagrid;
	  
   $(document).ready(function() {
   
		var column_id = new GF_Datagrid_Column({
			id: \'idintegration\',
			caption: \''; ?>
ID<?php echo '\',
			appearance: {
				width: 90
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_name = new GF_Datagrid_Column({
			id: \'name\',
			caption: \''; ?>
Nazwa porównywarki<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_symbol = new GF_Datagrid_Column({
			id: \'symbol\',
			caption: \''; ?>
Symbol porównywarki<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		
		
		

    var options = {
			id: \'integration\',
			mechanics: {
				key: \'idintegration\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllIntegration,
				edit_row: editIntegration,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editIntegration
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_symbol
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT
			],
			group_actions: [
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-integration\'), options);
    
   });
   
   /*]]>*/
   
   '; ?>

   
  </script>