<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/category-list.png" alt=""/>{trans}TXT_CATEGORY_LIST{/trans}</h2>
<ul class="possibilities">
	<li><a href="#" id="refresh" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/datagrid/refresh.png" alt=""/>{trans}TXT_REFRESH_SEO{/trans}</span></a></li>
</ul>
<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			function openCategoryEditor(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + sId;
				}
			};
			$(document).ready(function() {
				$('#refresh').click(function(){
					return xajax_doAJAXRefreshSeoCategory();
				});
			});
		/*]]>*/
	{/literal}
</script>

<div class="block">
	{fe_form form=$tree render_mode="JS"}
</div>
