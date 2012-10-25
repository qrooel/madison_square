<div style="width:50%;float:left;">
<h2>{trans}TXT_CATEGORIES{/trans}</h2>
<ul>
{section name=categoryId loop=$categories}
		<li style="list-style-type: none;">
			<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].seo}">
					<h2>{$categories[categoryId].label}</h2>
			</a>
			{if count($categories[categoryId].children) > 0}
				<ul>
					{section name=subcategoryId loop=$categories[categoryId].children}
					<li style="list-style-type: none;">
						<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].children[subcategoryId].seo}">
						{$categories[categoryId].children[subcategoryId].label}
						</a>
						{if count($categories[categoryId].children[subcategoryId].children) > 0}
							<ul>
								{section name=subThirdcategoryId loop=$categories[categoryId].children[subcategoryId].children}
								<li style="list-style-type: none;">
									<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].children[subcategoryId].children[subThirdcategoryId].seo}">
									{$categories[categoryId].children[subcategoryId].children[subThirdcategoryId].label}
									</a>
								</li>
								{/section}
							</ul>
						{/if}
					</li>
					{/section}
				</ul>
			{/if}
		</li>
	{/section}
</ul>
</div>
<div style="width:50%;float:left;">
<h2>{trans}TXT_BASIC_INFORMATION{/trans}</h2>
<ul>
{section name=cat loop=$pages}
	<li style="list-style-type: none;"><a href="{$URL}{seo controller=staticcontent}/{$pages[cat].id}/{$pages[cat].seo}"><h2>{$pages[cat].name}</h2></a>
	{if count($pages[cat].children) > 0}
	<ul>
	{section name=under loop=$pages[cat].children}
		<li style="list-style-type: none;"><a href="{$URL}{seo controller=staticcontent}/{$pages[cat].children[under].id}/{$pages[cat].children[under].seo}">{$pages[cat].children[under].name}</a></li>
	{/section}
	</ul>
	{/if}
	</li>
{/section}
</ul>
</div>