{if $view == 0}
<ul style="list-style: none; margin-left:0px;">
{section name=producerId loop=$producers}
	<li{if $producers[producerId].seo == $CURRENT_PARAM} class="active"{/if}>
		<a href="{$URL}{seo controller=producerlist}/{$producers[producerId].seo}">
			{$producers[producerId].name}
		</a>
	</li>
{/section}
</ul>
{else}
<div class="field-select" style="margin: 0px 0px 10px 10px;padding-top: 10px;"> 
	<span class="field"> 
		<select id="languages" onchange="location.href = this.value;">
				<option value="{$URL}">{trans}TXT_CHOOSE_SELECT{/trans}</option>
		 	{section name=producerId loop=$producers}
				{if $CURRENT_PARAM == $producers[producerId].seo}
					<option value="{$URL}{seo controller=producerlist}/{$producers[producerId].seo}" selected="selected">{$producers[producerId].name}</option>
				{else}
					<option value="{$URL}{seo controller=producerlist}/{$producers[producerId].seo}">{$producers[producerId].name}</option>
				{/if}
			{/section}
		</select>
	</span> 
</div> 
{/if}
