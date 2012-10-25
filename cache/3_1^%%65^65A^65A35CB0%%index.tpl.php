<?php /* Smarty version 2.6.19, created on 2012-10-08 21:55:43
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/poll/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/poll-list.png" alt=""/>Lista ankiet</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj ankietę</span></a></li>
</ul>

<div class="block">
	<div id="list-polls"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/
   
   function deletePoll(dg, id) {
   		var oRow = theDatagrid.GetRow(id);
		var topic = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.questions +\'</strong> ?\';
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
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
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
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };
	 
	 function enableNews(dg, id) {
		xajax_enableNews(dg, id);
	 };
	 
	 function disablePoll(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Widoczny<?php echo '\';
		var msg = \''; ?>
Wyłącz publikacje bloku<?php echo ' <strong>\' + oRow.questions +\'</strong> ?\';
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
		var title = \''; ?>
Widoczny<?php echo '\';
		var msg = \''; ?>
Publikuj blok<?php echo ' <strong>\' + oRow.questions +\'</strong> ?\';
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
			caption: \''; ?>
Widoczny<?php echo '\',
			action: enablePoll,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/off.png<?php echo '\',
			condition: function(oR) { return oR[\'publish\'] != \'1\'; }
		 });
		 
		 var action_disablePoll= new GF_Action({
			caption: \''; ?>
Nie publikuj<?php echo '\',
			action: disablePoll,
			img: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/on.png<?php echo '\',
			condition: function(oR) { return oR[\'publish\'] == \'1\'; }
		 });
		
		var column_id = new GF_Datagrid_Column({
			id: \'idpoll\',
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
		
		var column_questions = new GF_Datagrid_Column({
			id: \'questions\',
			caption: \''; ?>
Pytanie<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetQuestionsSuggestions,
			}
		});
		
		var column_votes = new GF_Datagrid_Column({
			id: \'votes\',
			caption: \''; ?>
Odpowiedzi<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
    	var options = {
			id: \'poll\',
			mechanics: {
				key: \'idpoll\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllPoll,
				delete_row: deletePoll,
				edit_row: editPoll,
				delete_group: deleteMultiplePolls,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editPoll
				'; ?>
<?php endif; ?><?php echo '
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
    
    theDatagrid = new GF_Datagrid($(\'#list-polls\'), options);
		
	 });
   
   /*]]>*/
   
   '; ?>

   
  </script>