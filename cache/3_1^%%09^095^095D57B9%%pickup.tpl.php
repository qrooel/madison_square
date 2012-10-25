<?php /* Smarty version 2.6.19, created on 2012-10-09 09:37:50
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/pickup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/pickup.tpl', 26, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/pickup.tpl', 36, false),)), $this); ?>
<fieldset class="listing">
	<legend><span>Adres odbioru zamówienia:</span></legend>
		<dl>
			<dt>Nazwa firmy:</dt><dd><?php echo $this->_tpl_vars['content']['companyname']; ?>
</dd>
		</dl>
		<dl>
			<dt>Nazwa sklepu:</dt><dd><?php echo $this->_tpl_vars['content']['shopname']; ?>
</dd>
		</dl>
		<dl>
			<dt>Ulica:</dt><dd><?php echo $this->_tpl_vars['content']['street']; ?>
 <?php echo $this->_tpl_vars['content']['streetno']; ?>
 <?php if ($this->_tpl_vars['content']['placeno'] != ''): ?> / <?php echo $this->_tpl_vars['content']['placeno']; ?>
<?php endif; ?></dd>
		</dl>
		<dl>
			<dt>Miasto:</dt><dd><?php echo $this->_tpl_vars['content']['postcode']; ?>
 <?php echo $this->_tpl_vars['content']['placename']; ?>
</dd>
		</dl>
		<dl>
			<dt>Dane klienta:</dt><dd><?php echo $this->_tpl_vars['orderData']['deliveryAddress']['firstname']; ?>
 <?php echo $this->_tpl_vars['orderData']['deliveryAddress']['surname']; ?>
</dd>
		</dl>
		<dl>
			<dt>Ulica:</dt><dd><?php echo $this->_tpl_vars['orderData']['deliveryAddress']['street']; ?>
 <?php echo $this->_tpl_vars['orderData']['deliveryAddress']['streetno']; ?>
 <?php if ($this->_tpl_vars['orderData']['deliveryAddress']['placeno'] != ''): ?> / <?php echo $this->_tpl_vars['orderData']['deliveryAddress']['placeno']; ?>
<?php endif; ?></dd>
		</dl>
		<dl>
			<dt>Miasto:</dt><dd><?php echo $this->_tpl_vars['orderData']['deliveryAddress']['postcode']; ?>
 <?php echo $this->_tpl_vars['orderData']['deliveryAddress']['placename']; ?>
</dd>
		</dl>
		<?php if (isset ( $this->_tpl_vars['orderData']['priceWithDispatchMethodPromo'] ) && $this->_tpl_vars['orderData']['priceWithDispatchMethodPromo'] > 0): ?>
		<dl>
			<dt>Koszt zamówienia wraz z dostawą:</dt><dd><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['orderData']['priceWithDispatchMethodPromo']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></dd>
		</dl>
		<?php else: ?>
		<dl>
			<dt>Koszt zamówienia wraz z dostawą:</dt><dd><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['orderData']['priceWithDispatchMethod']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></dd>
		</dl>
		<?php endif; ?>
</fieldset>
								
<div class="buttons">
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'mainside'), $this);?>
/" class="button"><span>Wróć do zakupów</span></a>
</div>					