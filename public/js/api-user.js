$(function(){
		max =6;
		index=1;
		$(document).on('click','.add_more_option' , function(e)
		{
			//index = $(".field_row .cont").length+1;
			
			e.preventDefault();
			if(index<max)
			{
				option ='<div class="cont"><div class="col-md-12" style="margin-bottom:15px;"><div class="index" style="display: inline-block;float: left;line-height: 2;">'+index+ '</div><div class="col-xs-6"><input class="form-control"  type="text" name="key[]" value="Fill up key"> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="value[]" value="Fill up value"> </div><a href="#" class="remove_field "> Remove   </a></div>';			
				$(this).parent().parent('.field_row').append(option);
				index++;
			}
		});
		$(document).on('click','.remove_field', function(e){
			e.preventDefault();
			$(this).parent().remove();
			index--;
		});
		
	$("#other").slideUp();
	$(document).on('change','#org',function(e){
		org =	$(this).val();
			if(org=="other")
			{
				$("#other").slideDown();
			}
			else{
				$("#other").slideUp();
			}

	});

	$("#file-3").fileinput({
	    showUpload: false,
	    showCaption: false,
	    browseClass: "btn btn-primary btn-sm",
	    fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
	});
	$(".select2").select2({
		tags: true,
		maximumSelectionLength: 1
	});

	$(".select2-department").select2();
}());