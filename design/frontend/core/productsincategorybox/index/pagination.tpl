{if $dataset.total > 0}
<ul>
	{section name=links loop=$dataset.totalPages}
		{if $smarty.section.links.first}
			{if $dataset.activePage == 1}
				<li class="previous disabled"><a href="">{trans}TXT_PREVIOUS{/trans}</a></li>
			{else}
				<li class="previous"><a href="{$URL}{seo controller=$controller seo=$currentCategory.seo page=$dataset.previousPage price=$priceRange producers=$currentProducers attributes=$currentAttributes}">{trans}TXT_PREVIOUS{/trans}</a></li>
			{/if}
		{/if}
			<li class="page {if $dataset.totalPages[links] == $dataset.activePage}active{/if}" ><a href="{$URL}{seo controller=$controller seo=$currentCategory.seo page=$dataset.totalPages[links] price=$priceRange producers=$currentProducers attributes=$currentAttributes }">{$dataset.totalPages[links]}</a></li>
		{if $smarty.section.links.last}
			{if $dataset.activePage == $dataset.lastPage}
				<li class="next disabled"><a href="">{trans}TXT_NEXT{/trans}</a></li>
			{else}
			<li class="next"><a href="{$URL}{seo controller=$controller seo=$currentCategory.seo page=$dataset.nextPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }">{trans}TXT_NEXT{/trans}</a></li>
			{/if}
		{/if}
	{/section}
</ul>
{/if}
