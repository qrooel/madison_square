{if isset($clientdata)} 
		{if $products <> ''}
			<ul class="product-list list-long">
				{$products}
			</ul>
			{if $pagination == 1}
				{pagination dataset=$dataset controller=categorylist id=$currentCategory.id seo=$currentCategory.seo}
			{/if}
			
		{else} 
			<p>{trans}ERR_EMPTY_WISHLIST{/trans}</p>
		{/if}
{/if}