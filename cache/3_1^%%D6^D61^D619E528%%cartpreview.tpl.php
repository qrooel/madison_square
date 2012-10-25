<?php /* Smarty version 2.6.19, created on 2012-10-25 23:19:14
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/cartpreview.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/cartpreview.tpl', 1, false),array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/cartpreview.tpl', 3, false),)), $this); ?>
<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'cart'), $this);?>
/">
	 Ilość produktów: <strong><?php echo $this->_tpl_vars['count']; ?>
</strong><br />
	 Wartość: <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['globalPrice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong><br />
</a>