<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/checkpoint.png" alt=""/>{trans}TXT_CHECKPOINTS{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_CHECKPOINTS{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-checkpoint"></div>
</div>

<script type="text/javascript">
   
   {literal}
   /*<![CDATA[*/
   
   	function restore(dg, column_date){
		//Not done yet. Sorry  :(
	};

	function deleteCheckpoint(dg, id){
   		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + id +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_deleteCheckpoint(p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 function deleteCheckpoints(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids,
		};
		var func = function(p) {
			return xajax_deleteCheckpoint(p.ids);
		};
		new GF_Alert(title, msg, func, true, params);
	};
			
	 var dataProvider;
   $(document).ready(function() {
   
	   var column_id = new GF_Datagrid_Column({
			id: 'id',
			caption: '{/literal}{trans}TXT_CHECKPOINTS{/trans}{literal}',
			appearance: {
				visible: false
			}
		});
		
		var column_date = new GF_Datagrid_Column({
			id: 'date',
			caption: '{/literal}{trans}TXT_CHECKPOINTS{/trans}{literal}',
		});
		
		var column_type = new GF_Datagrid_Column({
			id: 'type',
			caption: '{/literal}{trans}TXT_TYPE{/trans}{literal}',
		});
			
	dataProvider = new GF_Datagrid_Data_Provider({
		key: 'date',
	}, {/literal}{$chkpoints}{literal});

    var options = {
			id: 'chk',
			mechanics: {
				key: 'id',
				right_click_menu: false
			},
			event_handlers: {
				delete_row: deleteCheckpoint,
				delete_group: deleteCheckpoints,
				load: function(oRequest, sResponseHandler) {
				dataProvider.Load(oRequest, sResponseHandler);
				}
			},
			columns: [
				column_id,
				column_date,
				column_type
			],
			row_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			group_actions: [
				GF_Datagrid.ACTION_DELETE
			],
			context_actions: [
				GF_Datagrid.ACTION_DELETE
			]
    };
    
    var theDatagrid = new GF_Datagrid($('#list-checkpoint'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
