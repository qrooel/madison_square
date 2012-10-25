{if isset($sendContact) && ($sendContact==1)}
<script>
{literal}
GMessage('{/literal}{trans}TXT_SEND_QUERY{/trans}{literal}','');
{/literal}
</script>
{/if} 

{fe_form form=$form render_mode="JS"} 
