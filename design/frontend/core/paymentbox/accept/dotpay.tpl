<div><p>Trwa przekierowanie na strony systemu płatności Dotpay.pl. Proszę czekać...</p></div>

<form action="https://ssl.dotpay.pl/" method="post" id="dotpay">
	<input type="hidden" name="id" value="{$content.idsprzedawcy}">
	<input type="hidden" name="amount" value="{$orderData.priceWithDispatchMethod}">
	<input type="hidden" name="currency" value="{$currencySymbol}">
	<input type="hidden" name="description" value="{trans}TXT_ORDER{/trans} {$orderId}">
	<input type="hidden" name="lang" value="{$languageCode|substr:0:2}">
	<input type="hidden" name="email" value="{$orderData.clientdata.email}">
	<input type="hidden" name="firstname" value="{$orderData.clientdata.firstname}">
	<input type="hidden" name="lastname" value="{$orderData.clientdata.surname}">
	<input type="hidden" name="control" value="{$content.crc}">
	<input type="hidden" name="URL" value="{$URL}{seo controller=payment}/confirm/">
	<input type="hidden" name="typ" value="3">
	<input type="hidden" name="URLC" value="{$URL}dotpayreport/">
	<input type="hidden" name="txtguzik" value="Powrót do {$SHOP_NAME} ">
	<input type="hidden" name="street" value="{$orderData.clientdata.street}">
	<input type="hidden" name="street_n1" value="{$orderData.clientdata.streetno}">
	{if $orderData.clientdata.placeno != ''}<input type="hidden" name="street_n2" value="{$orderData.clientdata.placeno}">{/if}
	<input type="hidden" name="city" value="{$orderData.clientdata.placename}">
	<input type="hidden" name="postcode" value="{$orderData.clientdata.postcode}">
	<input type="hidden" name="phone" value="{$orderData.clientdata.phone}">
	<input type="hidden" name="country" value="Polska">
</form>
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#dotpay').submit();
});
{/literal}
</script>