<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/order-list.png" alt=""/>Zamówienia do wysłania poprzez Inpost</h2>

<ul class="possibilities">
	<li><a href="{$URL}inpost/confirm" class="button"><span><img src="{$DESIGNPATH}_images_panel/datagrid/update.png" alt=""/>Aktualizuj statusy paczek</span></a></li>
</ul>

<div class="block">
	<div id="list-orders"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/
   
     function createPackage(dg, id) {
    	location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/add/{literal}' + id + '';
   		};
	 
	 function downloadSlip(dg, id) {
		var oRow = theDatagrid.GetRow(id);
    	location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/view/{literal}' + oRow.inpostpackage + '';
	 };

	 var theDatagrid;
   
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idorder',
			caption: '{/literal}{trans}TXT_ID{/trans}{literal}',
			appearance: {
				width: 30
			},
			sorting: {
				default_order: GF_Datagrid.SORT_DIR_DESC
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_client = new GF_Datagrid_Column({
			id: 'client',
			caption: '{/literal}{trans}TXT_CLIENT{/trans}{literal}',
			appearance: {
				width: 140
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_price = new GF_Datagrid_Column({
			id: 'price',
			caption: '{/literal}{trans}TXT_ORDER_BASE_VALUE{/trans}{literal}',
			appearance: {
				width: 90,
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_paczkomat = new GF_Datagrid_Column({
			id: 'paczkomat',
			caption: 'Paczkomat',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_inpostpackage = new GF_Datagrid_Column({
			id: 'inpostpackage',
			caption: 'Numer paczki',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});

		var column_packagestatus = new GF_Datagrid_Column({
			id: 'packagestatus',
			caption: 'Status paczki',
			appearance: {
				width: 90,
			},
			filter: {
				type: GF_Datagrid.FILTER_SELECT,
				options: [
					{id: '', caption: ''}, 
					{id: 'Pending', caption: 'Oczekuje na przygotowanie'}, 
					{id: 'Created', caption: 'Oczekuje na wysyłkę'},
					{id: 'Prepared', caption: 'Gotowa do wysyłki'},
					{id: 'Sent', caption: 'Przesyłka Nadana'},
					{id: 'InTransit', caption: 'W drodze'},
					{id: 'Stored', caption: 'Oczekuje na odbiór'},
					{id: 'Avizo', caption: 'Ponowne Avizo'},
					{id: 'Expired', caption: 'Nie odebrana'},
					{id: 'Delivered', caption: 'Dostarczona'},
					{id: 'RetunedToAgency', caption: 'Przekazana do Oddziału'},
					{id: 'Cancelled', caption: 'Anulowana'},
					{id: 'Claimed', caption: 'Przyjęto zgłoszenie reklamacyjne'},
					{id: 'ClaimProcessed', caption: 'Rozpatrzono zgłoszenie reklamacyjne'},
				],
			}
		});
		
		var column_globalprice = new GF_Datagrid_Column({
			id: 'globalprice',
			caption: '{/literal}{trans}TXT_TOTAL_ORDER_VALUE{/trans}{literal}',
			appearance: {
				width: 80
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});
		
		var column_adddate = new GF_Datagrid_Column({
			id: 'adddate',
			caption: '{/literal}{trans}TXT_ADDDATE{/trans}{literal}',
			appearance: {
				width: 110
			},
			filter: {
				type: GF_Datagrid.FILTER_BETWEEN,
			}
		});

		var action_createPackage = new GF_Action({
			img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/icons/datagrid/add.png',
			caption: 'Wygeneruj paczkę',
			action: createPackage,
			condition: function(oR) { return oR['inpostpackage'] == ''; }
		});
		
		var action_downloadSlip = new GF_Action({
			img: '{/literal}{$DESIGNPATH}{literal}/_images_panel/icons/datagrid/save.png',
			caption: 'Pobierz etykietę',
			action: downloadSlip,
			condition: function(oR) { return oR['inpostpackage'] != ''; }
		});
		
	    var options = {
			id: 'order',
			mechanics: {
				key: 'idorder',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllOrder,
			},
			columns: [
				column_id,
				column_client,
				column_paczkomat,
				column_inpostpackage,
				column_packagestatus,
				column_price,
				column_globalprice,
				column_adddate,
			],
			row_actions: [
				action_createPackage,
				action_downloadSlip
			],
			context_actions: [
				action_createPackage,
				action_downloadSlip
			],
			group_actions: [
			],
		
    };
    
    theDatagrid = new GF_Datagrid($('#list-orders'), options);
    
	});
   
   /*]]>*/
   
   {/literal}
   
  </script>
