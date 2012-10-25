{if isset($loginerror)}
<script>
{literal}
GError('{/literal}{trans}{$loginerror}{/trans}{literal}','');
{/literal}
</script>
{/if} 

{fe_form form=$form render_mode="JS"}
{if isset($facebooklogin) && $facebooklogin != ''}
<div style="margin: 20px;">
<p>{trans}TXT_LOGIN_WITH_FACEBOOK{/trans}</p>
<a href="{$facebooklogin}"><img src="{$DESIGNPATH}_images_frontend/core/buttons/facebook.png" /></a>
</div>
{/if}
