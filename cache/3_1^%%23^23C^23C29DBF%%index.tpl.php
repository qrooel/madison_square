<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/producerbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/producerbox/index/index.tpl', 5, false),)), $this); ?>
<?php if ($this->_tpl_vars['view'] == 0): ?>
<ul style="list-style: none; margin-left:0px;">
<?php unset($this->_sections['producerId']);
$this->_sections['producerId']['name'] = 'producerId';
$this->_sections['producerId']['loop'] = is_array($_loop=$this->_tpl_vars['producers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['producerId']['show'] = true;
$this->_sections['producerId']['max'] = $this->_sections['producerId']['loop'];
$this->_sections['producerId']['step'] = 1;
$this->_sections['producerId']['start'] = $this->_sections['producerId']['step'] > 0 ? 0 : $this->_sections['producerId']['loop']-1;
if ($this->_sections['producerId']['show']) {
    $this->_sections['producerId']['total'] = $this->_sections['producerId']['loop'];
    if ($this->_sections['producerId']['total'] == 0)
        $this->_sections['producerId']['show'] = false;
} else
    $this->_sections['producerId']['total'] = 0;
if ($this->_sections['producerId']['show']):

            for ($this->_sections['producerId']['index'] = $this->_sections['producerId']['start'], $this->_sections['producerId']['iteration'] = 1;
                 $this->_sections['producerId']['iteration'] <= $this->_sections['producerId']['total'];
                 $this->_sections['producerId']['index'] += $this->_sections['producerId']['step'], $this->_sections['producerId']['iteration']++):
$this->_sections['producerId']['rownum'] = $this->_sections['producerId']['iteration'];
$this->_sections['producerId']['index_prev'] = $this->_sections['producerId']['index'] - $this->_sections['producerId']['step'];
$this->_sections['producerId']['index_next'] = $this->_sections['producerId']['index'] + $this->_sections['producerId']['step'];
$this->_sections['producerId']['first']      = ($this->_sections['producerId']['iteration'] == 1);
$this->_sections['producerId']['last']       = ($this->_sections['producerId']['iteration'] == $this->_sections['producerId']['total']);
?>
	<li<?php if ($this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['seo'] == $this->_tpl_vars['CURRENT_PARAM']): ?> class="active"<?php endif; ?>>
		<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'producerlist'), $this);?>
/<?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['seo']; ?>
">
			<?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['name']; ?>

		</a>
	</li>
<?php endfor; endif; ?>
</ul>
<?php else: ?>
<div class="field-select" style="margin: 0px 0px 10px 10px;padding-top: 10px;"> 
	<span class="field"> 
		<select id="languages" onchange="location.href = this.value;">
				<option value="<?php echo $this->_tpl_vars['URL']; ?>
">--- wybierz ---</option>
		 	<?php unset($this->_sections['producerId']);
$this->_sections['producerId']['name'] = 'producerId';
$this->_sections['producerId']['loop'] = is_array($_loop=$this->_tpl_vars['producers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['producerId']['show'] = true;
$this->_sections['producerId']['max'] = $this->_sections['producerId']['loop'];
$this->_sections['producerId']['step'] = 1;
$this->_sections['producerId']['start'] = $this->_sections['producerId']['step'] > 0 ? 0 : $this->_sections['producerId']['loop']-1;
if ($this->_sections['producerId']['show']) {
    $this->_sections['producerId']['total'] = $this->_sections['producerId']['loop'];
    if ($this->_sections['producerId']['total'] == 0)
        $this->_sections['producerId']['show'] = false;
} else
    $this->_sections['producerId']['total'] = 0;
if ($this->_sections['producerId']['show']):

            for ($this->_sections['producerId']['index'] = $this->_sections['producerId']['start'], $this->_sections['producerId']['iteration'] = 1;
                 $this->_sections['producerId']['iteration'] <= $this->_sections['producerId']['total'];
                 $this->_sections['producerId']['index'] += $this->_sections['producerId']['step'], $this->_sections['producerId']['iteration']++):
$this->_sections['producerId']['rownum'] = $this->_sections['producerId']['iteration'];
$this->_sections['producerId']['index_prev'] = $this->_sections['producerId']['index'] - $this->_sections['producerId']['step'];
$this->_sections['producerId']['index_next'] = $this->_sections['producerId']['index'] + $this->_sections['producerId']['step'];
$this->_sections['producerId']['first']      = ($this->_sections['producerId']['iteration'] == 1);
$this->_sections['producerId']['last']       = ($this->_sections['producerId']['iteration'] == $this->_sections['producerId']['total']);
?>
				<?php if ($this->_tpl_vars['CURRENT_PARAM'] == $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['seo']): ?>
					<option value="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'producerlist'), $this);?>
/<?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['seo']; ?>
" selected="selected"><?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['name']; ?>
</option>
				<?php else: ?>
					<option value="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'producerlist'), $this);?>
/<?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['seo']; ?>
"><?php echo $this->_tpl_vars['producers'][$this->_sections['producerId']['index']]['name']; ?>
</option>
				<?php endif; ?>
			<?php endfor; endif; ?>
		</select>
	</span> 
</div> 
<?php endif; ?>