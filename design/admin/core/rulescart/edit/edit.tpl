<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/promotion-edit.png" alt=""/>{trans}TXT_EDIT_RULE_CART{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}rulescart/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_RULES_CART_LIST{/trans}" alt="{trans}TXT_RULES_CART_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_rulescart" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_rulescart" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			function openRulesCartEditor(sId) {
				if (sId == undefined) {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/';
				}
				else {
					window.location = '{/literal}{$URL}{$CURRENT_CONTROLLER}{literal}/edit/' + sId;
				}
			};


		/*]]>*/
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
  