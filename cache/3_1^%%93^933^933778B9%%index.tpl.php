<?php /* Smarty version 2.6.19, created on 2012-10-09 06:21:09
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/tags/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/tags-list.png" alt=""/>Lista tagów</h2>

<div class="block">
	<div id="list-tags"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
   function deleteTags(dg, id) {
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
			return xajax_doDeleteTags(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	function deleteMultipleTags(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteTags(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idtags\',
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
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['name']; ?>
<?php echo '
				],
			}
		});
		
		
		var column_textcount = new GF_Datagrid_Column({
			id: \'textcount\',
			caption: \''; ?>
Ilość<?php echo '\',
			appearance: {
				width: 130
			},
			sorting: {
				default_order: \'desc\'
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_productname = new GF_Datagrid_Column({
			id: \'productname\',
			caption: \''; ?>
Produkt<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [{id:\'\',caption:\'\'},'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['productname']; ?>
<?php echo ']
			}
		});
		
		var column_client = new GF_Datagrid_Column({
			id: \'client\',
			caption: \''; ?>
Dane klienta<?php echo '\',
			appearance: {
				width: 200
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_view = new GF_Datagrid_Column({
			id: \'view\',
			caption: \''; ?>
Widok<?php echo '\',
			appearance: {
				width: 150
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['view']; ?>
<?php echo '
				],
			}
		});
		
		
    var options = {
			id: \'tags\',
			mechanics: {
				key: \'idtags\',
				default_sorting: \'textcount\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllTags,
				delete_row: deleteTags,
				delete_group: deleteMultipleTags
			},
			columns: [
				column_id,
				column_name,
				column_textcount,
				column_productname,
				column_client,
				column_view
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-tags\'), options);
    
   });
   
   /*]]>*/
   
   '; ?>

   
  </script>