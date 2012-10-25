<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/newsletter-list.png" alt=""/>{trans}TXT_NEWSLETTER_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_NEWSLETTER{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-newsletter"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
    function editNewsletter(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
   
	 function deleteNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteNewsletter(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleNewsletter(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteNewsletter(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
   
	 var theDatagrid;
	 
   $(document).ready(function() {

	/*
  		var action_sendNewsletter = new GF_Action({
			caption: '{/literal}{trans}TXT_SEND{/trans}{literal}',
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/buttons/arrow-right-green.png{literal}',
		});
	*/
		
		var column_id = new GF_Datagrid_Column({
			id: 'idnewsletter',
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
				width: 120,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_subject = new GF_Datagrid_Column({
			id: 'subject',
			caption: '{/literal}{trans}TXT_SUBJECT{/trans}{literal}',
			appearance: {
				width: 120,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		var column_email = new GF_Datagrid_Column({
			id: 'email',
			caption: '{/literal}{trans}TXT_EMAIL{/trans}{literal}',
			appearance: {
				width: 120,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
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

    var options = {
			id: 'newsletter',
			mechanics: {
				key: 'idnewsletter',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllNewsletter,
				delete_row: deleteNewsletter,
				edit_row: editNewsletter,
				delete_group: deleteMultipleNewsletter,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editNewsletter
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_subject,
				column_email,
				column_adddate,
				column_editdate
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
    
    theDatagrid = new GF_Datagrid($('#list-newsletter'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
