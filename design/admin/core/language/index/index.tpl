<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/language-list.png" alt=""/>{trans}TXT_LANGUAGES_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_LANGUAGE{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-languages"></div>
</div>

<script type="text/javascript">

{literal}

/*<![CDATA[*/
function processFlag(oRow) {
	if (oRow.flag != '') {
		oRow.flag = '<img src="' + GCore.DESIGN_PATH + '_images_common/icons/languages/'+oRow.flag+'" style="vertical-align: middle;" alt="{/literal}{trans}TXT_VIEW_THUMB{/trans}{literal}"/>';
	}
	return oRow;
};
	 
function editLanguage(dg, id) {
	location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
};

function deleteLanguage(dg, id) {
	var oRow = theDatagrid.GetRow(id);
	var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
	var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.translation +'</strong> ?';
	var params = {
		dg: dg,
		id: id
	};
	var func = function(p) {
		return xajax_doDeleteLanguage(p.dg, p.id);
	};
	new GF_Alert(title, msg, func, true, params);
};

var theDatagrid;

$(document).ready(function() {

	var action_removableLanguage = new GF_Action({
		caption: '{/literal}{trans}TXT_DELETE{/trans}{literal}',
		action: deleteLanguage,
		img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/delete.png{literal}',
		condition: function(oR) { return oR['idlanguage'] != '1'; }
	});
		 
	var column_id = new GF_Datagrid_Column({
		id: 'idlanguage',
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
			width: 90,
		},
	});

	var column_translation = new GF_Datagrid_Column({
		id: 'translation',
		caption: '{/literal}{trans}TXT_TRANSLATION{/trans}{literal}',
		appearance: {
			width: 90,
		},
	});

	var column_currency = new GF_Datagrid_Column({
		id: 'currency',
		caption: '{/literal}{trans}TXT_DEFAULT_LANGUAGE_CURRENCY{/trans}{literal}',
		filter: {
			type: GF_Datagrid.FILTER_SELECT,
			options: [
			{/literal}{$datagrid_filter.currency}{literal}
			],
		}

	});

	var column_flag = new GF_Datagrid_Column({
		id: 'flag',
		caption: '{/literal}{trans}TXT_LANGUAGE_FLAG{/trans}{literal}',
		appearance: {
			width: 30,
			no_title: true
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

	var options = {
		id: 'language',
		mechanics: {
			key: 'idlanguage',
			rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
		},
		event_handlers: {
			load: xajax_LoadAllLanguage,
			delete_row: deleteLanguage,
			edit_row: editLanguage,
			process: processFlag,
			{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
			click_row: editLanguage
			{/literal}{/if}{literal}
		},
		columns: [
			column_id,
			column_flag,
			column_name,
			column_translation,
			column_currency,
			column_adddate,
			column_adduser,
			column_editdate,
			column_edituser
		],
		row_actions: [
			GF_Datagrid.ACTION_EDIT,
			action_removableLanguage
		],
		context_actions: [
			GF_Datagrid.ACTION_EDIT,
			action_removableLanguage
		]
	};

	theDatagrid = new GF_Datagrid($('#list-languages'), options);

});

/*]]>*/

{/literal}

  </script>
