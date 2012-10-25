<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:00
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/layerednavigationbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/layerednavigationbox/index/index.tpl', 7, false),)), $this); ?>
<form class="filter">
<?php if (count ( $this->_tpl_vars['ranges'] ) > 0): ?>
<h3>Cena</h3>
<div class="filter-price">
	<input type=hidden name="tier" id="tier" value="0-99999" />
	<?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['ranges']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
	<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => $this->_tpl_vars['ranges'][$this->_sections['r']['index']]['step'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'page' => $this->_tpl_vars['currentPage']), $this);?>
" class="price<?php if ($this->_tpl_vars['currentPrice'] == $this->_tpl_vars['ranges'][$this->_sections['r']['index']]['step']): ?> active<?php endif; ?>"><?php echo $this->_tpl_vars['ranges'][$this->_sections['r']['index']]['label']; ?>
 <?php echo $this->_tpl_vars['currencySymbol']; ?>
</a></p>
	<?php endfor; endif; ?>
	<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => 'od0do99999','producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'page' => $this->_tpl_vars['currentPage']), $this);?>
" class="price<?php if ($this->_tpl_vars['currentPrice'] == '' || $this->_tpl_vars['currentPrice'] == 'od0do99999'): ?> active<?php endif; ?>">Wszystkie</a></p>
</div>
<?php endif; ?>
<?php if (count ( $this->_tpl_vars['producers'] ) > 0): ?>
<h3>Producent</h3>
<div class="filter-checkboxes">
<?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=$this->_tpl_vars['producers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
	<?php if (in_array ( $this->_tpl_vars['producers'][$this->_sections['p']['index']]['seo'] , $this->_tpl_vars['currentProducers'] )): ?>
	<div class="filtration selected">
	<p><strong><?php echo $this->_tpl_vars['producers'][$this->_sections['p']['index']]['name']; ?>
</strong><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'removeproducer' => $this->_tpl_vars['producers'][$this->_sections['p']['index']]['seo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'page' => $this->_tpl_vars['currentPage']), $this);?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/icon-close.png"></a></p>
	</div>
	<?php else: ?>
	<div class="filtration">
	<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'addproducer' => $this->_tpl_vars['producers'][$this->_sections['p']['index']]['seo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'page' => $this->_tpl_vars['currentPage']), $this);?>
"><?php echo $this->_tpl_vars['producers'][$this->_sections['p']['index']]['name']; ?>
</a></p>
	</div>
	<?php endif; ?>
<?php endfor; endif; ?>
</div>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group']):
?>
<h3><?php echo $this->_tpl_vars['group']['name']; ?>
</h3>
<div class="filter-checkboxes">
<?php $_from = $this->_tpl_vars['group']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['attribute']):
?>
<?php $this->assign('name', "g".($this->_tpl_vars['key'])."-".($this->_tpl_vars['k'])); ?>
<?php if (in_array ( $this->_tpl_vars['name'] , $this->_tpl_vars['currentAttributes'] )): ?>
<div class="filtration selected">
<p><strong><?php echo $this->_tpl_vars['attribute']; ?>
</strong><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'group' => $this->_tpl_vars['key'],'removeattribute' => $this->_tpl_vars['k']), $this);?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/icon-close.png"></a></p>
</div>
<?php else: ?>
<div class="filtration">
<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'group' => $this->_tpl_vars['key'],'addattribute' => $this->_tpl_vars['k']), $this);?>
"><?php echo $this->_tpl_vars['attribute']; ?>
</a></p>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endforeach; endif; unset($_from); ?>

<?php $_from = $this->_tpl_vars['staticattributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['staticattribute']):
?>
<h3><?php echo $this->_tpl_vars['staticattribute']['name']; ?>
</h3>
<div class="filter-checkboxes">
<?php $_from = $this->_tpl_vars['staticattribute']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['attribute']):
?>
<?php $this->assign('name', "s".($this->_tpl_vars['key'])."-".($this->_tpl_vars['k'])); ?>
<?php if (in_array ( $this->_tpl_vars['name'] , $this->_tpl_vars['currentStaticAttributes'] )): ?>
<div class="filtration selected">
<p><strong><?php echo $this->_tpl_vars['attribute']; ?>
</strong><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'staticgroup' => $this->_tpl_vars['key'],'removestaticattribute' => $this->_tpl_vars['k']), $this);?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/icon-close.png"></a></p>
</div>
<?php else: ?>
<div class="filtration">
<p><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => $this->_tpl_vars['currentController'],'seo' => $this->_tpl_vars['currentSeo'],'price' => $this->_tpl_vars['currentPrice'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes'],'staticattributes' => $this->_tpl_vars['currentStaticAttributes'],'staticgroup' => $this->_tpl_vars['key'],'addstaticattribute' => $this->_tpl_vars['k']), $this);?>
"><?php echo $this->_tpl_vars['attribute']; ?>
</a></p>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endforeach; endif; unset($_from); ?>

</form>