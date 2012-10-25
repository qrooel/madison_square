{section name=i loop=$items}
<li>
	<h4>
		<a href="{$URL}{seo controller=productcart}/{$items[i].seo}" title="{$items[i].name}">
			<span class="image"><img src="{$items[i].photo}" alt="{$items[i].name}"/></span>
			<span class="name">{$items[i].name}</span>
			{if $catalogmode == 0}
			{if $items[i].discountprice != NULL && $items[i].discountprice > 0}
				{if $showtax == 0}
					<span class="price"><ins>{$items[i].discountpricenetto}</ins> <del>{$items[i].pricenetto}</del></span>
				{else}
					<span class="price"><ins>{$items[i].discountprice}</ins> <del>{$items[i].price}</del></span>
				{/if}
			{else}
				{if $showtax == 0}
					<span class="price">{$items[i].pricenetto}</span>
				{else}
					<span class="price">{$items[i].price}</span>
				{/if}	
			{/if}
			{/if}
		</a>
	</h4>
	<div class="description">
		<p>{$items[i].shortdescription} <a class="read-more" href="{$URL}{seo controller=productcart}/{$items[i].seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
</li>
{/section}