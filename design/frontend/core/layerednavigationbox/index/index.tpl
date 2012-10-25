<form class="filter">
{if count($ranges) > 0}
<h3>{trans}TXT_PRICE{/trans}</h3>
<div class="filter-price">
	<input type=hidden name="tier" id="tier" value="0-99999" />
	{section name=r loop=$ranges}
	<p><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=$ranges[r].step producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes page=$currentPage}" class="price{if $currentPrice == $ranges[r].step} active{/if}">{$ranges[r].label} {$currencySymbol}</a></p>
	{/section}
	<p><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=od0do99999 producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes page=$currentPage}" class="price{if $currentPrice == '' || $currentPrice == 'od0do99999'} active{/if}">{trans}TXT_ALL{/trans}</a></p>
</div>
{/if}
{if count($producers) > 0}
<h3>{trans}TXT_PRODUCER{/trans}</h3>
<div class="filter-checkboxes">
{section name=p loop=$producers}
	{if in_array($producers[p].seo, $currentProducers)}
	<div class="filtration selected">
	<p><strong>{$producers[p].name}</strong><a href="{$URL}{seo controller=$currentController seo=$currentSeo removeproducer=$producers[p].seo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes page=$currentPage}"><img src="{$DESIGNPATH}_images_frontend/core/icons/icon-close.png"></a></p>
	</div>
	{else}
	<div class="filtration">
	<p><a href="{$URL}{seo controller=$currentController seo=$currentSeo addproducer=$producers[p].seo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes page=$currentPage}">{$producers[p].name}</a></p>
	</div>
	{/if}
{/section}
</div>
{/if}
{foreach from=$groups item=group key=key}
<h3>{$group.name}</h3>
<div class="filter-checkboxes">
{foreach from=$group.attributes item=attribute key=k}
{assign var="name" value="g$key-$k"}
{if in_array($name, $currentAttributes)}
<div class="filtration selected">
<p><strong>{$attribute}</strong><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes group=$key removeattribute=$k}"><img src="{$DESIGNPATH}_images_frontend/core/icons/icon-close.png"></a></p>
</div>
{else}
<div class="filtration">
<p><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes group=$key addattribute=$k}">{$attribute}</a></p>
</div>
{/if}
{/foreach}
</div>
{/foreach}

{foreach from=$staticattributes item=staticattribute key=key}
<h3>{$staticattribute.name}</h3>
<div class="filter-checkboxes">
{foreach from=$staticattribute.attributes item=attribute key=k}
{assign var="name" value="s$key-$k"}
{if in_array($name, $currentStaticAttributes)}
<div class="filtration selected">
<p><strong>{$attribute}</strong><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes staticgroup=$key removestaticattribute=$k}"><img src="{$DESIGNPATH}_images_frontend/core/icons/icon-close.png"></a></p>
</div>
{else}
<div class="filtration">
<p><a href="{$URL}{seo controller=$currentController seo=$currentSeo price=$currentPrice producers=$currentProducers attributes=$currentAttributes staticattributes=$currentStaticAttributes staticgroup=$key addstaticattribute=$k}">{$attribute}</a></p>
</div>
{/if}
{/foreach}
</div>
{/foreach}

</form>