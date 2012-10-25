<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/pagescheme-edit.png" alt=""/>{trans}TXT_PAGE_SCHEME_EDIT{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}pagescheme/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_PAGE_SCHEME_TEMPLATES_LIST{/trans}" alt="{trans}TXT_PAGE_SCHEME_TEMPLATES_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_pagescheme" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_pagescheme" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
	<li><a href="#edit_pagescheme" rel="submit[continue]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_CONTINUE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}