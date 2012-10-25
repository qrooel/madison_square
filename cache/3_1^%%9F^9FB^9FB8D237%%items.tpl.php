<?php /* Smarty version 2.6.19, created on 2012-10-25 23:19:14
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/items.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/items.tpl', 5, false),array('modifier', 'truncate', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/items.tpl', 11, false),)), $this); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice'] != NULL && $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice'] > 0): ?>
<li class="promo">
	<h4>
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
" title="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
">
			<span class="image">
			<img class="mainphoto" src="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['photo']; ?>
" alt="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
"/>
			<img class="promo" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-promo.png" alt="Promocja!" title="Promocja!"/>
			<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['new'] == 1): ?><img class="novelty" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-novelty.png" alt="Nowość!" title="Nowość!"><?php endif; ?>
			</span>
			<span class="name"><?php echo ((is_array($_tmp=$this->_tpl_vars['items'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 70) : smarty_modifier_truncate($_tmp, 70)); ?>
</span>
		</a>
		<?php if ($this->_tpl_vars['catalogmode'] == 0 && $this->_tpl_vars['items'][$this->_sections['i']['index']]['price'] > 0): ?>
			<?php if ($this->_tpl_vars['showtax'] == 0): ?>
				<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountpricenetto']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</del></span>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['showtax'] == 1): ?>
				<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</del></span>
			<?php endif; ?>	
			<?php if ($this->_tpl_vars['showtax'] == 2): ?>
				<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</del></span>
				<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountpricenetto']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</del> netto</span>
			<?php endif; ?>	
		<?php else: ?>
			<span class="price">Zapytaj o cenę</span>
		<?php endif; ?>	
	</h4>
	<?php if ($this->_tpl_vars['enableopinions'] == 1): ?>
		<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['opinions'] > 0): ?>
		<p class="rating">
		<?php if ($this->_tpl_vars['enablerating'] > 0): ?><span class="stars"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['rating']; ?>
.png" alt="Ocena Klientów: <?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['rating']; ?>
"/></span><?php endif; ?>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
#product-opinions">Przeczytaj <?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['opinions']; ?>
 opinii</a>
		</p>
		<?php else: ?>
		<p class="rating">
			<?php if ($this->_tpl_vars['enablerating'] > 0): ?><span class="stars"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span><?php endif; ?>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
#product-opinions">Napisz opinię jako pierwszy</a>
		</p>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['dateto'] != NULL): ?><p class="promotion-time-left">Promocja do: <strong><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['dateto']; ?>
</strong></p><?php endif; ?>
	<div class="description">
		<p><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['shortdescription']; ?>
<br /><br /><a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
">Zobacz więcej</a></p>
	</div>
	<?php if ($this->_tpl_vars['catalogmode'] == 0 && $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto'] > 0): ?>
	<p class="add-to-cart">
		<a rel="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productaddcartbox'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['id']; ?>
" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
" class="button-red">Do koszyka</a>
	</p>
	<?php else: ?>
	<p class="request-quote">
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['id']; ?>
" class="button-green">Wyślij zapytanie</a>
	</p>
	<?php endif; ?>
</li>
<?php else: ?>
<li>
	<h4>
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
" title="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
">
			<span class="image">
				<img class="mainphoto"  src="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['photo']; ?>
" alt="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
"/>
				<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['new'] == 1): ?><img class="novelty" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-novelty.png" alt="Nowość!" title="Nowość!"><?php endif; ?>
			</span>
			<span class="name"><?php echo ((is_array($_tmp=$this->_tpl_vars['items'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 70) : smarty_modifier_truncate($_tmp, 70)); ?>
</span>
		</a>
		<?php if ($this->_tpl_vars['catalogmode'] == 0 && $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto'] > 0): ?>
			<?php if ($this->_tpl_vars['showtax'] == 0): ?>
			<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</span>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['showtax'] == 1): ?>
				<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</span>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['showtax'] == 2): ?>
				<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</span> <span class="price netto"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
 netto</span>
			<?php endif; ?>	
		<?php else: ?>
			<span class="price">Zapytaj o cenę</span>
		<?php endif; ?>	
	</h4>
	<?php if ($this->_tpl_vars['enableopinions'] == 1): ?>
		<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['opinions'] > 0): ?>
		<p class="rating">
			<?php if ($this->_tpl_vars['enablerating'] > 0): ?><span class="stars"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['rating']; ?>
.png" alt="Ocena Klientów: <?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['rating']; ?>
"/></span><?php endif; ?>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
#product-opinions">Przeczytaj <?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['opinions']; ?>
 opinii</a>
		</p>
		<?php else: ?>
		<p class="rating">
			<?php if ($this->_tpl_vars['enablerating'] > 0): ?><span class="stars"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span><?php endif; ?>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
#product-opinions">Napisz opinię jako pierwszy</a>
		</p>
		<?php endif; ?>
	<?php endif; ?>
	<div class="description">
		<p><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['shortdescription']; ?>
<br /><br /><a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
">Zobacz więcej</a></p>
	</div>
	<?php if ($this->_tpl_vars['catalogmode'] == 0 && $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto'] > 0): ?>
	<p class="add-to-cart">
		<a rel="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productaddcartbox'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['id']; ?>
" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
" class="button-red">Do koszyka</a>
	</p>
	<?php else: ?>
	<p class="request-quote">
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['id']; ?>
" class="button-green">Wyślij zapytanie</a>
	</p>
	<?php endif; ?>
</li>
<?php endif; ?>
<?php endfor; endif; ?>