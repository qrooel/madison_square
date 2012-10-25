{if isset($cms[0])}
	{section name=i loop=$cms}
	
	{if isset($cms[i].undercategorybox[0])}
		<div class="layout-box-content"><h3>{trans}TXT_UNDER_CATEGORY{/trans} :</h3>
			<ul>{section name=cat loop=$cms[i].undercategorybox}
				<li><a href="{$URL}{seo controller=staticcontent}/{$cms[i].undercategorybox[cat].id}">{$cms[i].undercategorybox[cat].name}</a></li>
			{/section}
			</ul>
		</div>
	{/if}
	{if count($cms) > 1}
		<h3>{$cms[i].topic}</h3>
	{/if}
		{$cms[i].content}
	{/section}
{else}
	{if count($cmscategories) > 0}
	<div class="categories-list" >
		<ul>
		{section name=i loop=$cmscategories}
		<li style="list-style-type: none;">
			<a href="{$URL}{seo controller=staticcontent}/{$cmscategories[i].id}">
				<h2>{$cmscategories[i].name}</h2>
			</a>
		</li>
		{/section}
		</ul>
</div>
	{else}
		<h3>{trans}ERR_CMS_NO_EXIST{/trans}</h3>
	{/if}	
{/if}
