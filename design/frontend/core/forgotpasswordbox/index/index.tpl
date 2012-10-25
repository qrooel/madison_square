{if isset($emailerror)}
<script>
{literal}
GError('{/literal}{trans}{$emailerror}{/trans}{literal}','');
{/literal}
</script>
{/if} 

{if isset($sendPasswd)}
<script>
{literal}
GMessage('{/literal}{trans}{$sendPasswd}{/trans}{literal}','');
{/literal}
</script>
{/if} 
		
{fe_form form=$form render_mode="JS"} 