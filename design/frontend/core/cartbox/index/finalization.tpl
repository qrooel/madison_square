<fieldset class="listing">
	<legend><span>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}</span></legend>
	{if isset($productCart)}
	<dl>
	{section name=s loop=$summary}
		<dt>{$summary[s].label}</dt><dd>{$summary[s].value}</dd>
	{/section}
	</dl>
	{/if}
</fieldset>
