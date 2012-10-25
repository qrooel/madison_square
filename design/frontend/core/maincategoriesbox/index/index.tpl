<div class="category-list clear-fix">
{if count($maincategories) > 0}
<ul>
{section name=categoryId loop=$maincategories}
<li>
	<a href="{$URL}{if $maincategories[categoryId].url !=''}{$maincategories[categoryId].url}{else}{seo controller=categorylist}/{$maincategories[categoryId].seo}{/if}">
		{if isset($maincategories[categoryId].photo) &&  $maincategories[categoryId].photo !=''}
			<img src="{$maincategories[categoryId].photo}" alt="{$maincategories[td].name}"/>
		{else}
			<img src="{$DESIGNPATH}_gallery/_100_100/1.png" alt="{$maincategories[td].name}"/>
		{/if}
	</a>
	<div class="category_name">
		<a href="{$URL}{if $maincategories[categoryId].url !=''}{$maincategories[categoryId].url}{else}{seo controller=categorylist}/{$maincategories[categoryId].seo}{/if}">{$maincategories[categoryId].name}</a>
	</div>
</li>
{/section}
</ul>
{/if}
</div>
				
			
			
