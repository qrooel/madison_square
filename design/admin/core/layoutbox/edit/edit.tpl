<h2><img src="{$DESIGNPATH}_images_panel/icons/modules/layoutbox-edit.png" alt=""/>{trans}TXT_LAYOUT_BOX_EDIT{/trans}</h2>
<ul class="possibilities">
	<li><a href="{$URL}layoutbox/index" class="button return"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/arrow-left-gray.png" title="{trans}TXT_LAYOUT_BOX_LIST{/trans}" alt="{trans}TXT_LAYOUT_BOX_LIST{/trans}"/></span></a></li>
	<li><a href="#edit_layoutbox" rel="reset" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/clean.png" alt=""/>{trans}TXT_START_AGAIN{/trans}</span></a></li>
	<li><a href="#edit_layoutbox" rel="submit" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_FINISH{/trans}</span></a></li>
	<li><a href="#edit_layoutbox" rel="submit[continue]" class="button"><span><img src="{$DESIGNPATH}_images_panel/icons/buttons/check.png" alt=""/>{trans}TXT_SAVE_AND_CONTINUE{/trans}</span></a></li>
</ul>

<script type="text/javascript">
	{literal}
		/*<![CDATA[*/
			var iSchemeChanges = 0;
			var ChangeScheme = GEventHandler(function(eEvent) {
				if (iSchemeChanges++ < 1) {
					return;
				}
				var sSchemeId = eEvent.sValue;
				var agFields = eEvent.gForm.GetField('look').m_agFields;
				var gSelect = eEvent.gForm.GetField('choose_template');
				if (sSchemeId == '') {
					return;
				}
				xajax_GetSchemeValues({
					id: sSchemeId,
					data: eEvent.mArgument
				}, GCallback(GEventHandler(function(eEvent) {
					if (eEvent.values != undefined) {
						for (var i in agFields) {
							var gField = agFields[i];
							if ((gField.m_oOptions.sSelector == undefined) || (eEvent.values[gField.m_oOptions.sSelector] == undefined)) {
								continue;
							}
							var sSelector = gField.m_oOptions.sSelector;
							var mValue = eEvent.values[gField.m_oOptions.sSelector];
							if ((gField instanceof GFormTextField) || (gField instanceof GFormSelect)) {
								if ((gField.m_oOptions.sCssAttribute == undefined) || (mValue[gField.m_oOptions.sCssAttribute] == undefined)) {
									continue;
								}
								gField.SetValue(mValue[gField.m_oOptions.sCssAttribute]['value']);
							}
							else if (gField instanceof GFormFontStyle) {
								if (mValue['font'] == undefined) {
									continue;
								}
								gField.SetValue(mValue['font']);
							}
							else if (gField instanceof GFormColourSchemePicker) {
								if (mValue['background'] == undefined) {
									continue;
								}
								gField.SetValue(mValue['background']);
							}
							else if (gField instanceof GFormBorder) {
								if (mValue['border'] == undefined) {
									continue;
								}
								gField.SetValue(mValue['border']);
							}
							else if (gField instanceof GFormLocalFile) {
								if (mValue['icon'] == undefined) {
									continue;
								}
								gField.SetValue(mValue['icon']);
							}
						}
					}
					gSelect.SetValue('');
				})));
			});
		/*]]>*/
	{/literal}
</script>

{fe_form form=$form render_mode="JS"}