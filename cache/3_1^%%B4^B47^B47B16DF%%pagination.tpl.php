<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:00
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsincategorybox/index/pagination.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsincategorybox/index/pagination.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['dataset']['total'] > 0): ?>
<ul>
	<?php unset($this->_sections['links']);
$this->_sections['links']['name'] = 'links';
$this->_sections['links']['loop'] = is_array($_loop=$this->_tpl_vars['dataset']['totalPages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['links']['show'] = true;
$this->_sections['links']['max'] = $this->_sections['links']['loop'];
$this->_sections['links']['step'] = 1;
$this->_sections['links']['start'] = $this->_sections['links']['step'] > 0 ? 0 : $this->_sections['links']['loop']-1;
if ($this->_sections['links']['show']) {
    $this->_sections['links']['total'] = $this->_sections['links']['loop'];
    if ($this->_sections['links']['total'] == 0)
        $this->_sections['links']['show'] = false;
} else
    $this->_sections['links']['total'] = 0;
if ($this->_sections['links']['show']):

            for ($this->_sections['links']['index'] = $this->_sections['links']['start'], $this->_sections['links']['iteration'] = 1;
                 $this->_sections['links']['iteration'] <= $this->_sections['links']['total'];
                 $this->_sections['links']['index'] += $this->_sections['links']['step'], $this->_sections['links']['iteration']++):
$this->_sections['links']['rownum'] = $this->_sections['links']['iteration'];
$this->_sections['links']['index_prev'] = $this->_sections['links']['index'] - $this->_sections['links']['step'];
$this->_sections['links']['index_next'] = $this->_sections['links']['index'] + $this->_sections['links']['step'];
$this->_sections['links']['first']      = ($this->_sections['links']['iteration'] == 1);
$this->_sections['links']['last']       = ($this->_sections['links']['iteration'] == $this->_sections['links']['total']);
?>
		<?php if ($this->_sections['links']['first']): ?>
			<?php if ($this->_tpl_vars['dataset']['activePage'] == 1): ?>
				<li class="previous disabled"><a href="">Poprzednia</a></li>
			<?php else: ?>
				<li class="previous"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['controller'],'seo' => $this->_tpl_vars['currentCategory']['seo'],'page' => $this->_tpl_vars['dataset']['previousPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
">Poprzednia</a></li>
			<?php endif; ?>
		<?php endif; ?>
			<li class="page <?php if ($this->_tpl_vars['dataset']['totalPages'][$this->_sections['links']['index']] == $this->_tpl_vars['dataset']['activePage']): ?>active<?php endif; ?>" ><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['controller'],'seo' => $this->_tpl_vars['currentCategory']['seo'],'page' => $this->_tpl_vars['dataset']['totalPages'][$this->_sections['links']['index']],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
"><?php echo $this->_tpl_vars['dataset']['totalPages'][$this->_sections['links']['index']]; ?>
</a></li>
		<?php if ($this->_sections['links']['last']): ?>
			<?php if ($this->_tpl_vars['dataset']['activePage'] == $this->_tpl_vars['dataset']['lastPage']): ?>
				<li class="next disabled"><a href="">Następna</a></li>
			<?php else: ?>
			<li class="next"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['controller'],'seo' => $this->_tpl_vars['currentCategory']['seo'],'page' => $this->_tpl_vars['dataset']['nextPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
">Następna</a></li>
			<?php endif; ?>
		<?php endif; ?>
	<?php endfor; endif; ?>
</ul>
<?php endif; ?>