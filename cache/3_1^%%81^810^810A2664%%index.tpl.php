<?php /* Smarty version 2.6.19, created on 2012-10-09 06:20:35
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsearchlistbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productsearchlistbox/index/index.tpl', 4, false),)), $this); ?>
<div class="filter" id="filter">
<div class="display">
<?php if ($this->_tpl_vars['view'] == 0): ?>
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch','seo' => $this->_tpl_vars['searchPhrase'],'view' => 1,'page' => $this->_tpl_vars['currentPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" title="Lista"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-list.png"></a>
	<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-grid-active.png" title="Siatka">
<?php else: ?>
	<img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_images_frontend/core/icons/view-list-active.png" title="Lista">
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch','seo' => $this->_tpl_vars['searchPhrase'],'view' => 0,'page' => $this->_tpl_vars['currentPage'],'price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
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
<?php echo smarty_function_seo(array('controller' => 'productsearch','seo' => $this->_tpl_vars['searchPhrase'],'page' => 1,'sort' => $this->_tpl_vars['key'],'dir' => 'asc','price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['orderBy'] && $this->_tpl_vars['orderDir'] == 'asc'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['sorting']; ?>
 - rosnąco</option> 
	    		<option value="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'productsearch','seo' => $this->_tpl_vars['searchPhrase'],'page' => 1,'sort' => $this->_tpl_vars['key'],'dir' => 'desc','price' => $this->_tpl_vars['priceRange'],'producers' => $this->_tpl_vars['currentProducers'],'attributes' => $this->_tpl_vars['currentAttributes']), $this);?>
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
<script type="text/javascript">
<?php echo '
$(document).ready(function(){
	$(\'#filter\').show();
});
'; ?>

</script>