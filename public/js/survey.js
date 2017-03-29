$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $.validate({
  	lang:'en'
  });
});

$(function(){
	$("#survey_timer").countdowntimer({
		hours : $(this).attr('data-hours'),
		minutes : $(this).attr('data-minutes'),
		seconds : $(this).attr('data-seconds'),
		size : "lg",
		timeUp : timeisUp
	});
});

function timeisUp(){
	//$("#post").submit();
}
