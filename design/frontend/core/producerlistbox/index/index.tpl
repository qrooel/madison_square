{if ($showdescription == 1 && $producer.description != '') || ($producer.photo != '' && $showphoto == 1)}
<div class="category-description">
{if $producer.photo != '' && $showphoto == 1}
<img src="{$producer.photo}" alt="{$producer.name}"/>
{/if}

{if $producer.description !=''  && $showdescription == 1}
	{$producer.description}
{/if}
</div>
{/if}

{if $products !==''}
<div class="filter" id="filter">
<div class="display">
{if $view == 0}
	<a href="{$URL}{seo controller=producerlist seo=$producer.seo view=1 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_LIST{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-list.png"></a>
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid-active.png" title="{trans}TXT_VIEW_GRID{/trans}">
{else}
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-list-active.png" title="{trans}TXT_VIEW_LIST{/trans}">
	<a href="{$URL}{seo controller=producerlist seo=$producer.seo view=0 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_GRID{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid.png"></a>
{/if}
 </div>
<div class="field-select"> 
	<label>{trans}TXT_SORT_RESULTS{/trans}</label> 
    <span class="field"> 
	    <select name="order" id="order" onchange="location.href=this.value"> 
	    	{foreach from=$sorting item=sorting key=key}
	    		<option value="{$URL}{seo controller=producerlist seo=$producer.seo page=1 sort=$key dir=asc price=$priceRange}" {if $key == $orderBy && $orderDir == 'asc'}selected{/if}>{$sorting} - {trans}TXT_ASC{/trans}</option> 
	    		<option value="{$URL}{seo controller=producerlist seo=$producer.seo page=1 sort=$key dir=desc price=$priceRange}" {if $key == $orderBy && $orderDir == 'desc'}selected{/if}>{$sorting} - {trans}TXT_DESC{/trans}</option> 
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
	<p style="padding-top: 5px;padding-left: 5px;">{trans}ERR_EMPTY_PRODUCT_LIST{/trans}</p>
{/if}
