{section name=i loop=$items}
{if $items[i].discountprice != NULL && $items[i].discountprice > 0}
<li class="promo">
	<h4>
		<a href="{$URL}{seo controller=productcart}/{$items[i].seo}" title="{$items[i].name}">
			<span class="image">
			<img class="mainphoto" src="{$items[i].photo}" alt="{$items[i].name}"/>
			<img class="promo" src="{$DESIGNPATH}_images_frontend/core/icons/product-promo.png" alt="Promocja!" title="Promocja!"/>
			{if $items[i].new == 1}<img class="novelty" src="{$DESIGNPATH}_images_frontend/core/icons/product-novelty.png" alt="Nowość!" title="Nowość!">{/if}
			</span>
			<span class="name">{$items[i].name|truncate:70}</span>
		</a>
		{if $catalogmode == 0 && $items[i].price > 0}
			{if $showtax == 0}
				<span class="price"><ins>{$items[i].discountpricenetto}</ins> <del>{$items[i].pricenetto}</del></span>
			{/if}
			{if $showtax == 1}
				<span class="price"><ins>{$items[i].discountprice}</ins> <del>{$items[i].price}</del></span>
			{/if}	
			{if $showtax == 2}
				<span class="price"><ins>{$items[i].discountprice}</ins> <del>{$items[i].price}</del></span>
				<span class="price"><ins>{$items[i].discountpricenetto}</ins> <del>{$items[i].pricenetto}</del> {trans}TXT_NETTO{/trans}</span>
			{/if}	
		{else}
			<span class="price">{trans}TXT_REQUEST_QUOTE{/trans}</span>
		{/if}	
	</h4>
	
	{if $enableopinions == 1}
		{if $items[i].opinions > 0}
		<p class="rating">
		{if $enablerating > 0}<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$items[i].rating}.png" alt="Ocena Klientów: {$items[i].rating}"/></span>{/if}
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}#product-opinions">Przeczytaj {$items[i].opinions} opinii</a>
		</p>
		{else}
		<p class="rating">
			{if $enablerating > 0}<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span>{/if}
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}#product-opinions">{trans}TXT_NO_PRODUCT_REVIEWS{/trans}</a>
		</p>
		{/if}
	{/if}
	{if $items[i].dateto != NULL}<p class="promotion-time-left">{trans}TXT_PROMOTION_ENDDATE{/trans}: <strong>{$items[i].dateto}</strong></p>{/if}
	<div class="description">
		<p>{$items[i].shortdescription}<br /><br /><a class="read-more" href="{$URL}{seo controller=productcart}/{$items[i].seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
	{if $catalogmode == 0 && $items[i].pricenetto > 0}
	<p class="add-to-cart">
		<a rel="{$URL}{seo controller=productaddcartbox}/{$items[i].id}" href="{$URL}{seo controller=productcart}/{$items[i].seo}" class="button-red">{trans}TXT_ADD_TO_CART{/trans}</a>
	</p>
	{else}
	<p class="request-quote">
		<a href="{$URL}{seo controller=contact}/{$items[i].id}" class="button-green">{trans}TXT_SEND_YOUR_QUERY{/trans}</a>
	</p>
	{/if}
</li>
{else}
<li>
	<h4>
		<a href="{$URL}{seo controller=productcart}/{$items[i].seo}" title="{$items[i].name}">
			<span class="image">
				<img class="mainphoto"  src="{$items[i].photo}" alt="{$items[i].name}"/>
				{if $items[i].new == 1}<img class="novelty" src="{$DESIGNPATH}_images_frontend/core/icons/product-novelty.png" alt="Nowość!" title="Nowość!">{/if}
			</span>
			<span class="name">{$items[i].name|truncate:70}</span>
		</a>
		{if $catalogmode == 0 && $items[i].pricenetto > 0}
			{if $showtax == 0}
			<span class="price">{$items[i].pricenetto}</span>
			{/if}
			{if $showtax == 1}
				<span class="price">{$items[i].price}</span>
			{/if}
			{if $showtax == 2}
				<span class="price">{$items[i].price}</span> <span class="price netto">{$items[i].pricenetto} {trans}TXT_NETTO{/trans}</span>
			{/if}	
		{else}
			<span class="price">{trans}TXT_REQUEST_QUOTE{/trans}</span>
		{/if}	
	</h4>
	{if $enableopinions == 1}
		{if $items[i].opinions > 0}
		<p class="rating">
			{if $enablerating > 0}<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-{$items[i].rating}.png" alt="Ocena Klientów: {$items[i].rating}"/></span>{/if}
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}#product-opinions">Przeczytaj {$items[i].opinions} opinii</a>
		</p>
		{else}
		<p class="rating">
			{if $enablerating > 0}<span class="stars"><img src="{$DESIGNPATH}_images_frontend/core/icons/stars-0.png" alt="Ocena Klientów: 0"/></span>{/if}
			<a href="{$URL}{seo controller=productcart}/{$items[i].seo}#product-opinions">{trans}TXT_NO_PRODUCT_REVIEWS{/trans}</a>
		</p>
		{/if}
	{/if}
	<div class="description">
		<p>{$items[i].shortdescription}<br /><br /><a class="read-more" href="{$URL}{seo controller=productcart}/{$items[i].seo}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
	{if $catalogmode == 0 && $items[i].pricenetto > 0}
	<p class="add-to-cart">
		<a rel="{$URL}{seo controller=productaddcartbox}/{$items[i].id}" href="{$URL}{seo controller=productcart}/{$items[i].seo}" class="button-red">{trans}TXT_ADD_TO_CART{/trans}</a>
	</p>
	{else}
	<p class="request-quote">
		<a href="{$URL}{seo controller=contact}/{$items[i].id}" class="button-green">{trans}TXT_SEND_YOUR_QUERY{/trans}</a>
	</p>
	{/if}
</li>
{/if}
{/section}