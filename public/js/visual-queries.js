$(function(){
	$(".select2").select2();

	$('.visual').change(function(){
		window.location.href= 'create/'+$(this).val();
	});

	$('#genData').click(function(){
		if($('.filterCol').val() == null){
			$('#mesg').html('Please select columns!');
			$('#mesg').fadeIn(200);
			return false;
		}
		var formdata = new FormData();
		formdata.append('columns',JSON.stringify($('.filterCol').val()));
		formdata.append('_token',$('input[name=_token]').val());
		formdata.append('db_table',$('input[name=db_table]').val());
		$('#floatingBarsG').fadeIn(200);
		$.ajax({
			type: 'POST',
			url: route()+"/visual/query/getColValue",
			data: formdata,
			contentType: false,
			processData: false,
			success: function(res){
				$('#floatingBarsG').fadeOut(200);
				$('.columns_data').html(res);
				$(".select2").select2();
			}
		});
	});
	$('.filterCol').change(function(){
		$('#mesg').html('');
		$('#mesg').fadeOut(200);
	});

}());