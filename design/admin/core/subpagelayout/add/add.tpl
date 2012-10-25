<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/subpagelayout-add.png" alt=""/>{trans}TXT_SUBPAGE_LAYOUT_ADD{/trans}</h2>
<ul class="possibilities">
{if isset($list) && $list == 1}
	<li><a href="{$URL}subpagelayout/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_SUBPAGE_LAYOUT_LIST{/trans}" alt="{trans}TXT_SUBPAGE_LAYOUT_LIST{/trans}"/></span></a></li>
</ul>
{fe_form form=$emptyList render_mode="JS"}
{else}
	<li><a href="{$URL}subpagelayout/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_SUBPAGE_LAYOUT_LIST{/trans}" alt="{trans}TXT_SUBPAGE_LAYOUT_LIST{/trans}"/></span></a></li>
		<li><a href="#add_subpagelayout" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
		<li><a href="#add_subpagelayout" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
	</ul>
	
	{fe_form form=$form render_mode="JS"}

	<script type="text/javascript">
		{literal}
			/*<![CDATA[*/
				
				var checkForDuplicates = GEventHandler(function(eEvent) {
					var jSelects = $('#columns_data > .GFormRepetition select');
					var iSelects = jSelects.length;
					for (var i = 0; i < iSelects; i++) {
						var jSelect1 = jSelects.eq(i);
						for (var j = i + 1; j < iSelects; j++) {
							var jSelect2 = jSelects.eq(j);
							if (jSelect1.val() == jSelect2.val()) {
								GCore.StopWaiting();
								GError('Wykryto duplikaty', 'Na jednej podstronie nie może wystąpić kilka takich samych boksów. Zduplikowane boksy to: "' + jSelect1.find('option:selected').text() + '"');
								return false;
							}
						}
					}
					return true;
				});
				
				GCore.OnLoad(function() {
					$('#add_subpagelayout').submit(checkForDuplicates);
				});
				
			/*]]>*/
		{/literal}
	</script>
	
{/if}
