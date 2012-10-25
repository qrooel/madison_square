<?php /* Smarty version 2.6.19, created on 2012-10-08 10:09:30
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substr', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 12, false),array('modifier', 'strip_tags', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 211, false),array('modifier', 'truncate', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 211, false),array('function', 'css_namespace', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 18, false),array('function', 'math', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 116, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 201, false),array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productaddcartbox/index/index.tpl', 122, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
	<head>
		<!-- begin: Meta information -->
			<title><?php if ($this->_tpl_vars['metadata']['keyword_title'] != ''): ?><?php echo $this->_tpl_vars['metadata']['keyword_title']; ?>
 : <?php endif; ?><?php echo $this->_tpl_vars['SHOP_NAME']; ?>
</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta http-equiv="Author" content="Verison; http://verison.pl"/>
			<meta name="description" content="<?php echo $this->_tpl_vars['metadata']['keyword_description']; ?>
"/>
			<meta name="keywords" content="<?php echo $this->_tpl_vars['metadata']['keyword']; ?>
"/>
			<meta name="robots" content="all" />
			<meta name="revisit-after" content="1 Day" />
			<meta http-equiv="content-language" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['languageCode'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 2) : substr($_tmp, 0, 2)); ?>
"/>
			<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/logos/<?php echo $this->_tpl_vars['FAVICON']; ?>
"/>
			<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<!-- end: Meta information -->
		<!-- begin: Stylesheets -->
			<!--[if !(lt IE 7)]><!-->
			<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "gekosale.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
			<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
			<!--<![endif]-->
			<!--[if lt IE 7]>
				<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "ie6style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
			<![endif]-->
			<!--[if IE 7]>
			 	<link rel="stylesheet" href="<?php echo smarty_function_css_namespace(array('css_file' => "ie7style.css",'mode' => 'frontend'), $this);?>
" type="text/css"/>
			<![endif]-->
		<!-- end: Stylesheets -->
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_libs/gekosale.libs.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_frontend/core/gekosale.js"></script>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_frontend/core/init.js"></script>
		<!-- begin: JS libraries and scripts inclusion -->
		<!-- end: JS libraries and scripts inclusion -->
		<?php if ($this->_tpl_vars['gacode'] != ''): ?>
			<script type="text/javascript">
			<?php echo '
			    var _gaq = _gaq || [];
			    _gaq.push([\'_setAccount\', \''; ?>
<?php echo $this->_tpl_vars['gacode']; ?>
<?php echo '\']);
			    _gaq.push([\'_trackPageview\']);
			    _gaq.push([\'_trackPageLoadTime\']);
			'; ?>

	  		</script>
		<?php endif; ?>
		<!-- begin: JS variables binding -->
			<script type="text/javascript">
				<?php echo '
					/*<![CDATA[*/
						new GCore({
							iCookieLifetime: 30,
							sDesignPath: \''; ?>
<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo '\',
							sController: \''; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '\',
							sCartRedirect: \''; ?>
<?php echo $this->_tpl_vars['cartredirect']; ?>
<?php echo '\'
						});
					/*]]>*/
				'; ?>

			</script>
		<!-- end: JS variables binding -->
		<?php echo $this->_tpl_vars['xajax']; ?>

	</head>
	<?php flush() ?>
	<body style="background: #fff;">
	<div class="product-photos">
		<?php if (isset ( $this->_tpl_vars['product']['mainphoto']['normal']['path'] )): ?>
		<img class="mainphoto" style="margin-right: 10px;height: 300px;" src="<?php echo $this->_tpl_vars['product']['mainphoto']['normal']; ?>
" alt="Zdjęcie główne">
		<?php endif; ?>
	</div>
	
	
	<div class="product-details">
    	<?php if ($this->_tpl_vars['catalogmode'] == 0 && $this->_tpl_vars['product']['pricewithoutvat'] > 0): ?>
    	<form action="" method="">
	    	<input type="hidden" id="preventaddcart" value="0" />
			<input type="hidden" id="attributevariants" value="0" />
			<input type="hidden" id="availablestock" value="<?php echo $this->_tpl_vars['product']['stock']; ?>
" />
			<input type="hidden" id="variantprice" value="<?php echo $this->_tpl_vars['product']['price']; ?>
" />	
	        <?php if (( $this->_tpl_vars['attset'] != NULL )): ?>
        	<fieldset class="options">		
        		<?php $_from = $this->_tpl_vars['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grid'] => $this->_tpl_vars['attributesgroup']):
?>
        		<div class="field-select">
					<?php if (count ( $this->_tpl_vars['attributesgroup']['attributes'] ) == 1): ?>
						<label><strong><?php echo $this->_tpl_vars['attributesgroup']['name']; ?>
:</strong> 
						<?php $_from = $this->_tpl_vars['attributesgroup']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v'] => $this->_tpl_vars['variant']):
?>
							<?php echo $this->_tpl_vars['variant']; ?>

						<?php endforeach; endif; unset($_from); ?>
						</label>
					<?php else: ?>
						<label><?php echo $this->_tpl_vars['attributesgroup']['name']; ?>
</label>
					<?php endif; ?>
					<span class="field" <?php if (count ( $this->_tpl_vars['attributesgroup']['attributes'] ) == 1): ?>style="position: absolute; left: -10000px"<?php endif; ?>>
	        		<select id="<?php echo $this->_tpl_vars['grid']; ?>
" name="<?php echo $this->_tpl_vars['grid']; ?>
" class="attributes">
	        		<?php $_from = $this->_tpl_vars['attributesgroup']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v'] => $this->_tpl_vars['variant']):
?>
	        			<option value="<?php echo $this->_tpl_vars['v']; ?>
"><?php echo $this->_tpl_vars['variant']; ?>
</option>
	        		<?php endforeach; endif; unset($_from); ?>
	        		</select>
        			</span>
        		</div>
        		<?php endforeach; endif; unset($_from); ?>
        		<?php $_from = $this->_tpl_vars['product']['staticattributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group']):
?>
        		<div class="field-select">
					<label><strong><?php echo $this->_tpl_vars['group']['name']; ?>
:</strong> 
						<?php $_from = $this->_tpl_vars['group']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['attributes'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['attributes']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['v'] => $this->_tpl_vars['variant']):
        $this->_foreach['attributes']['iteration']++;
?>
							<?php if (($this->_foreach['attributes']['iteration'] == $this->_foreach['attributes']['total'])): ?>
								<?php echo $this->_tpl_vars['variant']['name']; ?>

								<?php else: ?>
								<?php echo $this->_tpl_vars['variant']['name']; ?>
,
								<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					</label>
        		</div>
        		<?php endforeach; endif; unset($_from); ?>
			</fieldset>
		<?php endif; ?>	
			
			<fieldset>
				<div>
					<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
                    <span class="price" style="color: #900;">Promocja <?php echo smarty_function_math(array('equation' => "(1 - (x / y)) * 100",'x' => $this->_tpl_vars['product']['discountprice'],'y' => $this->_tpl_vars['product']['price'],'format' => "%.2f"), $this);?>
%</span><br /><br />
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['showtax'] == 0): ?>
                    <span class="price">Cena netto: 
                    <strong id="changeprice-netto">
                    <?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['discountpricenetto']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php else: ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['pricewithoutvat']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
                    </strong>
                    <?php endif; ?>
                    
                    <?php if ($this->_tpl_vars['showtax'] == 1): ?>
					<span class="price">Cena:</span>
					<span class="price" id="changeprice">
					<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['discountprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php else: ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['price']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($this->_tpl_vars['showtax'] == 2): ?>
					<span class="price">Cena:</span>
					<span class="price" id="changeprice">
					<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['discountprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php else: ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['price']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
                    </span><br />
                    <span class="netto">Cena netto: 
                    <strong id="changeprice-netto">
                    <?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['discountpricenetto']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php else: ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['pricewithoutvat']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
                    </strong>
                    <?php endif; ?>
                </div>
               
                <div class="toCart" id="available">
					<?php if ($this->_tpl_vars['product']['unit'] == 2): ?>
					<input id="product-qty" type="hidden" value="1">
					<div class="field-text">
                    	<span>Szerokość</span>
                    	<span class="field">
                      		<label for="quantity">cm</label>
                           <input id="width" type="text" value="1">
                        </span>
                    </div>
                    <div class="field-text">
                    	<span>Wysokość</span>
                    	<span class="field">
                      		<label for="quantity">cm</label>
                            <input id="height" type="text" value="1">
                        </span>
                    </div>
                    <a href="#" id="add-cart" class="button redButton" style="margin-top: 15px;">Do koszyka</a>
					<?php else: ?>
					<div class="field-text">
                    	<span class="field">
                      		<label for="quantity">szt</label>
                            <input id="product-qty" type="text" value="1">
                        </span>
                    </div>
                    <a href="#" id="add-cart" class="button redButton">Do koszyka</a>
					<?php endif; ?>
                    
				</div>
				<div class="toCart" id="noavailable" style="display:none;height: 42px;">
                	<h3>Brak wybranego produktu w magazynie.</h3>
				</div>
			</fieldset>   
            </form>
            <?php else: ?>
            <fieldset class="cartOpt">
            	<legend>Koszyk</legend>
                <div class="cartOptionsContent">
                	<div class="cartOptions">
                    	<div class="toCart">
                    		<strong class="price" id="changeprice">Zapytaj o cenę</strong>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
/<?php echo $this->_tpl_vars['product']['idproduct']; ?>
" class="button greenButton">Wyślij zapytanie</a>
						</div>
					</div>
				</div>
			</fieldset>  
            <?php endif; ?>	
                  
               </div>
               
           <div class="description">
				<p><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['shortdescription'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 350) : smarty_modifier_truncate($_tmp, 350)); ?>
<br /><br /><a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['product']['seo']; ?>
">Zobacz więcej</a></p>
			</div>

<script type="text/javascript">
<?php echo '
$(\'.read-more\').click(function(){
	parent.location.href = $(this).attr(\'href\');
});
$(document).ready(function(){

	var liczbarat = 10;
	var oprocentowanie = 0.13;
	var rata = 0;
	var wartosc = parseFloat($(\'#changeprice\').text());
	rata = ((((wartosc + 12) * (1 + oprocentowanie))) * 1.01) / liczbarat;
	rata = Math.round(rata * 100) / 100;
	$(\'#eratyvalue\').text(rata);
	
	var producttrackstock = '; ?>
<?php echo $this->_tpl_vars['product']['trackstock']; ?>
<?php echo ';
	
	$(\'#add-cart\').unbind(\'click\').bind(\'click\', function(){
		if(producttrackstock == 1){
			if($(\'#availablestock\').val() > 0){
				return xajax_addProductToCart('; ?>
<?php echo $this->_tpl_vars['product']['idproduct']; ?>
<?php echo ', $(\'#attributevariants\').val(), $(\'#product-qty\').val(), $(\'#availablestock\').val() +\',\'+ $(\'#variantprice\').val(), '; ?>
<?php echo $this->_tpl_vars['product']['trackstock']; ?>
<?php echo ');
			}else{
				parent.GError(\''; ?>
Brak wybranego produktu w magazynie.<?php echo '\');
				return false;
			}
		}else{
			return xajax_addProductToCart('; ?>
<?php echo $this->_tpl_vars['product']['idproduct']; ?>
<?php echo ', $(\'#attributevariants\').val(), $(\'#product-qty\').val(), $(\'#availablestock\').val() +\',\'+ $(\'#variantprice\').val(), '; ?>
<?php echo $this->_tpl_vars['product']['trackstock']; ?>
<?php echo ');
		}
	});
	'; ?>

	<?php if (( $this->_tpl_vars['attset'] != NULL )): ?>
	<?php echo '
		GProductAttributes({
			aoVariants: '; ?>
<?php echo $this->_tpl_vars['variants']; ?>
<?php echo ',
			bTrackStock: producttrackstock
		});
	'; ?>

	<?php else: ?>
	<?php echo '
		if(producttrackstock == 1 && ($(\'#availablestock\').val() == 0)){
			$(\'#available\').hide();
			$(\'#noavailable\').show();
		}else{
			$(\'#available\').show();
			$(\'#noavailable\').hide();
		}
	'; ?>

	<?php endif; ?>
	<?php echo '
});
'; ?>

</script>
</body> 