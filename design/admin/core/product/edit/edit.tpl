<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/product-edit.png" alt=""/>{trans}TXT_EDIT_PRODUCT{/trans} "{$productName}"</h2>
<ul class="possibilities">
   <li><a href="{$URL}product/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_PRODUCTS_LIST{/trans}" alt="{trans}TXT_PRODUCTS_LIST{/trans}"/></span></a></li>
   <li><a href="#edit_product" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
   <li><a href="#edit_product" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
   <li><a href="#edit_product" rel="submit[continue]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_CONTINUE{/trans}</span></a></li>
</ul>

{fe_form form=$form render_mode="JS"}
<script type="text/javascript">
{literal}
$(document).ready(function() {
	$.each(GCore.aoLanguages,function(l,language){
		var name = "#basic_pane__language_data__"+language.id+"__name";
		var seo = "#basic_pane__language_data__"+language.id+"__seo";
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