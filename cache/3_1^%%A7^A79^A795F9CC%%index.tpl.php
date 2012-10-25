<?php /* Smarty version 2.6.19, created on 2012-10-25 10:05:15
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/newsletterbox/index/index.tpl */ ?>
<?php if (( isset ( $this->_tpl_vars['errlink'] ) || ( isset ( $this->_tpl_vars['activelink'] ) ) || ( isset ( $this->_tpl_vars['inactivelink'] ) ) )): ?>
	<?php if (( isset ( $this->_tpl_vars['errlink'] ) && $this->_tpl_vars['errlink'] == 1 )): ?>
		<p class="error"><strong>Nieprawidłowy link</strong></p>
	<?php elseif (( isset ( $this->_tpl_vars['inactivelink'] ) && $this->_tpl_vars['inactivelink'] == 1 )): ?>
		<p>Zostałeś wypisany z Newsletter</p>
	<?php else: ?>
		<p>Właśnie zostałeś zapisany do Newsletter</p>
	<?php endif; ?>
<?php else: ?>
		<p>Zapisz się do naszego newslettera, aby mieć zawsze świeże informację na temat naszych produktów</p>
		<div class="field-text">
			<label for="newsletterformphrase">E-mail</label>
			<span class="field">
				<input id="newsletterformphrase" name="mail" type="text" value="<?php echo $this->_tpl_vars['email']; ?>
"/>
			</span>
		</div>
		
		<div id="info"></div>
		<a href="#" class="button" onclick="xajax_addNewsletter($('#newsletterformphrase').val());return false;"><span>Zapisz</span></a>
		<a href="#" class="button" onclick="xajax_deleteNewsletter($('#newsletterformphrase').val());return false;"><span>Usuń</span></a>
<?php endif; ?>