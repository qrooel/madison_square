<?php /* Smarty version 2.6.19, created on 2012-10-08 10:09:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/payment.tpl */ ?>
<fieldset class="listing">
<legend><span>Wybierz sposób płatności</span></legend>
<?php if (isset ( $this->_tpl_vars['checkedDelivery']['dispatchmethodid'] ) && ( $this->_tpl_vars['checkedDelivery']['dispatchmethodid'] > 0 )): ?>
	<dl>
	<?php $_from = $this->_tpl_vars['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['payment']):
?>
		<dt>
			<label>
				<input type="radio" name="paymentsradio" id="payment-<?php echo $this->_tpl_vars['payment']['idpaymentmethod']; ?>
" value="<?php echo $this->_tpl_vars['payment']['name']; ?>
" <?php if (( $this->_tpl_vars['payment']['idpaymentmethod'] == $this->_tpl_vars['checkedPayment']['idpaymentmethod'] )): ?>checked="checked"<?php endif; ?> onclick="xajax_setPeymentChecked(<?php echo $this->_tpl_vars['payment']['idpaymentmethod']; ?>
, '<?php echo $this->_tpl_vars['payment']['name']; ?>
'); return false;" />
				<?php if (isset ( $this->_tpl_vars['payment']['wariantsklepu'] ) && ( $this->_tpl_vars['payment']['wariantsklepu'] ) > 0 && isset ( $this->_tpl_vars['payment']['numersklepu'] ) && $this->_tpl_vars['payment']['numersklepu'] > 0): ?>
				<?php echo $this->_tpl_vars['payment']['name']; ?>

				<a href="https://www.eraty.pl/symulator/oblicz.php?numerSklepu=<?php echo $this->_tpl_vars['payment']['numersklepu']; ?>
&wariantSklepu=<?php echo $this->_tpl_vars['payment']['wariantsklepu']; ?>
&typProduktu=0&wartoscTowarow=<?php echo $this->_tpl_vars['priceWithDispatchMethod']; ?>
" target='_blank' > Policz ratę </a>
				<?php else: ?>
				<?php echo $this->_tpl_vars['payment']['name']; ?>

				<?php endif; ?>
			</label>
		</dt>
		<dd>&nbsp;</dd>
	<?php endforeach; endif; unset($_from); ?>
	</dl>
<?php endif; ?>
</fieldset>