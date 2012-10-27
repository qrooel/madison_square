{if count($categories) == 0}
	<p>{trans}ERR_EMPTY_MENUCATEGORY{/trans}</p>
{else}
{if $showall == 1}
	<ul>
		{section name=categoryId loop=$categories}
			{if ($hideempty == 0) || ($hideempty == 1 && $categories[categoryId].totalproducts > 0)}
			<li {if in_array($categories[categoryId].id, $path)}class="active"{/if}>
				<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].seo}" {if count($categories[categoryId].children) > 0}class="hasChildren top-level"{/if}>
					{$categories[categoryId].label} {if $showcount == 1}({$categories[categoryId].totalproducts}){/if}
				</a>
				{if count($categories[categoryId].children) > 0}
				<div class="submenu-x">
					<div class="submenu-wrapper">
						<ul>
							{section name=subcategoryId loop=$categories[categoryId].children}
							{if ($hideempty == 0) || ($hideempty == 1 && $categories[categoryId].children[subcategoryId].totalproducts > 0)}
								<li class="{if in_array($categories[categoryId].children[subcategoryId].id, $path)}current{/if}">
									<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].children[subcategoryId].seo}">
										<span>
											{$categories[categoryId].children[subcategoryId].label} {if $showcount == 1}({$categories[categoryId].children[subcategoryId].totalproducts}){/if}
										</span>
									</a>
								</li>
							{/if}
							{/section}
						</ul>
					</div>
				</div>
				{/if}
			</li>
			{/if}
		{/section}
	</ul>
{else}
	<ul>
		{section name=categoryId loop=$categories}
			{if ($hideempty == 0) || ($hideempty == 1 && $categories[categoryId].totalproducts > 0)}
				{if in_array($categories[categoryId].id, $include)}
				<li {if in_array($categories[categoryId].id, $path)}class="active"{/if}>
					<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].seo}" {if count($categories[categoryId].children) > 0}class="hasChildren top-level"{/if}>
						{$categories[categoryId].label} {if $showcount == 1}({$categories[categoryId].totalproducts}){/if}
					</a>
					{if count($categories[categoryId].children) > 0}
					<div class="submenu-x">
						<div class="submenu-wrapper">
							<ul>
								{section name=subcategoryId loop=$categories[categoryId].children}
								{if ($hideempty == 0) || ($hideempty == 1 && $categories[categoryId].children[subcategoryId].totalproducts > 0)}
									<li class="{if in_array($categories[categoryId].children[subcategoryId].id, $path)}current{/if}">
										<a href="{$URL}{seo controller=categorylist}/{$categories[categoryId].children[subcategoryId].seo}">
											<span>
												{$categories[categoryId].children[subcategoryId].label} {if $showcount == 1}({$categories[categoryId].children[subcategoryId].totalproducts}){/if}
											</span>
										</a>
									</li>
								{/if}
								{/section}
							</ul>
						</div>
					</div>
					{/if}
				</li>
				{/if}
			{/if}
		{/section}
	</ul>
{/if}
{/if}
