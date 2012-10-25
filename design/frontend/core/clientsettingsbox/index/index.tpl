<ol class="order-stage">
	<li class="settings active"><a href="{$URL}{seo controller=clientsettings}/">{trans}TXT_SETTINGS{/trans}</a></li>
	<li class="orders"><a href="{$URL}{seo controller=clientorder}/">{trans}TXT_ORDERS{/trans}</a></li>
	<li class="address"><a href="{$URL}{seo controller=clientaddress}/">{trans}TXT_CLIENT_ADDRESS{/trans}</a></li>
</ol>

{if isset($error)}
<script>
{literal}
GError('{/literal}{$error}{literal}','');
{/literal}
</script>
{/if} 
{if isset($success)}
<script>
{literal}
GMessage('{/literal}{$success}{literal}','');
{/literal}
</script>
{/if} 

{fe_form form=$formPass render_mode="JS"} 

{if isset($clientChangedMail)}
<script>
{literal}
GMessage('{/literal}{$clientChangedMail}{literal}','');
{/literal}
</script>
<script type="text/javascript">
	window.onload = xajax_sendAlert();
</script> 
{/if}
{fe_form form=$formEmail render_mode="JS"} 