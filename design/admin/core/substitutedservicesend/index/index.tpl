<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/newsletter-list.png" alt=""/>{trans}TXT_SUBSTITUTED_SERVICE{/trans}</h2>
<div class="block">
	<div id="list-substitutedservices"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
   function sendSubstitutedService(dg, id) {
	   location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/confirm/{literal}' + id + '';
   };
   
   function viewSubstitutedService(dg, id) {
	   location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/view/{literal}' + id + '';
   };
 
   var theDatagrid;
	 
   $(document).ready(function() {
	   
	   var action_sendSubstitutedService = new GF_Action({
			caption: '{/literal}{trans}TXT_SEND{/trans}{literal}',
			action: sendSubstitutedService,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/send.png{literal}',
			condition: function(oR) { return oR['disable'] != '0'; }
		 });
	   
	   var action_viewSubstitutedService = new GF_Action({
			caption: '{/literal}{trans}TXT_VIEW_REPORT{/trans}{literal}',
			action: viewSubstitutedService,
			img: '{/literal}{$DESIGNPATH}_images_panel/icons/datagrid/report.png{literal}',
			condition: function(oR) { return oR['disable'] != '0'; }
		 });

		var column_id = new GF_Datagrid_Column({
			id: 'idsubstitutedservice',
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
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		var column_transmailname = new GF_Datagrid_Column({
			id: 'transmailname',
			caption: '{/literal}{trans}TXT_TRANSMAIL{/trans}{literal}',
			appearance: {
				width: 250,
			}
		});
		
    var options = {
			id: 'idsubstitutedservice',
			mechanics: {
				key: 'idsubstitutedservice'
			},
			event_handlers: {
				load: xajax_LoadAllSubstitutedservice
			},
			columns: [
				column_id,
				column_name,
				column_transmailname
			],
			row_actions: [
			  action_sendSubstitutedService,
			  action_viewSubstitutedService
			],
			context_actions: [
			  action_sendSubstitutedService,
			  action_viewSubstitutedService
			]
    };
    
    theDatagrid = new GF_Datagrid($('#list-substitutedservices'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
