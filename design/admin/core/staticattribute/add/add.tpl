<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/atributes-add.png" alt=""/>{trans}TXT_ADD_ATTRIBUTE_PRODUCT{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}staticattribute/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_ATTRIBUTE_PRODUCTS_LIST{/trans}" alt="{trans}TXT_ATTRIBUTE_PRODUCTS_LIST{/trans}"/></span></a></li>
	<li><a href="#add_staticattribute" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#add_staticattribute" rel="submit[next]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_SAVE_AND_ADD_ANOTHER{/trans}</span></a></li>
	<li><a href="#add_staticattribute" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
  