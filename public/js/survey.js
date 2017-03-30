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
