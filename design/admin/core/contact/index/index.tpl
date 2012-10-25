<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/contact-list.png" alt=""/>{trans}TXT_CONTACTS_LIST{/trans}</h2>

<ul class="possibilities">
	<li><a href="{$URL}{$CURRENT_CONTROLLER}/add" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_ADD_CONTACT{/trans}</span></a></li>
</ul>

<div class="block">
	<div id="list-contacts"></div>
</div>

<script type="text/javascript">
   
   {literal}
   
   /*<![CDATA[*/

	 function editContact(dg, id) {
    location.href = '{/literal}{$URL}{$CURRENT_CONTROLLER}/edit/{literal}' + id + '';
	 };

	 function deleteContact(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} <strong>' + oRow.name +'</strong> ?';
		var params = {
			dg: dg,
			id: id
		};
		var func = function(p) {
			return xajax_doDeleteContact(p.dg, p.id);
		};
    new GF_Alert(title, msg, func, true, params);
	 };
	 
	 function deleteMultipleContact(dg, ids) {
		var title = '{/literal}{trans}TXT_DELETE{/trans}{literal}';
		var msg = '{/literal}{trans}TXT_DELETE_CONFIRM{/trans}{literal} ' + ids.join(', ') + '?';
		var params = {
			dg: dg,
			ids: ids
		};
		var func = function(p) {
			return xajax_doDeleteContact(p.dg, p.ids);
		};
    new GF_Alert(title, msg, func, true, params);
	 };

	 var theDatagrid;
	 
   $(document).ready(function() {
		
		var column_id = new GF_Datagrid_Column({
			id: 'idcontact',
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
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_email = new GF_Datagrid_Column({
			id: 'email',
			caption: '{/literal}{trans}TXT_EMAIL{/trans}{literal}',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_phone = new GF_Datagrid_Column({
			id: 'phone',
			caption: '{/literal}{trans}TXT_PHONE{/trans}{literal}'
		});
		
		var column_fax = new GF_Datagrid_Column({
			id: 'fax',
			caption: '{/literal}{trans}TXT_FAX{/trans}{literal}'
		});
		
		var column_address = new GF_Datagrid_Column({
			id: 'address',
			caption: '{/literal}{trans}TXT_ADDRESS{/trans}{literal}',
			appearance: {
				width: 240
			}
		});
		
		var column_street = new GF_Datagrid_Column({
			id: 'street',
			caption: '{/literal}{trans}TXT_STREET{/trans}{literal}',
			appearance: {
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetStreetSuggestions,
			}
		});
		
		var column_streetno = new GF_Datagrid_Column({
			id: 'streetno',
			caption: '{/literal}{trans}TXT_STREETNO{/trans}{literal}',
			appearance: {
				visible: false
			}
		});
		
		var column_placeno = new GF_Datagrid_Column({
			id: 'placeno',
			caption: '{/literal}{trans}TXT_PLACENO{/trans}{literal}',
			appearance: {
				visible: false
			}
		});
		
		var column_placename = new GF_Datagrid_Column({
			id: 'placename',
			caption: '{/literal}{trans}TXT_PLACENAME{/trans}{literal}',
			appearance: {
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetPlacenameSuggestions,
			}
		});
		
		var column_postcode = new GF_Datagrid_Column({
			id: 'postcode',
			caption: '{/literal}{trans}TXT_POSTCODE{/trans}{literal}',
			appearance: {
				visible: false
			}
		});

    var options = {
			id: 'contact',
			mechanics: {
				key: 'idcontact',
				rows_per_page: {/literal}{$globalsettings.interface.datagrid_rows_per_page}{literal}
			},
			event_handlers: {
				load: xajax_LoadAllContact,
				delete_row: deleteContact,
				edit_row: editContact,
				delete_group: deleteMultipleContact,
				{/literal}{if $globalsettings.interface.datagrid_click_row_action == 'edit'}{literal}
				click_row: editContact
				{/literal}{/if}{literal}
			},
			columns: [
				column_id,
				column_name,
				column_email,
				column_address,
				column_phone,
				column_fax,
				column_street,
				column_streetno,
				column_placeno,
				column_placename,
				column_postcode
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
    
    theDatagrid = new GF_Datagrid($('#list-contacts'), options);
    
   });
   
   /*]]>*/
   
   {/literal}
   
  </script>
