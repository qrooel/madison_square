<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/clientnewsletter-list.png" alt=""/>{trans}TXT_CLIENT_NEWSLETTERS_LIST{/trans}</h2>

<div class="block">
	<div id="list-clientnewsletters"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
	 function deleteClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.email +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteClientNewsletter(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleClientNewsletter(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteClientNewsletter(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
   
   	function enableClientNewsletter(dg, id) {
		xajax_enableClientNewsletter(dg, id);
	 };
	 
	 function disableClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DISABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_CONFIRM{/trans}{literal} <strong>' + oRow.email +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableClientNewsletter(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableClientNewsletter(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_ENABLE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_CONFIRM{/trans}{literal} <strong>' + oRow.email +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableClientNewsletter(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
   
  		 var action_enableClientNewsletter = new GF_Action({
			caption: '{/literal}{trans}TXT_ENABLE_CLIENT{/trans}{literal}',
			action: enableClientNewsletter,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/deactivate.png{literal}',
			condition: function(oR) { return oR['active'] != '{/literal}{trans}TXT_ACTIVE{/trans}{literal}'; }
		 });
		 
		 var action_disableClientNewsletter = new GF_Action({
			caption: '{/literal}{trans}TXT_DISABLE_CLIENT{/trans}{literal}',
			action: disableClientNewsletter,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/activate.png{literal}',
			condition: function(oR) { return oR['active'] == '{/literal}{trans}TXT_ACTIVE{/trans}{literal}'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idclientnewsletter',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_email = new GF_Datagrid_Column({
			id: 'email',
			caption: '{/literal}{trans}TXT_EMAIL{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		var column_adddate = new GF_Datagrid_Column({
			id: 'adddate',
			caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
			appearance: {
				width: 140,
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

    	var options = {
			id: 'clientnewsletter',
			mechanics: {
				key: 'idclientnewsletter',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllClientNewsletter,
				delete_row: deleteClientNewsletter,
				delete_group: deleteMultipleClientNewsletter,
			},
			columns: [
				column_id,
				column_email,
				column_adddate
			],
			row_actions: [
				action_enableClientNewsletter,
				action_disableClientNewsletter,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
  				action_enableClientNewsletter,
				action_disableClientNewsletter,
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-clientnewsletters'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
