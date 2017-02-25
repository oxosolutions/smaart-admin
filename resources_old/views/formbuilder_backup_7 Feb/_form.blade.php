


{{-- <div class="input_fields_wrap">
    <button class="add_field_button">Add More Fields</button>
    <div><input type="text" name="mytext[]"></div>
</div> --}}
<div class="box-body">

  <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!!Form::label('name','Surrvey Title') !!}
    {!!Form::text('name',null, ['class'=>'form-control','placeholder'=>'Surrvey Title']) !!}
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('surrvey_desc') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('surrvey_desc','Surrvey Description') !!}
    {!!Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter Map Description','id'=>'surrvey_desc']) !!}
    @if($errors->has('surrvey_desc'))
      <span class="help-block">
            {{ $errors->first('surrvey_desc') }}
      </span>
    @endif
  </div>
<div class="input_fields_wrap">
  
</div>
    <button style="width:150px;" class="add_field_button Normal btn btn-block btn-success">Add Question</button><br>

 

</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
  
  $(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var wrapper_option  = $(".wrapper_option"); //Fields wrapper
    var add_option      = $(".add_field_option");
    var option_max      = 5;

    var option =1; 
//Add Option

  $(document).on('click','.add_field_option',function(e){
    alert(x);
          e.preventDefault();
          option++;
          options ="";
          if(option < option_max)
          {
            options ='<div class="form-group"><label>Option '+option +' </label> <input class="form-control" type="text" name="ans[option]['+x+'][]" value="Fill up option"><a href="#" class="remove_option "> remove   </a></div>';
            //$(this).parent('div')
            $(".opt_"+x).append(options);
          }


    });
    

    var x = 0; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
      option =1;
       x++;
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            //text box increment
           ques = '<div > <a href="#" class="remove_field "> remove   </a><div class="form-group  " style="margin-top: 2%;">    <label for="question"> Question '+x+'</label>    <input class="form-control" placeholder="Question text" id="ques" name="question['+x+']" type="text">  </div><div class="form-group  " style="margin-top: 2%;">    <label for="slug"> Slug </label>    <input class="form-control" placeholder="slug" id="ques" name="slug['+x+']" type="text">  </div><div class="form-group  " style="margin-top: 2%;">    <label for="question"> Question Type</label><select   id="type" class="select form-control" name="type['+x+']"><optgroup label="Basic"><option value="" selected="selected">Select Type</option><option value="text" selected="selected">Text</option><option value="textarea">Text Area</option><option value="number">Number</option><option value="email">Email</option><option value="password">Password</option></optgroup><optgroup label="Content"><option value="wysiwyg">Wysiwyg Editor</option><option value="image">Image</option><option value="file">File</option></optgroup><optgroup label="Choice"><option value="select">Select</option><option value="checkbox">Checkbox</option><option value="radio">Radio Button</option><option value="true_false">True / False</option></optgroup><optgroup label="Relational"><option value="page_link">Page Link</option><option value="post_object">Post Object</option><option value="relationship">Relationship</option><option value="taxonomy">Taxonomy</option><option value="user">User</option></optgroup><optgroup label="jQuery"><option value="google_map">Google Map</option><option value="date_picker">Date Picker</option><option value="color_picker">Color Picker</option></optgroup><optgroup label="Layout"><option value="message">Message</option><option value="tab">Tab</option></optgroup></select></div><div class="ans  wrapper_option opt_'+x+'"></div></div>';
            $(wrapper).append(ques); //add input box
        }
        
    });

    

    $(wrapper).on("click",".remove_option", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); option--;
    })
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

//ANSWER fIELD
    $(wrapper).on("change","#type", function(e){ //user click on remove text
        e.preventDefault(); 
        type = $(this).val();
        //typeVal = "";
        if(type=="text")
        {
          typeVal = '<label for="question"> Text Answer '+x+'</label>    <input class="form-control" placeholder="" id="ques" name="ans['+x+']" type="text">';
        }else if(type=="textarea"){
           typeVal = '<label for="question"> Text Answer '+x+'</label>    <input class="form-control" placeholder="" id="ques" name="ans['+x+']" type="text">';

        }else if(type=="checkbox" || type=="radio" ){
         
            typeVal ='<button style="width:150px;" class="add_field_option Normal btn btn-block btn-success">Add Option</button><br>';

            typeVal += '<div class="form-group" ><label for="question">  Option '+option+'</label>';
            typeVal +='<input class="form-control"  type="text" name="ans[option]['+x+'][]" value="Fill up option"></div>';

        }
        $(this).parent('div').siblings('.ans').html(typeVal);
             
    })

});
  
</script>
<!-- /.box-body -->
