$(document).ready(function() {
	
	GCore.Init();
	$('#layout-box-35 ul').GAccordion();
	$('#layout-box-36 ul').GAccordion();
	$('#layout-box-43 ul').GAccordion();
	$('#productTabs').tabs({ fx: { opacity: 'toggle', duration: 300 } });
	if($.browser.msie){
		$('input').each(function(){
			if($(this).attr('type') != 'submit'){
				$(this).GSelect();
			}
		});
	}else{
		$('input:not[submit]').GSelect();
	}
	
    $('select').GSelect();
    $('radio').GSelect();
    $('textarea').GSelect();
    $(".thumbs a, a.fancy, .product-photos a").fancybox({
    	'transitionIn'	:	'elastic',
    	'transitionOut'	:	'elastic',
    	'speedIn'		:	600, 
    	'speedOut'		:	200, 
    	'overlayShow'	:	true,
    	'autoScale' 	:	true
    });
	
	$('.add-to-cart a').live('click', function(){
		$.fancybox({
			'overlayShow'	:	true,
			'autoScale' 	:	true,
			'type'			: 'iframe',
			'width'			: 780,
			'height'			: 300,
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'scrolling'		: 	'no',
			'href' : $(this).attr('rel')
		});
		return false;
	});

	$('span.sflink').each(function(){
		$(this).replaceWith($('<a>').attr('href',Base64.decode($(this).attr('class').split(' ').pop())).text($(this).text()));
    });
	
	$('#product-search-phrase').GSearch(); 
	
	$('.current').parent().parent().parent().addClass('active');
	
	$('span.mailme').each(function(){
		var spt = $(this);
		var at = / at /;
		var dot = / dot /g;
		var addr = $(spt).text().replace(at,"@").replace(dot,".");
		$(spt).after('<a href="mailto:'+addr+'">'+ addr +'</a>');
		$(spt).remove();
	});
});

