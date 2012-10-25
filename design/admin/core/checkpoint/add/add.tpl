<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/checkpoint.png" alt=""/>{trans}TXT_ADD_CHECKPOINTS{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}checkpoint/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_CHECKPOINTS{/trans}" alt="{trans}TXT_CHECKPOINTS{/trans}"/></span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
<script type="text/javascript">
{literal}
	$(document).ready(function(){
		$('.with-image').val('Dalej');
	});
{/literal}
</script>
