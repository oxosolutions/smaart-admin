<style type="text/css">
  .add_row .fa-share{
        color: #7A9BBE;
        padding: 6px;
        margin-left: 10px;
        transform: rotate(272deg);
  }
  .add_row span{
    font-family: Comic Sans MS, sans-serif !important;
    color: #7A9BBE;
    font-size: 12px;
    height: 13px;
    line-height: 1em;
    text-shadow: 0 1px 0 #FFFFFF;
  }
  .add_row > .col-md-8 > button{
    float: right;
    margin-right: 13px;
  }
  .add_row{
    padding: 9px;
        background: #EAF2FA;
    border: #c7d7e2 solid 1px;
  }
  .row{
        margin-right: 0px;
    margin-left: 0px;
  }
</style>
<!-- /.box-header -->
<div class="box-body no-padding">
<input type="hidden" name="surrvey_id" value="{{$surrvey_id}}">
  <table class="table table-striped">
    <tr>
      <th style="width: 100px;">Field Order</th>
      <th>Field Label</th>
      <th>Field Name </th>
      <th >Field Type</th>
    </tr>
  </table>

  <div class="fields">
  @if($question)
                    <?php $index=1; ?>

            @foreach($question as $key => $val)
              <?php $fieldData =   json_decode($val->answer,true); ?>
              <div class="field-group form-main">
                <div class="row mainRow">
                    <div class="col-md-1 middle">
                        <div class="circle">{{$index}}</div>
                    </div>
                    <div class="col-md-4 action middle">
                        <span class="field-label">{{$val->question}}</span>
                        <div class="links"><a href="javascript:;" class="edit_fields">Edit</a> | <a href="javascript:;" class="duplicate">Duplicate</a> | <a href="javascript:;" class="del-field">Delete</a></div>
                    </div>
                    <div class="col-md-3 middle">
                      {{$fieldData['slug']}}
                    </div>
                    <div class="col-md-2 middle">
                        {{@$fieldData['type']}} 
                    </div>
                </div>
                <div class="fields_list" style="display: none;">
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Field Label*</span><br>
                <span class="field-description">This is the name which will appear on the EDIT page</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="ques[]" value="{{$val->question}}" class="form-control field-label-input" />
            </div>
        </div>    
               

        <div class="row field_row">
            <div class="col-md-3">
                <span class="field-title">Field Name*</span><br>
                <span class="field-description">Single word, no spaces, underscore and dashes allowed</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="fieldname[]" value="{{$fieldData['slug']}}" class="form-control field-name" />
            </div>
        </div>
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Type*</span>         
            </div>
            <div class="col-md-8 form-group">
               <select id="type_{{$index}}" class="select form-control type"  name="type[]">
                    <optgroup label="Basic">
                        <option value="" selected="selected">Select Type</option>
                        <option value="text" selected="selected">Text</option>
                        <option value="textarea">Text Area</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="password">Password</option>
                    </optgroup>
                    <optgroup label="Content">
                        <option value="image">Image</option>
                        <option value="file">File</option>
                    </optgroup>
                    <optgroup label="Choice">
                        <option value="select">Select</option>
                        <option value="multi_select">Multi Select</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio Button</option>
                    </optgroup>
                </select>
            </div>  
        </div>
                <script type="text/javascript"> $('#type_{{$index}}').val('{{ @$fieldData['type']}}'); </script>

        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Instructions</span><br>
                <span class="field-description">Instructions for authors. Shown when submitting data</span> 
            </div>
            <div class="col-md-8 form-group">
                <textarea class="form-control" rows="3" name="instruction[]" class="form-control">{{@$fieldData['instruction']}}</textarea>
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Description</span><br>
                <span class="field-description">Field Description</span> 
            </div>
            <div class="col-md-8 form-group">
                <textarea class="form-control" rows="3" name="question_desc[]" class="form-control">{{@$fieldData['question_desc']}}</textarea>
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Field Order</span><br>
                <span class="field-description">Question Order be in number [0-9]</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="question_order[]" value="{{@$fieldData['question_order']}}" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Media</span><br>
                <span class="field-description">Media mp3</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="media[]" value="{{@$fieldData['media']}}" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Pattern</span><br>
                <span class="field-description">Pattern</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="pattern[]" value="{{@$fieldData['pattern']}}" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Extra Options</span><br>
                <span class="field-description">Extra Options</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="extra_options[]" value="{{@$fieldData['extra_options']}}" class="form-control field-label-input" />
            </div>
        </div>
         <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Validation</span><br>
                <span class="field-description">validations</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="validation[]" value="{{@$fieldData['validation']}}" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Required?</span><br>
            </div>
            <div class="col-md-8 form-group">
            @if(@$fieldData['required']==1)
            
                <input type="radio" checked name="required[]" value="1"> yes
                <input type="radio"  name="required[]" value="0"> no
            @else
                <input type="radio"  name="required[]" value="1"> yes
                <input type="radio" checked name="required[]" value="0"> no
            @endif
                
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Placeholder Text</span><br>
                <span class="field-description">Appears within the input</span> 
            </div>
            <div class="col-md-8 form-group">
                <input type="text"  name="placeholder[]" value="{{@$fieldData['placeholder']}}" class="form-control" />
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Formatting</span><br>
                <span class="field-description">Affects value on front end</span> 
            </div>
            <div class="col-md-8 form-group">
                 <select id="format" class="select form-control type"  name="format[]">
                 
                    <option value="No_Formatting" selected="selected">No Formatting</option>
                    <option value="text" selected="selected">Convert HTML into tags</option>
                  
               
                </select>
            </div>  
        </div>
        <script type="text/javascript"> $('#format').val('{{ @$fieldData['format']}}'); </script>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Conditional Logic</span><br>
                
            </div>
            <div class="col-md-8 form-group">
           @if(@$fieldData['conditions']=='yes') 

                <input type="radio" checked name="conditional_logic[]" value="yes"> yes
                <input type="radio" name="conditional_logic[]" value="no"> no
            @else
                <input type="radio"  name="conditional_logic[]" value="yes"> yes
                <input type="radio" checked name="conditional_logic[]" value="no"> no
            @endif
            </div>  
        </div>
@if(isset($fieldData['minimum']))
        <div class="row field_row ">
            <div class="col-md-3" >
                <span class="field-title">Minimum Value</span><br>
                <span class="field-description">Add the Minimum Value</span>
            </div>
            <div class="col-md-8 form-group">
                <input type="number" name="minimum[]" value="{{@$fieldData['minimum']}}" class="form-control">
            </div>  
        </div>
        <div class="row field_row ">
            <div class="col-md-3" >
                <span class="field-title">Maximum Value</span><br>
                <span class="field-description">Add the Maximum Value</span>
               
            </div>
            <div class="col-md-8 form-group">
                <input type="number" name="maximum[]" value="{{@$fieldData['maximum']}}" class="form-control">
            </div>  
        </div>
@endif        
@if(isset($fieldData['option']))
           <div class="row field_row  ">
           
            <div class="col-md-12 form-group">
                <button style="width:150px;" class="view_option Normal btn btn-block btn-success">
                                Show/Hide Option
                </button><br>           
              </div>  
          </div>
        <div class="row field_row choice">
            <div class="col-md-3" >
                <span class="field-title">Choices</span><br>
                <span class="field-description">Enter each choice on a new line.<br>For more control, you may specify both a value and label like this:<br>red : Red<br>blue : Blue</span><br>
            </div>
            <div class="col-md-8 form-group choice-option" >
                <button style="width:150px;" class="add_field_option Normal btn btn-block btn-success">
                                Add Option
                </button><br>
               
                 <?php  $aKey = $index -1; ?>

                  @foreach($fieldData['option']['value'] as $key => $value)
                    
                <div>
                  <div class="col-md-12" style="margin-bottom:15px;">
                    <div class="col-xs-5">
                      <input class="form-control"  type="text" name="option[key][{{$aKey}}][]" value="{{$fieldData['option']['key'][$key]}}">
                    </div>
                    <div class="col-xs-5"> 
                     <input class="form-control"  type="text" name="option[val][{{$aKey}}][]" value="{{$value}}"> 
                    </div>
                    <a href="#" class="remove_option "> remove   </a>
                  </div>    

                  <div class="col-md-12" style="margin-bottom:15px;">
                        <div class="col-xs-5">
                          <label>Option Next </label>
                        </div> 
                        <div class="col-xs-5">
                          <input class="form-control"  type="text" name="option[option_next][{{$aKey}}][]" value="{{@$fieldData['option']['option_next'][$key]}}">
                       </div>
                  </div>
                  <div class="col-md-12" style="margin-bottom:15px;">
                    <div class="col-xs-5"><label>Option Status </label> </div>
                      <div class="col-xs-5"> 
                       <input class="form-control"  type="text" name="option[option_status][{{$aKey}}][]" value="{{@$fieldData['option']['option_status'][$key]}}"> 
                      </div>
                  </div>
                    <div class="col-md-12" style="margin-bottom:15px;">
                    <div class="col-xs-5"><label>Option Prompt </label> </div>
                      <div class="col-xs-5">  
                      <input class="form-control"  type="text" name="option[option_prompt][{{$aKey}}][]" value="{{@$fieldData['option']['option_prompt'][$key]}}">
                     </div>
                    </div>
              </div>
                  @endforeach
                
             </div>  
        </div>
@endif        

        <div class="row field_row">
            <div class="col-md-3">
                <span class="field-title">Message</span><br>
                <span class="field-description">eg. Show extra content</span> 
            </div>
            <div class="col-md-8 form-group">
              <input type="text" class="form-control" value="{{@$fieldData['message']}}"  name="message[]" />
            </div>  
        </div>
        
</div>
                </div>
                
                <?php $index++; ?>
            @endforeach
        @else      
    <p class="no_field">No fields. Click the + Add Field button to create your first field. </p>
 @endif
  </div>

  <div class="row add_row">
  		<div class="col-md-4 ">
        <i class="fa fa-share" aria-hidden="true"></i>
        <span>Drag and drop to reorder</span>
      </div>
      <div class="col-md-8">
        <button class="btn btn-sm btn-primary add-field">
        Add Field 
        </button>
      </div>
  </div>
</div>
<!-- /.box-body -->
        
<style type="text/css">
  .mainRow{
      /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#7abcff+0,60abf8+14,107eed+80 */
      /* background: rgb(122,188,255); Old browsers
       background: -moz-linear-gradient(top, rgba(122,188,255,1) 0%, rgba(96,171,248,1) 14%, rgba(16,126,237,1) 80%); FF3.6-15
       background: -webkit-linear-gradient(top, rgba(122,188,255,1) 0%,rgba(96,171,248,1) 14%,rgba(16,126,237,1) 80%); Chrome10-25,Safari5.1-6
       background: linear-gradient(to bottom, rgba(122,188,255,1) 0%,rgba(96,171,248,1) 14%,rgba(16,126,237,1) 80%); W3C, IE10+, FF16+, Chrome26+, Opera12+, */ 
      /* filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7abcff', endColorstr='#107eed',GradientType=0 );  *//* IE6-9 */
      height: 58px;
      color: #000;
      border: 1px solid #E8E8E8;
      
  }
  .order{
      text-align: center;
      width: 90px;
  }
  .links{
      font-weight: 100;
      display: none;
      font-size: 12px;
      width: 100%;
  }
  .action:hover .links{
      display: block;
  }
  .action{
      height: 50px;
      color: #0073aa;
      padding-left: 2.5%;
  }
  .links a {
      color: #0073aa;
      position: relative;
  }
  .links a:hover{
      text-decoration: underline;
  }
  .field-label{
      font-weight: 700;
  }
  .circle{
      border-radius: 50px;
      border: 1px solid #CCC;
      width: 28px;
      line-height: 25px;
      text-align: center;
      cursor: move;
      margin-left: 40%;
  }
  .no_field{
      border:1px solid #CCC;
      height: 60px;
      padding-top: 1.5%;
      padding-left: 1%;
  }
  .middle{
      padding-top: 1.5%;
  }
</style>

<style type="text/css">
.field_row{
    border:1px solid #ededed;
}
.form-main .form-group{
    margin-top: 15px;
}
.field_row > .col-md-3{
    padding: 15px;
    background: #f9f9f9;

    height: auto;
}
.field-title{
    font-weight: 700;

}
.field-description{
    font-size: 14px;
    color: #696969;
}
.bg-color{
    background-color: #f9f9f9;
   /* box-shadow: inset -2px 0px 12px -8px rgba(0,0,0,0.75);*/
}
</style>
<style type="text/css">
    .field-group .label-form{
        min-height: 75px;
        height: auto;
    }
    li{
        list-style: none
    }
</style>