<fieldset class="payment">

{foreach from=$payments item=payment key=key}
	<div class="field-radio">
		<label>
		{if ($payment.idpaymentmethod == $checkedPayment.idpaymentmethod)}
			<input type="radio" name="paymentsradio" id="{$payment.idpaymentmethod}" value="{$payment.name}" checked="checked" onclick="xajax_setPeymentChecked(id, value); return false;" />
		{else}
			<input type="radio" name="paymentsradio" id="{$payment.idpaymentmethod}" value="{$payment.name}" onclick="xajax_setPeymentChecked(id, value); return false;" /> 
		{/if}
		{if  isset($payment.wariantsklepu) && ($payment.wariantsklepu)>0 && isset($payment.numersklepu) && $payment.numersklepu > 0}
		<span>
			{$payment.name}
			<a href="https://www.eraty.pl/symulator/oblicz.php?numerSklepu={$payment.numersklepu}&wariantSklepu={$payment.wariantsklepu}&typProduktu=0&wartoscTowarow={$priceWithDispatch}" target='_blank' > Policz ratę </a>
		</span>
		{else}
		<span>{$payment.name} </span>
		{/if}
		</label>
	</div>
{/foreach}

</fieldset>
						
<div class="buttons" id="action_buttons">
	<a id="payment-prev" href="{$URL}{seo controller=delivery}/"><img src="{$DESIGNPATH}/_images_frontend/buttons/wstecz.png" alt="{trans}TXT_BACK{/trans}"/></a>
	{if (isset($checkedPayment) && $checkedPayment != 0)}
	<a id="payment-next" href="{$URL}{seo controller=finalization}/"><img src="{$DESIGNPATH}/_images_frontend/buttons/dalej.png" alt="{trans}TXT_NEXT{/trans}" value="{trans}TXT_NEXT{/trans}"/></a>
	{/if}
</div>			

<script type="text/javascript">
{literal}
$(document).ready(function() {

	$('.tabs').parent().tabs({
		disabled: [3]
	});
	$('input[name=paymentsradio]').click(function(){
		$('.tabs').parent().tabs({
			disabled: []
		});
	});
	if($('input[name=paymentsradio]:checked').val()){
		$('.tabs').parent().tabs({
			disabled: []
		});
	}

	$('#payment-prev').live('click',function(){
		$('.tabs').parent().tabs("select",1);
		return false;
	});
	
	$('#payment-next').live('click',function(){
		$('.tabs').parent().tabs("select",3);
		return false;
	});
});
{/literal}
</script>
