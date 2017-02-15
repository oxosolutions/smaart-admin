$(function(){
$(".hid").hide();
	 $(document).on('change','#source',function(e){
		    e.preventDefault();
		    $(".hid").hide();
		   	idVal =  $(this).val();
		   $("#"+idVal).show();		    
		});

	$(".select2").select2();
	/*$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });*/
	$("#visual *").attr("disabled", "disabled").off('click');
	$('#getColumns').click(function(){
		if($('select[name=dataset_id]').val() == ''){
			$('#mesg').html('Please select dataset!');
			$('#mesg').fadeIn(200);
			return false;
		}else{
			$('#mesg').html('Getting columns from selected dataset, this step may take 10 to 20 seconds!');
			$('#mesg').fadeIn(200);
		}

		// $(this).attr('disabled','disabled').off('click');
		$('#floatingBarsG').fadeIn(200);
		var fadeEffect = setInterval(function(){
			$('#mesg').fadeToggle(300);
		},500);
		var DatasetID = $('select[name=dataset_id]').val();
		$.ajax({
			url:route()+"/dataset/columns/"+DatasetID,
			success:function(res){
				$('.datasetColumns').html(res);
				$("#visual *").attr("disabled", false);
				$('#mesg').fadeOut(200);
				$('#floatingBarsG').fadeOut(200);
				$(".select2").select2();
				clearInterval(fadeEffect);
				/*$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
			      checkboxClass: 'icheckbox_minimal-blue',
			      radioClass: 'iradio_minimal-blue'
			    });*/
			}
		});
	});

	$('.select2').change(function(){
		$('#mesg').html('');
		$('#mesg').fadeOut(200);
	});

	if($('.model_id').val() != '' && $('.model_id').val() != undefined){
		$("#visual *").attr("disabled", false);
		$(".select2").select2();
	}

	$(document).on('click','.add-more', function(e){
		e.preventDefault();
		var DatasetID = $('select[name=dataset_id]').val();
		$.ajax({
			url:route()+"/dataset/columns/"+DatasetID+"/clone/"+parseInt($('.chart_count').length + 1),
			success: function(res){
				$('.repeat_div').append(res);
				$(".select2").select2();
				$('.chart_count:last').html($('.chart_count').length);
				/*$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
			      checkboxClass: 'icheckbox_minimal-blue',
			      radioClass: 'iradio_minimal-blue'
			    });*/
			}
		});
	});

	$(document).on('click','.delete-clone', function(e){
		e.preventDefault();
		$(this).parent('div').parent('div').parent('.clone').remove();
		var index = 1;
		$('.chart_count').each(function(ind){
			$(this).html(index);
			index++;
		});
	});

	/*$(document).on('click','.count-column', function(e){
		if($(this).is(':checked')){
			$(this).parent('label').parent('div').parent('div').find('.second_col').slideUp(200);
		}else{
			$(this).parent('label').parent('div').parent('div').find('.second_col').slideDown(200);
		}
	});*/

}());