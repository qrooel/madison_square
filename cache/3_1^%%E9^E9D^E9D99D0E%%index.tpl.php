<?php /* Smarty version 2.6.19, created on 2012-10-08 10:09:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl', 30, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl', 32, false),array('function', 'seo_js', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl', 90, false),array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl', 94, false),array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/index.tpl', 40, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_libs/jquery.scrollTo.min.js"></script>
<?php if (isset ( $this->_tpl_vars['paymenterror'] )): ?>
<script>
<?php echo '
GError(\''; ?>
<?php echo $this->_tpl_vars['paymenterror']; ?>
<?php echo '\',\'\');
'; ?>

</script>
<?php endif; ?> 
<?php if (isset ( $this->_tpl_vars['dispatcherror'] )): ?>
<script>
<?php echo '
GError(\''; ?>
<?php echo $this->_tpl_vars['dispatcherror']; ?>
<?php echo '\',\'\');
'; ?>

</script>
<?php endif; ?> 
<?php if (( $this->_tpl_vars['count'] ) > 0): ?>
<table class="cart" cellspacing="0">
	<thead>
		<tr>
			<th class="name">Nazwa produktu</th>
			<th class="price">Cena</th>
			<th class="quantity">Ilość</th>
			<th class="subtotal">Wartość</th>
			<th class="delete">Usuń</th>
		</tr>
	</thead>
	<tbody>
	<?php $_from = $this->_tpl_vars['productCart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['product']):
?>	
		<?php if (( $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['standard'] == 1 )): ?>
		<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">	
			<th scope="row" class="name">
				<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['seo']; ?>
">
					<span class="picture">
						<img src="<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['smallphoto']; ?>
" alt="Zdjęcie główne"/>
					</span>
					<strong><?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['name']; ?>
</strong>
				</a>
			</th>
			<th class="price">
				<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['newprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			</th>
			<th class="quantity">
				<img style="cursor: pointer" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
/_images_frontend/core/buttons/minus-2.png" alt="(Minus)" onClick="xajax_checkQuantityDec(<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
, null); return false;"/>
				<input class="product-quantity" type="text" title="<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['qty']; ?>
" name="quantity[<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
]" value="<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['qty']; ?>
" onchange="xajax_changeQuantity(<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
,null,this.value);"/>
				<img style="cursor: pointer" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
/_images_frontend/core/buttons/plus-2.png" alt="(Minus)" onClick="xajax_checkQuantityInc(<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
, null); return false;"/>
			</th>
			<td class="subtotal">
				<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['qtyprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			</td>	
			<td class="delete">
				<input type="submit" name="delete[<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
]" value="Usuń" onClick="xajax_deleteProductFromCart(<?php echo $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['idproduct']; ?>
, null); return false;" />
			</td>
		</tr>
		<?php endif; ?>
		<?php if (( $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['attributes'] <> NULL )): ?>
		<?php $_from = $this->_tpl_vars['productCart'][$this->_tpl_vars['key']]['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['attribprod']):
?>
		<tr class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">
			<th scope="row" class="name">
				<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['attribprod']['seo']; ?>
">
					<span class="picture">
						<img src="<?php echo $this->_tpl_vars['attribprod']['smallphoto']; ?>
" alt="Zdjęcie główne"/>
					</span>
					<strong><?php echo $this->_tpl_vars['attribprod']['name']; ?>
</strong>
				</a>
				<?php $_from = $this->_tpl_vars['attribprod']['features']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['feature']):
?>
					<?php echo $this->_tpl_vars['feature']['group']; ?>
: <?php echo $this->_tpl_vars['feature']['attributename']; ?>
<br />
				<?php endforeach; endif; unset($_from); ?>
		</th>
		<th class="price">
			<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['attribprod']['newprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		</th>
		<th class="quantity">
			<img style="cursor: pointer" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
/_images_frontend/core/buttons/minus-2.png" alt="(Minus)"  onClick="xajax_checkQuantityDec(<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
,<?php echo $this->_tpl_vars['attribprod']['attr']; ?>
); return false;"/>
			<input class="product-quantity" type="text" title="<?php echo $this->_tpl_vars['attribprod']['qty']; ?>
" name="quantity[<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
]" value="<?php echo $this->_tpl_vars['attribprod']['qty']; ?>
" onchange="xajax_changeQuantity(<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
,<?php echo $this->_tpl_vars['attribprod']['attr']; ?>
,this.value);" />
			<img style="cursor: pointer" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
/_images_frontend/core/buttons/plus-2.png" alt="(Minus)" onClick="xajax_checkQuantityInc(<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
,<?php echo $this->_tpl_vars['attribprod']['attr']; ?>
); return false;"/>
		</th>
		<th class="subtotal">
			<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['attribprod']['qtyprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		</th>
		<th class="delete">
			<input type="submit" name="delete[<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
]" value="Usuń" onClick="xajax_deleteProductFromCart(<?php echo $this->_tpl_vars['attribprod']['idproduct']; ?>
, <?php echo $this->_tpl_vars['attribprod']['attr']; ?>
); return false;"/>
		</th>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>	
	</tbody>
</table>
<?php if ($this->_tpl_vars['guestcheckout'] == 0): ?>
<p style="text-align: center;">Aby kontynuować proces zamawiania musisz być zalogowany.</p>
<p style="text-align: center;"><span class="<?php echo smarty_function_seo_js(array('controller' => 'clientlogin'), $this);?>
">Logowanie</span> lub <span class="<?php echo smarty_function_seo_js(array('controller' => 'registrationcart'), $this);?>
">Rejestracja</span></p>
<?php else: ?>

<?php if (isset ( $this->_tpl_vars['coupons'] )): ?>
	<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['coupons'],'render_mode' => 'JS'), $this);?>

<?php endif; ?> 	

<?php if ($this->_tpl_vars['minimumordervalue'] > 0): ?>
<fieldset class="listing">
	<legend><span>Minimalna wartość zamówienia</span></legend>
	<p>Aby zrealizować zamówienie musisz dodać do koszyka produkty o wartości <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['minimumordervalue']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
</fieldset>
<?php else: ?>
<?php if (( isset ( $this->_tpl_vars['checkRulesCart'] ) )): ?>

<?php $_from = $this->_tpl_vars['checkRulesCart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['rule']):
?>
<?php if ($this->_tpl_vars['checkRulesCart'][$this->_tpl_vars['key']]['type'] == 0): ?>
<fieldset class="listing">
	<legend><span>Spełnij poniższe warunki, a uzyskasz rabat <?php if (( isset ( $this->_tpl_vars['checkRulesCart'][$this->_tpl_vars['key']]['discount'] ) )): ?><?php echo $this->_tpl_vars['checkRulesCart'][$this->_tpl_vars['key']]['discount']; ?>
<?php endif; ?></span></legend>
	<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/gifts.png" style="float: left;margin-right: 10px;"/>
	<?php $_from = $this->_tpl_vars['checkRulesCart'][$this->_tpl_vars['key']]['conditions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['condition']):
?>
		<p><?php echo $this->_tpl_vars['checkRulesCart'][$this->_tpl_vars['key']]['conditions'][$this->_tpl_vars['k']]; ?>
</p>
	<?php endforeach; endif; unset($_from); ?>
</fieldset>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

<div class="layout-two-columns">
	<div class="column">
		<fieldset class="listing" id="delivery">
			<legend><span>Wybierz sposób dostawy</span></legend>
			<dl>
				<?php $_from = $this->_tpl_vars['deliverymethods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['delivery']):
?>
					<dt>
					<label>
					<input type="radio" name="del" id="delivery-<?php echo $this->_tpl_vars['delivery']['dispatchmethodid']; ?>
" value="<?php echo $this->_tpl_vars['delivery']['dispatchmethodid']; ?>
" <?php if (( $this->_tpl_vars['delivery']['dispatchmethodid'] == $this->_tpl_vars['checkedDelivery']['dispatchmethodid'] )): ?>checked="checked"<?php endif; ?> onclick="xajax_setDispatchmethodChecked(<?php echo $this->_tpl_vars['delivery']['dispatchmethodid']; ?>
); return false;" />
					<?php echo $this->_tpl_vars['delivery']['name']; ?>
 <?php if ($this->_tpl_vars['delivery']['description'] <> ''): ?><a href="#delivery-help-<?php echo $this->_tpl_vars['delivery']['dispatchmethodid']; ?>
" class="show-tooltip" ><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/help-2.png" /></a><?php endif; ?>
					</label>
					</dt>
					<dd>
					<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['delivery']['dispatchmethodcost']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					</dd>
					<div style="display:none;">
						<div style="width:380px;" id="delivery-help-<?php echo $this->_tpl_vars['delivery']['dispatchmethodid']; ?>
"><?php if ($this->_tpl_vars['delivery']['photo'] != NULL): ?><img src="<?php echo $this->_tpl_vars['delivery']['photo']; ?>
" /><?php endif; ?><?php echo $this->_tpl_vars['delivery']['description']; ?>
</div>
					</div>
				<?php endforeach; endif; unset($_from); ?>
			</dl> 
		</fieldset>
	</div>
	<div class="column">
		<div id="payment">
		<?php echo $this->_tpl_vars['payment']; ?>

		</div>
	</div>
</div>
<div id="finalization">
<?php echo $this->_tpl_vars['finalization']; ?>

</div>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>
 
<?php if ($this->_tpl_vars['uploadSettings']['uploaderenabled'] == 1 && $this->_tpl_vars['uploadSettings']['uploadmaxfilesize'] > 0 && $this->_tpl_vars['uploadSettings']['uploadchunksize'] > 0 && ! empty ( $this->_tpl_vars['uploadSettings']['uploadextensions'] )): ?>
<fieldset class="listing">
	<legend><span>Dołącz pliki do zamówienia</span></legend>
	<div id="container" style="padding-top: 0px;">
		<div id="filelist">Jeżeli chcesz dodać pliki do zamówienia wybierz je z dysku a następnie naciśnij przycisk "Wgraj"</div>
		<br />
		<a id="pickfiles" href="#" class="button-red">Wybierz pliki</a>
		<a id="uploadfiles" href="#" class="button-red">Wgraj pliki</a>
	</div>
</fieldset>
<?php endif; ?>
<?php if (count ( $this->_tpl_vars['deliverymethods'] ) > 0): ?>
<div class="buttons" style="width: 100%">
	<a href="#" class="button" style="float: left; margin-left: 10px;" onclick="history.back();return false;"><span>Wróć do zakupów</span></a>
	<a href="#" class="submit-order" style="float: right" onclick="$('#order').submit();return false; ">Złóż zamówienie</a>
</div>
<?php else: ?>
	<p style="font-size: 18px;color: #990000;text-align: center;">TXT_NO_SHIPPING_FOR_THIS_COUNTRY</p>
<?php endif; ?>


<?php endif; ?>

<?php endif; ?>

<?php else: ?>
	<span style="text-align: center; margin : 10px;"> 
		<h4>Koszyk jest pusty</h4>
	</span>
<?php endif; ?>	

<script type="text/javascript">
<?php echo '
function restoreQty(){
	$(\'.product-quantity\').each(function(){
		$(this).val($(this).attr(\'title\'));
	});
}

$(document).ready(function() {
	
	$(".show-tooltip").fancybox({
		\'overlayShow\'	:	true,
		\'width\'			: 280,
		\'height\'			: 100,
		\'speedIn\'		:	600, 
		\'speedOut\'		:	200, 
		\'scrolling\'		: 	\'no\',
	});

	$(\'#billing_data__billing_address_columns__right_billing__country, #shipping_data__shipping_address_columns__right_shipping__country\').change(function(){
		if($(\'#shipping_data__copy\').val() == 1){
			return xajax_setAjaxShippingCountryId ($(this).val());
		}
	});

	$(\'form\').submit(function(){
		var errors = $(\'.invalid\').size();
		if(errors > 0){
			$.scrollTo($(\'#billing_data\'));
		}
	});
	
});
'; ?>

</script>
<?php if ($this->_tpl_vars['uploadSettings']['uploaderenabled'] == 1 && $this->_tpl_vars['uploadSettings']['uploadmaxfilesize'] > 0 && $this->_tpl_vars['uploadSettings']['uploadchunksize'] > 0 && ! empty ( $this->_tpl_vars['uploadSettings']['uploadextensions'] )): ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_libs/plupload/plupload.full.js"></script>
<script type="text/javascript">
<?php echo '
$(function() {
	var uploader = new plupload.Uploader({
		runtimes : \'flash\',
		browse_button : \'pickfiles\',
		container : \'container\',
		max_file_size : \''; ?>
<?php echo $this->_tpl_vars['uploadSettings']['uploadmaxfilesize']; ?>
<?php echo 'mb\',
		chunk_size : \''; ?>
<?php echo $this->_tpl_vars['uploadSettings']['uploadchunksize']; ?>
<?php echo 'kb\',
		unique_names: true,
		url : \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/add\',
		flash_swf_url : \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '_js_libs/plupload/plupload.flash.swf\',
		filters : [
			{title : "'; ?>
<?php echo $this->_tpl_vars['uploadSettings']['uploadextensions']; ?>
<?php echo '", extensions : "'; ?>
<?php echo $this->_tpl_vars['uploadSettings']['uploadextensions']; ?>
<?php echo '"},
		],
	});

	$(\'#uploadfiles\').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind(\'FilesAdded\', function(up, files) {
		$.each(files, function(i, file) {
			$(\'#filelist\').append(
				\'<div id="\' + file.id + \'">\' +
				file.name + \' (\' + plupload.formatSize(file.size) + \') <b></b>\' +
			\'</div>\');
		});
		up.refresh();
	});

	uploader.bind(\'UploadProgress\', function(up, file) {
		$(\'#\' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind(\'Error\', function(up, err) {
		$(\'#filelist\').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);
		up.refresh(); 
	});

	uploader.bind(\'FileUploaded\', function(up, file) {
		$(\'#\' + file.id + " b").html("100%");
	});
});
'; ?>

</script>
<?php endif; ?>