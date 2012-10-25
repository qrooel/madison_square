<?php /* Smarty version 2.6.19, created on 2012-10-25 23:19:14
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/footer.tpl', 14, false),array('function', 'seo_js', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/_tpl/frontend/core/footer.tpl', 36, false),)), $this); ?>
<!-- begin: Footer -->
				<div id="footer">
						<ul>
						<?php if (isset ( $this->_tpl_vars['contentcategory'] )): ?>
						
							<?php unset($this->_sections['cat']);
$this->_sections['cat']['name'] = 'cat';
$this->_sections['cat']['loop'] = is_array($_loop=$this->_tpl_vars['contentcategory']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat']['show'] = true;
$this->_sections['cat']['max'] = $this->_sections['cat']['loop'];
$this->_sections['cat']['step'] = 1;
$this->_sections['cat']['start'] = $this->_sections['cat']['step'] > 0 ? 0 : $this->_sections['cat']['loop']-1;
if ($this->_sections['cat']['show']) {
    $this->_sections['cat']['total'] = $this->_sections['cat']['loop'];
    if ($this->_sections['cat']['total'] == 0)
        $this->_sections['cat']['show'] = false;
} else
    $this->_sections['cat']['total'] = 0;
if ($this->_sections['cat']['show']):

            for ($this->_sections['cat']['index'] = $this->_sections['cat']['start'], $this->_sections['cat']['iteration'] = 1;
                 $this->_sections['cat']['iteration'] <= $this->_sections['cat']['total'];
                 $this->_sections['cat']['index'] += $this->_sections['cat']['step'], $this->_sections['cat']['iteration']++):
$this->_sections['cat']['rownum'] = $this->_sections['cat']['iteration'];
$this->_sections['cat']['index_prev'] = $this->_sections['cat']['index'] - $this->_sections['cat']['step'];
$this->_sections['cat']['index_next'] = $this->_sections['cat']['index'] + $this->_sections['cat']['step'];
$this->_sections['cat']['first']      = ($this->_sections['cat']['iteration'] == 1);
$this->_sections['cat']['last']       = ($this->_sections['cat']['iteration'] == $this->_sections['cat']['total']);
?>
							<?php if ($this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['footer'] == 1): ?>
							<li class="top">
								<h4><span><?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['name']; ?>
</span></h4>
								<ul>
								<?php if (count ( $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'] ) > 0): ?>
								<?php unset($this->_sections['under']);
$this->_sections['under']['name'] = 'under';
$this->_sections['under']['loop'] = is_array($_loop=$this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['under']['show'] = true;
$this->_sections['under']['max'] = $this->_sections['under']['loop'];
$this->_sections['under']['step'] = 1;
$this->_sections['under']['start'] = $this->_sections['under']['step'] > 0 ? 0 : $this->_sections['under']['loop']-1;
if ($this->_sections['under']['show']) {
    $this->_sections['under']['total'] = $this->_sections['under']['loop'];
    if ($this->_sections['under']['total'] == 0)
        $this->_sections['under']['show'] = false;
} else
    $this->_sections['under']['total'] = 0;
if ($this->_sections['under']['show']):

            for ($this->_sections['under']['index'] = $this->_sections['under']['start'], $this->_sections['under']['iteration'] = 1;
                 $this->_sections['under']['iteration'] <= $this->_sections['under']['total'];
                 $this->_sections['under']['index'] += $this->_sections['under']['step'], $this->_sections['under']['iteration']++):
$this->_sections['under']['rownum'] = $this->_sections['under']['iteration'];
$this->_sections['under']['index_prev'] = $this->_sections['under']['index'] - $this->_sections['under']['step'];
$this->_sections['under']['index_next'] = $this->_sections['under']['index'] + $this->_sections['under']['step'];
$this->_sections['under']['first']      = ($this->_sections['under']['iteration'] == 1);
$this->_sections['under']['last']       = ($this->_sections['under']['iteration'] == $this->_sections['under']['total']);
?>
								<?php if ($this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['footer'] == 1): ?>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'staticcontent'), $this);?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['id']; ?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['seo']; ?>
"><?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['children'][$this->_sections['under']['index']]['name']; ?>
</a></li>
								<?php endif; ?>
								<?php endfor; endif; ?>
								<?php endif; ?>
								
								<?php unset($this->_sections['page']);
$this->_sections['page']['name'] = 'page';
$this->_sections['page']['loop'] = is_array($_loop=$this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['page']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['page']['show'] = true;
$this->_sections['page']['max'] = $this->_sections['page']['loop'];
$this->_sections['page']['step'] = 1;
$this->_sections['page']['start'] = $this->_sections['page']['step'] > 0 ? 0 : $this->_sections['page']['loop']-1;
if ($this->_sections['page']['show']) {
    $this->_sections['page']['total'] = $this->_sections['page']['loop'];
    if ($this->_sections['page']['total'] == 0)
        $this->_sections['page']['show'] = false;
} else
    $this->_sections['page']['total'] = 0;
if ($this->_sections['page']['show']):

            for ($this->_sections['page']['index'] = $this->_sections['page']['start'], $this->_sections['page']['iteration'] = 1;
                 $this->_sections['page']['iteration'] <= $this->_sections['page']['total'];
                 $this->_sections['page']['index'] += $this->_sections['page']['step'], $this->_sections['page']['iteration']++):
$this->_sections['page']['rownum'] = $this->_sections['page']['iteration'];
$this->_sections['page']['index_prev'] = $this->_sections['page']['index'] - $this->_sections['page']['step'];
$this->_sections['page']['index_next'] = $this->_sections['page']['index'] + $this->_sections['page']['step'];
$this->_sections['page']['first']      = ($this->_sections['page']['iteration'] == 1);
$this->_sections['page']['last']       = ($this->_sections['page']['iteration'] == $this->_sections['page']['total']);
?>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'staticcontent'), $this);?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['page'][$this->_sections['page']['index']]['id']; ?>
/<?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['page'][$this->_sections['page']['index']]['seo']; ?>
"><?php echo $this->_tpl_vars['contentcategory'][$this->_sections['cat']['index']]['page'][$this->_sections['page']['index']]['topic']; ?>
</a></li>
								<?php endfor; endif; ?>
								</ul>
							</li>
							<?php endif; ?>
							<?php endfor; endif; ?>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['catalogmode'] == 0): ?>
							<li class="top">
								<h4><span>Twoje konto</span></h4>
								<ul>
								<?php if (isset ( $this->_tpl_vars['clientdata'] )): ?>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientsettings'), $this);?>
/">Ustawienia</a></li>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientorder'), $this);?>
/">Zamówienia</a></li>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientaddress'), $this);?>
/">Adresy klienta</a></li>								
								<?php else: ?>
									<li><span class="<?php echo smarty_function_seo_js(array('controller' => 'clientlogin'), $this);?>
">Logowanie do konta</span></li>
									<li><span class="<?php echo smarty_function_seo_js(array('controller' => 'registrationcart'), $this);?>
">Zarejestruj się</span></li>
								<?php endif; ?>
								</ul>
							</li>
						<?php endif; ?>
							<li class="top">
								<h4><span>Przydatne linki</span></h4>
								<ul>
									<li><span class="<?php echo smarty_function_seo_js(array('controller' => 'contact'), $this);?>
">Kontakt</span></li>
									<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'sitemap'), $this);?>
">Mapa strony</a></li>
									<li><span class="<?php echo smarty_function_seo_js(array('controller' => 'newsletter'), $this);?>
">Newsletter</span></li>
								</ul>
							</li>
							<li class="top">
								<h4><span>Poleć nas znajomym</span></h4>
								<ul>
									<li class="blip"><a href="http://blip.pl/dashboard?body=<?php echo $this->_tpl_vars['URL']; ?>
&amp;title=<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
" target="_blank">Blip</a></li>
									<li class="facebook"><a href="http://www.facebook.com/share.php?u=<?php echo $this->_tpl_vars['URL']; ?>
&amp;title=<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
" target="_blank">Facebook</a></li>
									<li class="wykop"><a href="http://www.wykop.pl/dodaj?url=<?php echo $this->_tpl_vars['URL']; ?>
&amp;title=<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
" target="_blank">Wykop</a></li>
								</ul>
							</li>
						</ul>
				</div>
			<!-- end: Footer -->
			
			<!-- begin: Copyright bar -->
				<div id="copyright-bar">
					<p class="copyright">
						<a href="http://www.gekosale.pl/" title="Gekosale.pl - bezpłatne oprogramowanie sklepu internetowego" target="_blank"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/logos/logo-mini.png" alt="Gekosale" width="110" height="21" /></a>
					</p>
				</div>
			<!-- end: Copyright bar -->
		</div>
		<script type="text/javascript">  
		<?php echo '
			$(document).ready(function(){
				var container = $(\'#footer\').width();
				var cols = $(\'li.top\').length;
				$(\'li.top\').width(container / cols);
			});
		'; ?>

		</script>
		<?php echo $this->_tpl_vars['footerJS']; ?>

		<?php if ($this->_tpl_vars['gacode'] != ''): ?>
		<script type="text/javascript">
		<?php echo '
		    (function() {
		    	var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
		        ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
		        var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
		    })();
		'; ?>

		</script>
		<?php endif; ?>
	</body>
</html>