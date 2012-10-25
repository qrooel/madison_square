<ul>
{section name=links loop=$dataset.totalPages}
{if $smarty.section.links.first}
	{if $dataset.activePage == 1}
	<li class="previous disabled"><a href="#">Poprzednie</a></li>
	{else}
	<li class="previous"><a href="{$URL}{seo controller=$controller seo=$searchPhrase page=$dataset.previousPage price=$priceRange producers=$currentProducers attributes=$currentAttributes}">{trans}TXT_PREVIOUS{/trans}</a></li>
	{/if}
{/if}
	<li class="page {if $dataset.totalPages[links] == $dataset.activePage}active{/if}" ><a id="{$dataset.totalPages[links]}"  href="{$URL}{seo controller=$controller seo=$searchPhrase page=$dataset.totalPages[links] price=$priceRange producers=$currentProducers attributes=$currentAttributes}">{$dataset.totalPages[links]}</a></li>
{if $smarty.section.links.last}
	{if $dataset.activePage == $dataset.lastPage}
	<li class="next disabled"><a href="#">Następne</a></li>
	{else}
	<li class="next"><a href="{$URL}{seo controller=$controller seo=$searchPhrase page=$dataset.nextPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }">{trans}TXT_NEXT{/trans}</a></li>
	{/if}
{/if}
{/section}
</ul>
