$(function(){
	$('body').on('click','.editSVG',function(){
		window.dataID = $(this).attr('data-id');
		$.ajax({
			type:'GET',
			url: route()+'/map/svg/'+dataID,
			success: function(res){
				$('#mapEditor').html(res);
				$('.overlay').fadeIn(200);
				$('#mapEditor').fadeIn(200);
			}

		});
	});

});