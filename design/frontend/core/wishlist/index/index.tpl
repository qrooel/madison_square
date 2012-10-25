	<div id="content">

					<div class="block dark">	
					
					{if isset($clientdata)} 
					
						<h2>{trans}TXT_WISHLIST{/trans}</h2>
{if isset($wishlist[0])}
						<table class="cart" cellspacing="0">
							<thead>
								<tr>
									<th class="name">{trans}TXT_PRODUCT_NAME{/trans}:</th>
									<th class="price">{trans}TXT_PRICE{/trans}:</th>
									<th class="delete">{trans}TXT_ADD_TO_CART{/trans}:</th>
									<th class="delete">{trans}TXT_DELETE{/trans}:</th>
								</tr>
							</thead>
							
							{foreach from=$wishlist item=product key=key}	
								<!-- PRODUCT WITHOUT ATTRIBUTES -->
								{if ($wishlist[$key].productattributes == NULL)}
									<tr class="{cycle values="o,e"}">	
										<th scope="row" class="name">
											<a href="{$URL}productcart/index/{$wishlist[$key].idproduct}">
												<span class="picture">
												{if isset($wishlist[$key].photoid)}
													<img src="{$wishlist[$key].smallphoto}" alt="{trans}TXT_MAIN_PHOTO{/trans}"/>
												{else}
													{trans}ERR_MAIN_PHOTO{/trans}
												{/if}
												</span>
												<strong>{$wishlist[$key].name}</strong>
											</a>
										</th>
										
								
								<th class="price">		
									{if ($wishlist[$key].showtax) == 1}
									    <p>{$wishlist[$key].pricewithoutvat} {trans}TXT_CURRENCY{/trans} Netto</p>
									{elseif ($wishlist[$key].showtax) == 0}
									    <p>{$wishlist[$key].price}</span> {trans}TXT_CURRENCY{/trans} Brutto</span></p>
									{else}
										<p>{$wishlist[$key].price}</span> {trans}TXT_CURRENCY{/trans} {$wishlist[$key].pricewithoutvat} {trans}TXT_CURRENCY{/trans} Netto</p>
									{/if}	
								</th>		
										
										
										
										<th class="delete">
											<input type="submit" name="addToCart[{$wishlist[$key].name}]" value="{trans}TXT_ADD{/trans}" onClick="xajax_addProductToCart({$wishlist[$key].idproduct}, null, 1, {$wishlist[$key].stock}); return false;" />
										</th>
										<th class="delete">
											<input type="submit" name="delete[{$wishlist[$key].name}]" value="{trans}TXT_DELETE{/trans}" onClick="xajax_deleteProductFromWishList({$wishlist[$key].idproduct}, 0); return false;" />
										</th>
								</tr>
								{else}
								<!-- PRODUCT WITH ATTRIBUTES -->
									<tr class="{cycle values="o,e"}">	
										<th scope="row" class="name">
											<a href="{$URL}productcart/index/{$wishlist[$key].idproduct}">
												<span class="picture">
												{if isset($wishlist[$key].photoid)}
													<img src="{$wishlist[$key].smallphoto}" alt="{trans}TXT_MAIN_PHOTO{/trans}"/>
												{else}
													{trans}ERR_MAIN_PHOTO{/trans}
												{/if}
												</span>
												<strong>{$wishlist[$key].name}</strong>
											</a>
											{$wishlist[$key].productattributes}
										</th>
										<th class="price">
											<p>{$wishlist[$key].newprice} {trans}TXT_CURRENCY{/trans}</p>
										</th>
										<th class="delete">
											<input type="submit" name="addToCart[{$wishlist[$key].name}]" value="{trans}TXT_ADD{/trans}" onClick="xajax_addProductToCart({$wishlist[$key].idproduct}, {$wishlist[$key].productattributesetid}, 1, {$wishlist[$key].stock}); return false;" />
										</th>
										<th class="delete">
											<input type="submit" name="delete[{$wishlist[$key].name}]" value="{trans}TXT_DELETE{/trans}" onClick="xajax_deleteProductFromWishList({$wishlist[$key].idproduct}, {$wishlist[$key].productattributesetid}); return false;" />
											</th>
									</tr>
									
								{/if}
							{/foreach}
							</table>
{else}
	{trans}ERR_EMPTY_WISHLIST{/trans}
{/if}					
							
						{else}
							<h2>{trans}TXT_YOUR_ACCOUNT{/trans}: {trans}TXT_SETTINGS{/trans}</h2>
							<p>{trans}TXT_LOGIN_TO_YOUR_ACCOUNT{/trans}
							<a href="{$URL}clientlogin/index/"><strong>{trans}TXT_CLICK_HERE{/trans}</strong></a></p>
						{/if}
					</div>
				</div>