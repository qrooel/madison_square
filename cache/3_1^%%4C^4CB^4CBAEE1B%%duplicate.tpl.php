<?php /* Smarty version 2.6.19, created on 2012-10-10 02:00:27
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/duplicate/duplicate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/product/duplicate/duplicate.tpl', 7, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/product-duplicate.png" alt=""/>Duplikacja produktu</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
product/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista produktów" alt="Lista produktów"/></span></a></li>
	<li><a href="#duplicate_product" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#duplicate_product" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz</span></a></li>
</ul>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

<script type="text/javascript">
    <?php echo '
           /*<![CDATA[*/
    function liveUrlTitle(title)
       {
           var defaultTitle = \'\';
           var NewText = title;
                      if (defaultTitle != \'\')
           {
               if (NewText.substr(0, defaultTitle.length) == defaultTitle)
               {
                   NewText = NewText.substr(defaultTitle.length)
               }               }
                      NewText = NewText.toLowerCase();
           var separator = "-";
                      if (separator != "_")
           {
               NewText = NewText.replace(/\\_/g, separator);
           }
           else
           {
               NewText = NewText.replace(/\\-/g, separator);
           }
              // Foreign Character Attempt
                      var NewTextTemp = \'\';
           for(var pos=0; pos<NewText.length; pos++)
           {
               var c = NewText.charCodeAt(pos);
                              if (c >= 32 && c < 128)
               {
                   NewTextTemp += NewText.charAt(pos);
               }
               else
               {
                   if (c == \'223\') {NewTextTemp += \'ss\'; continue;}
               if (c == \'224\') {NewTextTemp += \'a\'; continue;}
               if (c == \'225\') {NewTextTemp += \'a\'; continue;}
               if (c == \'226\') {NewTextTemp += \'a\'; continue;}
               if (c == \'229\') {NewTextTemp += \'a\'; continue;}
               if (c == \'227\') {NewTextTemp += \'ae\'; continue;}
               if (c == \'230\') {NewTextTemp += \'ae\'; continue;}
               if (c == \'228\') {NewTextTemp += \'ae\'; continue;}
               if (c == \'231\') {NewTextTemp += \'c\'; continue;}
               if (c == \'232\') {NewTextTemp += \'e\'; continue;}
               if (c == \'233\') {NewTextTemp += \'e\'; continue;}
               if (c == \'234\') {NewTextTemp += \'e\'; continue;}
               if (c == \'235\') {NewTextTemp += \'e\'; continue;}
               if (c == \'236\') {NewTextTemp += \'i\'; continue;}
               if (c == \'237\') {NewTextTemp += \'i\'; continue;}
               if (c == \'238\') {NewTextTemp += \'i\'; continue;}
               if (c == \'239\') {NewTextTemp += \'i\'; continue;}
               if (c == \'241\') {NewTextTemp += \'n\'; continue;}
               if (c == \'242\') {NewTextTemp += \'o\'; continue;}
               if (c == \'243\') {NewTextTemp += \'o\'; continue;}
               if (c == \'244\') {NewTextTemp += \'o\'; continue;}
               if (c == \'245\') {NewTextTemp += \'o\'; continue;}
               if (c == \'246\') {NewTextTemp += \'oe\'; continue;}
               if (c == \'249\') {NewTextTemp += \'u\'; continue;}
               if (c == \'250\') {NewTextTemp += \'u\'; continue;}
               if (c == \'251\') {NewTextTemp += \'u\'; continue;}
               if (c == \'252\') {NewTextTemp += \'ue\'; continue;}
               if (c == \'255\') {NewTextTemp += \'y\'; continue;}
               if (c == \'257\') {NewTextTemp += \'aa\'; continue;}
               if (c == \'269\') {NewTextTemp += \'ch\'; continue;}
               if (c == \'275\') {NewTextTemp += \'ee\'; continue;}
               if (c == \'291\') {NewTextTemp += \'gj\'; continue;}
               if (c == \'299\') {NewTextTemp += \'ii\'; continue;}
               if (c == \'311\') {NewTextTemp += \'kj\'; continue;}
               if (c == \'316\') {NewTextTemp += \'lj\'; continue;}
               if (c == \'326\') {NewTextTemp += \'nj\'; continue;}
               if (c == \'353\') {NewTextTemp += \'sh\'; continue;}
               if (c == \'363\') {NewTextTemp += \'uu\'; continue;}
               if (c == \'382\') {NewTextTemp += \'zh\'; continue;}
               if (c == \'256\') {NewTextTemp += \'aa\'; continue;}
               if (c == \'268\') {NewTextTemp += \'ch\'; continue;}
               if (c == \'274\') {NewTextTemp += \'ee\'; continue;}
               if (c == \'290\') {NewTextTemp += \'gj\'; continue;}
               if (c == \'298\') {NewTextTemp += \'ii\'; continue;}
               if (c == \'310\') {NewTextTemp += \'kj\'; continue;}
               if (c == \'315\') {NewTextTemp += \'lj\'; continue;}
               if (c == \'325\') {NewTextTemp += \'nj\'; continue;}
               if (c == \'352\') {NewTextTemp += \'sh\'; continue;}
               if (c == \'362\') {NewTextTemp += \'uu\'; continue;}
               if (c == \'381\') {NewTextTemp += \'zh\'; continue;}
              
			   if (c == \'281\') {NewTextTemp += \'e\'; continue;}
			   if (c == \'261\') {NewTextTemp += \'a\'; continue;}
			   if (c == \'263\') {NewTextTemp += \'c\'; continue;}
			   if (c == \'322\') {NewTextTemp += \'l\'; continue;}
			   if (c == \'324\') {NewTextTemp += \'n\'; continue;}
			   if (c == \'347\') {NewTextTemp += \'s\'; continue;}
			   if (c == \'347\') {NewTextTemp += \'s\'; continue;}
			   if (c == \'378\') {NewTextTemp += \'z\'; continue;}
			   if (c == \'380\') {NewTextTemp += \'z\'; continue;}
                              }
           }
              NewText = NewTextTemp;
                      NewText = NewText.replace(\'/<(.*?)>/g\', \'\');
           NewText = NewText.replace(\'/\\&#\\d+\\;/g\', \'\');
           NewText = NewText.replace(\'/\\&\\#\\d+?\\;/g\', \'\');
           NewText = NewText.replace(\'/\\&\\S+?\\;/g\',\'\');
           NewText = NewText.replace(/[\'\\"\\?\\.\\!*$\\#@%;:,=\\(\\)\\[\\]]/g,\'\');
           NewText = NewText.replace(/\\s+/g, separator);
           NewText = NewText.replace(/\\//g, separator);
           NewText = NewText.replace(/[^a-z0-9-_]/g,\'\');
           NewText = NewText.replace(/\\+/g, separator);
           NewText = NewText.replace(/[-_]+/g, separator);
           NewText = NewText.replace(/\\&/g,\'\');
           NewText = NewText.replace(/-$/g,\'\');
           NewText = NewText.replace(/_$/g,\'\');
           NewText = NewText.replace(/^_/g,\'\');
           NewText = NewText.replace(/^-/g,\'\');
                      return NewText;           }

          $(document).ready(function() {
          $.each(GCore.aoLanguages,function(l,language){
              var name = "#basic_pane__language_data__"+language.id+"__name";
              var seo = "#basic_pane__language_data__"+language.id+"__seo";
              var keywordtitle = "#basic_pane__language_data__"+language.id+"__keywordtitle";
              $(name).bind(\'change keyup\',function(){
                  $(seo).val(liveUrlTitle($(this).val()));
                  $(keywordtitle).val($(this).val());
              });
              if($(seo).val() == \'\'){
               $(seo).val(liveUrlTitle($(name).val()));
              }
             }); 
       	});
    /*]]>*/
    '; ?>

   </script>