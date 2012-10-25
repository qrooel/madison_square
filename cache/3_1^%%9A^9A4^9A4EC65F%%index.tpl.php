<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productpromotionsbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pagination', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productpromotionsbox/index/index.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['products'] <> ''): ?>
<?php if ($this->_tpl_vars['enablerss'] == 1): ?><p class="rss" style="height: 20px;"><span style="text-align: right;margin-top: 10px;margin-right: 10px;float: right;margin-bottom: 10px;"><a href="<?php echo $this->_tpl_vars['URL']; ?>
feeds/productpromotion"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/rss.png" title="RSS - Promocje w Sklepie"></a></span></p><?php endif; ?>
	<ul class="product-list <?php if ($this->_tpl_vars['view'] == 0): ?>list-grid<?php else: ?>list-long<?php endif; ?>">
		<?php echo $this->_tpl_vars['products']; ?>

	</ul>
	<?php if ($this->_tpl_vars['pagination'] == 1): ?>
		<?php echo smarty_function_pagination(array('dataset' => $this->_tpl_vars['dataset'],'controller' => 'categorylist','id' => $this->_tpl_vars['currentCategory']['id'],'seo' => $this->_tpl_vars['currentCategory']['seo']), $this);?>

	<?php endif; ?>
<?php else: ?>
	<p>Brak produktów</p>
<?php endif; ?>