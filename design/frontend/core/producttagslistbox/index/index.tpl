
							
						{if count($productList)}	
						
						
						
						
						<ul class="product-list list-long">
							{section name=i loop=$productList}
							
								<li>
									<h4>
										<a href="{$URL}{seo controller=productcart}/{$productList[i].idproduct}">
											<span class="image"><img src="{$productList[i].photo}" alt=""/></span>
											<span class="name">{$productList[i].productname}</span>
											{if $showtax == 0}
												<span class="price">{$productList[i].pricewithoutvat}</span>
											{else}
												<span class="price">{$productList[i].price}</span>
											{/if}	
										</a>	
									</h4>
									
									<div class="description">
		<p>{$productList[i].shortdescription|truncate:250|strip_tags:false}<br /><br /><a class="read-more" href="{$URL}{seo controller=productcart}/{$productList[i].idproduct}">{trans}TXT_SHOW_ALL{/trans}</a></p>
	</div>
									<p class="add-to-cart">
										<a rel="{$URL}{seo controller=productaddcartbox}/{$productList[i].idproduct}" href="{$URL}{seo controller=productcart}/{$productList[i].idproduct}/" class="button-red">{trans}TXT_ADD_TO_CART{/trans}</a>
									</p>
								</li>
							{/section}
						</ul>	
					
					{else}
						<h2>{trans}ERR_EMPTY_PRODUCT_TAGS{/trans}</h2>
					{/if}
