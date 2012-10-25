<?php /* Smarty version 2.6.19, created on 2012-10-08 09:35:24
         compiled from /home/qrooel/public_html/ac.vipserv.org/madison_square2/design/frontend/core/slideshowbox/index/index.tpl */ ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
_js_libs/jquery.nivo.slider.js"></script>
<div id="slider-<?php echo $this->_tpl_vars['id']; ?>
" class="nivoSlider" style="height:<?php echo $this->_tpl_vars['height']; ?>
px;display:block">
<?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['slideshow']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 <a href="<?php echo $this->_tpl_vars['slideshow'][$this->_sections['s']['index']]['url']; ?>
"><img src="<?php echo $this->_tpl_vars['DESIGNPATH']; ?>
<?php echo $this->_tpl_vars['slideshow'][$this->_sections['s']['index']]['image']; ?>
" alt="" title="<?php echo $this->_tpl_vars['slideshow'][$this->_sections['s']['index']]['caption']; ?>
" /></a>
<?php endfor; endif; ?>
</div>
<script type="text/javascript">
<?php echo '
$(window).load(function() {
	$(\'#slider-'; ?>
<?php echo $this->_tpl_vars['id']; ?>
<?php echo '\').nivoSlider({
        effect:\'fade\', // Specify sets like: \'fold,fade,sliceDown\'
        animSpeed:500, // Slide transition speed
        pauseTime:3000, // How long each slide will show
        startSlide:0, // Set starting Slide (0 index)
        directionNav:true, // Next & Prev navigation
        directionNavHide:false, // Only show on hover
        controlNav:true, // 1,2,3... navigation
        controlNavThumbs:false, // Use thumbnails for Control Nav
        pauseOnHover:true, // Stop animation while hovering
    });
});
'; ?>

</script>