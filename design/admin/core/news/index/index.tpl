<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/news-list.png" alt=""/>{trans}TXT_NEWS{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_NEWS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-news"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function deleteNews(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteNews(p.id, p.dg);
		};
    new GF_Alert(topic, msg, func, true, params);
	 };
	 
	function deleteMultipleNews(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteNews(p.ids,p.dg);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function editNews(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };
	 
	function enableNews(dg, id) {
		xajax_enableNews(dg, id);
	 };
	 
	 function disableNews(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DISABLE_PUBLISH{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_disableNews(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };	
	 
	 function enableNews(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_PUBLISH{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_ENABLE_PUBLISH{/trans}{literal} <strong>' + oRow.topic +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_enableNews(p.dg, p.id);
		};
		new GF_Alert(title, msg, func, true, params);
	 };
	 
   var theDatagrid;
   
   $(document).ready(function() {
   
  	 	var action_enableNews = new GF_Action({
			caption: '{/literal}{trans}TXT_PUBLISH{/trans}{literal}',
			action: enableNews,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/off.png{literal}',
			condition: function(oR) { return oR['publish'] != '1'; }
		 });
		 
		 var action_disableNews= new GF_Action({
			caption: '{/literal}{trans}TXT_NOT_PUBLISH{/trans}{literal}',
			action: disableNews,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/on.png{literal}',
			condition: function(oR) { return oR['publish'] == '1'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: 'idnews',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			sorting: {
				default_order: GF_Datagrid.SORT_DIR_DESC
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_topic = new GF_Datagrid_Column({
			id: 'topic',
			caption: '{/literal}{trans}TXT_TOPIC{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetTopicSuggestions,
			}
		});
		 
    	var options = {
			id: 'news',
			mechanics: {
				key: 'idnews',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllNews,
				delete_row: deleteNews,
				edit_row: editNews,
				delete_group: deleteMultipleNews,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editNews
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_topic,
			],
			row_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableNews,
				action_disableNews,
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_EDIT,
				action_enableNews,
				action_disableNews,
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
    };
    
    theDatagrid = new GF_Datagrid($('#list-news'), options);
	
	});
   
   /*]]>*/
   
   {/literal}
   
</script>
