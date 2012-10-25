<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:05
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/wishlistbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pagination', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/wishlistbox/index/index.tpl', 7, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?> 
		<?php if ($this->_tpl_vars['products'] <> ''): ?>
			<ul class="product-list list-long">
				<?php echo $this->_tpl_vars['products']; ?>

			</ul>
			<?php if ($this->_tpl_vars['pagination'] == 1): ?>
				<?php echo smarty_function_pagination(array('dataset' => $this->_tpl_vars['dataset'],'controller' => 'categorylist','id' => $this->_tpl_vars['currentCategory']['id'],'seo' => $this->_tpl_vars['currentCategory']['seo']), $this);?>

			<?php endif; ?>
			
		<?php else: ?> 
			<p>Twoja lista życzeń jest pusta.</p>
		<?php endif; ?>
<?php endif; ?>