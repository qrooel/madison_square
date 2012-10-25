<?php /* Smarty version 2.6.19, created on 2012-10-08 21:55:28
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/pagescheme/edit/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/pagescheme/edit/edit.tpl', 8, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/pagescheme-edit.png" alt=""/>Edycja szablonu stylów sklepu</h2>
<ul class="possibilities">
	<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
pagescheme/index" class="button return"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/arrow-left-gray.png" title="Lista szablonów CSS sklepu" alt="Lista szablonów CSS sklepu"/></span></a></li>
	<li><a href="#edit_pagescheme" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#edit_pagescheme" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i zakończ</span></a></li>
	<li><a href="#edit_pagescheme" rel="submit[continue]" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i kontynuuj</span></a></li>
</ul>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>