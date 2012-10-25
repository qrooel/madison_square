<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:42
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/add/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/add/add.tpl', 8, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/product-edit.png" alt=""/>Dodaj produkt</h2>
<ul class="possibilities">
   <li><a href="<?php echo $this->_tpl_vars['URL']; ?>
product/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista produktów" alt="Lista produktów"/></span></a></li>
   <li><a href="#add_product" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
   <li><a href="#add_product" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz</span></a></li>
</ul>

<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

 <script type="text/javascript">
    <?php echo '
           /*<![CDATA[*/

          $(document).ready(function() {
         	$.each(GCore.aoLanguages,function(l,language){
              var name = "#basic_pane__language_data__"+language.id+"__name";
              var seo = "#basic_pane__language_data__"+language.id+"__seo";
              var keywordtitle = "#basic_pane__language_data__"+language.id+"__keywordtitle";
              $(name).bind(\'change keyup\',function(){
            	  xajax_doAJAXCreateSeo({
						name: $(name).val()
				  }, GCallback(function(eEvent) {
						$(seo).val(eEvent.seo);
				  }));
            	  $(keywordtitle).val($(name).val());
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
    /*]]>*/
    '; ?>

   </script> 