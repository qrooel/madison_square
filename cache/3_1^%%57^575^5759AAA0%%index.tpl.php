<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:38
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'transjs', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/index/index.tpl', 43, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/product-list.png" alt=""/>Lista produktów</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj produkt</span></a></li>
</ul>

<div class="block">
	<div id="list-products"></div>
</div>

<script type="text/javascript">

<?php echo '

/*<![CDATA[*/

function processProduct(row) {

	if (row.thumb != \'\') {
		row.name = \'<a title="" href="\' + row.thumb + \'" class="show-thumb"><img src="'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_images_panel/icons/datagrid/details.png" style="vertical-align: middle;" /></a> \'+ row.name;
	}else{
		row.name = \'<img style="opacity: 0.2;vertical-align: middle;" src="'; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_images_panel/icons/datagrid/details.png" style="vertical-align: middle;" /> \'+ row.name;
	}
	return {
		idproduct: row.idproduct,
		name: row.name,
		seo: row.seo,
		thumb: row.thumb,
		name: row.name,
		delivelercode: row.delivelercode,
		ean: row.ean,
		producer: row.producer,
		deliverer: row.deliverer,
		buyprice: row.buyprice,
		buyprice_gross: row.buyprice_gross,
		stock: row.stock,
		sellprice: row.sellprice,
		sellprice_gross: row.sellprice_gross,
		vat: row.vat,
		weight: row.weight,
		status: row.status,
		adddate: row.adddate,
		enable: (row.enable == 1) ? \''; ?>
<?php $this->_tag_stack[] = array('transjs', array()); $_block_repeat=true;$this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>TXT_YES<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '\' : \''; ?>
<?php $this->_tag_stack[] = array('transjs', array()); $_block_repeat=true;$this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>TXT_NO<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '\',
		categoriesname: row.categoriesname
	};
};

function dataLoaded(dDg) {
	dDg.m_jBody.find(\'.show-thumb\').mouseenter(GTooltip.ShowThumbForThis).mouseleave(GTooltip.HideThumbForThis);
 };
 
function editProduct(dg, id) {
	location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
};

function editProductTab(dg, id) {
	window.open(\''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id);
};

function viewProduct(dg, id) {
	var oRow = theDatagrid.GetRow(id);
	window.open(oRow.seo);
};

function duplicateProduct(dg, id) {
	location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/duplicate/<?php echo '\' + id + \'\';
};

function deleteProduct(dg, id) {
	var oRow = theDatagrid.GetRow(id);
	var title = \''; ?>
Usuń<?php echo '\';
	var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + oRow.name + \'?\';
	var params = {
		dg: dg,
		id: id,
		view: '; ?>
<?php echo $this->_tpl_vars['view']; ?>
<?php echo '
	};
	var func = function(p) {
		return xajax_doDeleteProduct(p.id, p.dg);
	};
	new GF_Alert(title, msg, func, true, params);
};

function deleteMultipleProducts(dg, ids) {
	var title = \''; ?>
Usuń<?php echo '\';
	var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
	var params = {
		dg: dg,
		ids: ids,
		view: '; ?>
<?php echo $this->_tpl_vars['view']; ?>
<?php echo '
	};
	var func = function(p) {
		return xajax_doDeleteProduct(p.ids, p.dg, p.view);
	};
	new GF_Alert(title, msg, func, true, params);
};

function changeStatus(dg, id, status) {
	return xajax_doChangeProductStatus(id, dg, status);
};

function changeStatusMulti(dg, ids, status) {
	return xajax_doChangeProductStatus(ids, dg, status);
};

function changeEnableMulti(dg, ids) {
	return xajax_setProductEnable(dg, ids, 1);
};

function changeDisableMulti(dg, ids) {
	return xajax_setProductEnable(dg, ids, 0);
};

function disableProduct(dg, id) {
 	var oRow = theDatagrid.GetRow(id);
	var title = \''; ?>
Widoczny<?php echo '\';
	var msg = \''; ?>
Wyłącz wyświetlanie produktu<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
	var params = {
		dg: dg,
		id: id
	};
	var func = function(p) {
		return xajax_setProductEnable(p.dg, p.id, 0);
	};
	new GF_Alert(title, msg, func, true, params);
 };	
 
 function enableProduct(dg, id) {
 	var oRow = theDatagrid.GetRow(id);
	var title = \''; ?>
Widoczny<?php echo '\';
	var msg = \''; ?>
Wyświetl produkt w sklepie<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
	var params = {
		dg: dg,
		id: id
	};
	var func = function(p) {
		return xajax_setProductEnable(p.dg, p.id,1);
	};
	new GF_Alert(title, msg, func, true, params);
 };
 
var theDatagrid;

$(document).ready(function() {

	var column_id = new GF_Datagrid_Column({
		id: \'idproduct\',
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
			width: 210,
			align: GF_Datagrid.ALIGN_LEFT
		},
		filter: {
			type: GF_Datagrid.FILTER_AUTOSUGGEST,
			source: xajax_GetNameSuggestions,
		}
	});

	var column_delivelercode = new GF_Datagrid_Column({
		id: \'delivelercode\',
		caption: \''; ?>
Kod dostawcy<?php echo '\',
		appearance: {
			width: 80,
			visible: false,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_INPUT,
		}
	});
	
	var column_ean = new GF_Datagrid_Column({
		id: \'ean\',
		caption: \''; ?>
Kod EAN<?php echo '\',
		appearance: {
			width: 60,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_INPUT,
		}
	});

	var column_price = new GF_Datagrid_Column({
		id: \'sellprice\',
		caption: \''; ?>
Netto<?php echo '\',
		editable: true,
		appearance: {
			width: 60,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});
	
	var column_weight = new GF_Datagrid_Column({
		id: \'weight\',
		caption: \''; ?>
Waga<?php echo '\',
		appearance: {
			width: 40,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_price_gross = new GF_Datagrid_Column({
		id: \'sellprice_gross\',
		caption: \''; ?>
Brutto<?php echo '\',
		editable: true,
		appearance: {
			width: 60,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_buyprice = new GF_Datagrid_Column({
		id: \'buyprice\',
		caption: \''; ?>
Cena zakupu<?php echo '\',
		editable: true,
		appearance: {
			width: 90,
			visible: false,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_buyprice_gross = new GF_Datagrid_Column({
		id: \'buyprice_gross\',
		caption: \''; ?>
Cena zakupu brutto<?php echo '\',
		editable: true,
		appearance: {
			width: 130,
			visible: false,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_stock = new GF_Datagrid_Column({
		id: \'stock\',
		caption: \''; ?>
Magazyn<?php echo '\',
		editable: true,
		appearance: {
			width: 60,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_status = new GF_Datagrid_Column({
		id: \'status\',
		caption: \''; ?>
Status<?php echo '\',
		appearance: {
			width: 60
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
				'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['status']; ?>
<?php echo '
			],
		}
	});
	
	var column_enable = new GF_Datagrid_Column({
		id: \'enable\',
		caption: \''; ?>
Widoczny<?php echo '\',
		appearance: {
			width: 60
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
				{id: \'\', caption: \'\'}, {id: \'1\', caption: \''; ?>
<?php $this->_tag_stack[] = array('transjs', array()); $_block_repeat=true;$this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>TXT_YES<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '\'}, {id: \'0\', caption: \''; ?>
<?php $this->_tag_stack[] = array('transjs', array()); $_block_repeat=true;$this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>TXT_NO<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['transjs'][0][0]->do_translate_js($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '\'}
			],
		}
	});
	
	var column_producer = new GF_Datagrid_Column({
		id: \'producer\',
		caption: \''; ?>
Producent<?php echo '\',
		appearance: {
			width: 90
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['producer']; ?>
<?php echo '
			],
		}
	});

	var column_deliverer = new GF_Datagrid_Column({
		id: \'deliverer\',
		caption: \''; ?>
Dostawca<?php echo '\',
		appearance: {
			width: 90,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['deliverer']; ?>
<?php echo '
			],
		}
	});

	var column_vat = new GF_Datagrid_Column({
		id: \'vat\',
		caption: \''; ?>
Podatek VAT<?php echo '\',
		appearance: {
			width: 60,
			visible: false,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			'; ?>
<?php echo $this->_tpl_vars['datagrid_filter']['vat']; ?>
<?php echo '
			],
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

	var action_enableProduct = new GF_Action({
		caption: \''; ?>
Widoczny<?php echo '\',
		action: enableProduct,
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/off.png<?php echo '\',
		condition: function(oR) { return oR[\'enable\'] == \'0\'; }
	});
	 
	var action_disableProduct= new GF_Action({
		caption: \''; ?>
Nie publikuj<?php echo '\',
		action: disableProduct,
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/on.png<?php echo '\',
		condition: function(oR) { return oR[\'enable\'] == \'1\'; }
	});
	
	var action_duplicate = new GF_Action({
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '/_images_panel/datagrid/duplicate-row.png\',
		caption: \''; ?>
DUPLIKAT<?php echo '\',
		action: duplicateProduct
	});
	
	var action_edittab = new GF_Action({
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '/_images_panel/icons/datagrid/edit.png\',
		caption: \''; ?>
Edytuj w nowej karcie<?php echo '\',
		action: editProductTab
	});

	var action_changeStatus = new GF_Action({
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '/_images_panel/datagrid/change-status.png\',
		caption: \''; ?>
Zmień status<?php echo '\',
		action: changeStatus,
		values: '; ?>
<?php echo $this->_tpl_vars['productStatuses']; ?>
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
<?php echo $this->_tpl_vars['productStatuses']; ?>
<?php echo '
	});
	
	var action_changeEnableMulti = new GF_Action({
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/on.png<?php echo '\',
		caption: \''; ?>
Widoczny<?php echo '\',
		action: changeEnableMulti,
	});
	
	var action_changeDisableMulti = new GF_Action({
		img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/off.png<?php echo '\',
		caption: \''; ?>
Nie publikuj<?php echo '\',
		action: changeDisableMulti,
	});

	var options = {
		id: \'product\',
		mechanics: {
			key: \'idproduct\',
			rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
		},
		event_handlers: {
			load: xajax_LoadAllProduct,
			process: processProduct,
			delete_row: deleteProduct,
			loaded: dataLoaded,
			edit_row: editProduct,
			view_row: viewProduct,
			delete_group: deleteMultipleProducts,
			update_row: function(sId, oRow) {
				xajax_doUpdateProduct(sId, oRow.stock, oRow.sellprice_gross, oRow.sellprice, oRow.buyprice_gross, oRow.buyprice);
			},
			'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
			click_row: editProduct
			'; ?>
<?php endif; ?><?php echo '
		},
		columns: [
			column_id,
			column_name,
			column_category,
			column_delivelercode,
			column_ean,
			column_producer,
			column_deliverer,
			column_buyprice,
			column_buyprice_gross,
			column_price,
			column_price_gross,
			column_stock,
			column_weight,
			column_status,
			column_enable,
			column_vat,
			column_adddate,
			column_adduser,
			column_editdate,
			column_edituser
		],
		row_actions: [
			GF_Datagrid.ACTION_EDIT,
			action_changeStatus,
			action_enableProduct,
			action_disableProduct,
			action_duplicate,
			GF_Datagrid.ACTION_VIEW,
			GF_Datagrid.ACTION_DELETE
		],
		group_actions: [
			action_changeStatusMulti,
			action_changeEnableMulti,
			action_changeDisableMulti,
			GF_Datagrid.ACTION_DELETE
		],
		context_actions: [
			GF_Datagrid.ACTION_EDIT,
			action_changeStatus,
			GF_Datagrid.ACTION_DELETE,
			GF_Datagrid.ACTION_VIEW,
			action_duplicate,
			action_edittab,
		]
	};

	theDatagrid = new GF_Datagrid($(\'#list-products\'), options);

});

/*]]>*/

'; ?>


  </script>