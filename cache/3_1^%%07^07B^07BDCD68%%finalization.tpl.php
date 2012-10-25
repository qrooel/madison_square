<?php /* Smarty version 2.6.19, created on 2012-10-08 10:09:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/cartbox/index/finalization.tpl */ ?>
<fieldset class="listing">
	<legend><span>Podsumowanie</span></legend>
	<?php if (isset ( $this->_tpl_vars['productCart'] )): ?>
	<dl>
	<?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['summary']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>
		<dt><?php echo $this->_tpl_vars['summary'][$this->_sections['s']['index']]['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['summary'][$this->_sections['s']['index']]['value']; ?>
</dd>
	<?php endfor; endif; ?>
	</dl>
	<?php endif; ?>
</fieldset>