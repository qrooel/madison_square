var aoQuickAccessPossibilites;

$(document).ready(function() {

	$.datepicker.regional['pl'] = GFormDate.Language;
	$.datepicker.setDefaults($.datepicker.regional['pl']);
	
	$('.block').GBlock();
	$('.box').GBox();
	$('select').GSelect();
	
	$('#message-bar').GMessageBar();
	
	if (aoQuickAccessPossibilites == undefined) {
		aoQuickAccessPossibilites = [];
	}
	$('#quick-access').GQuickAccess({
		aoPossibilities: aoQuickAccessPossibilites
	});
	
	$('#navigation').GMenu();
	
	$('.simple-stats .tabs').tabs({
		fx: {
			opacity: 'toggle',
			duration: 75
		}
	});
	
	$('.scrollable-tabs').GScrollableTabs();
	
	GCore.Init();	
	GLanguageSelector();
	GViewSelector();
	$('.order-notes').tabs();
	
	$('#navigation > li > ul > li > ul > li.active').parent().parent().parent().parent().addClass('active');
	$('#navigation > li > ul > li.active').parent().parent().addClass('active');
	$('#navigation > li > ul > li.active').parent().addClass('active');
	
});
