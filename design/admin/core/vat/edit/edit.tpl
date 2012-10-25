<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/vat-edit.png" alt=""/>{trans}TXT_EDIT_VAT{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}vat/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_VAT_LIST{/trans}" alt="{trans}TXT_VAT_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_vat" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_vat" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
