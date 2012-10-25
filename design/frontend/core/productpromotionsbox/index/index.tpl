{if $products <> ''}
{if $enablerss == 1}<p class="rss" style="height: 20px;"><span style="text-align: right;margin-top: 10px;margin-right: 10px;float: right;margin-bottom: 10px;"><a href="{$URL}feeds/productpromotion"><img src="{$DESIGNPATH}_images_frontend/core/icons/rss.png" title="RSS - {trans}TXT_PRODUCT_PROMOTION{/trans}"></a></span></p>{/if}
	<ul class="product-list {if $view == 0}list-grid{else}list-long{/if}">
		{$products}
	</ul>
	{if $pagination == 1}
		{pagination dataset=$dataset controller=categorylist id=$currentCategory.id seo=$currentCategory.seo}
	{/if}
{else}
	<p>{trans}ERR_EMPTY_PRODUCT_LIST{/trans}</p>
{/if}