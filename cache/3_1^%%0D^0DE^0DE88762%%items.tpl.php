<?php /* Smarty version 2.6.19, created on 2012-10-09 06:20:34
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/searchresults/view/items.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/searchresults/view/items.tpl', 10, false),array('modifier', 'strip_tags', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/searchresults/view/items.tpl', 24, false),array('modifier', 'truncate', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/searchresults/view/items.tpl', 24, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['items'] ) > 0): ?>
<div style="z-index: 900000; width: 450px;right: 289px;" class="layout-box-type-product-list layout-box-width-class-2 layout-box"> 
<div class="layout-box-content" style="margin-top: 0px">
						
<ul class="list-long"> 
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
				<img class="promo" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/product-promo.png" alt="Promocja!" title="Promocja!"/>
				<img src="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['photo']; ?>
" alt="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
"/>
				</span>
				<span class="name"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
</span>
				<?php if ($this->_tpl_vars['showtax'] == 0): ?>
					<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountpricenetto']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</del></span>
				<?php else: ?>
					<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</del></span>
				<?php endif; ?>		
			</a>
		</h4>
		<div class="description">
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['items'][$this->_sections['i']['index']]['shortdescription'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 250) : smarty_modifier_truncate($_tmp, 250)); ?>

		</div>
	</li>
	<?php else: ?>
	<li>
		<h4>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
" title="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
">
				<span class="image"><img src="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['photo']; ?>
" alt="<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
"/></span>
				<span class="name"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['name']; ?>
</span>
				<?php if ($this->_tpl_vars['showtax'] == 0): ?>
					<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</span>
				<?php else: ?>
					<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</span>
				<?php endif; ?>		
			</a>
		</h4>
		<div class="description">
			<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['items'][$this->_sections['i']['index']]['shortdescription'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 250) : smarty_modifier_truncate($_tmp, 250)); ?>

		</div>
	</li>
	<?php endif; ?>
<?php endfor; endif; ?> 
</ul> 
							
<p class="see-more" style="margin-top: 5px;margin-right: 10px;float: right;"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch'), $this);?>
/<?php echo $this->_tpl_vars['phrase']; ?>
">Zobacz wszystkie</a></p> 
</div>
</div>
</div>
<?php else: ?>
<div class="layout-box" style="z-index: 900000; width: 450px;right: 305px;">
	<div class="layout-box-content">
		<p>Brak produktów o podanej frazie</p>
	</div>
<?php endif; ?>