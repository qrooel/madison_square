<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/news-add.png" alt=""/>{trans}TXT_ADD_NEWS{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}news/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_NEWS{/trans}" alt="{trans}TXT_NEWS{/trans}"/></span></a></li>
	<li><a href="#add_news" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#add_news" rel="submit[next]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_SAVE_AND_ADD_ANOTHER{/trans}</span></a></li>
	<li><a href="#add_news" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}
<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			
			$(document).ready(function() {
				$.each(GCore.aoLanguages,function(l,language){
	              var topic = "#required_data__language_data__"+language.id+"__topic";
	              var seo = "#required_data__language_data__"+language.id+"__seo";
	              $(topic).bind('change',function(){
	              	xajax_doAJAXCreateSeo({
						name: $(this).val(),
						language: language.id
					}, GCallback(function(eEvent) {
						$(seo).val(eEvent.seo);
					}));
	                  
	              });
	            });
            });

			
		/*]]>*/
	{/literal}
</script>
  