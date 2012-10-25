<?php /* Smarty version 2.6.19, created on 2012-10-08 09:41:18
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/crosssell/index/index.tpl */ ?>
<h2>Lista produktów w sprzedaży krzyżowej</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj sprzedaż krzyżową</span></a></li>
</ul>

<div class="block">
	<div id="list-crosssell"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/

	 function editCrosssell(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	 
	 function deleteCrosssell(dg, id) {
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
			return xajax_doDeleteCrosssell(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

   function deleteMultipleCrosssells(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteCrosssell(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idcrosssell\',
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
Produkt<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});

		var column_category = new GF_Datagrid_Column({
			id: \'categoriesname\',
			caption: \''; ?>
Kategorie<?php echo '\',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_TREE,
				filtered_column: \'ancestorcategoryid\',
				options: '; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['categoryid']; ?>
<?php echo ',
				load_children: xajax_LoadCategoryChildren
			}
		});
		
		var column_productcount = new GF_Datagrid_Column({
			id: \'productcount\',
			caption: \''; ?>
Liczba produktów<?php echo '\',
			appearance: {
				width: 130
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
			id: \'crosssell\',
			mechanics: {
				key: \'idcrosssell\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllCrosssell,
				delete_row: deleteCrosssell,
				edit_row: editCrosssell,
				delete_group: deleteMultipleCrosssells,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editCrosssell
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_category,
				column_productcount,
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
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-crosssell\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>