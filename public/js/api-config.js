$(function(){

	'use-strict'

	$(document).on('click','.put-token',function(e){
	
		$(this).html('');
		var replaced = $(this).parent("p").parent('div').html().replace('YOUR_UNIQUE_USER_TOKEN',$('input[name=token_user]').val());
		$(this).parent("p").parent('div').html(replaced);
		
	});
}())