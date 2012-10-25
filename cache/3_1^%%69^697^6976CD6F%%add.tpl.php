<?php /* Smarty version 2.6.19, created on 2012-10-08 10:13:58
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/news/add/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/news/add/add.tpl', 8, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/news-add.png" alt=""/>Dodaj</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
news/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Aktualności" alt="Aktualności"/></span></a></li>
	<li><a href="#add_news" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#add_news" rel="submit[next]" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/add.png" alt=""/>Zapisz i dodaj następny</span></a></li>
	<li><a href="#add_news" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i zakończ</span></a></li>
</ul>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

<script type="text/javascript">
	<?php echo '
		/*<![CDATA[*/
			
			$(document).ready(function() {
				$.each(GCore.aoLanguages,function(l,language){
	              var topic = "#required_data__language_data__"+language.id+"__topic";
	              var seo = "#required_data__language_data__"+language.id+"__seo";
	              $(topic).bind(\'change\',function(){
	              	xajax_doAJAXCreateSeo({
						name: $(this).val(),
						language: language.id
					}, GCallback(function(eEvent) {
						$(seo).val(eEvent.seo);
					}));
	                  
	              });
	            });
            });

			
		/*]]>*/
	'; ?>

</script>
  