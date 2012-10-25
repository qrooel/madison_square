{if count($productCart)>0} 
							<ul>
							{foreach from=$productCart item=product key=key}
								<li>
									<h4>
										{if ($productCart[$key].standard == 1)}
										<a href="{$URL}{seo controller=productcart}/{$productCart[$key].seo}">
											<span class="name">{$productCart[$key].name}</span>
											<span class="price">{price}{$productCart[$key].qtyprice}{/price}</span>
										</a>
										{/if}
										{if ($productCart[$key].attributes) <> NULL}
											{foreach from=$productCart[$key].attributes item=attribprod}
											<a href="{$URL}{seo controller=productcart}/{$attribprod.seo}">
												<span class="name">{$attribprod.name}</span>
												<span class="price">{price}{$attribprod.qtyprice}{/price}</span>
											</a>
											{/foreach}
										{/if}
										
									</h4>
								</li>
							{/foreach}
							</ul>
							{else}
							<p class="empty">{trans}TXT_EMPTY_CART{/trans}</p>
							{/if}
							
							{if count($productCart)>0} 
							<dl>
								<dt>{trans}TXT_PRODUCTS_ON_CART{/trans}:</dt><dd>{$count} {trans}TXT_QTY{/trans}</dd>
								<dt>{trans}TXT_PRODUCT_SUBTOTAL{/trans}:</dt><dd>{price}{$globalPrice}{/price}</dd>
							</dl>
							<p class="place-order">
								<a href="{$URL}{seo controller=cart}" class="button-red">{trans}TXT_SHOW_CART{/trans}</a><br/>
							</p>
							{/if}