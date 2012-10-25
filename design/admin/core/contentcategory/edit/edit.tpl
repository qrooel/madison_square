<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/contentcategory-edit.png" alt=""/>{trans}TXT_EDIT_CONTENT_CATEGORY{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}contentcategory/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_CONTENTCATEGORY_LIST{/trans}" alt="{trans}TXT_CONTENTCATEGORY_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_contentcategory" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_contentcategory" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
<script type="text/javascript">
{literal}
$(document).ready(function() {
	$('#reset-parent').click(function(){
		$('input[type="radio"]').each(function(){
			$(this).attr('checked','');  
		});
		$('input[name="required_data[contentcategoryid]"]').remove();
	});	
});
{/literal}
</script>
  