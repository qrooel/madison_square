{if isset($mailsend)}
			<p class="error"><strong>{trans}TXT_ATTENTION{/trans}: {trans}{$mailsend}{/trans}</strong></p>
		{/if}
							
		{if isset($forbiddencodeaddressform)}
			<p class="error"><strong>{trans}TXT_ATTENTION{/trans}: {trans}{$forbiddencodeaddressform}{/trans}</strong></p>
		{/if}
		
<ol class="order-stage">
	<li class="settings"><a href="{$URL}{seo controller=clientsettings}/">{trans}TXT_SETTINGS{/trans}</a></li>
	<li class="orders"><a href="{$URL}{seo controller=clientorder}/">{trans}TXT_ORDERS{/trans}</a></li>
	<li class="address active"><a href="{$URL}{seo controller=clientaddress}/">{trans}TXT_CLIENT_ADDRESS{/trans}</a></li>
</ol>

{fe_form form=$formBilling render_mode="JS"}
{fe_form form=$formShipping render_mode="JS"}
