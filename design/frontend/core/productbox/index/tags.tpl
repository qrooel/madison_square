{if count($tagsProduct) > 0}
<fieldset class="listing">
	<legend><span>{trans}TXT_PRODUCTS_TAGS{/trans}</span></legend>
	<div id="cloud">
	{section name=i loop=$tagsProduct}
		<a class="tag{$tagsProduct[i].percentage}" href="{$URL}{seo controller=producttags}/{$tagsProduct[i].idtags}">{$tagsProduct[i].tagsname}</a>
	{/section}
	</div>
</fieldset>
{/if}