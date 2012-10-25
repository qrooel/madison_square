<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/showcasebox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'boxparams', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/showcasebox/index/index.tpl', 13, false),)), $this); ?>
<div class="carousel" id="<?php echo $this->_tpl_vars['boxId']; ?>
">
	<ul>
		<?php echo $this->_tpl_vars['products']; ?>

	</ul>
	<p class="controls">
		<a class="previous" href="#"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/showcase-left.png" alt=""/></a><a class="next" href="#"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/showcase-right.png" alt=""/></a>
	</p>
</div>

<div class="bottom-tabs">
	<ul>
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['showcasecategories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_boxparams(array('category' => $this->_tpl_vars['showcasecategories'][$this->_sections['i']['index']]['id']), $this);?>
"><span><?php echo $this->_tpl_vars['showcasecategories'][$this->_sections['i']['index']]['caption']; ?>
</span></a></li>
		<?php endfor; endif; ?>
	</ul>
</div>