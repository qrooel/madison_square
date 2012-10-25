<script type="text/javascript" src="{$DESIGNPATH}_js_libs/jquery.nivo.slider.js"></script>
<div id="slider-{$id}" class="nivoSlider" style="height:{$height}px;display:block">
{section name=s loop=$slideshow}
 <a href="{$slideshow[s].url}"><img src="{$DESIGNPATH}{$slideshow[s].image}" alt="" title="{$slideshow[s].caption}" /></a>
{/section}
</div>
<script type="text/javascript">
{literal}
$(window).load(function() {
	$('#slider-{/literal}{$id}{literal}').nivoSlider({
        effect:'fade', // Specify sets like: 'fold,fade,sliceDown'
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
{/literal}
</script>