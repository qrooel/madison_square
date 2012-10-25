<div class="carousel" id="{$boxId}">
	<ul>
		{$products}
	</ul>
	<p class="controls">
		<a class="previous" href="#"><img src="{$DESIGNPATH}_images_frontend/core/icons/showcase-left.png" alt=""/></a><a class="next" href="#"><img src="{$DESIGNPATH}_images_frontend/core/icons/showcase-right.png" alt=""/></a>
	</p>
</div>

<div class="bottom-tabs">
	<ul>
		{section name=i loop=$showcasecategories}
			<li><a href="{$URL}{boxparams category=$showcasecategories[i].id}"><span>{$showcasecategories[i].caption}</span></a></li>
		{/section}
	</ul>
</div>