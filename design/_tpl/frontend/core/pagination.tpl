<div class="layout-box-footer">
	<div class="pagination">
		<ul>
			{section name=links loop=$dataset.totalPages}
				{if $smarty.section.links.first}
					{if $dataset.activePage == 1}
						<li class="previous disabled"><a href="#">Poprzednie</a></li>
					{else}
						<li class="previous"><a href="{$URL}{seo controller=$controller}/{$dataset.previousPage}">Poprzednie</a></li>
					{/if}
				{/if}
				<li {if $dataset.totalPages[links] == $dataset.activePage}class="active"{/if}><a id="$dataset.totalPages[links]" href="{$URL}{seo controller=$controller}/{$dataset.totalPages[links]}">{$dataset.totalPages[links]}</a></li>
				{if $smarty.section.links.last}
					{if $dataset.activePage == $dataset.lastPage}
						<li class="next disabled"><a href="#">Następne</a></li>
					{else}
						<li class="next"><a href="{$URL}{seo controller=$controller}/{$dataset.nextPage}">Następne</a></li>
					{/if}
				{/if}
			{/section}
		</ul>
	</div>
</div>