<fieldset class="listing">
<legend><span>{trans}TXT_PAYMENT_TYPE{/trans}</span></legend>
{if isset($checkedDelivery.dispatchmethodid) && ($checkedDelivery.dispatchmethodid > 0)}
	<dl>
	{foreach from=$payments item=payment key=key}
		<dt>
			<label>
				<input type="radio" name="paymentsradio" id="payment-{$payment.idpaymentmethod}" value="{$payment.name}" {if ($payment.idpaymentmethod == $checkedPayment.idpaymentmethod)}checked="checked"{/if} onclick="xajax_setPeymentChecked({$payment.idpaymentmethod}, '{$payment.name}'); return false;" />
				{if  isset($payment.wariantsklepu) && ($payment.wariantsklepu)>0 && isset($payment.numersklepu) && $payment.numersklepu > 0}
				{$payment.name}
				<a href="https://www.eraty.pl/symulator/oblicz.php?numerSklepu={$payment.numersklepu}&wariantSklepu={$payment.wariantsklepu}&typProduktu=0&wartoscTowarow={$priceWithDispatchMethod}" target='_blank' > Policz ratę </a>
				{else}
				{$payment.name}
				{/if}
			</label>
		</dt>
		<dd>&nbsp;</dd>
	{/foreach}
	</dl>
{/if}
</fieldset>
