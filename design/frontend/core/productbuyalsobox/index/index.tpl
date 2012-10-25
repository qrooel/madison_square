{if $products <> ''}
	<ul class="product-list {if $view == 0}list-grid{else}list-long{/if}">
		{$products}
	</ul>
	{if $pagination == 1}
		{pagination dataset=$dataset}
	{/if}
	
{else}
	<p>{trans}ERR_EMPTY_PRODUCT_LIST{/trans}</p>
{/if}
						