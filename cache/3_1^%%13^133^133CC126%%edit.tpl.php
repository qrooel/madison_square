<?php /* Smarty version 2.6.19, created on 2012-10-08 22:00:18
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/layoutbox/edit/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/layoutbox/edit/edit.tpl', 74, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/layoutbox-edit.png" alt=""/>Edycja boksu</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
layoutbox/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista boksów" alt="Lista boksów"/></span></a></li>
	<li><a href="#edit_layoutbox" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#edit_layoutbox" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i zakończ</span></a></li>
	<li><a href="#edit_layoutbox" rel="submit[continue]" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i kontynuuj</span></a></li>
</ul>

<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			var iSchemeChanges = 0;
			var ChangeScheme = GEventHandler(function(eEvent) {
				if (iSchemeChanges++ < 1) {
					return;
				}
				var sSchemeId = eEvent.sValue;
				var agFields = eEvent.gForm.GetField(\'look\').m_agFields;
				var gSelect = eEvent.gForm.GetField(\'choose_template\');
				if (sSchemeId == \'\') {
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
								gField.SetValue(mValue[gField.m_oOptions.sCssAttribute][\'value\']);
							}
							else if (gField instanceof GFormFontStyle) {
								if (mValue[\'font\'] == undefined) {
									continue;
								}
								gField.SetValue(mValue[\'font\']);
							}
							else if (gField instanceof GFormColourSchemePicker) {
								if (mValue[\'background\'] == undefined) {
									continue;
								}
								gField.SetValue(mValue[\'background\']);
							}
							else if (gField instanceof GFormBorder) {
								if (mValue[\'border\'] == undefined) {
									continue;
								}
								gField.SetValue(mValue[\'border\']);
							}
							else if (gField instanceof GFormLocalFile) {
								if (mValue[\'icon\'] == undefined) {
									continue;
								}
								gField.SetValue(mValue[\'icon\']);
							}
						}
					}
					gSelect.SetValue(\'\');
				})));
			});
		/*]]>*/
	'; ?>

</script>

<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>