<?php /* Smarty version 2.6.19, created on 2012-10-25 23:19:14
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/tagsbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/tagsbox/index/index.tpl', 4, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['tags'][0] )): ?>	
	<div id="cloud">
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['tags']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<a class="tag<?php echo $this->_tpl_vars['tags'][$this->_sections['i']['index']]['percentage']; ?>
" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'producttags'), $this);?>
/<?php echo $this->_tpl_vars['tags'][$this->_sections['i']['index']]['idtags']; ?>
"><?php echo $this->_tpl_vars['tags'][$this->_sections['i']['index']]['name']; ?>
</a>
		<?php endfor; endif; ?>
	</div>
	<?php else: ?>
	<p>Brak tagów</p>
<?php endif; ?>
