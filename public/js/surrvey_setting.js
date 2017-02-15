$(function(){
$("#expire_time").attr('checked', 'checked');





	$('.dates').datepicker({
          dateFormat: 'yy-mm-dd',
          autoclose: true,
    });
	$("#role_based , #individual_based ,#auth_req").slideUp();
	$(".SCHenable ,#timer_types, #durnation , #res_lmt").hide();

	$(document).on('click','.auth_req',function(e)
	{
		$("#auth_req").slideUp();
			vReq = $(this).val();
			if(vReq=="enable")
			{
			$("#auth_req").slideDown();
			}
			else{
							$("#auth_req").slideUp();
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