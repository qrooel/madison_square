<?php /* Smarty version 2.6.19, created on 2012-10-08 10:11:36
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/order/edit/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/order/edit/edit.tpl', 22, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/orders-edit.png" alt=""/>Edycja zamówienia <?php echo $this->_tpl_vars['order']['order_id']; ?>
 (<?php echo $this->_tpl_vars['order']['view']; ?>
) z dnia <?php echo $this->_tpl_vars['order']['order_date']; ?>
</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista zamówień" alt="Lista zamówień"/></span></a></li>
	<?php if ($this->_tpl_vars['order']['previous'] > 0): ?><li><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/edit/<?php echo $this->_tpl_vars['order']['previous']; ?>
" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-green.png" title="Poprzednie zamówienie" alt="Poprzednie zamówienie"/>Poprzednie zamówienie</span></a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['order']['next'] > 0): ?><li><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/edit/<?php echo $this->_tpl_vars['order']['next']; ?>
" class="button"><span><img class="right "src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-right-green.png" title="Następne zamówienie" alt="Następne zamówienie"/>Następne zamówienie</span></a></li><?php endif; ?>
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/confirm/<?php echo $this->_tpl_vars['order']['order_id']; ?>
" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/print.png" title="Następne zamówienie" alt="Następne zamówienie"/>Drukuj</span></a></li>
	<li><a href="#edit_order" id="save_order" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz</span></a></li>
</ul>
<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			GCore.OnLoad(function() {
				$(\'.view-order\').GTabs();
			});
		/*]]>*/
	'; ?>

</script>
<div class="view-order GForm">
	
	<fieldset>
		<legend><span>Ogólne</span></legend>
		<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

		
		<div class="layout-two-columns">
		 	
			<div class="column">
				<h3><span><strong>Zmień status</strong></span></h3>
				<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['statusChange'],'render_mode' => 'JS'), $this);?>

			</div>
		 	
			<div class="column">
				<h3><span><strong>Dodaj notkę</strong></span></h3>
				<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['addNotes'],'render_mode' => 'JS'), $this);?>

			</div>
			
		</div>
		
		
	</fieldset>
	

	
	<fieldset>
		<legend><span>Faktury</span></legend>
		
		<ul class="changes-detailed">
		<?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['order']['invoices']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?>
		<li>
			<h4><span><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['symbol']; ?>
 - <em><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['invoicedate']; ?>
</em> <a href="<?php echo $this->_tpl_vars['URL']; ?>
invoice/view/<?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['idinvoice']; ?>
,0">ORYGINAŁ</a> | <a href="<?php echo $this->_tpl_vars['URL']; ?>
invoice/view/<?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['idinvoice']; ?>
,1">KOPIA</a></span></h4>
			<?php if ($this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['comment'] != ''): ?><p>Komentarz: <strong><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['comment']; ?>
</strong></p><?php endif; ?>
			<p>Termin płatności: <strong><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['paymentduedate']; ?>
</strong></p>
			<p>Osoba wystawiająca fakturę: <strong><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['salesperson']; ?>
</strong></p>
			<p>Zapłacono: <strong><?php echo $this->_tpl_vars['order']['invoices'][$this->_sections['n']['index']]['totalpayed']; ?>
</strong></p>
		</li>
		<?php endfor; endif; ?>
		</ul>
		<p class="information">
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
invoice/add/<?php echo $this->_tpl_vars['order']['order_id']; ?>
,1" class="button"><span>Wystaw fakturę PROFORMA</span></a>
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
invoice/add/<?php echo $this->_tpl_vars['order']['order_id']; ?>
,2" class="button"><span>Wystaw fakturę VAT</span></a>
		</p>
		<br />
		
	</fieldset>
	
	<fieldset>
		<legend><span>Przebieg zamówienia</span></legend>
			<?php if (count ( $this->_tpl_vars['order']['order_history'] ) > 0): ?>
				<ul class="changes-detailed">
					<?php $_from = $this->_tpl_vars['order']['order_history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['change']):
?>
						<li>
							<h4><span><?php echo $this->_tpl_vars['change']['date']; ?>
 - <em><?php if ($this->_tpl_vars['change']['inform']): ?>Powiadomiony<?php else: ?>Niepowiadomiony<?php endif; ?></em></span></h4>
							<?php if (isset ( $this->_tpl_vars['change']['orderstatusname'] )): ?><p>status: <strong><?php echo $this->_tpl_vars['change']['orderstatusname']; ?>
</strong></p><?php endif; ?>
							<?php if (isset ( $this->_tpl_vars['change']['content'] )): ?><p>Komentarz: <strong><?php echo $this->_tpl_vars['change']['content']; ?>
</strong></p><?php endif; ?>
							<p class="author">Autor: <strong><?php echo $this->_tpl_vars['change']['firstname']; ?>
 <?php echo $this->_tpl_vars['change']['surname']; ?>
</strong></p>
						</li>
					<?php endforeach; endif; unset($_from); ?>
				</ul>
			<?php else: ?>
				<p class="information">Brak zmian do wyświetlenia</p>
			<?php endif; ?>
	</fieldset>
	<?php if (count ( $this->_tpl_vars['order']['order_files'] ) > 0): ?>
	<fieldset>
		<legend><span>Pliki do zamówienia</span></legend>
			<?php unset($this->_sections['f']);
$this->_sections['f']['name'] = 'f';
$this->_sections['f']['loop'] = is_array($_loop=$this->_tpl_vars['order']['order_files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<p class="information"><a href="<?php echo $this->_tpl_vars['order']['order_files'][$this->_sections['f']['index']]['path']; ?>
" target="_blank"><?php echo $this->_tpl_vars['order']['order_files'][$this->_sections['f']['index']]['path']; ?>
</a></p>
			<?php endfor; endif; ?>
	</fieldset>
	<?php endif; ?>
	
	<fieldset>
		<legend><span>Notatka do zamowienia</span></legend>
			<ul class="changes-detailed">
				<?php $_from = $this->_tpl_vars['orderNotes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ordernotes']):
?>
					<li>
						<h4><span><?php echo $this->_tpl_vars['ordernotes']['adddate']; ?>
</span></h4>
						<?php if (isset ( $this->_tpl_vars['ordernotes']['content'] )): ?><p>Komentarz: <strong><?php echo $this->_tpl_vars['ordernotes']['content']; ?>
</strong></p><?php endif; ?>
						<p class="author">Autor: <strong><?php echo $this->_tpl_vars['ordernotes']['firstname']; ?>
 <?php echo $this->_tpl_vars['ordernotes']['surname']; ?>
</strong></p>
					</li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
	</fieldset>
	
	<!--<fieldset>
		<legend><span>Notatka do produktu</span></legend>
			<ul class="changes-detailed">
				Do zrobienia
			</ul>
	</fieldset>-->
	
	<fieldset>
		<legend><span>Notatka o kliencie</span></legend>
			<ul class="changes-detailed">
				<?php $_from = $this->_tpl_vars['clientNotes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['clientnotes']):
?>
					<li>
						<h4><span><?php echo $this->_tpl_vars['clientnotes']['adddate']; ?>
</span></h4>
						<?php if (isset ( $this->_tpl_vars['clientnotes']['content'] )): ?><p>Komentarz: <strong><?php echo $this->_tpl_vars['clientnotes']['content']; ?>
</strong></p><?php endif; ?>
						<p class="author">Autor: <strong><?php echo $this->_tpl_vars['clientnotes']['clientname']; ?>
 <?php echo $this->_tpl_vars['clientnotes']['clientsurname']; ?>
</strong></p>
					</li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>Historia zamówień klienta</span></legend>
			<ul class="changes-detailed">
				<?php $_from = $this->_tpl_vars['clientOrderHistory']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['clientorderhistory']):
?>
					<li>
						<h4><span><?php echo $this->_tpl_vars['clientorderhistory']['adddate']; ?>
</span></h4>
						<p>Nr. zamówienia:  <strong><a href="<?php echo $this->_tpl_vars['URL']; ?>
order/edit/<?php echo $this->_tpl_vars['clientorderhistory']['idorder']; ?>
">#<?php echo $this->_tpl_vars['clientorderhistory']['idorder']; ?>
</a></strong></p>
						<p class="author">Wartość zamówienia : <strong><?php echo $this->_tpl_vars['clientorderhistory']['globalprice']; ?>
</strong><?php echo $this->_tpl_vars['currencysymbol']; ?>
</p>
					</li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
	</fieldset>
	
	<fieldset>
		<legend><span>Komentarz klienta</span></legend>
		<?php if (isset ( $this->_tpl_vars['order']['customeropinion'] )): ?>
			<p class="information"><?php echo $this->_tpl_vars['order']['customeropinion']; ?>
</p>
		<?php else: ?>
			<p class="information">Klient jeszcze nie skomentował tego zamówienia</p>
		<?php endif; ?>
	</fieldset>
</div>

<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			
			var RecalculateOrder = function(eEvent, bWithDeliveryMethodsUpdate) {
				var fNetValue = parseFloat($(\'.field-order-editor .selected-products tr.total .GF_Datagrid_Col_net_subsum span\').text());
				var fVatValue = parseFloat($(\'.field-order-editor .selected-products tr.total .GF_Datagrid_Col_vat_value span\').text());
				var fWeight = parseFloat($(\'.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span\').text());
				fNetValue = isNaN(fNetValue) ? 0 : fNetValue;
				fVatValue = isNaN(fVatValue) ? 0 : fVatValue;
				fWeight = isNaN(fWeight) ? 0 : fWeight;
				var gSelectedDatagrid = $(\'.field-order-editor\').get(0).gNode.m_gSelectedDatagrid;
				var aoProducts = [];
				for (var i in gSelectedDatagrid.m_aoRows) {
					aoProducts.push({
						id: gSelectedDatagrid.m_aoRows[i].idproduct,
						variant: gSelectedDatagrid.m_aoRows[i].variant,
						quantity: gSelectedDatagrid.m_aoRows[i].quantity,
						price: gSelectedDatagrid.m_aoRows[i].price
					});
				};
				$(\'#additional_data__summary_data__total_net_total\').val(fNetValue.toFixed(2));
				$(\'#additional_data__summary_data__total_vat_value\').val(fVatValue.toFixed(2));
				$(\'#pricenetto\').val(fNetValue.toFixed(2));
				$(\'#pricebrutto\').val((fNetValue + fVatValue).toFixed(2));
				
				if ((bWithDeliveryMethodsUpdate != undefined) && bWithDeliveryMethodsUpdate) {					
					xajax_GetDispatchMethodForPrice({
						products: aoProducts,
						idorder: '; ?>
<?php echo $this->_tpl_vars['order']['id']; ?>
<?php echo ',
						net_total: (fNetValue).toFixed(2),
						gross_total: (fNetValue + fVatValue).toFixed(2),
						weight_total: (fWeight).toFixed(2),
					}, GCallback(function(oResponse) {
						$(\'#edit_order\').get(0).GetField(\'delivery_method\').ExchangeOptions(oResponse.options);
					}));
				}
				xajax_CalculateDeliveryCost({
					products: aoProducts,
					idorder: '; ?>
<?php echo $this->_tpl_vars['order']['id']; ?>
<?php echo ',
					weight: parseFloat($(\'.field-order-editor .selected-products tr.total .GF_Datagrid_Col_weight span\').text()),
					price_for_deliverers: $(\'#pricebrutto\').val(),
					net_total: $(\'#pricenetto\').val(),
					delivery_method: $(\'#additional_data__payment_data__delivery_method\').val(),
					rules_cart: $(\'#additional_data__payment_data__rules_cart\').val(),
					currency: $(\'#currencyid\').val()
				}, GCallback(function(oResponse) {
					var fDeliveryValue = parseFloat(oResponse.cost);
					fDeliveryValue = isNaN(fDeliveryValue) ? 0 : fDeliveryValue;
					var fCouponValue = parseFloat(oResponse.coupon);
					fCouponValue = isNaN(fCouponValue) ? 0 : fCouponValue;
					$(\'#additional_data__summary_data__total_delivery\').val(fDeliveryValue.toFixed(2));
					$(\'#dispatchmethodprice\').val(fDeliveryValue.toFixed(2));
					if(oResponse.rulesCart.discount != undefined) {
						var sSymbol =  oResponse.rulesCart.symbol;
						var fDiscount = parseFloat(oResponse.rulesCart.discount);
						var fOldTotal = parseFloat(fNetValue + fVatValue + fDeliveryValue - fCouponValue);
						switch (sSymbol) {
							case \'%\':
								fNewTotal = fOldTotal * (fDiscount / 100);
								break;
							case \'+\':
								fNewTotal = fOldTotal + fDiscount;
								break;
							case \'-\':
								fNewTotal = fOldTotal - fDiscount;
								break;
							case \'=\':
								fNewTotal = fDiscount;
								break;
						}
						$(\'#additional_data__summary_data__total_total\').val((fNewTotal).toFixed(2));
					} else {
						$(\'#additional_data__summary_data__total_total\').val((fNetValue + fVatValue + fDeliveryValue - fCouponValue).toFixed(2));
					}
					
					$(\'#additional_data__summary_data__total_delivery\').val(fDeliveryValue.toFixed(2));
					$(\'#coupon\').val(fCouponValue.toFixed(2));
					$(\'#dispatchmethodprice\').val(fDeliveryValue.toFixed(2));
				}));
			};
			
			var OnProductListChanged = GEventHandler(function(eEvent) {
				var gSelectedDatagrid = $(\'.field-order-editor\').get(0).gNode.m_gSelectedDatagrid;
				if(gSelectedDatagrid.m_aoRows.length){
					RecalculateOrder(eEvent, true);
				}
			});
			
			$(document).ready(function() {
				$(\'#additional_data__payment_data__delivery_method\').live(\'change\',RecalculateOrder);
				$(\'#additional_data__payment_data__rules_cart\').change(RecalculateOrder);
				$("<input />").attr({type:\'hidden\',name:\'coupon\',id:\'coupon\',value:\'0\'}).appendTo($("#edit_order"));
				$("<input />").attr({type:\'hidden\',name:\'dispatchmethodprice\',id:\'dispatchmethodprice\'}).appendTo($("#edit_order"));
				$("<input />").attr({type:\'hidden\',name:\'pricebrutto\',id:\'pricebrutto\'}).appendTo($("#edit_order"));
				$("<input />").attr({type:\'hidden\',name:\'pricenetto\',id:\'pricenetto\'}).appendTo($("#edit_order"));
				$("<input />").attr({type:\'hidden\',name:\'currencyid\',id:\'currencyid\',value:\''; ?>
<?php echo $this->_tpl_vars['currencyid']; ?>
<?php echo '\'}).appendTo($("#edit_order"));
			});	

		/*]]>*/
	'; ?>

</script>