<?php /* Smarty version 2.6.19, created on 2012-10-08 21:56:05
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/currencieslist/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/view.png" alt=""/>Waluty</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj walutę</span></a></li>
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
#refresh" id="refresh" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/refresh.png" alt=""/>Pobierz kursy</span></a></li>
</ul>

<div class="block">
	<div id="list-currencieslist"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/

   function processCurrency(row) {
   	
	return {
		id: row.id,
		name: row.name,
		currencysymbol: row.currencysymbol,
		currencyto: row.exchangerate
	};
};

    function updateCurrency(dg, id) {
    	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Aktualizacja kursów walutowych<?php echo '\';
		var msg = \''; ?>
Czy chcesz zaktualizować kursy dla waluty<?php echo ' <strong>\' + oRow.currencysymbol +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doUpdateCurrency(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editCurrencieslist(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	
	 function calculateCurrencieslist(dg, id) {

		return xajax_doDeleteCurrencieslist(id);
		
	 };
	 
	 function deleteCurrencieslist(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var topic = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.currencysymbol +\'</strong> ?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteCurrencieslist(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	
	function deleteMultipleCurrencieslist(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteCurrencieslist(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 

	var theDatagrid;
	
	var action_updateCurrency = new GF_Action({
			caption: \''; ?>
Aktualizuj<?php echo '\',
			action: updateCurrency,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/refresh.png<?php echo '\'
			
	});
			
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'id\',
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
Nazwa waluty<?php echo '\',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
			}
		});
		
		var column_currencysymbol = new GF_Datagrid_Column({
			id: \'currencysymbol\',
			caption: \''; ?>
Symbol waluty<?php echo '\',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT
				
			}
		});
		
		var column_currencyto = new GF_Datagrid_Column({
			id: \'currencyto\',
			caption: \''; ?>
Kursy wymiany<?php echo '\',
			appearance: {
				width: 70
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['currencyto']; ?>
<?php echo '
				],
			}
		});
		
    	var options = {
			id: \'currencieslist\',
			mechanics: {
				key: \'id\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllCurrencieslist,
				edit_row: editCurrencieslist,
				delete_row: deleteCurrencieslist,
				delete_group: deleteMultipleCurrencieslist,
				process: processCurrency,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editCurrencieslist
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_name,
				column_currencysymbol,
				column_currencyto
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_updateCurrency,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_updateCurrency,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-currencieslist\'), options);
		
	 });
   
	 $(\'#refresh\').click(function(){
	 	return xajax_refreshAllCurrencies();
	 });
   /*]]>*/
   
   '; ?>

   
  </script>