<div class="filter" id="filter">
<div class="display">
{if $view == 0}
	<a href="{$URL}{seo controller=productsearch seo=$searchPhrase view=1 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_LIST{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-list.png"></a>
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid-active.png" title="{trans}TXT_VIEW_GRID{/trans}">
{else}
	<img src="{$DESIGNPATH}_images_frontend/core/icons/view-list-active.png" title="{trans}TXT_VIEW_LIST{/trans}">
	<a href="{$URL}{seo controller=productsearch seo=$searchPhrase view=0 page=$currentPage price=$priceRange producers=$currentProducers attributes=$currentAttributes }" title="{trans}TXT_VIEW_GRID{/trans}"><img src="{$DESIGNPATH}_images_frontend/core/icons/view-grid.png"></a>
{/if}
 </div>
<div class="field-select"> 
	<label>{trans}TXT_SORT_RESULTS{/trans}</label> 
    <span class="field"> 
	    <select name="order" id="order" onchange="location.href=this.value"> 
	    	{foreach from=$sorting item=sorting key=key}
	    		<option value="{$URL}{seo controller=productsearch seo=$searchPhrase page=1 sort=$key dir=asc price=$priceRange producers=$currentProducers attributes=$currentAttributes }" {if $key == $orderBy && $orderDir == 'asc'}selected{/if}>{$sorting} - {trans}TXT_ASC{/trans}</option> 
	    		<option value="{$URL}{seo controller=productsearch seo=$searchPhrase page=1 sort=$key dir=desc price=$priceRange producers=$currentProducers attributes=$currentAttributes }" {if $key == $orderBy && $orderDir == 'desc'}selected{/if}>{$sorting} - {trans}TXT_DESC{/trans}</option> 
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
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#filter').show();
});
{/literal}
</script>
