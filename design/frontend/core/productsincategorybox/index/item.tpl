{if $product.discountprice != NULL && $product.discountprice > 0}
<li class="promo">
	<h4>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}" title="{$product.name}">
			<span class="image">
			<img class="promo" src="{$DESIGNPATH}_images_frontend/core/icons/product-promo.png" alt="Promocja!" title="Promocja!"/>
			<img src="{$product.photo}" alt="{$product.name}"/>
			</span>
			<span class="name">{$product.name}</span>
			{if $showtax == 0}
				<span class="price"><ins>{$product.discountpricenetto}</ins> <del>{$product.pricenetto}</del></span>
			{else}
				<span class="price"><ins>{$product.discountprice}</ins> <del>{$product.price}</del></span>
			{/if}		
		</a>
	</h4>
	{if $product.opinions > 0}
	<p class="rating">
		<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$product.rating}.png" alt="Ocena Klientów: 4"/></span>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}#product-opinions">Przeczytaj {$product.opinions} opinii</a>
	</p>
	{else}
	<p class="rating">
		<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}#product-opinions">Bądź pierwszym który oceni ten produkt</a>
	</p>
	{/if}
	<div class="description">
		<p>{$product.shortdescription}<a class="read-more" href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
	<p class="add-to-cart">
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}"><img src="{$DESIGNPATH}_images_frontend/core/buttons/add-to-cart.png" alt="{trans}TXT_ADD_TO_CART{/trans}"/></a>
	</p>
</li>
{else}
<li>
	<h4>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}" title="{$product.name}">
			<span class="image"><img src="{$product.photo}" alt="{$product.name}"/></span>
			<span class="name">{$product.name}</span>
			{if $showtax == 0}
				<span class="price">{$product.pricenetto}</span>
			{else}
				<span class="price">{$product.price}</span>
			{/if}		
		</a>
	</h4>
	{if $product.opinions > 0}
	<p class="rating">
		<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$product.rating}.png" alt="Ocena Klientów: 4"/></span>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}#product-opinions">Przeczytaj {$product.opinions} opinii</a>
	</p>
	{else}
	<p class="rating">
		<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span>
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}#product-opinions">Bądź pierwszym który oceni ten produkt</a>
	</p>
	{/if}
	<div class="description">
		<p>{$product.shortdescription}<a class="read-more" href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
	<p class="add-to-cart">
		<a href="{$URL}{seo controller=productcart}/{$product.id}/{$product.seo}"><img src="{$DESIGNPATH}_images_frontend/core/buttons/add-to-cart.png" alt="{trans}TXT_ADD_TO_CART{/trans}"/></a>
	</p>
</li>
{/if}
