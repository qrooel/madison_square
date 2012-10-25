<?php /* Smarty version 2.6.19, created on 2012-10-09 06:29:25
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/contact/index/index.tpl */ ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/contact-list.png" alt=""/>Lista kontaktów</h2>

<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/add" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Dodaj kontakt</span></a></li>
</ul>

<div class="block">
	<div id="list-contacts"></div>
</div>

<script type="text/javascript">
   
   <?php echo '
   
   /*<![CDATA[*/

	 function editContact(dg, id) {
    location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
/edit/<?php echo '\' + id + \'\';
	 };

	 function deleteContact(dg, id) {
	 	var oRow = theDatagrid.GetRow(id);
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' <strong>\' + oRow.name +\'</strong> ?\';
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
		var title = \''; ?>
Usuń<?php echo '\';
		var msg = \''; ?>
Czy skasować rekord<?php echo ' \' + ids.join(\', \') + \'?\';
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
			id: \'idcontact\',
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
		
		var column_name = new GF_Datagrid_Column({
			id: \'name\',
			caption: \''; ?>
Nazwa<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetNameSuggestions,
			}
		});
		
		var column_email = new GF_Datagrid_Column({
			id: \'email\',
			caption: \''; ?>
E-mail<?php echo '\',
			filter: {
				type: GF_Datagrid.FILTER_INPUT,
			}
		});
		
		var column_phone = new GF_Datagrid_Column({
			id: \'phone\',
			caption: \''; ?>
Telefon<?php echo '\'
		});
		
		var column_fax = new GF_Datagrid_Column({
			id: \'fax\',
			caption: \''; ?>
Fax<?php echo '\'
		});
		
		var column_address = new GF_Datagrid_Column({
			id: \'address\',
			caption: \''; ?>
Adres<?php echo '\',
			appearance: {
				width: 240
			}
		});
		
		var column_street = new GF_Datagrid_Column({
			id: \'street\',
			caption: \''; ?>
Ulica<?php echo '\',
			appearance: {
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetStreetSuggestions,
			}
		});
		
		var column_streetno = new GF_Datagrid_Column({
			id: \'streetno\',
			caption: \''; ?>
Nr budynku<?php echo '\',
			appearance: {
				visible: false
			}
		});
		
		var column_placeno = new GF_Datagrid_Column({
			id: \'placeno\',
			caption: \''; ?>
Nr lokalu<?php echo '\',
			appearance: {
				visible: false
			}
		});
		
		var column_placename = new GF_Datagrid_Column({
			id: \'placename\',
			caption: \''; ?>
Miasto<?php echo '\',
			appearance: {
				visible: false
			},
			filter: {
				type: GF_Datagrid.FILTER_AUTOSUGGEST,
				source: xajax_GetPlacenameSuggestions,
			}
		});
		
		var column_postcode = new GF_Datagrid_Column({
			id: \'postcode\',
			caption: \''; ?>
Kod pocztowy<?php echo '\',
			appearance: {
				visible: false
			}
		});

    var options = {
			id: \'contact\',
			mechanics: {
				key: \'idcontact\',
				rows_per_page: '; ?>
<?php echo $this->_tpl_vars['globalsettings']['interface']['datagrid_rows_per_page']; ?>
<?php echo '
			},
			event_handlers: {
				load: xajax_LoadAllContact,
				delete_row: deleteContact,
				edit_row: editContact,
				delete_group: deleteMultipleContact,
				'; ?>
<?php if ($this->_tpl_vars['globalsettings']['interface']['datagrid_click_row_action'] == 'edit'): ?><?php echo '
				click_row: editContact
				'; ?>
<?php endif; ?><?php echo '
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
    
    theDatagrid = new GF_Datagrid($(\'#list-contacts\'), options);
    
   });
   
   /*]]>*/
   
   '; ?>

   
  </script>