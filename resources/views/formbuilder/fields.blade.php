<div class="field-group form-main">
    <div class="row mainRow">
        <div class="col-md-1 middle">
            <div class="circle">1</div>
        </div>
        <div class="col-md-4 action middle">
            <span class="field-label">Field Label</span>
            <div class="links"><a href="javascript:;" class="edit_fields">Edit</a> | <a href="javascript:;" class="duplicate">Duplicate</a> | <a href="javascript:;" class="del-field">Delete</a></div>
        </div>
        <div class="col-md-3 middle">
            Test
        </div>
        <div class="col-md-2 middle">
            Hey
        </div>
    </div>
    <div class="fields_list" style="display: none;">
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Field Label*</span><br>
                <span class="field-description">This is the name which will appear on the EDIT page</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="ques[]" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
                <span class="field-title">Field Name*</span><br>
                <span class="field-description">Single word, no spaces, underscore and dashes allowed</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="fieldname[]" class="form-control field-name" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Type*</span>         
            </div>
            <div class="col-md-8 form-group">
               <select id="type" class="select form-control type"  name="type[]">
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
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Instructions</span><br>
                <span class="field-description">Instructions for authors. Shown when submitting data</span> 
            </div>
            <div class="col-md-8 form-group">
                <textarea class="form-control" rows="3" name="instruction[]" class="form-control"></textarea>
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Field Description</span><br>
                <span class="field-description">Field Description</span> 
            </div>
            <div class="col-md-8 form-group">
                <textarea class="form-control" rows="3" name="question_desc[]" class="form-control"></textarea>
            </div>  
        </div>
         <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Field Order</span><br>
                <span class="field-description">Question Order be in number [0-9]</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="question_order[]" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Media</span><br>
                <span class="field-description">Media mp3</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="media[]" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Pattern</span><br>
                <span class="field-description">Pattern</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="pattern[]" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Extra Options</span><br>
                <span class="field-description">Extra Options</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="extra_options[]" class="form-control field-label-input" />
            </div>
        </div>
         <div class="row field_row">
            <div class="col-md-3">
            <span class="field-title">Validation</span><br>
                <span class="field-description">validations</span>
            </div>
            <div class="col-md-8 form-group">
                 <input type="text" name="validation[]" class="form-control field-label-input" />
            </div>
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Required?</span><br>
            </div>
            <div class="col-md-8 form-group">
                <input type="radio" name="required[]" value="1"> yes
                <input type="radio" name="required[]" value="0"> no
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Placeholder Text</span><br>
                <span class="field-description">Appears within the input</span> 
            </div>
            <div class="col-md-8 form-group">
                <input type="text"  name="placeholder[]" class="form-control" />
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Formatting</span><br>
                <span class="field-description">Affects value on front end</span> 
            </div>
            <div class="col-md-8 form-group">
                 <select  class="select form-control type"  name="format[]">
                   
                    <option value="No Formatting" selected="selected">No Formatting</option>
                    <option value="text" selected="selected">Convert HTML into tags</option>
                  
               
                </select>
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3" >
                <span class="field-title">Conditional Logic</span><br>
                
            </div>
            <div class="col-md-8 form-group">
                <input type="radio" name="conditional_logic[]" value="yes"> yes
                <input type="radio" name="conditional_logic[]" value="no"> no
            </div>  
        </div>
        <div class="row field_row number">
            <div class="col-md-3" >
                <span class="field-title">Minimum Value</span><br>
                <span class="field-description">Add the Minimum Value</span>
            </div>
            <div class="col-md-8 form-group">
                <input type="number" name="minimum[]" class="form-control">
            </div>  
        </div>
        <div class="row field_row number">
            <div class="col-md-3" >
                <span class="field-title">Maximum Value</span><br>
                <span class="field-description">Add the Maximum Value</span>
               
            </div>
            <div class="col-md-8 form-group">
                <input type="number" name="maximum[]" class="form-control">
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
               
            </div>  
        </div>
        <div class="row field_row">
            <div class="col-md-3">
                <span class="field-title">Message</span><br>
                <span class="field-description">eg. Show extra content</span> 
            </div>
            <div class="col-md-8 form-group">
                <input type="text" class="form-control"  name="message[]" />
            </div>  
        </div>
        
</div>
