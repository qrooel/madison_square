<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/category-edit.png" alt=""/>{trans}TXT_EDIT_CATEGORY{/trans}: {$categoryName}</h2>
<ul class="possibilities">
	<li><a href="{$URL}category/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_CATEGORY_LIST{/trans}" alt="{trans}TXT_CATEGORY_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_category" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_category" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>

<script type="text/javascript">
{literal}
			function openCategoryEditor(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + sId;
				}
			};
			
			function openCategoryEditorDuplicate(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/duplicate/' + sId;
				}
			};
			
$(document).ready(function() {
	$.each(GCore.aoLanguages,function(l,language){
	    var name = "#required_data__language_data__"+language.id+"__name";
	    var seo = "#required_data__language_data__"+language.id+"__seo";
	    
	    var sRefreshLink =  $('<img title="{/literal}{trans}TXT_REFRESH{/trans}{literal}" src="' + GCore.DESIGN_PATH + '_images_panel/icons/datagrid/refresh.png" />').css({
			cursor: 'pointer',
			'margin-top': '3px',
			'margin-left': '3px',
		});
		$(seo).parent().parent().append(sRefreshLink);

		sRefreshLink.click(function(){
			xajax_doAJAXCreateSeoCategory({
				name: $(name).val(),
				language: language.id
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		});

	    if($(seo).val() == ''){
	      	xajax_doAJAXCreateSeoCategory({
				name: $(name).val(),
				language: language.id
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
        }
	});
});
{/literal}
</script>

<div class="layout-two-columns">

	<div class="column narrow-collapsed">
		<div class="block">
			{fe_form form=$tree render_mode="JS"}
		</div>
	</div>

	<div class="column wide-collapsed">
		{fe_form form=$form render_mode="JS"}
	</div>
	
</div>
  