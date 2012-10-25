{if $currentCategory.description != '' || $currentCategory.shortdescription !='' || $currentCategory.photo != ''}
<div class="category-description">
{if $currentCategory.photo != ''}
<img src="{$currentCategory.photo}" alt="{$currentCategory.name}" />
{/if}

{if $currentCategory.description !=''}
	{$currentCategory.description}
{else}
	{$currentCategory.shortdescription}
{/if}

</div>
{/if}

{if count($subcategories) > 0}
<ul class="subcategories-list" style="clear: both;">
		{section name=categoryId loop=$subcategories}
		<li>
			<h4>
				<a href="{$URL}{if $subcategories[categoryId].url !=''}{$subcategories[categoryId].url}{else}{seo controller=categorylist}/{$subcategories[categoryId].seo}{/if}">
				{if isset($subcategories[categoryId].photo) &&  $subcategories[categoryId].photo !=''}<span class="image"><img src="{$subcategories[categoryId].photo}" alt="{$subcategories[td].name}"/></span>{/if}
					<span class="name">{$subcategories[categoryId].name}</span>
				</a>
			</h4>
			<div class="description">
				{$subcategories[categoryId].shortdescription}
				<p><a class="read-more" href="{$URL}{if $subcategories[categoryId].url !=''}{$subcategories[categoryId].url}{else}{seo controller=categorylist}/{$subcategories[categoryId].seo}{/if}">{trans}TXT_DISPLAY_PRODUCTS{/trans}</a></p>
			</p>
			</div>
		</li>
		{/section}
	</ul>
{/if}
{if $products !==''}
<div class="filter" id="filter">
<div class="display">
{if $view == 0}
	<a href="{$URL}{seo controller=categorylist seo=$currentCategory.seo view=1 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_LIST{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-list.png"></a>
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid-active.png" title="{trans}TXT_VIEW_GRID{/trans}">
{else}
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-list-active.png" title="{trans}TXT_VIEW_LIST{/trans}">
	<a href="{$URL}{seo controller=categorylist seo=$currentCategory.seo view=0 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_GRID{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid.png"></a>
{/if}
 </div>
<div class="field-select"> 
	<label>{trans}TXT_SORT_RESULTS{/trans}</label> 
    <span class="field"> 
	    <select name="order" id="order" onchange="location.href=this.value"> 
	    	{foreach from=$sorting item=sorting key=key}
	    		<option value="{$URL}{seo controller=categorylist seo=$currentCategory.seo page=1 sort=$key dir=asc price=$priceRange producers=$currentProducers attributes=$currentAttributes }" {if $key == $orderBy && $orderDir == 'asc'}selected{/if}>{$sorting} - {trans}TXT_ASC{/trans}</option> 
	    		<option value="{$URL}{seo controller=categorylist seo=$currentCategory.seo page=1 sort=$key dir=desc price=$priceRange producers=$currentProducers attributes=$currentAttributes }" {if $key == $orderBy && $orderDir == 'desc'}selected{/if}>{$sorting} - {trans}TXT_DESC{/trans}</option> 
	    	{/foreach}
	    </select> 
    </span> 
</div> 
</div>
<ul class="product-list {if $view == 0}list-grid{else}list-long{/if}">
	{$products}
</ul>
{if $showpagination == 1}
<div class="layout-box-footer">
	<div class="pagination">
		{$pagination}
	</div>
</div>
{/if}
{else}
	{if count($subcategories) == 0}
	<p style="padding-top: 5px;padding-left: 5px;">{trans}ERR_EMPTY_PRODUCT_LIST{/trans}</p>
	{/if}
{/if}

