<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
	
		var ChangeContentTransMail = function(oData) {
			var gField = oData.gForm.GetField(oData.sFieldTarget);
			if (gField != undefined) {
				xajax_SetContentTransMail({
					id: oData.sValue
				}, GCallback(function(eEvent) {
					return true;
				}));
			}
		};
		
		/*]]>*/
	{/literal}
</script>

<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/notification.png" alt=""/>{trans}TXT_NOTIFICATION_EDIT{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}substitutedservice/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_SUBSTITUTED_SERVICE_LIST{/trans}" alt="{trans}TXT_SUBSTITUTED_SERVICE_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_substitutedservice" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}