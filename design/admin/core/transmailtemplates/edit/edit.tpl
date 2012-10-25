<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			var ChangeTagsForThisAction = function(oData) {
				var gField = oData.gForm.GetField(oData.sFieldTarget);
				if (gField != undefined) {
					xajax_GetAllTagsForThisAction({
						id: oData.sValue
					}, GCallback(function(eEvent) {
						var aoValues = [];
						for (var j in eEvent.data) {
							aoValues.push({
								sCaption: eEvent.data[j][0],
								sValue: eEvent.data[j][1]
							});
						}
						gField.ChangeItems(aoValues, eEvent.title);
					}));
				}
			};
			
		/*]]>*/
	{/literal}
</script>

<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/newsletter-edit.png" alt=""/>{trans}TXT_EDIT_TEMPLATE{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}transmailtemplates/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_TRANSMAILS_LIST{/trans}" alt="{trans}TXT_TRANSMAILS_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_transmailtemplates" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_transmailtemplates" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}