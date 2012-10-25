<?php /* Smarty version 2.6.19, created on 2012-10-08 09:37:05
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/registrationcartbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fe_form', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/registrationcartbox/index/index.tpl', 13, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/registrationcartbox/index/index.tpl', 28, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['enableregistration'] ) && $this->_tpl_vars['enableregistration'] == 1): ?>
<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?>
	<strong>Zalogowany użytkownik:</strong><br><br>
	<strong> <?php echo $this->_tpl_vars['clientdata']['firstname']; ?>
 <?php echo $this->_tpl_vars['clientdata']['surname']; ?>
 </strong>
<?php else: ?>
	<?php if (isset ( $this->_tpl_vars['registrationok'] )): ?>
	<p class="error"><strong>
		<?php echo $this->_tpl_vars['registrationok']; ?>

		Na wpisany podczas rejestracji E-mail, została wysłana wiadomość.
	</strong></p>
	<?php else: ?>
		<?php if (! isset ( $this->_tpl_vars['facebookRegister'] )): ?>
			<?php echo smarty_function_fe_form(array('form' => $this->_tpl_vars['form'],'render_mode' => 'JS'), $this);?>
 
			<?php if (isset ( $this->_tpl_vars['facebooklogin'] ) && $this->_tpl_vars['facebooklogin'] != ''): ?>
			<div style="margin: 20px;">
			<p>Zarejestruj się korzystając z konta Facebook</p>
			<a href="<?php echo $this->_tpl_vars['facebooklogin']; ?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/buttons/facebook.png" /></a>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['facebookRegister'] )): ?>
<div id="fb-root"></div>
<?php echo '
<div class="listing" style="margin-left: 7px;margin-bottom: 20px;">
<fb:registration fields="[{\'name\':\'name\'},{\'name\':\'email\'}, {\'name\':\'phone\',\'description\':\''; ?>
Telefon<?php echo '\',\'type\':\'text\'}]" redirect-uri="'; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'registrationcart'), $this);?>
<?php echo '"></fb:registration>
</div> 
'; ?>

<script>
<?php echo '
	window.fbAsyncInit = function() {
    	FB.init({
          appId: \''; ?>
<?php echo $this->_tpl_vars['faceboookappid']; ?>
<?php echo '\',
          cookie: true,
          xfbml: true,
          oauth: true
        });
      };
      (function() {
        var e = document.createElement(\'script\'); e.async = true;
        e.src = document.location.protocol +
          \'//connect.facebook.net/pl_PL/all.js\';
        document.getElementById(\'fb-root\').appendChild(e);
      }());
      '; ?>

    </script>
<?php endif; ?>	
<?php else: ?>
<p>Rejestracja w sklepie nie jest możliwa w tym momencie</p>
<?php endif; ?>