<?php /* Smarty version 2.6.19, created on 2012-10-08 09:39:16
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/order/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/order-list.png" alt=""/>Lista zamówień</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" id="add_order" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj zamówienie</span></a></li>
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
exchange/view/4" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/xls.png" alt=""/>Eksportuj zamówienia</span></a></li>
</ul>

<div class="block">
	<div id="list-orders"></div>
</div>

<?php if ($this->_tpl_vars['view'] == 0): ?>
<script type="text/javascript">
<?php echo '
$(document).ready(function(){
	$(\'#add_order\').click(function(){
		var title = \''; ?>
Wybierz sklep aby zrealizować zamówienie<?php echo '\';
		var msg = \'\';
		var params = {};
   	 	new GF_Alert(title, msg, true, true, params);
      	return false;
	});
});
'; ?>

</script>
<?php endif; ?>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
     function viewOrder(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/view/<?php echo '\' + id + \'\';
   };
	 
	 function editOrder(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteOrder(dg, id) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + id + \'?\';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteOrder(p.id, p.dg);
		};
   	 new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleOrders(dg, ids) {
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteOrder(p.ids, p.dg);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	function changeStatus(dg, id, status) {
	return xajax_doChangeOrderStatus(id, dg, status);
};

function changeStatusMulti(dg, ids, status) {
	return xajax_doChangeOrderStatus(ids, dg, status);
};

	 var theDatagrid;
   
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idorder\',
			caption: \''; ?>
ID<?php echo '\',
			appearance: {
				width: 30
			},
			sorting: {
				default_order: GF_Datagrid.SORT_DIR_DESC
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_client = new GF_Datagrid_Column({
			id: \'client\',
			caption: \''; ?>
Dane klienta<?php echo '\',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetClientSuggestions,
			}
		});
		
		var column_price = new GF_Datagrid_Column({
			id: \'price\',
			caption: \''; ?>
Wartość podstawowa<?php echo '\',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_products = new GF_Datagrid_Column({
			id: \'products\',
			caption: \''; ?>
Produkty<?php echo '\',
			appearance: {
				width: 190,
				align: GF_Datagrid.ALIGN_LEFT
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_globalprice = new GF_Datagrid_Column({
			id: \'globalprice\',
			caption: \''; ?>
Wartość całkowita<?php echo '\',
			appearance: {
				width: 80
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_dispatchmethodprice = new GF_Datagrid_Column({
			id: \'dispatchmethodprice\',
			caption: \''; ?>
Koszt wysyłki<?php echo '\',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_orderstatusname = new GF_Datagrid_Column({
			id: \'orderstatusname\',
			caption: \''; ?>
Status<?php echo '\',
			appearance: {
				width: 190,
			},
			filter: {
				type: GF_Datagrid.FILTER_TREE,
				filtered_column: \'orderstatusid\',
				options: '; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['orderstatusid']; ?>
<?php echo ',
			}
		});
		
		var column_dispatchmethodname = new GF_Datagrid_Column({
			id: \'dispatchmethodname\',
			caption: \''; ?>
Sposób dostawy<?php echo '\',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['dispatchmethodname']; ?>
<?php echo '
				],
			}
		});
		
		var column_paymentmethodname = new GF_Datagrid_Column({
			id: \'paymentmethodname\',
			caption: \''; ?>
Sposób płatności<?php echo '\',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['paymentmethodname']; ?>
<?php echo '
				],
			}
		});
		
		var column_clientid = new GF_Datagrid_Column({
			id: \'clientid\',
			caption: \''; ?>
Dane klienta<?php echo '\',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['clientid']; ?>
<?php echo '
				],
			}
		});
		
		var column_adddate = new GF_Datagrid_Column({
			id: \'adddate\',
			caption: \''; ?>
Data dodania<?php echo '\',
			appearance: {
				width: 110
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

		var action_changeStatus = new GF_Action({
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '/_images_panel/datagrid/change-status.png\',
			caption: \''; ?>
Zmień status<?php echo '\',
			action: changeStatus,
			values: '; ?>
<?php echo $this->_tpl_vars['order_statuses']; ?>
<?php echo '
		});
	
		var action_changeStatusMulti = new GF_Action({
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '/_images_panel/datagrid/change-status.png\',
			caption: \''; ?>
Zmień status<?php echo '\',
			action: changeStatusMulti,
			values: '; ?>
<?php echo $this->_tpl_vars['order_statuses']; ?>
<?php echo '
		});
	
    var options = {
			id: \'order\',
			mechanics: {
				key: \'idorder\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllOrder,
				view_row: viewOrder,
				delete_row: deleteOrder,
				edit_row: editOrder,
				delete_group: deleteMultipleOrders,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editOrder
				'; ?>
<?php endif; ?><?php echo '
			},
			columns: [
				column_id,
				column_client,
				column_products,
				column_price,
				column_dispatchmethodprice,
				column_orderstatusname,
				column_dispatchmethodname,
				column_paymentmethodname,
				column_clientid,
				column_globalprice,
				column_adddate,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_changeStatus,
				GF_Datagrid.ACTION_DELETE,
//				GF_Datagrid.ACTION_VIEW,
			],
			context_actions: [
				action_changeStatus,
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				action_changeStatus,
				GF_Datagrid.ACTION_DELETE
			],
		
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-orders\'), options);
    
    $(\'.GF_Datagrid_Col_adddate .GF_Datagrid_filter_between .from,.GF_Datagrid_Col_adddate .GF_Datagrid_filter_between .to\').datepicker({dateFormat: \'yy-mm-dd\'});

	});
   
   /*]]>*/
   
   '; ?>

   
  </script>