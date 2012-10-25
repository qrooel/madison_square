<?php /* Smarty version 2.6.19, created on 2012-10-09 07:03:09
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productprintbox/index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productprintbox/index/index.tpl', 5, false),array('function', 'seo', '/home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/productprintbox/index/index.tpl', 11, false),)), $this); ?>
<h1><a href="<?php echo $this->_tpl_vars['URL']; ?>
"><span><img src="design/_images_frontend/core/logos/<?php echo $this->_tpl_vars['SHOP_LOGO']; ?>
" alt="<?php echo $this->_tpl_vars['SHOP_NAME']; ?>
"/></span></a></h1>

	<div class="product-photos">
		<?php if (isset ( $this->_tpl_vars['product']['mainphoto']['normal']['path'] )): ?>
		<img class="mainphoto" src="design/<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['mainphoto']['normal'])) ? $this->_run_mod_handler('replace', true, $_tmp, $this->_tpl_vars['DESIGNPATH'], '') : smarty_modifier_replace($_tmp, $this->_tpl_vars['DESIGNPATH'], '')); ?>
">
		<?php endif; ?>
	</div>
	
	<div class="product-details">
		<?php if ($this->_tpl_vars['product']['producerphoto']['small'] <> ''): ?>
    	<a href="<?php if ($this->_tpl_vars['product']['producerurl'] != ''): ?><?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'redirect'), $this);?>
/<?php echo $this->_tpl_vars['product']['producerurl']; ?>
<?php else: ?>#<?php endif; ?>" target="_blank" class="producer-logo"><img src="design/<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['producerphoto'])) ? $this->_run_mod_handler('replace', true, $_tmp, $this->_tpl_vars['DESIGNPATH'], '') : smarty_modifier_replace($_tmp, $this->_tpl_vars['DESIGNPATH'], '')); ?>
" alt="<?php echo $this->_tpl_vars['product']['producername']; ?>
"></a>    
    	<?php else: ?>
    	<a href="<?php if ($this->_tpl_vars['product']['producerurl'] != ''): ?><?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'redirect'), $this);?>
/<?php echo $this->_tpl_vars['product']['producerurl']; ?>
<?php else: ?>#<?php endif; ?>" target="_blank" class="producer-logo"><?php echo $this->_tpl_vars['product']['producername']; ?>
</a>    
    	<?php endif; ?>
    	
               </div>
           
           	   <div id="productTabs" class="layout-box withTabs"> 

           
           <div class="boxContent"> 
                <div id="product-description" class="tabContent"> 
                 	<?php echo $this->_tpl_vars['product']['description']; ?>

                </div> 
                <div id="product-technical-data" class="tabContent"> 
                	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['technicalData']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                	<fieldset class="listing">
                	<h2><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['name']; ?>
</h2>
                	<dl> 
						<?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>	
							 <dt><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['name']; ?>
</dt>
							 <dd><?php echo $this->_tpl_vars['technicalData'][$this->_sections['i']['index']]['attributes'][$this->_sections['a']['index']]['value']; ?>
</dd>
						<?php endfor; endif; ?>	
					</dl> 
                	</fieldset>
                	
					
					<?php endfor; endif; ?>
                </div> 
                
                 
                
           </div> 
        
       </div> 