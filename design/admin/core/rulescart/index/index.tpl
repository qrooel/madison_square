<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/rulescart-list.png" alt=""/>{trans}TXT_RULES_CART_LIST{/trans}</h2>

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

<div class="block">
	{fe_form form=$tree render_mode="JS"}
</div>
