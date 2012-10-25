<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/poll-list.png" alt=""/>{trans}TXT_POLLS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_POLL{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-polls"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deletePoll(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.questions +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeletePoll(p.dg, p.id);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	 
	 function deleteMultiplePolls(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeletePoll(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editPoll(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	 
	 function enableNews(dg, id) {
		xajax_enableNews(dg, id);
	 };
	 
	 function disablePoll(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_PUBLISH{/trans}{literal} <strong>' + oRow.questions +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disablePoll(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enablePoll(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_PUBLISH{/trans}{literal} <strong>' + oRow.questions +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enablePoll(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
   
  	 var action_enablePoll = new GF_Action({
			caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
			action: enablePoll,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
			condition: function(oR) { return oR['publish'] != '1'; }
		 });
		 
		 var action_disablePoll= new GF_Action({
			caption: '{/literal}{trans}TXT_NOT_PUBLISH{/trans}{literal}',
			action: disablePoll,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
			condition: function(oR) { return oR['publish'] == '1'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idpoll',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_questions = new GF_Datagrid_Column({
			id: 'questions',
			caption: '{/literal}{trans}TXT_QUESTIONS{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetQuestionsSuggestions,
			}
		});
		
		var column_votes = new GF_Datagrid_Column({
			id: 'votes',
			caption: '{/literal}{trans}TXT_ANSWERS_DATA{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    	var options = {
			id: 'poll',
			mechanics: {
				key: 'idpoll',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllPoll,
				delete_row: deletePoll,
				edit_row: editPoll,
				delete_group: deleteMultiplePolls,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editPoll
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_questions,
				column_votes,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_enablePoll,
				action_disablePoll
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				GF_Datagrid.ACTION_DELETE,
				action_enablePoll,
				action_disablePoll
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-polls'), options);
		
	 });
   
   /*]]>*/
   
   {/literal}
   
  </script>
