{if isset($clientdata)}
	<div id="cloud">
		{section name=i loop=$tags}
			<a class="tag{$tags[i].percentage}" href="{$URL}{seo controller=producttags}/{$tags[i].idtags}">{$tags[i].name}</a>
		{/section}
	</div>
{/if}