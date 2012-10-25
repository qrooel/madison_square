<?php /* Smarty version 2.6.19, created on 2012-10-08 10:10:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/banktransfer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'price', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/banktransfer.tpl', 24, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/paymentbox/accept/banktransfer.tpl', 39, false),)), $this); ?>
<fieldset class="listing">
	<h2><span>Dane do przelewu bankowego:</span></h2>
	<?php if (isset ( $this->_tpl_vars['content'] ) && ! empty ( $this->_tpl_vars['content'] )): ?>
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
			<dt>Konto bankowe:</dt><dd><strong><?php echo $this->_tpl_vars['content']['banknr']; ?>
 - <?php echo $this->_tpl_vars['content']['bankname']; ?>
</strong></dd>
		</dl>
		<dl>
			<dt>Tytuł:</dt><dd><?php echo $this->_tpl_vars['orderData']['clientdata']['firstname']; ?>
 <?php echo $this->_tpl_vars['orderData']['clientdata']['surname']; ?>
, Numer zamówienia: <?php echo $this->_tpl_vars['orderId']; ?>
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
	<?php else: ?>
		<div>
			<p>Proszę skontaktować się z administratorem sklepu w celu uzyskania numeru konta bankowego.</p>
		</div>
	<?php endif; ?>
</fieldset>
								
<div class="buttons">
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'mainside'), $this);?>
/" class="button"><span>Wróć do zakupów</span></a>
</div>					