<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:09
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/contactbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/contactbox/index/index.tpl', 9, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['sendContact'] ) && ( $this->_tpl_vars['sendContact'] == 1 )): ?>
<script>
<?php echo '
GMessage(\''; ?>
Twoje zapytanie zostało wysłane<?php echo '\',\'\');
'; ?>

</script>
<?php endif; ?> 

<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>
 