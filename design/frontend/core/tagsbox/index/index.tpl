{if isset($tags[0])}	
	<div id="cloud">
		{section name=i loop=$tags}
			<a class="tag{$tags[i].percentage}" href="{$URL}{seo controller=producttags}/{$tags[i].idtags}">{$tags[i].name}</a>
		{/section}
	</div>
	{else}
	<p>{trans}ERR_EMPTY_TAGS{/trans}</p>
{/if}

