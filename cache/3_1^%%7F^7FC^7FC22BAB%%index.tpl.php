<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/newslistbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/newslistbox/index/index.tpl', 6, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['newslist'][0] )): ?>	
	<ul class="list">
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['newslist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<li>
		<h4>
			<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'news'), $this);?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['idnews']; ?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['seo']; ?>
">
				<span class="name"><?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['topic']; ?>
</span>
				<span class="date"><?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['adddate']; ?>
</span>
			</a>
		</h4>
		<div class="description">
			<?php if (isset ( $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['mainphoto']['small'] ) && $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['mainphoto']['small'] != ''): ?>
				<a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'news'), $this);?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['idnews']; ?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['seo']; ?>
"><img src="<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['mainphoto']['small']; ?>
" alt="<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['topic']; ?>
" class="mainphoto" /></a>
			<?php endif; ?>
			<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['summary']; ?>

			<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'news'), $this);?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['idnews']; ?>
/<?php echo $this->_tpl_vars['newslist'][$this->_sections['i']['index']]['seo']; ?>
">Czytaj dalej</a></p>
		</div>
	</li>
	<?php endfor; endif; ?>
	</ul>
	
<?php else: ?>
	<p>Brak nowości</p>
<?php endif; ?>
<div class="layout-box-footer">
	<p><?php if ($this->_tpl_vars['enablerss'] == 1): ?><span class="rss"><a href="<?php echo $this->_tpl_vars['URL']; ?>
feeds/news"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/rss.png" title="RSS - Aktualności"></a></span><?php endif; ?><span style="text-align: right;margin-right: 10px;float: right;margin-bottom: 10px;"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'news'), $this);?>
">Zobacz więcej</a></span></p>
</div>