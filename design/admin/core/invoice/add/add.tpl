<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/invoice-add.png" alt=""/>{trans}TXT_INVOICE_ADD{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}invoice/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_GROUPS_LIST{/trans}" alt="{trans}TXT_GROUPS_LIST{/trans}"/></span></a></li>
	<li><a href="#add_invoice" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#add_invoice" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}