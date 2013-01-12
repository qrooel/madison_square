<div><p>Trwa przekierowanie na strony systemu płatności Przelewy24.pl. Proszę czekać...</p></div>

<form action="https://secure.przelewy24.pl" method="post" id="przelewy24">
    <input type="hidden" name="p24_session_id" value="{$content.sessionid}" />
	<input type="hidden" name="p24_id_sprzedawcy" value="{$content.idsprzedawcy}">
	<input type="hidden" name="p24_kwota" value="{$content.kwota}">
    <input type="hidden" name="p24_klient" value="{$orderData.clientdata.firstname} {$orderData.clientdata.surname}" /> <!-- IMIE I NAZWISKO ODBIORCY ZAMOWIENIA -->
    <input type="hidden" name="p24_adres" value="{$orderData.clientdata.street} {$orderData.clientdata.streetno}{if $orderData.clientdata.placeno != ''}/{$orderData.clientdata.placeno}{/if}" /> <!-- odbiorca zamówienia, ulica, numer domu i lokalu -->
    <input type="hidden" name="p24_kod" value="{$orderData.clientdata.postcode}" /> <!-- odbiorca zamówienia, kod pocztowy -->
    <input type="hidden" name="p24_miasto" value="{$orderData.clientdata.placename}" /> <!-- odbiorca zamówienia, miejscowość -->
    <input type="hidden" name="p24_kraj" value="PL" /> <!-- odbiorca zamówienia, kraj (kod np. PL, DE, itp.) -->
    <input type="hidden" name="p24_email" value="{$orderData.clientdata.email}">
    <input type="hidden" name="p24_return_url_ok" value="{$URL}przelewy24report" /> <!-- pomyślne przeprowadzenie transakcji -->
    <input type="hidden" name="p24_return_url_error" value="{$URL}przelewy24report" /> <!-- błęd w transakcji -->
    <input type="hidden" name="p24_opis" value="{trans}TXT_ORDER{/trans} {$orderId}" />
    <input type="hidden" name="p24_language" value="pl" />
    <input type="hidden" name="p24_crc" value="{$content.crc}" />
</form>

<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#przelewy24').submit();
});
{/literal}
</script>