<?php /* Smarty version 2.6.19, created on 2012-10-25 10:05:15
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/showcasebox/index/item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/showcasebox/index/item.tpl', 4, false),)), $this); ?>
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
			<?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
			<?php if ($this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice'] != NULL && $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice'] > 0): ?>
				<?php if ($this->_tpl_vars['showtax'] == 0): ?>
					<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountpricenetto']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</del></span>
				<?php else: ?>
					<span class="price"><ins><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['discountprice']; ?>
</ins> <del><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</del></span>
				<?php endif; ?>
			<?php else: ?>
				<?php if ($this->_tpl_vars['showtax'] == 0): ?>
					<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['pricenetto']; ?>
</span>
				<?php else: ?>
					<span class="price"><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['price']; ?>
</span>
				<?php endif; ?>	
			<?php endif; ?>
			<?php endif; ?>
		</a>
	</h4>
	<div class="description">
		<p><?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['shortdescription']; ?>
 <a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productcart'), $this);?>
/<?php echo $this->_tpl_vars['items'][$this->_sections['i']['index']]['seo']; ?>
">Zobacz więcej</a></p>
	</div>
</li>
<?php endfor; endif; ?>