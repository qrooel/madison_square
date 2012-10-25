<?php /* Smarty version 2.6.19, created on 2012-10-08 18:58:50
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/invoice/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/invoice-list.png" alt=""/>Faktury</h2>
<ul class="possibilities">
	<li><a href="#" id="export" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/save.png" alt="" />Eksport zaznaczonych</span></a></li>
</ul>
<div class="block">
	<div id="list-invoice"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
    var theDatagrid;

    function viewOrder(dg, id) {
        location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/view/<?php echo '\' + id + \'\';
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
			return xajax_doDeleteInvoice(p.id, p.dg);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: \'idinvoice\',
			caption: \''; ?>
ID<?php echo '\',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_invoicedate = new GF_Datagrid_Column({
			id: \'invoicedate\',
			caption: \''; ?>
Data<?php echo '\',
			appearance: {
				width: 140,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_symbol = new GF_Datagrid_Column({
			id: \'symbol\',
			caption: \''; ?>
Faktura<?php echo '\',
			appearance: {
				width: GF_Datagrid.WIDTH_AUTO,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_orderid = new GF_Datagrid_Column({
			id: \'orderid\',
			caption: \''; ?>
Zamówienie<?php echo '\',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    	var options = {
			id: \'invoice\',
			mechanics: {
				key: \'idinvoice\'
			},
			event_handlers: {
				load: xajax_LoadAllInvoice,
				view_row: viewOrder,
				delete_row: deleteOrder,
			},
			columns: [
				column_id,
				column_invoicedate,
				column_symbol,
				column_orderid,
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE,
				GF_Datagrid.ACTION_VIEW,
			],
    };
    
    theDatagrid = new GF_Datagrid($(\'#list-invoice\'), options);

    $(\'#export\').click(function(){
    	var selected = theDatagrid.GetSelected();
    	if(selected.length){
			location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/confirm/<?php echo '\'+ Base64.encode(JSON.stringify(selected));
    	}else{
    		var title = \''; ?>
Eksport faktur<?php echo '\';
			var msg = \''; ?>
Nie wybrano żadnych faktur do eksportu<?php echo '\';
			var params = {};
			var func = function(p) {

			};
	    	new GMessage(title, msg);
    	}
		return false;
    });
    
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>