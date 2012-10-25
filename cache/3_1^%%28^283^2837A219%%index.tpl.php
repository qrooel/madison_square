<?php /* Smarty version 2.6.19, created on 2012-10-08 09:59:03
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/category/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/category/index/index.tpl', 26, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/category-list.png" alt=""/>Lista kategorii</h2>
<ul class="possibilities">
	<li><a href="#" id="refresh" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/datagrid/refresh.png" alt=""/>Odśwież strukturę SEO</span></a></li>
</ul>
<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			function openCategoryEditor(sId) {
				if (sId == undefined) {
					window.location = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/\';
				}
				else {
					window.location = \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '/edit/\' + sId;
				}
			};
			$(document).ready(function() {
				$(\'#refresh\').click(function(){
					return xajax_doAJAXRefreshSeoCategory();
				});
			});
		/*]]>*/
	'; ?>

</script>

<div class="block">
	<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['tree'],'render_mode' => 'JS'), $this);?>

</div>