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

<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/notification.png" alt=""/>{trans}TXT_NOTIFICATION_ADD{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}substitutedservice/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_SUBSTITUTED_SERVICE_LIST{/trans}" alt="{trans}TXT_SUBSTITUTED_SERVICE_LIST{/trans}"/></span></a></li>
	<li><a href="#add_substitutedservice" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#add_substitutedservice" rel="submit[next]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/add.png" alt=""/>{trans}TXT_SAVE_AND_ADD_ANOTHER{/trans}</span></a></li>
	<li><a href="#add_substitutedservice" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
</ul>
{fe_form form=$form render_mode="JS"}