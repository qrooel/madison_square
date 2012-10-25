<?php /* Smarty version 2.6.19, created on 2012-10-08 10:02:00
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsincategorybox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsincategorybox/index/index.tpl', 21, false),)), $this); ?>
<?php if ($this->_tpl_vars['currentCategory']['description'] != '' || $this->_tpl_vars['currentCategory']['shortdescription'] != '' || $this->_tpl_vars['currentCategory']['photo'] != ''): ?>
<div class="category-description">
<?php if ($this->_tpl_vars['currentCategory']['photo'] != ''): ?>
<img src="<?php echo $this->_tpl_vars['currentCategory']['photo']; ?>
" alt="<?php echo $this->_tpl_vars['currentCategory']['name']; ?>
" />
<?php endif; ?>

<?php if ($this->_tpl_vars['currentCategory']['description'] != ''): ?>
	<?php echo $this->_tpl_vars['currentCategory']['description']; ?>

<?php else: ?>
	<?php echo $this->_tpl_vars['currentCategory']['shortdescription']; ?>

<?php endif; ?>

</div>
<?php endif; ?>

<?php if (count ( $this->_tpl_vars['subcategories'] ) > 0): ?>
<ul class="subcategories-list" style="clear: both;">
		<?php unset($this->_sections['categoryId']);
$this->_sections['categoryId']['name'] = 'categoryId';
$this->_sections['categoryId']['loop'] = is_array($_loop=$this->_tpl_vars['subcategories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<li>
			<h4>
				<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php if ($this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['url'] != ''): ?><?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['url']; ?>
<?php else: ?><?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['seo']; ?>
<?php endif; ?>">
				<?php if (isset ( $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['photo'] ) && $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['photo'] != ''): ?><span class="image"><img src="<?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['photo']; ?>
" alt="<?php echo $this->_tpl_vars['subcategories'][$this->_sections['td']['index']]['name']; ?>
"/></span><?php endif; ?>
					<span class="name"><?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['name']; ?>
</span>
				</a>
			</h4>
			<div class="description">
				<?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['shortdescription']; ?>

				<p><a class="read-more" href="<?php echo $this->_tpl_vars['URL']; ?>
<?php if ($this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['url'] != ''): ?><?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['url']; ?>
<?php else: ?><?php echo smarty_function_seo(array('controller' => 'categorylist'), $this);?>
/<?php echo $this->_tpl_vars['subcategories'][$this->_sections['categoryId']['index']]['seo']; ?>
<?php endif; ?>">Wyświetl produkty w kategorii</a></p>
			</p>
			</div>
		</li>
		<?php endfor; endif; ?>
	</ul>
<?php endif; ?>
<?php if ($this->_tpl_vars['products'] !== ''): ?>
<div class="filter" id="filter">
<div class="display">
<?php if ($this->_tpl_vars['view'] == 0): ?>
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist','seo' => $this->_tpl_vars['currentCategory']['seo'],'view' => 1,'page' => $this->_tpl_vars['currentPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" title="Lista"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-list.png"></a>
	<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-grid-active.png" title="Siatka">
<?php else: ?>
	<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-list-active.png" title="Lista">
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist','seo' => $this->_tpl_vars['currentCategory']['seo'],'view' => 0,'page' => $this->_tpl_vars['currentPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" title="Siatka"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-grid.png"></a>
<?php endif; ?>
 </div>
<div class="field-select"> 
	<label>Sortuj według</label> 
    <span class="field"> 
	    <select name="order" id="order" onchange="location.href=this.value"> 
	    	<?php $_from = $this->_tpl_vars['sorting']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['sorting']):
?>
	    		<option value="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist','seo' => $this->_tpl_vars['currentCategory']['seo'],'page' => 1,'sort' => $this->_tpl_vars['key'],'dir' => 'asc','price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['orderBy'] && $this->_tpl_vars['orderDir'] == 'asc'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['sorting']; ?>
 - rosnąco</option> 
	    		<option value="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'categorylist','seo' => $this->_tpl_vars['currentCategory']['seo'],'page' => 1,'sort' => $this->_tpl_vars['key'],'dir' => 'desc','price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['orderBy'] && $this->_tpl_vars['orderDir'] == 'desc'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['sorting']; ?>
 - malejąco</option> 
	    	<?php endforeach; endif; unset($_from); ?>
	    </select> 
    </span> 
</div> 
</div>
<ul class="product-list <?php if ($this->_tpl_vars['view'] == 0): ?>list-grid<?php else: ?>list-long<?php endif; ?>">
	<?php echo $this->_tpl_vars['products']; ?>

</ul>
<?php if ($this->_tpl_vars['showpagination'] == 1): ?>
<div class="layout-box-footer">
	<div class="pagination">
		<?php echo $this->_tpl_vars['pagination']; ?>

	</div>
</div>
<?php endif; ?>
<?php else: ?>
	<?php if (count ( $this->_tpl_vars['subcategories'] ) == 0): ?>
	<p style="padding-top: 5px;padding-left: 5px;">Brak produktów</p>
	<?php endif; ?>
<?php endif; ?>
