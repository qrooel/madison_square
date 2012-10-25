<?php /* Smarty version 2.6.19, created on 2012-10-11 08:55:52
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/newsbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/newsbox/index/index.tpl', 22, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['news'] )): ?>

<?php if (isset ( $this->_tpl_vars['news']['mainphoto']['small'] ) && $this->_tpl_vars['news']['mainphoto']['small'] != ''): ?>
	<a rel="news" class="fancy" href="<?php echo $this->_tpl_vars['news']['mainphoto']['orginal']; ?>
" title="<?php echo $this->_tpl_vars['news']['topic']; ?>
"><img src="<?php echo $this->_tpl_vars['news']['mainphoto']['small']; ?>
" alt="<?php echo $this->_tpl_vars['news']['topic']; ?>
" style="float: left;margin: 0 10px 10px 0;"/></a>
<?php endif; ?>
<?php echo $this->_tpl_vars['news']['content']; ?>

<h4 >Data dodania: <strong><?php echo $this->_tpl_vars['news']['adddate']; ?>
</strong></h4>
<div class="thumbs" style="float: left;clear: both;">
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['news']['otherphoto']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<a rel="news" href="<?php echo $this->_tpl_vars['news']['otherphoto'][$this->_sections['i']['index']]['orginal']; ?>
" title="<?php echo $this->_tpl_vars['news']['topic']; ?>
"><img src="<?php echo $this->_tpl_vars['news']['otherphoto'][$this->_sections['i']['index']]['small']; ?>
" alt="<?php echo $this->_tpl_vars['news']['topic']; ?>
"/></a>
<?php endfor; endif; ?>
</div>

<div class="buttons" style="float:left;margin-left:5px;clear:both;">
	<a href="javascript:history.back();" class="button"><span><span>Wstecz</span></span></a>
</div>
<?php else: ?>
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
<?php endif; ?>