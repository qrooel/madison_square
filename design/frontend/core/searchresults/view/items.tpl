{if count($items) > 0}
<div style="z-index: 900000; width: 450px;right: 289px;" class="layout-box-type-product-list layout-box-width-class-2 layout-box"> 
<div class="layout-box-content" style="margin-top: 0px">
						
<ul class="list-long"> 
{section name=i loop=$items}
	{if $items[i].discountprice != NULL && $items[i].discountprice > 0}
	<li class="promo">
		<h4>
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}" title="{$items[i].name}">
				<span class="image">
				<img class="promo" src="{$DESIGNPATH}_images_frontend/core/icons/product-promo.png" alt="Promocja!" title="Promocja!"/>
				<img src="{$items[i].photo}" alt="{$items[i].name}"/>
				</span>
				<span class="name">{$items[i].name}</span>
				{if $showtax == 0}
					<span class="price"><ins>{$items[i].discountpricenetto}</ins> <del>{$items[i].pricenetto}</del></span>
				{else}
					<span class="price"><ins>{$items[i].discountprice}</ins> <del>{$items[i].price}</del></span>
				{/if}		
			</a>
		</h4>
		<div class="description">
			{$items[i].shortdescription|strip_tags:false|truncate:250}
		</div>
	</li>
	{else}
	<li>
		<h4>
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}" title="{$items[i].name}">
				<span class="image"><img src="{$items[i].photo}" alt="{$items[i].name}"/></span>
				<span class="name">{$items[i].name}</span>
				{if $showtax == 0}
					<span class="price">{$items[i].pricenetto}</span>
				{else}
					<span class="price">{$items[i].price}</span>
				{/if}		
			</a>
		</h4>
		<div class="description">
			{$items[i].shortdescription|strip_tags:false|truncate:250}
		</div>
	</li>
	{/if}
{/section} 
</ul> 
							
<p class="see-more" style="margin-top: 5px;margin-right: 10px;float: right;"><a href="{$URL}{seo controller=productsearch}/{$phrase}">Zobacz wszystkie</a></p> 
</div>
</div>
</div>
{else}
<div class="layout-box" style="z-index: 900000; width: 450px;right: 305px;">
	<div class="layout-box-content">
		<p>{trans}ERR_EMPTY_PRODUCT_SEARCH{/trans}</p>
	</div>
{/if}