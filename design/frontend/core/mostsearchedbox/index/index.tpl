{if isset($mostsearched[0])}	
	<div id="cloud">
		{section name=i loop=$mostsearched}
			<a class="tag{$mostsearched[i].percentage}" href="{$URL}{seo controller=productsearch}/{$mostsearched[i].phrase}">{$mostsearched[i].name}</a>
		{/section}
	</div>
	{else}
	<p>{trans}ERR_EMPTY_TAGS{/trans}</p>
{/if}

