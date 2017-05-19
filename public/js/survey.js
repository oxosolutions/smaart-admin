$(document).ready(function(){

	viewType = $("#viewType").val();
	if(viewType=="survey")
	{
		var nameArray = {};
		var maxIndex =0;
			$('.survey-form input , select, textarea').each(function(index){
				name = $.trim($(this).attr('class'));
				type = $.trim($(this).attr('type'));
				if(name =='_token'|| name =='survey_started_on'|| name =='survey_id'|| name =='code'|| name=='button')
				{}
				else{
						if(name)
						{
							nameArray[name] = name;
						}
					}
				maxIndex = index;
			});

			console.log(nameArray);
		$('.survey-form ').on('blur','input, select, textarea',function(){
				countQues =0;
				$.each(nameArray, function( index, value ) {  

					type = $("."+value).attr('type');
					//console.log('type---'+type);

					if(type =="radio" )
					{
						val = $("."+value+":checked").val();

					}else{
					val  = $.trim($("."+value).val());
					//console.log(val);
					}

					if(val)
					{
						console.log(value);
						// mark = $("#mark_"+value).html('<b style="color:Green;">Filled</b>');
						mark = $("#mark_"+value).parent().css({'background':'rgba(0, 128, 0, 0.2)'});
						console.log(mark);
							countQues++;	
					}else{
						$("#mark_"+value).parent().css({'background':'rgba(255, 0, 0, 0.2)'});
						// $("#mark_"+value).html('<b style="color:red;">Pending</b>');
					}
				});
			console.log(countQues);

				$("#sum_filled_ques").html(countQues);
				$("#progress").val(countQues);
				$('.aione-progress-inside').css({'width':countQues+'%'});

				// $( window ).bind("resize", function(){
				//     $(".aione-progress-inside").width( 600 );
				// });

			});


	}
});


// function filled_question(nameArray)
// {
// 	console.log(nameArray);
// 	var countQues=0;
// 	var checkFill;
// 	$.each(nameArray, function( index, value ) {
//   		//console.log(index);
//   		// if(index=="radio")
//   		// {
//   			//debugger;
//   		// 		if($("input[name="+value+"]").isCheck())
//   		// 		{
//   		// 			checkFill = true;
//   		// 		}
//   		// }else
  		
// 		console.log(value);
//   		checkFill = $('#input_'+value).val();
  			
  		
//   		if(checkFill)
//   		{
//   			console.log(checkFill);

//   			countQues++;	
//   		}
// 	});
		
	// $('#survey_form_1 .text input').each(function(){
		
	// 	valll = $(this).val(); 
		
	// 	 if(valll)
	// 	 	{ 
	// 	 		countQues++;
	// 	 	}
	// });
// $("#progress").val(countQues);
// 	console.log(countQues);
// }

$(function() {
	
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"   
  $.validate({
  	lang:'en'
  });
});

$(function(){
	

	var expire_time = $("#survey_timer").attr('data-expire-time');
	$("#survey_timer").countdowntimer({
		dateAndTime : expire_time,
		size : "lg",
		timeUp : timeisUp
	});
});

function timeisUp(){
	//$(".survey-form").submit(); 
}
