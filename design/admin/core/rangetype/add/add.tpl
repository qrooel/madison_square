<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/opinion-add.png" alt=""/>{trans}TXT_ADD_RANGETYPE{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}rangetype/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_RANGETYPE_LIST{/trans}" alt="{trans}TXT_RANGETYPE_LIST{/trans}"/></span></a></li>
	<li><a href="#add_rangetype" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#add_rangetype" rel="submit[next]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_SAVE_AND_ADD_ANOTHER{/trans}</span></a></li>
	<li><a href="#add_rangetype" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
  