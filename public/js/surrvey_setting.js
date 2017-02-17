$(function(){
	 //iCheck for checkbox and radio inputs
    // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    //   checkboxClass: 'icheckbox_minimal-blue',
    //   radioClass: 'iradio_minimal-blue'
    // });
    // //Red color scheme for iCheck
    // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    //   checkboxClass: 'icheckbox_minimal-red',
    //   radioClass: 'iradio_minimal-red'
    // });
    // //Flat red color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //   checkboxClass: 'icheckbox_flat-green',
    //   radioClass: 'iradio_flat-green'
     
    // });

	$("#role_based , #individual_based ").slideUp();
	$(".SCHenable ,#timer_types, #durnation , #res_lmt").hide();

//$("#expire_time").attr('checked', 'checked');

		if($('input[name=authentication_required]:checked').val()=="enable")
		{
			$("#auth_req").slideDown();
			if($('input[name=authentication_type]:checked').val()=="role_based")
			{ 
				$("#role_based").slideDown();
			}else if($('input[name=authentication_type]:checked').val()=="individual_based")
	 			$("#individual_based").slideDown();
		}else{
				$("#auth_req").slideUp();
		}
		if($('input[name=scheduling]:checked').val()=="enable")
		{
			$(".SCHenable").show();
		}
		if($('input[name=timer_status]:checked').val()=="enable")
		{
			$("#timer_types").show();
		}
		if($('input[name=timer_type]:checked').val()=="durnation")
		{
			$("#durnation").show();
		}
		if($('input[name=response_limit_status]:checked').val()=="enable")
		{
			$("#res_lmt").show();
		}
		if($('input[name=error_messages]:checked').val()=="enable")
		{
			$(".mess").show();
		}else{
			$(".mess").hide();
		}

	$('.dates').datepicker({
          dateFormat: 'yy-mm-dd',
          autoclose: true,
    });
	
	$(document).on('click','.auth_req',function(e)
	{
		
		
		$("#auth_req").slideUp();
			vReq = $(this).val();
			if(vReq=="enable")
			{
			$("#auth_req").slideDown();
			if($('input[name=authentication_type]:checked').val()=="role_based")
			{ 
				$("#role_based").slideDown();
			}else if($('input[name=authentication_type]:checked').val()=="individual_based")
	 			{
	 				$("#individual_based").slideDown();
	 			}
			
			}
			else{
					$("#auth_req").slideUp();
					$("#role_based , #individual_based").slideUp();
			}
	});
	$(document).on('click','.auth_type',function(e)
	{
		$("#role_based , #individual_based").slideUp();
			v = $(this).val();
			$("#"+v).slideDown();
	});

	$(document).on('click','.timer',function(e){
		timer_val = $(this).val();
		if(timer_val=="enable")
		{
			$("#timer_types").show(1000)
		}else{
			$("#timer_types").hide(1000)
			$("#durnation").hide(1000)
		}
	});
	$(document).on('click','.time_types',function(e){
		timer_val = $(this).val();
		if(timer_val=="durnation")
		{
			$("#durnation").show(1000)
		}else{
			$("#durnation").hide(1000)
		}
	});


	$(document).on('click','.res_lmt',function(e){
		timer_val = $(this).val();
		if(timer_val=="enable")
		{
			$("#res_lmt").show(1000)
		}else{
			$("#res_lmt").hide(1000)
		}
	});
	

	$(document).on('click','.scheduling',function(e){
			val = $(this).val();
			if(val=='enable')
			{
				$(".SCH"+val).show(1000);
			}else{
				$(".SCHenable").hide(1000);
			}
	});
	//ERROR MESSAGE
	$(document).on('click','.error_messages',function(e){
			val = $(this).val();
			if(val=='enable')
			{
				$(".mess").slideDown(1000);
			}else{
				$(".mess").slideUp(1000);
			}
	});
	

});