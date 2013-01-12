<div><p>Trwa przekierowanie na strony systemu płatności Transferuj.pl. Proszę czekać...</p></div>

<form action="https://secure.transferuj.pl" method="post" id="transferuj">
	<input type="hidden" name="id" value="{$content.idsprzedawcy}">
	<input type="hidden" name="kwota" value="{$orderData.priceWithDispatchMethod}">
	<input type="hidden" name="opis" value="{trans}TXT_ORDER{/trans} {$orderId}">
	<input type="hidden" name="crc" value="{$content.crc}">
	<input type="hidden" name="md5sum" value="{$content.md5sum}">
	<input type="hidden" name="wyn_url" value="{$URL}{seo controller=transferujreport}/">
	<input type="hidden" name="opis_sprzed" value="{$SHOP_NAME}">
	<input type="hidden" name="pow_url" value="{$URL}{seo controller=payment}/confirm/">
	<input type="hidden" name="pow_url_blad" value="{$URL}{seo controller=payment}/cancel/">
	<input type="hidden" name="pow_tekst" value="Powrót do {$SHOP_NAME} ">
	<input type="hidden" name="email" value="{$orderData.clientdata.email}">
	<input type="hidden" name="nazwisko" value="{$orderData.clientdata.surname}">
	<input type="hidden" name="imie" value="{$orderData.clientdata.firstname}">
	<input type="hidden" name="adres" value="{$orderData.clientdata.street} {$orderData.clientdata.streetno}{if $orderData.clientdata.placeno != ''}/{$orderData.clientdata.placeno}{/if}">
	<input type="hidden" name="miasto" value="{$orderData.clientdata.placename}">
	<input type="hidden" name="kod" value="{$orderData.clientdata.postcode}">
	<input type="hidden" name="kraj" value="Polska">
	<input type="hidden" name="telefon" value="{$orderData.clientdata.phone}">
</form>
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#transferuj').submit();
});
{/literal}
</script>