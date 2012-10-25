<?php /* Smarty version 2.6.19, created on 2012-10-08 09:37:10
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/clientloginbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/clientloginbox/index/index.tpl', 9, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['loginerror'] )): ?>
<script>
<?php echo '
GError(\''; ?>
<?php echo $this->_tpl_vars['loginerror']; ?>
<?php echo '\',\'\');
'; ?>

</script>
<?php endif; ?> 

<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>

<?php if (isset ( $this->_tpl_vars['facebooklogin'] ) && $this->_tpl_vars['facebooklogin'] != ''): ?>
<div style="margin: 20px;">
<p>Zaloguj się korzystając ze swojego konta na Facebook</p>
<a href="<?php echo $this->_tpl_vars['facebooklogin']; ?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/buttons/facebook.png" /></a>
</div>
<?php endif; ?>