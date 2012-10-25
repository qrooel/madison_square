<?php /* Smarty version 2.6.19, created on 2012-10-25 23:19:14
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/categoriesbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square/design/frontend/core/categoriesbox/index/index.tpl', 9, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['categories'] ) == 0): ?>
	<p>Brak kategorii</p>
<?php else: ?>
<?php if ($this->_tpl_vars['showall'] == 1): ?>
	<ul>
		<?php unset($this->_sections['categoryId']);
$this->_sections['categoryId']['name'] = 'categoryId';
$this->_sections['categoryId']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['categoryId']['show'] = true;
$this->_sections['categoryId']['max'] = $this->_sections['categoryId']['loop'];
$this->_sections['categoryId']['step'] = 1;
$this->_sections['categoryId']['start'] = $this->_sections['categoryId']['step'] > 0 ? 0 : $this->_sections['categoryId']['loop']-1;
if ($this->_sections['categoryId']['show']) {
    $this->_sections['categoryId']['total'] = $this->_sections['categoryId']['loop'];
    if ($this->_sections['categoryId']['total'] == 0)
        $this->_sections['categoryId']['show'] = false;
} else
    $this->_sections['categoryId']['total'] = 0;
if ($this->_sections['categoryId']['show']):

            for ($this->_sections['categoryId']['index'] = $this->_sections['categoryId']['start'], $this->_sections['categoryId']['iteration'] = 1;
                 $this->_sections['categoryId']['iteration'] <= $this->_sections['categoryId']['total'];
                 $this->_sections['categoryId']['index'] += $this->_sections['categoryId']['step'], $this->_sections['categoryId']['iteration']++):
$this->_sections['categoryId']['rownum'] = $this->_sections['categoryId']['iteration'];
$this->_sections['categoryId']['index_prev'] = $this->_sections['categoryId']['index'] - $this->_sections['categoryId']['step'];
$this->_sections['categoryId']['index_next'] = $this->_sections['categoryId']['index'] + $this->_sections['categoryId']['step'];
$this->_sections['categoryId']['first']      = ($this->_sections['categoryId']['iteration'] == 1);
$this->_sections['categoryId']['last']       = ($this->_sections['categoryId']['iteration'] == $this->_sections['categoryId']['total']);
?>
			<?php if (( $this->_tpl_vars['hideempty'] == 0 ) || ( $this->_tpl_vars['hideempty'] == 1 && $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['totalproducts'] > 0 )): ?>
			<li <?php if (in_array ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['id'] , $this->_tpl_vars['path'] )): ?>class="active"<?php endif; ?>>
				<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['seo']; ?>
" <?php if (count ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'] ) > 0): ?>class="hasChildren"<?php endif; ?>>
					<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['label']; ?>
 <?php if ($this->_tpl_vars['showcount'] == 1): ?>(<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['totalproducts']; ?>
)<?php endif; ?>
				</a>
				<?php if (count ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'] ) > 0): ?>
				<div class="submenu">
					<div class="submenu-wrapper">
						<ul>
							<?php unset($this->_sections['subcategoryId']);
$this->_sections['subcategoryId']['name'] = 'subcategoryId';
$this->_sections['subcategoryId']['loop'] = is_array($_loop=$this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['subcategoryId']['show'] = true;
$this->_sections['subcategoryId']['max'] = $this->_sections['subcategoryId']['loop'];
$this->_sections['subcategoryId']['step'] = 1;
$this->_sections['subcategoryId']['start'] = $this->_sections['subcategoryId']['step'] > 0 ? 0 : $this->_sections['subcategoryId']['loop']-1;
if ($this->_sections['subcategoryId']['show']) {
    $this->_sections['subcategoryId']['total'] = $this->_sections['subcategoryId']['loop'];
    if ($this->_sections['subcategoryId']['total'] == 0)
        $this->_sections['subcategoryId']['show'] = false;
} else
    $this->_sections['subcategoryId']['total'] = 0;
if ($this->_sections['subcategoryId']['show']):

            for ($this->_sections['subcategoryId']['index'] = $this->_sections['subcategoryId']['start'], $this->_sections['subcategoryId']['iteration'] = 1;
                 $this->_sections['subcategoryId']['iteration'] <= $this->_sections['subcategoryId']['total'];
                 $this->_sections['subcategoryId']['index'] += $this->_sections['subcategoryId']['step'], $this->_sections['subcategoryId']['iteration']++):
$this->_sections['subcategoryId']['rownum'] = $this->_sections['subcategoryId']['iteration'];
$this->_sections['subcategoryId']['index_prev'] = $this->_sections['subcategoryId']['index'] - $this->_sections['subcategoryId']['step'];
$this->_sections['subcategoryId']['index_next'] = $this->_sections['subcategoryId']['index'] + $this->_sections['subcategoryId']['step'];
$this->_sections['subcategoryId']['first']      = ($this->_sections['subcategoryId']['iteration'] == 1);
$this->_sections['subcategoryId']['last']       = ($this->_sections['subcategoryId']['iteration'] == $this->_sections['subcategoryId']['total']);
?>
							<?php if (( $this->_tpl_vars['hideempty'] == 0 ) || ( $this->_tpl_vars['hideempty'] == 1 && $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['totalproducts'] > 0 )): ?>
								<li class="<?php if (in_array ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['id'] , $this->_tpl_vars['path'] )): ?>current<?php endif; ?>">
									<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['seo']; ?>
">
										<span>
											<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['label']; ?>
 <?php if ($this->_tpl_vars['showcount'] == 1): ?>(<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['totalproducts']; ?>
)<?php endif; ?>
										</span>
									</a>
								</li>
							<?php endif; ?>
							<?php endfor; endif; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			</li>
			<?php endif; ?>
		<?php endfor; endif; ?>
	</ul>
<?php else: ?>
	<ul>
		<?php unset($this->_sections['categoryId']);
$this->_sections['categoryId']['name'] = 'categoryId';
$this->_sections['categoryId']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['categoryId']['show'] = true;
$this->_sections['categoryId']['max'] = $this->_sections['categoryId']['loop'];
$this->_sections['categoryId']['step'] = 1;
$this->_sections['categoryId']['start'] = $this->_sections['categoryId']['step'] > 0 ? 0 : $this->_sections['categoryId']['loop']-1;
if ($this->_sections['categoryId']['show']) {
    $this->_sections['categoryId']['total'] = $this->_sections['categoryId']['loop'];
    if ($this->_sections['categoryId']['total'] == 0)
        $this->_sections['categoryId']['show'] = false;
} else
    $this->_sections['categoryId']['total'] = 0;
if ($this->_sections['categoryId']['show']):

            for ($this->_sections['categoryId']['index'] = $this->_sections['categoryId']['start'], $this->_sections['categoryId']['iteration'] = 1;
                 $this->_sections['categoryId']['iteration'] <= $this->_sections['categoryId']['total'];
                 $this->_sections['categoryId']['index'] += $this->_sections['categoryId']['step'], $this->_sections['categoryId']['iteration']++):
$this->_sections['categoryId']['rownum'] = $this->_sections['categoryId']['iteration'];
$this->_sections['categoryId']['index_prev'] = $this->_sections['categoryId']['index'] - $this->_sections['categoryId']['step'];
$this->_sections['categoryId']['index_next'] = $this->_sections['categoryId']['index'] + $this->_sections['categoryId']['step'];
$this->_sections['categoryId']['first']      = ($this->_sections['categoryId']['iteration'] == 1);
$this->_sections['categoryId']['last']       = ($this->_sections['categoryId']['iteration'] == $this->_sections['categoryId']['total']);
?>
			<?php if (( $this->_tpl_vars['hideempty'] == 0 ) || ( $this->_tpl_vars['hideempty'] == 1 && $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['totalproducts'] > 0 )): ?>
				<?php if (in_array ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['id'] , $this->_tpl_vars['include'] )): ?>
				<li <?php if (in_array ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['id'] , $this->_tpl_vars['path'] )): ?>class="active"<?php endif; ?>>
					<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['seo']; ?>
" <?php if (count ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'] ) > 0): ?>class="hasChildren"<?php endif; ?>>
						<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['label']; ?>
 <?php if ($this->_tpl_vars['showcount'] == 1): ?>(<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['totalproducts']; ?>
)<?php endif; ?>
					</a>
					<?php if (count ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'] ) > 0): ?>
					<div class="submenu">
						<div class="submenu-wrapper">
							<ul>
								<?php unset($this->_sections['subcategoryId']);
$this->_sections['subcategoryId']['name'] = 'subcategoryId';
$this->_sections['subcategoryId']['loop'] = is_array($_loop=$this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['subcategoryId']['show'] = true;
$this->_sections['subcategoryId']['max'] = $this->_sections['subcategoryId']['loop'];
$this->_sections['subcategoryId']['step'] = 1;
$this->_sections['subcategoryId']['start'] = $this->_sections['subcategoryId']['step'] > 0 ? 0 : $this->_sections['subcategoryId']['loop']-1;
if ($this->_sections['subcategoryId']['show']) {
    $this->_sections['subcategoryId']['total'] = $this->_sections['subcategoryId']['loop'];
    if ($this->_sections['subcategoryId']['total'] == 0)
        $this->_sections['subcategoryId']['show'] = false;
} else
    $this->_sections['subcategoryId']['total'] = 0;
if ($this->_sections['subcategoryId']['show']):

            for ($this->_sections['subcategoryId']['index'] = $this->_sections['subcategoryId']['start'], $this->_sections['subcategoryId']['iteration'] = 1;
                 $this->_sections['subcategoryId']['iteration'] <= $this->_sections['subcategoryId']['total'];
                 $this->_sections['subcategoryId']['index'] += $this->_sections['subcategoryId']['step'], $this->_sections['subcategoryId']['iteration']++):
$this->_sections['subcategoryId']['rownum'] = $this->_sections['subcategoryId']['iteration'];
$this->_sections['subcategoryId']['index_prev'] = $this->_sections['subcategoryId']['index'] - $this->_sections['subcategoryId']['step'];
$this->_sections['subcategoryId']['index_next'] = $this->_sections['subcategoryId']['index'] + $this->_sections['subcategoryId']['step'];
$this->_sections['subcategoryId']['first']      = ($this->_sections['subcategoryId']['iteration'] == 1);
$this->_sections['subcategoryId']['last']       = ($this->_sections['subcategoryId']['iteration'] == $this->_sections['subcategoryId']['total']);
?>
								<?php if (( $this->_tpl_vars['hideempty'] == 0 ) || ( $this->_tpl_vars['hideempty'] == 1 && $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['totalproducts'] > 0 )): ?>
									<li class="<?php if (in_array ( $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['id'] , $this->_tpl_vars['path'] )): ?>current<?php endif; ?>">
										<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['seo']; ?>
">
											<span>
												<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['label']; ?>
 <?php if ($this->_tpl_vars['showcount'] == 1): ?>(<?php echo $this->_tpl_vars['categories'][$this->_sections['categoryId']['index']]['children'][$this->_sections['subcategoryId']['index']]['totalproducts']; ?>
)<?php endif; ?>
											</span>
										</a>
									</li>
								<?php endif; ?>
								<?php endfor; endif; ?>
							</ul>
						</div>
					</div>
					<?php endif; ?>
				</li>
				<?php endif; ?>
			<?php endif; ?>
		<?php endfor; endif; ?>
	</ul>
<?php endif; ?>
<?php endif; ?>