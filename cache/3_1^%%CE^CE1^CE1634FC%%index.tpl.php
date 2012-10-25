<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:05
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productbox/index/index.tpl', 75, false),array('function', 'math', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productbox/index/index.tpl', 98, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productbox/index/index.tpl', 168, false),array('function', 'cycle', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productbox/index/index.tpl', 263, false),)), $this); ?>
<div class="product-photos">
<?php if (isset ( $this->_tpl_vars['product']['mainphoto']['normal']['path'] )): ?>
	<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
	<img class="promo" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-promo.png" alt="Promocja" title="Promocja"/>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['product']['new'] == 1): ?>
	<img class="promo" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-novelty.png" alt="Nowy" title="Nowy"/>
	<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['product']['photo'][0]['photoid'] ) && $this->_tpl_vars['product']['photo'][0]['photoid'] == 1): ?>
		<img class="mainphoto" src="<?php echo $this->_tpl_vars['product']['mainphoto']['normal']; ?>
" alt="<?php echo $this->_tpl_vars['product']['productname']; ?>
">
	<?php else: ?>
		<a rel="product" href="<?php echo $this->_tpl_vars['product']['mainphoto']['orginal']; ?>
" class="mainphotoContainer">
			<img class="mainphoto" src="<?php echo $this->_tpl_vars['product']['mainphoto']['normal']; ?>
" alt="<?php echo $this->_tpl_vars['product']['productname']; ?>
">
		</a>
	<?php endif; ?>
<?php endif; ?>
	<div class="productThumbs"> 
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product']['otherphoto']['small']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
		<a rel="product" href="<?php echo $this->_tpl_vars['product']['otherphoto']['orginal'][$this->_sections['i']['index']]; ?>
"><img style="width:47px;" src="<?php echo $this->_tpl_vars['product']['otherphoto']['small'][$this->_sections['i']['index']]; ?>
" alt="Dodatkowe zdjęcie"></a> 
	<?php endfor; endif; ?>
    </div> 
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
        		<legend><span>Dodatkowe opcje</span></legend>
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
			</fieldset>
		<?php endif; ?>	
		<?php if (count ( $this->_tpl_vars['product']['staticattributes'] ) > 0): ?>
		<fieldset>
			<?php $_from = $this->_tpl_vars['product']['staticattributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group']):
?>
			<div>
			<strong><?php echo $this->_tpl_vars['group']['name']; ?>
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
       		</div>
        	<?php endforeach; endif; unset($_from); ?>
		</fieldset>	
		<?php endif; ?>
			<fieldset>
				<div>
					<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
                    <span class="price">Poprzednia cena: <?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['price']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span><br /><br />
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
					<span class="price" id="changeprice" <?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>style="color: #900;"<?php endif; ?>>
					<?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['discountprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php else: ?>
						<?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['price']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
                    </span>
                    <?php if ($this->_tpl_vars['product']['discountprice'] != NULL): ?>
                    <span class="price" style="color: #900;">(-<?php echo smarty_function_math(array('equation' => "(1 - (x / y)) * 100",'x' => $this->_tpl_vars['product']['discountprice'],'y' => $this->_tpl_vars['product']['price'],'format' => "%.2f"), $this);?>
%)</span><br /><br />
                    <?php endif; ?>
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
                    	
						
					<?php if (isset ( $this->_tpl_vars['eraty']['wariantsklepu'] ) && ( $this->_tpl_vars['eraty']['wariantsklepu'] ) > 0 && isset ( $this->_tpl_vars['eraty']['numersklepu'] ) && $this->_tpl_vars['eraty']['numersklepu'] > 0 && $this->_tpl_vars['product']['price'] > 100): ?>
		 				<div class="creditOptions">
                    		<span>Raty od <strong id="eratyvalue"></strong> miesięcznie</span>
                    		<a id="eraty" href="#" class="button greenButton">Oblicz ratę</a>
                   		</div>
					<?php endif; ?>
                  
            </form>
            <?php else: ?>
            <fieldset>
                <div class="toCart" id="available">
                    <a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
/<?php echo $this->_tpl_vars['product']['idproduct']; ?>
" class="button greenButton">Zapytaj o cenę</a>
				</div>
			</fieldset>
			
            <?php endif; ?>	
                   	
                   <div class="addons">
                   	   <?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
                   	   <ul> 
                           <li id="shippingCost">Koszt wysyłki: <strong>od <?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['deliverymin']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></li> 
                           <?php if ($this->_tpl_vars['product']['trackstock'] == 1): ?><li id="availbility">Stan magazynowy: <span id="stockavailablity" style="font-weight:bold"><?php echo $this->_tpl_vars['product']['stock']; ?>
</span> szt</li><?php endif; ?>
                       </ul> 
                       <?php endif; ?>
                       <ul>
                       <?php if (isset ( $this->_tpl_vars['clientdata'] )): ?> 
	                       <?php if (( $this->_tpl_vars['attset'] != NULL )): ?>
	                        	<li id="toClip"><a href="#" onclick="xajax_addProductToWishList(<?php echo $this->_tpl_vars['product']['idproduct']; ?>
, $('#attributevariants').val()); return true;">Dodaj do listy życzeń</a></li>
	                       <?php else: ?>
	                        	<li id="toClip"><a href="#" onclick="xajax_addProductToWishList(<?php echo $this->_tpl_vars['product']['idproduct']; ?>
, null); return true;">Dodaj do listy życzeń</a></li>
	                       <?php endif; ?>
					   <?php endif; ?>
                           <li id="print"><a href="<?php echo $this->_tpl_vars['URL']; ?>
productprintbox/<?php echo $this->_tpl_vars['product']['idproduct']; ?>
">Drukuj stronę produktu</a></li>
                       </ul>
                       <?php if ($this->_tpl_vars['product']['producername'] <> ''): ?>
                       <ul> 
                           <li id="showAll">Producent: <a href="<?php if ($this->_tpl_vars['product']['producerurl'] != ''): ?><?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'producerlist'), $this);?>
/<?php echo $this->_tpl_vars['product']['producerurl']; ?>
<?php else: ?>#<?php endif; ?>" class="producer-logo" title="<?php echo $this->_tpl_vars['product']['producername']; ?>
"><?php echo $this->_tpl_vars['product']['producername']; ?>
</a></li> 
                       </ul> 
                       <?php endif; ?>
                   </div>       
               </div>
    
<div id="productTabs" class="layout-box withTabs" <?php if ($this->_tpl_vars['tabbed'] == 0): ?>style="border: none;"<?php endif; ?>> 
	<?php if ($this->_tpl_vars['tabbed'] == 1): ?>      
	<ul class="tabs"> 
		<?php if ($this->_tpl_vars['product']['description'] != ''): ?> 
    	<li><a href="#product-description"><h3>Opis</h3></a></li>
    	<?php endif; ?>
   		<?php if ($this->_tpl_vars['product']['longdescription'] != ''): ?> 
    	<li><a href="#product-longdescription"><h3>Dodatkowe informacje</h3></a></li>
    	<?php endif; ?> 
    	<?php if (count ( $this->_tpl_vars['technicalData'] ) > 0): ?>
    	<li><a href="#product-technical-data"><h3>Dane techniczne</h3></a></li> 
    	<?php endif; ?>
    	<?php if (count ( $this->_tpl_vars['files'] ) > 0): ?>
    	<li><a href="#product-files"><h3>Pliki</h3></a></li> 
    	<?php endif; ?>
    	<?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
    	<li><a href="#product-delivery"><h3>Koszt dostawy</h3></a></li>
    	<?php endif; ?>
    	<?php if ($this->_tpl_vars['enableopinions'] > 0): ?> 
    	<li><a href="#product-opinions"><h3>Opinie</h3></a></li>
    	<?php endif; ?> 
    	<?php if ($this->_tpl_vars['enabletags'] > 0): ?> 
    	<li><a href="#product-tags"><h3>Tagi</h3></a></li>
    	<?php endif; ?> 
    	<?php if (isset ( $this->_tpl_vars['tierpricing'] ) && count ( $this->_tpl_vars['tierpricing'] ) > 0): ?> 
    	<li><a href="#tier-pricing"><h3>Dodatkowe rabaty</h3></a></li>
    	<?php endif; ?> 
	</ul> 
	<?php endif; ?>
	<div class="boxContent"> 
		<?php if ($this->_tpl_vars['product']['description'] != ''): ?> 
		<div id="product-description" class="tabContent"> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			<fieldset class="listing">
	        	<legend><span>Opis</span></legend>
			<?php endif; ?>
        	<?php echo $this->_tpl_vars['product']['description']; ?>

        	<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			</fieldset>
			<?php endif; ?>
        </div> 
        <?php endif; ?>
        <?php if ($this->_tpl_vars['product']['longdescription'] != ''): ?> 
        <div id="product-longdescription" class="tabContent"> 
        	<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			<fieldset class="listing">
	        	<legend><span>Dodatkowe informacje</span></legend>
			<?php endif; ?>
        	<?php echo $this->_tpl_vars['product']['longdescription']; ?>

        	<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			</fieldset>
			<?php endif; ?>
            
        </div> 
        <?php endif; ?>
        <?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
		<div id="product-delivery" class="tabContent"> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			<fieldset class="listing">
	        	<legend><span>Koszt dostawy</span></legend>
			<?php endif; ?>
				<dl> 
				<?php unset($this->_sections['d']);
$this->_sections['d']['name'] = 'd';
$this->_sections['d']['loop'] = is_array($_loop=$this->_tpl_vars['delivery']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['d']['show'] = true;
$this->_sections['d']['max'] = $this->_sections['d']['loop'];
$this->_sections['d']['step'] = 1;
$this->_sections['d']['start'] = $this->_sections['d']['step'] > 0 ? 0 : $this->_sections['d']['loop']-1;
if ($this->_sections['d']['show']) {
    $this->_sections['d']['total'] = $this->_sections['d']['loop'];
    if ($this->_sections['d']['total'] == 0)
        $this->_sections['d']['show'] = false;
} else
    $this->_sections['d']['total'] = 0;
if ($this->_sections['d']['show']):

            for ($this->_sections['d']['index'] = $this->_sections['d']['start'], $this->_sections['d']['iteration'] = 1;
                 $this->_sections['d']['iteration'] <= $this->_sections['d']['total'];
                 $this->_sections['d']['index'] += $this->_sections['d']['step'], $this->_sections['d']['iteration']++):
$this->_sections['d']['rownum'] = $this->_sections['d']['iteration'];
$this->_sections['d']['index_prev'] = $this->_sections['d']['index'] - $this->_sections['d']['step'];
$this->_sections['d']['index_next'] = $this->_sections['d']['index'] + $this->_sections['d']['step'];
$this->_sections['d']['first']      = ($this->_sections['d']['iteration'] == 1);
$this->_sections['d']['last']       = ($this->_sections['d']['iteration'] == $this->_sections['d']['total']);
?>	
					<dt><?php echo $this->_tpl_vars['delivery'][$this->_sections['d']['index']]['name']; ?>
</dt>
					<dd class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
"><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['delivery'][$this->_sections['d']['index']]['dispatchmethodcost']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></dd>
				<?php endfor; endif; ?>	
				</dl> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
				</fieldset>
			<?php endif; ?>
        	
        </div> 
        <?php endif; ?> 
        <?php if (isset ( $this->_tpl_vars['tierpricing'] ) && count ( $this->_tpl_vars['tierpricing'] ) > 0): ?> 
		<div id="tier-pricing" class="tabContent"> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			<fieldset class="listing">
	        	<legend><span>Dodatkowe rabaty</span></legend>
			<?php endif; ?>
				<dl> 
				<?php unset($this->_sections['t']);
$this->_sections['t']['name'] = 't';
$this->_sections['t']['loop'] = is_array($_loop=$this->_tpl_vars['tierpricing']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['t']['show'] = true;
$this->_sections['t']['max'] = $this->_sections['t']['loop'];
$this->_sections['t']['step'] = 1;
$this->_sections['t']['start'] = $this->_sections['t']['step'] > 0 ? 0 : $this->_sections['t']['loop']-1;
if ($this->_sections['t']['show']) {
    $this->_sections['t']['total'] = $this->_sections['t']['loop'];
    if ($this->_sections['t']['total'] == 0)
        $this->_sections['t']['show'] = false;
} else
    $this->_sections['t']['total'] = 0;
if ($this->_sections['t']['show']):

            for ($this->_sections['t']['index'] = $this->_sections['t']['start'], $this->_sections['t']['iteration'] = 1;
                 $this->_sections['t']['iteration'] <= $this->_sections['t']['total'];
                 $this->_sections['t']['index'] += $this->_sections['t']['step'], $this->_sections['t']['iteration']++):
$this->_sections['t']['rownum'] = $this->_sections['t']['iteration'];
$this->_sections['t']['index_prev'] = $this->_sections['t']['index'] - $this->_sections['t']['step'];
$this->_sections['t']['index_next'] = $this->_sections['t']['index'] + $this->_sections['t']['step'];
$this->_sections['t']['first']      = ($this->_sections['t']['iteration'] == 1);
$this->_sections['t']['last']       = ($this->_sections['t']['iteration'] == $this->_sections['t']['total']);
?>	
					<?php if ($this->_sections['t']['first']): ?>
						<?php if ($this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['min'] == 0): ?>
						<dt>do <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['max']; ?>
 szt.</dt>
						<?php else: ?>
						<dt>od <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['min']; ?>
 do <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['max']; ?>
 szt.</dt>
						<?php endif; ?>
					<?php elseif ($this->_sections['t']['last']): ?>
						<?php if ($this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['max'] == 0): ?>
							<dt>od <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['min']; ?>
 szt.</dt>
						<?php else: ?>
							<dt>od <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['min']; ?>
 do <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['max']; ?>
 szt.</dt>
						<?php endif; ?>
					<?php else: ?>
						<dt>od <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['min']; ?>
 do <?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['max']; ?>
 szt.</dt>
					<?php endif; ?>
					<dd class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
"><?php echo $this->_tpl_vars['tierpricing'][$this->_sections['t']['index']]['discount']; ?>
% rabatu</dd>
				<?php endfor; endif; ?>	
				</dl> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
				</fieldset>
			<?php endif; ?>
        	
        </div> 
        <?php endif; ?> 
         <?php if (count ( $this->_tpl_vars['files'] ) > 0): ?>
        <div id="product-files" class="tabContent"> 
        	<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
			<fieldset class="listing">
	        	<legend><span>Pliki</span></legend>
			<?php endif; ?>
        	<dl> 
			<?php unset($this->_sections['f']);
$this->_sections['f']['name'] = 'f';
$this->_sections['f']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['f']['show'] = true;
$this->_sections['f']['max'] = $this->_sections['f']['loop'];
$this->_sections['f']['step'] = 1;
$this->_sections['f']['start'] = $this->_sections['f']['step'] > 0 ? 0 : $this->_sections['f']['loop']-1;
if ($this->_sections['f']['show']) {
    $this->_sections['f']['total'] = $this->_sections['f']['loop'];
    if ($this->_sections['f']['total'] == 0)
        $this->_sections['f']['show'] = false;
} else
    $this->_sections['f']['total'] = 0;
if ($this->_sections['f']['show']):

            for ($this->_sections['f']['index'] = $this->_sections['f']['start'], $this->_sections['f']['iteration'] = 1;
                 $this->_sections['f']['iteration'] <= $this->_sections['f']['total'];
                 $this->_sections['f']['index'] += $this->_sections['f']['step'], $this->_sections['f']['iteration']++):
$this->_sections['f']['rownum'] = $this->_sections['f']['iteration'];
$this->_sections['f']['index_prev'] = $this->_sections['f']['index'] - $this->_sections['f']['step'];
$this->_sections['f']['index_next'] = $this->_sections['f']['index'] + $this->_sections['f']['step'];
$this->_sections['f']['first']      = ($this->_sections['f']['iteration'] == 1);
$this->_sections['f']['last']       = ($this->_sections['f']['iteration'] == $this->_sections['f']['total']);
?>	
				<dt><a href="<?php echo $this->_tpl_vars['URL']; ?>
redirect/view/<?php echo $this->_tpl_vars['files'][$this->_sections['f']['index']]['idfile']; ?>
"><?php echo $this->_tpl_vars['files'][$this->_sections['f']['index']]['name']; ?>
</a></dt>
				<dd class="<?php echo smarty_function_cycle(array('values' => "o,e"), $this);?>
">&nbsp;</dd>
			<?php endfor; endif; ?>	
			</dl> 
			<?php if ($this->_tpl_vars['tabbed'] == 0): ?>
				</fieldset>
			<?php endif; ?>
        </div>  
        <?php endif; ?>
        <?php if (count ( $this->_tpl_vars['technicalData'] ) > 0): ?>
        <div id="product-technical-data" class="tabContent"> 
        	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['technicalData']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
            <fieldset class="listing">
            	<legend><span><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['name']; ?>
</span></legend>
                <dl> 
				<?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>	
					<dt><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['name']; ?>
</dt>
					<?php if ($this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['type'] == 4): ?>
						<?php if ($this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['value'] == 1): ?>
						<dd>Tak</dd>
					 	<?php else: ?>
						<dd>Nie</dd>
					 	<?php endif; ?>
					<?php else: ?>
						<dd><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['value']; ?>
</dd>
					<?php endif; ?>
				<?php endfor; endif; ?>	
				</dl> 
			</fieldset>
            <?php endfor; endif; ?>
		</div> 
        <?php endif; ?>
        <?php if ($this->_tpl_vars['enableopinions'] > 0): ?> 
        <div id="product-opinions" class="tabContent"> 
        	<?php if (count ( $this->_tpl_vars['range'] )): ?>
        	<fieldset class="listing">
				<legend><span>Średnia ocena</span></legend>
				<dl>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['range']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
					<dt><?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['name']; ?>
</dt>
					<?php if ($this->_tpl_vars['range'][$this->_sections['i']['index']]['mean'] > 0): ?>
						<dd><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-<?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['mean']; ?>
.png" alt="Ocena Klientów: <?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['mean']; ?>
"/></dd>
					<?php else: ?>
						<dd>&nbsp;<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></dd>
					<?php endif; ?>
				<?php endfor; endif; ?>	
				</dl>
			</fieldset>
			<?php endif; ?>
			<?php if (count ( $this->_tpl_vars['productreview'] ) > 0): ?>
				<?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['productreview']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
				<fieldset class="listing">
					<legend><span><?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['firstname']; ?>
 (<?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['adddate']; ?>
)</span></legend>
					<p style="padding:10px;"><?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['review']; ?>
</p>
					<dl>
					<?php unset($this->_sections['g']);
$this->_sections['g']['name'] = 'g';
$this->_sections['g']['loop'] = is_array($_loop=$this->_tpl_vars['productreview'][$this->_sections['r']['index']]['ranges']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['g']['show'] = true;
$this->_sections['g']['max'] = $this->_sections['g']['loop'];
$this->_sections['g']['step'] = 1;
$this->_sections['g']['start'] = $this->_sections['g']['step'] > 0 ? 0 : $this->_sections['g']['loop']-1;
if ($this->_sections['g']['show']) {
    $this->_sections['g']['total'] = $this->_sections['g']['loop'];
    if ($this->_sections['g']['total'] == 0)
        $this->_sections['g']['show'] = false;
} else
    $this->_sections['g']['total'] = 0;
if ($this->_sections['g']['show']):

            for ($this->_sections['g']['index'] = $this->_sections['g']['start'], $this->_sections['g']['iteration'] = 1;
                 $this->_sections['g']['iteration'] <= $this->_sections['g']['total'];
                 $this->_sections['g']['index'] += $this->_sections['g']['step'], $this->_sections['g']['iteration']++):
$this->_sections['g']['rownum'] = $this->_sections['g']['iteration'];
$this->_sections['g']['index_prev'] = $this->_sections['g']['index'] - $this->_sections['g']['step'];
$this->_sections['g']['index_next'] = $this->_sections['g']['index'] + $this->_sections['g']['step'];
$this->_sections['g']['first']      = ($this->_sections['g']['iteration'] == 1);
$this->_sections['g']['last']       = ($this->_sections['g']['iteration'] == $this->_sections['g']['total']);
?>
						<dt><?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['ranges'][$this->_sections['g']['index']]['name']; ?>
</dt>
						<dd><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-<?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['ranges'][$this->_sections['g']['index']]['value']; ?>
.png" alt="Ocena Klienta: <?php echo $this->_tpl_vars['productreview'][$this->_sections['r']['index']]['ranges'][$this->_sections['g']['index']]['value']; ?>
"/></dd>
					<?php endfor; endif; ?>	
					</dl>
				</fieldset>
				<?php endfor; endif; ?>
			<?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?> 
			<fieldset class="listing">
				<legend><span>Dodaj swoją opinię</span></legend>
				<form id="review" method="post" action="#">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['range']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
					<div class="field-select"> 
						<label><?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['name']; ?>
</label> 
					    <span class="field"> 
						    <select name="<?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['id']; ?>
" id="range-<?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['id']; ?>
"> 
							    <option value="0">Wybierz ocenę</option> 
							    <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['range'][$this->_sections['i']['index']]['values']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
							    <option value="<?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['values'][$this->_sections['j']['index']]; ?>
"><?php echo $this->_tpl_vars['range'][$this->_sections['i']['index']]['values'][$this->_sections['j']['index']]; ?>
</option> 
								<?php endfor; endif; ?>
						    </select> 
					    </span> 
					</div> 
				<?php endfor; endif; ?>
					<div class="field-textarea"> 
       					<span class="field"><textarea id="htmlopinion" name="htmlopinion" rows="10" cols="60"></textarea></span>      
    				 </div>  
					<div class="field-buttons" style="height: 30px;">
						<a id="add-review" class="button" href="#"><span>Wyślij</span></a>
					</div>
				</form>
			</fieldset>
			<?php else: ?>
				<p>Aby dodać opinię musisz być zalogowany. <a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientlogin'), $this);?>
">Zaloguj się</a></p>
			<?php endif; ?>
		</div> 
        <?php endif; ?>
        <?php if ($this->_tpl_vars['enabletags'] > 0): ?> 
        <div id="product-tags" class="tabContent"> 
	    	<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?> 
	        <fieldset class="listing">
	        	<legend><span>Dodaj Tag</span></legend>
	            <form id="tags">
			        <div class="field-text">
			        	<span class="field">
			            	<input type="text" id="htmltag" size="20" maxlength="20" value="" />
			            </span>
			        </div>
			        <div class="field-buttons">
						<span class="button"><span><input type="submit" name="save" value="Dodaj" onClick="xajax_addProductTags(<?php echo $this->_tpl_vars['product']['idproduct']; ?>
, $('#htmltag').val());return false; "/></span></span>
					</div>
				</form>
			</fieldset>
			<?php endif; ?>
			<div id="tags-cloud">
				<?php echo $this->_tpl_vars['tags']; ?>

			</div>
		</div>
		<?php endif; ?>
		
       
	</div> 
</div>
<script type="text/javascript">
<?php echo '
$(\'#add-review\').unbind(\'click\').bind(\'click\',function(){
	var params = {};
	var form = $(\'form#review\').serializeArray();
	$.each(form, function(index,value) {
		params[value.name] = value.value;
	});
	return xajax_addOpinion('; ?>
<?php echo $this->_tpl_vars['product']['idproduct']; ?>
<?php echo ', params);
});

$(\'#eraty\').click(function(){
	
	$.fancybox({
		\'overlayShow\'	:	true,
		\'autoScale\' 	:	true,
		\'type\'			: \'iframe\',
		\'width\'			: 630,
		\'height\'			: 500,
		\'transitionIn\'	:	\'elastic\',
		\'transitionOut\'	:	\'elastic\',
		\'speedIn\'		:	600, 
		\'speedOut\'		:	200, 
		\'href\' : \'https://www.eraty.pl/symulator/oblicz.php?numerSklepu='; ?>
<?php echo $this->_tpl_vars['eraty']['numersklepu']; ?>
&wariantSklepu=<?php echo $this->_tpl_vars['eraty']['wariantsklepu']; ?>
<?php echo '&typProduktu=0&wartoscTowarow=\' + parseFloat($(\'#changeprice\').text())
	});
});

$(document).ready(function(){

	$(".show-form").fancybox({
		\'overlayShow\'	:	true,
		\'width\'			:   280,
		\'height\'	    :   100,
		\'speedIn\'		:	600, 
		\'speedOut\'		:	200, 
		\'scrolling\'		: 	\'no\',
	});
	
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

	$(\'#width, #height\').change(function(){
		var measure = ($(\'#width\').val() * $(\'#height\').val()) / 10000;
		$(\'#product-qty\').val(measure);
	});
	
	$(\'#add-cart\').unbind(\'click\').bind(\'click\', function(){
		if(producttrackstock == 1){
			if($(\'#availablestock\').val() > 0){
				return xajax_addProductToCart('; ?>
<?php echo $this->_tpl_vars['product']['idproduct']; ?>
<?php echo ', $(\'#attributevariants\').val(), $(\'#product-qty\').val(), $(\'#availablestock\').val() +\',\'+ $(\'#variantprice\').val(), '; ?>
<?php echo $this->_tpl_vars['product']['trackstock']; ?>
<?php echo ');
			}else{
				GError(\''; ?>
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