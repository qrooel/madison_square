<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/producer-edit.png" alt=""/>{trans}TXT_EDIT_PRODUCER{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}producer/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_PRODUCERS_LIST{/trans}" alt="{trans}TXT_PRODUCERS_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_producer" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_producer" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
<script type="text/javascript">
{literal}
$(document).ready(function() {
	$.each(GCore.aoLanguages,function(l,language){
		var name = "#required_data__language_data__"+language.id+"__name";
		var seo = "#required_data__language_data__"+language.id+"__seo";
		if($(seo).val() == ''){
			xajax_doAJAXCreateSeo({
				name: $(name).val()
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		}
		var sRefreshLink =  $('<img title="{/literal}{trans}TXT_REFRESH{/trans}{literal}" src="' + GCore.DESIGN_PATH + '_images_panel/icons/datagrid/refresh.png" />').css({
			cursor: 'pointer',
			'margin-top': '3px',
			'margin-left': '3px',
		});
		$(seo).parent().parent().append(sRefreshLink);

		sRefreshLink.click(function(){
			xajax_doAJAXCreateSeo({
				name: $(name).val()
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		});
  	});          
});
{/literal}
</script>