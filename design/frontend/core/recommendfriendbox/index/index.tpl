<a href="#reccomend-form" class="button recommend"><span>{trans}TXT_SEND_RECOMMENDATION{/trans}</span></a>

<div style="display: none;">
<div id="reccomend-form" style="width: 400px; height: 360px;">
	{fe_form form=$recommendform render_mode="JS"}
</div> 
</div>
<script type="text/javascript">
{literal}
$(".recommend").fancybox({
	'overlayShow'	:	true,
	'width'			: 280,
	'height'			: 100,
	'speedIn'		:	600, 
	'speedOut'		:	200, 
	'scrolling'		: 	'no',
});
{/literal}
</script>