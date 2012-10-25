<?php /* Smarty version 2.6.19, created on 2012-10-08 09:59:16
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/category/edit/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/category/edit/edit.tpl', 66, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/category-edit.png" alt=""/>Edycja kategorii: <?php echo $this->_tpl_vars['categoryName']; ?>
</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
category/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista kategorii" alt="Lista kategorii"/></span></a></li>
	<li><a href="#edit_category" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#edit_category" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz</span></a></li>
</ul>

<script type="text/javascript">
<?php echo '
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
			
			function openCategoryEditorDuplicate(sId) {
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
<?php echo '/duplicate/\' + sId;
				}
			};
			
$(document).ready(function() {
	$.each(GCore.aoLanguages,function(l,language){
	    var name = "#required_data__language_data__"+language.id+"__name";
	    var seo = "#required_data__language_data__"+language.id+"__seo";
	    
	    var sRefreshLink =  $(\'<img title="'; ?>
Odśwież<?php echo '" src="\' + GCore.DESIGN_PATH + \'_images_panel/icons/datagrid/refresh.png" />\').css({
			cursor: \'pointer\',
			\'margin-top\': \'3px\',
			\'margin-left\': \'3px\',
		});
		$(seo).parent().parent().append(sRefreshLink);

		sRefreshLink.click(function(){
			xajax_doAJAXCreateSeoCategory({
				name: $(name).val(),
				language: language.id
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		});

	    if($(seo).val() == \'\'){
	      	xajax_doAJAXCreateSeoCategory({
				name: $(name).val(),
				language: language.id
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
        }
	});
});
'; ?>

</script>

<div class="layout-two-columns">

	<div class="column narrow-collapsed">
		<div class="block">
			<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['tree'],'render_mode' => 'JS'), $this);?>

		</div>
	</div>

	<div class="column wide-collapsed">
		<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

	</div>
	
</div>
  