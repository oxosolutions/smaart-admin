$(function(){
	var optIndex = 1;
	var maxOpt =10;
	$(document).on('click','.remove_option',function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		optIndex--;
	});

	$(document).on('click','.view_option', function(e){
		e.preventDefault();
		$(this).parent().parent().siblings('.choice').toggle(1000);
	});

	$(document).on('click','.add_field_option',function(e){
		e.preventDefault();
		if(optIndex <maxOpt)
		{
		ques_no  = $(this).parent().parent().parent().siblings('div .mainRow').children('.middle').children('.circle').html();
		ques_no--;
		option ="";
		option ='<div><div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><input class="form-control"  type="text" name="option[key]['+ques_no+'][]" value="Fill up key"> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[val]['+ques_no+'][]" value="Fill up value"> </div><a href="#" class="remove_option "> remove   </a></div>';
		option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Next </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_next]['+ques_no+'][]" value="Option Next"> </div></div>';
		option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Status </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_status]['+ques_no+'][]" value="Option Next"> </div></div>';
		option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Prompt </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_prompt]['+ques_no+'][]" value="Option Next"> </div></div></div>';
		$(this).parent('div .choice-option').append(option);		
		optIndex++;
		}
	});
	$(document).on('change','#type',function(e){
		e.preventDefault();
		$(this).parent().parent().siblings('div .choice').children('div .choice-option').children('div .col-md-12').remove();
		$(this).parent().parent().siblings('.number').hide();
		typeVal = $(this).val();
		$(this).parent().parent().siblings('.'+typeVal).show();		
		if(typeVal=="checkbox" || typeVal=="radio" || typeVal=="multi_select" ){
			$(this).parent().parent().siblings('div .choice').show()
	        ques_no  = $(this).parent().parent().parent().siblings('div .mainRow').children('.middle').children('.circle').html();
	        ques_no--;
	       	option = "";
			option ='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><input class="form-control"  type="text" name="option[key]['+ques_no+'][]" placeholder="Fill up option key"> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[val]['+ques_no+'][]" placeholder="Fill up option value"> </div></div>';
			option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Next </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_next]['+ques_no+'][]" placeholder="Option Next"> </div></div>';
			option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Status </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_status]['+ques_no+'][]" placeholder="Option Status" > </div></div>';
			option +='<div class="col-md-12" style="margin-bottom:15px;"><div class="col-xs-5"><label>Option Prompt </label> </div>  <div class="col-xs-5">  <input class="form-control"  type="text" name="option[option_prompt]['+ques_no+'][]" placeholder="Option Next" > </div></div>';


			 $(this).parent().parent().siblings('div .choice').children('div .choice-option').append(option);
            }else{ 
        	    $('.choice').hide();
        }
	});

	$('.add-field').click(function(e){
		e.preventDefault();
		ajaxRequest('survey/field', function(result){
			$('.no_field').remove();
			$('.fields').append(result);
			$('.circle:last').html(($('.field-group').length));
			

		});
	});

	$('body').on('click','.del-field', function(){
		var elem = $(this).parents('.field-group');
		$(this).parents('.field-group').animate({
			'margin-left':'40%',
			'opacity':'0.5'
		},200, function(){
			elem.remove();
			ques_no--;
			if($('.field-group').length == 0){
				$('.fields').append('<p class="no_field">No fields. Click the + Add Field button to create your first field. </p>');
			}
		});
	});

	$('body').on('click','.edit_fields', function(){
			
		if($(this).parents('.form-main').find('.fields_list').hasClass('active-field')){
			$(this).parents('.form-main').find('.fields_list').slideUp(300).removeClass('active-field');
		}else{
			$(this).parents('.form-main').find('.fields_list').slideDown(300).addClass('active-field');
			$('.number').hide();
			$('.choice').slideUp();
			//$('.choice').slideDown();
		}
	});

	$('body').on('click','.duplicate', function(){
		var clone = $(this).parents('.form-main').clone();
		$('.fields').append(clone);
		$('.circle:last').html(($('.field-group').length));
	});

	$('.fields').sortable();

	$('body').on('keyup','.field-label-input',function(){
		$(this).parents('.form-main').find('.field-label').html($(this).val().substring(0, 30)+'...');
	});

	$('body').on('keyup','.field-name', function(){
		$(this).parents('.form-main').find('.field-label').html($(this).val().substring(0, 30)+'...');
	});

	var ajaxRequest = function(url,output){
		$.ajax({
			type: 'GET',
			url: route()+'/'+url,
			success: function(result){
				output(result)
			}
		});
	}
});

function selectOption(values)
{
	$("#type").val(values);
}