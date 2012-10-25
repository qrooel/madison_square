<?php /* Smarty version 2.6.19, created on 2012-10-08 10:13:55
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/news/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/news-list.png" alt=""/>Aktualności</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj</span></a></li>
</ul>

<div class="block">
	<div id="list-news"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
   function deleteNews(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.topic +\'</strong> ?\';
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
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
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
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	 
	function enableNews(dg, id) {
		xajax_enableNews(dg, id);
	 };
	 
	 function disableNews(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Widoczny<?php echo '\';
		var msg = \''; ?>
Wyłącz publikacje bloku<?php echo ' <strong>\' + oRow.topic +\'</strong> ?\';
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
		var title = \''; ?>
Widoczny<?php echo '\';
		var msg = \''; ?>
Publikuj blok<?php echo ' <strong>\' + oRow.topic +\'</strong> ?\';
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
			caption: \''; ?>
Widoczny<?php echo '\',
			action: enableNews,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/off.png<?php echo '\',
			condition: function(oR) { return oR[\'publish\'] != \'1\'; }
		 });
		 
		 var action_disableNews= new GF_Action({
			caption: \''; ?>
Nie publikuj<?php echo '\',
			action: disableNews,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/on.png<?php echo '\',
			condition: function(oR) { return oR[\'publish\'] == \'1\'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: \'idnews\',
			caption: \''; ?>
ID<?php echo '\',
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
			id: \'topic\',
			caption: \''; ?>
Tytuł<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetTopicSuggestions,
			}
		});
		 
    	var options = {
			id: \'news\',
			mechanics: {
				key: \'idnews\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllNews,
				delete_row: deleteNews,
				edit_row: editNews,
				delete_group: deleteMultipleNews,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editNews
				'; ?>
<?php endif; ?><?php echo '
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
    
    theDatagrid = new GF_Datagrid($(\'#list-news\'), options);
	
	});
   
   /*]]>*/
   
   '; ?>

   
</script>