<?php /* Smarty version 2.6.19, created on 2012-10-10 01:40:16
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/producer/add/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/producer/add/add.tpl', 8, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/producer-add.png" alt=""/>Dodaj producenta</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
producer/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Producenci" alt="Producenci"/></span></a></li>
	<li><a href="#add_producer" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#add_producer" rel="submit[next]" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Zapisz i dodaj następny</span></a></li>
	<li><a href="#add_producer" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i zakończ</span></a></li>
</ul>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

<script type="text/javascript">
<?php echo '
$(document).ready(function() {
	$.each(GCore.aoLanguages,function(l,language){
    	var name = "#required_data__language_data__"+language.id+"__name";
        var seo = "#required_data__language_data__"+language.id+"__seo";
        $(name).bind(\'change\',function(){
        	xajax_doAJAXCreateSeo({
				name: $(name).val()
			}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		});

        if($(seo).val() == \'\'){
        	xajax_doAJAXCreateSeo({
				name: $(name).val()
        	}, GCallback(function(eEvent) {
				$(seo).val(eEvent.seo);
			}));
		}
	}); 
});
'; ?>

</script> 