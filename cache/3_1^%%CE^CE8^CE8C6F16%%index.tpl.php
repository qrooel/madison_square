<?php /* Smarty version 2.6.19, created on 2012-10-08 09:39:31
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/globalsettings/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/admin/core/globalsettings/index/index.tpl', 6, false),)), $this); ?>
<h2><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/modules/configuration-list.png" alt=""/>Globalna konfiguracja</h2>
<ul class="possibilities">
	<li><a href="#edit_globalsettings" rel="reset" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/clean.png" alt=""/>Zacznij od nowa</span></a></li>
	<li><a href="#edit_globalsettings" rel="submit" class="button"><span><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_panel/icons/buttons/check.png" alt=""/>Zapisz i kontynuuj</span></a></li>
</ul>
<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>
