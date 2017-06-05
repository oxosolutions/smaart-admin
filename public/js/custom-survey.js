$(document).ready(function(){

	$(document).on('click','.menu-button',function(){
		$('#aione_sidenav_1').removeClass('aione-hide');
		$('#aione_sidenav_1').show();
		$('.fade-background').addClass('aione-block');
	});

	$(document).on('click','.fade-background',function(){
		$('#aione_sidenav_1').hide();
		$('.fade-background').removeClass('aione-block');
	});

	$('.aione-arrow').toggleClass('aione-arrow-left');
	$('.aione-arrow').parents('.root').find('ul').slideToggle();

	$(document).on('click','.aione-arrow',function(){
		$(this).toggleClass('aione-arrow-left');
		$(this).parents('.root').find('ul').slideToggle();
	});

	$(document).on('click','.menu-close',function(){
		$('#aione_sidenav_1').hide();
		$('.fade-background').removeClass('aione-block');
	});

});