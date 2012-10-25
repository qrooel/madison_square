<h1><a href="{$URL}"><span><img src="design/_images_frontend/core/logos/{$SHOP_LOGO}" alt="{$SHOP_NAME}"/></span></a></h1>

	<div class="product-photos">
		{if isset($product.mainphoto.normal.path)}
		<img class="mainphoto" src="design/{$product.mainphoto.normal|replace:$DESIGNPATH:''}">
		{/if}
	</div>
	
	<div class="product-details">
		{if $product.producerphoto.small <> ''}
    	<a href="{if $product.producerurl != ''}{$URL}{seo controller=redirect}/{$product.producerurl}{else}#{/if}" target="_blank" class="producer-logo"><img src="design/{$product.producerphoto|replace:$DESIGNPATH:''}" alt="{$product.producername}"></a>    
    	{else}
    	<a href="{if $product.producerurl != ''}{$URL}{seo controller=redirect}/{$product.producerurl}{else}#{/if}" target="_blank" class="producer-logo">{$product.producername}</a>    
    	{/if}
    	
               </div>
           
           	   <div id="productTabs" class="layout-box withTabs"> 

           
           <div class="boxContent"> 
                <div id="product-description" class="tabContent"> 
                 	{$product.description}
                </div> 
                <div id="product-technical-data" class="tabContent"> 
                	{section name=i loop=$technicalData}
                	<fieldset class="listing">
                	<h2>{$technicalData[i].name}</h2>
                	<dl> 
						{section name=a loop=$technicalData[i].attributes}	
							 <dt>{$technicalData[i].attributes[a].name}</dt>
							 <dd>{$technicalData[i].attributes[a].value}</dd>
						{/section}	
					</dl> 
                	</fieldset>
                	
					
					{/section}
                </div> 
                
                 
                
           </div> 
        
       </div> 