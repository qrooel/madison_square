<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/product-list.png" alt=""/>{trans}TXT_PRODUCTS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_PRODUCT{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-products"></div>
</div>

<script type="text/javascript">

{literal}

/*<![CDATA[*/

function processProduct(row) {

	if (row.thumb != '') {
		row.name = '<a title="" href="' + row.thumb + '" class="show-thumb"><img src="{/literal}{$DESIGNPATH}{literal}_images_panel/icons/datagrid/details.png" style="vertical-align: middle;" /></a> '+ row.name;
	}else{
		row.name = '<img style="opacity: 0.2;vertical-align: middle;" src="{/literal}{$DESIGNPATH}{literal}_images_panel/icons/datagrid/details.png" style="vertical-align: middle;" /> '+ row.name;
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
		enable: (row.enable == 1) ? '{/literal}{transjs}TXT_YES{/transjs}{literal}' : '{/literal}{transjs}TXT_NO{/transjs}{literal}',
		categoriesname: row.categoriesname
	};
};

function dataLoaded(dDg) {
	dDg.m_jBody.find('.show-thumb').mouseenter(GTooltip.ShowThumbForThis).mouseleave(GTooltip.HideThumbForThis);
 };
 
function editProduct(dg, id) {
	location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
};

function editProductTab(dg, id) {
	window.open('{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id);
};

function viewProduct(dg, id) {
	var oRow = theDatagrid.GetRow(id);
	window.open(oRow.seo);
};

function duplicateProduct(dg, id) {
	location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/duplicate/{literal}' + id + '';
};

function deleteProduct(dg, id) {
	var oRow = theDatagrid.GetRow(id);
	var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
	var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + oRow.name + '?';
	var params = {
		dg: dg,
		id: id,
		view: {/literal}{$view}{literal}
	};
	var func = function(p) {
		return xajax_doDeleteProduct(p.id, p.dg);
	};
	new GF_Alert(title, msg, func, true, params);
};

function deleteMultipleProducts(dg, ids) {
	var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
	var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
	var params = {
		dg: dg,
		ids: ids,
		view: {/literal}{$view}{literal}
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
	var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
	var msg = '{/literal}{trans}TXT_DISABLE_PRODUCT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
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
	var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
	var msg = '{/literal}{trans}TXT_ENABLE_PRODUCT{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
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
		id: 'idproduct',
		caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
		appearance: {
			width: 90,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_name = new GF_Datagrid_Column({
		id: 'name',
		caption: '{/literal}{trans}TXT_NAME{/trans}{literal}',
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
		id: 'delivelercode',
		caption: '{/literal}{trans}TXT_DELIVELERCODE{/trans}{literal}',
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
		id: 'ean',
		caption: '{/literal}{trans}TXT_EAN{/trans}{literal}',
		appearance: {
			width: 60,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_INPUT,
		}
	});

	var column_price = new GF_Datagrid_Column({
		id: 'sellprice',
		caption: '{/literal}{trans}TXT_JS_PRODUCT_SELECT_NET_SUBSUM{/trans}{literal}',
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
		id: 'weight',
		caption: '{/literal}{trans}TXT_WEIGHT{/trans}{literal}',
		appearance: {
			width: 40,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_price_gross = new GF_Datagrid_Column({
		id: 'sellprice_gross',
		caption: '{/literal}{trans}TXT_JS_PRODUCT_SELECT_SUBSUM{/trans}{literal}',
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
		id: 'buyprice',
		caption: '{/literal}{trans}TXT_BUYPRICE{/trans}{literal}',
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
		id: 'buyprice_gross',
		caption: '{/literal}{trans}TXT_BUYPRICE_GROSS{/trans}{literal}',
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
		id: 'stock',
		caption: '{/literal}{trans}TXT_JS_PRODUCT_VARIANTS_EDITOR_STOCK{/trans}{literal}',
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
		id: 'status',
		caption: '{/literal}{trans}TXT_STATUS{/trans}{literal}',
		appearance: {
			width: 60
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
				{/literal}{$datagrid_filter.status}{literal}
			],
		}
	});
	
	var column_enable = new GF_Datagrid_Column({
		id: 'enable',
		caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
		appearance: {
			width: 60
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
				{id: '', caption: ''}, {id: '1', caption: '{/literal}{transjs}TXT_YES{/transjs}{literal}'}, {id: '0', caption: '{/literal}{transjs}TXT_NO{/transjs}{literal}'}
			],
		}
	});
	
	var column_producer = new GF_Datagrid_Column({
		id: 'producer',
		caption: '{/literal}{trans}TXT_PRODUCER{/trans}{literal}',
		appearance: {
			width: 90
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			{/literal}{$datagrid_filter.producer}{literal}
			],
		}
	});

	var column_deliverer = new GF_Datagrid_Column({
		id: 'deliverer',
		caption: '{/literal}{trans}TXT_DELIVERER{/trans}{literal}',
		appearance: {
			width: 90,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			{/literal}{$datagrid_filter.deliverer}{literal}
			],
		}
	});

	var column_vat = new GF_Datagrid_Column({
		id: 'vat',
		caption: '{/literal}{trans}TXT_VAT{/trans}{literal}',
		appearance: {
			width: 60,
			visible: false,
			align: GF_Datagrid.ALIGN_RIGHT
		},
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			{/literal}{$datagrid_filter.vat}{literal}
			],
		}
	});

	var column_category = new GF_Datagrid_Column({
		id: 'categoriesname',
		caption: '{/literal}{trans}TXT_CATEGORY{/trans}{literal}',
		appearance: {
			width: 150
		},
		filter: {
			type: GF_Datagrid.FILTER_TREE,
			filtered_column: 'ancestorcategoryid',
			options: {/literal}{$datagrid_filter.categoryid}{literal},
			load_children: xajax_LoadCategoryChildren
		}
	});
	
	var column_adddate = new GF_Datagrid_Column({
		id: 'adddate',
		caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
		appearance: {
			width: 140,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_adduser = new GF_Datagrid_Column({
		id: 'adduser',
		caption: '{/literal}{trans}TXT_ADDUSER{/trans}{literal}',
		appearance: {
			width: 140,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_INPUT,
		}
	});

	var column_editdate = new GF_Datagrid_Column({
		id: 'editdate',
		caption: '{/literal}{trans}TXT_EDITDATE{/trans}{literal}',
		appearance: {
			width: 140,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_BETWEEN,
		}
	});

	var column_edituser = new GF_Datagrid_Column({
		id: 'edituser',
		caption: '{/literal}{trans}TXT_EDITUSER{/trans}{literal}',
		appearance: {
			width: 140,
			visible: false
		},
		filter: {
			type: GF_Datagrid.FILTER_INPUT,
		}
	});

	var action_enableProduct = new GF_Action({
		caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
		action: enableProduct,
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
		condition: function(oR) { return oR['enable'] == '0'; }
	});
	 
	var action_disableProduct= new GF_Action({
		caption: '{/literal}{trans}TXT_NOT_PUBLISH{/trans}{literal}',
		action: disableProduct,
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
		condition: function(oR) { return oR['enable'] == '1'; }
	});
	
	var action_duplicate = new GF_Action({
		img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/datagrid/duplicate-row.png',
		caption: '{/literal}{trans}TXT_DUPLICATE{/trans}{literal}',
		action: duplicateProduct
	});
	
	var action_edittab = new GF_Action({
		img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/icons/datagrid/edit.png',
		caption: '{/literal}{trans}TXT_EDIT_NEW_TAB{/trans}{literal}',
		action: editProductTab
	});

	var action_changeStatus = new GF_Action({
		img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/datagrid/change-status.png',
		caption: '{/literal}{trans}TXT_CHANGE_STATUS{/trans}{literal}',
		action: changeStatus,
		values: {/literal}{$productStatuses}{literal}
	});

	var action_changeStatusMulti = new GF_Action({
		img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/datagrid/change-status.png',
		caption: '{/literal}{trans}TXT_CHANGE_STATUS{/trans}{literal}',
		action: changeStatusMulti,
		values: {/literal}{$productStatuses}{literal}
	});
	
	var action_changeEnableMulti = new GF_Action({
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
		caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
		action: changeEnableMulti,
	});
	
	var action_changeDisableMulti = new GF_Action({
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
		caption: '{/literal}{trans}TXT_NOT_PUBLISH{/trans}{literal}',
		action: changeDisableMulti,
	});

	var options = {
		id: 'product',
		mechanics: {
			key: 'idproduct',
			rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
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
			{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
			click_row: editProduct
			{/literal}{/if}{literal}
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

	theDatagrid = new GF_Datagrid($('#list-products'), options);

});

/*]]>*/

{/literal}

  </script>
