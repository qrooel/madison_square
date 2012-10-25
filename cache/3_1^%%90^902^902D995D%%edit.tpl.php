<?php /* Smarty version 2.6.19, created on 2012-10-09 07:43:05
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/subpagelayout/edit/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/subpagelayout/edit/edit.tpl', 11, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/subpagelayout-edit.png" alt=""/>Edycja układu podstron</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
subpagelayout/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Użyj globalnej" alt="Użyj globalnej"/></span></a></li>
	<?php if ($this->_tpl_vars['viewSpecific']): ?>
		<li><a href="#" class="button" rel="use-global"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/delete-2.png" title="Użyj globalnej" alt="Użyj globalnej"/>Użyj globalnej</span></a></li>
	<?php endif; ?>
	<li><a href="#edit_subpagelayout" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#edit_subpagelayout" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz</span></a></li>
</ul>

<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>


<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			
			var checkForDuplicates = GEventHandler(function(eEvent) {
				var jSelects = $(\'#columns_data > .GFormRepetition select\');
				var iSelects = jSelects.length;
				for (var i = 0; i < iSelects; i++) {
					var jSelect1 = jSelects.eq(i);
					for (var j = i + 1; j < iSelects; j++) {
						var jSelect2 = jSelects.eq(j);
						if (jSelect1.val() == jSelect2.val()) {
							GCore.StopWaiting();
							GError(\'Wykryto duplikaty\', \'Na jednej podstronie nie może wystąpić kilka takich samych boksów. Zduplikowane boksy to: "\' + jSelect1.find(\'option:selected\').text() + \'"\');
							return false;
						}
					}
				}
				return true;
			});
			
			var disbandViewSpecific = GEventHandler(function(eEvent) {
				xajax_DeleteSubpageLayout({
					idsubpagelayout: \''; ?>
<?php echo $this->_tpl_vars['subpageLayout']['id']; ?>
<?php echo '\'
				}, GCallback(function(eEvent) {
					location.href = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
subpagelayout/index<?php echo '\';
				}));
				return false;
			});
			
			GCore.OnLoad(function() {
				$(\'#edit_subpagelayout\').submit(checkForDuplicates);
				$(\'a[rel="use-global"]\').click(disbandViewSpecific);
			});
			
		/*]]>*/
	'; ?>

</script>