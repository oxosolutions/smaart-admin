<div class="field-group">
    <div class="row mainRow">
        <div class="col-md-1 middle">
            <div class="circle">1</div>
        </div>
        <div class="col-md-4 action middle">
            <span class="field-label">Update software</span>
            <div class="links"><a href="#">Edit</a> | <a href="#">Duplicate</a> | <a href="javascript:;" class="del-field">Delete</a></div>
        </div>
        <div class="col-md-3 middle">
            Test
        </div>
        <div class="col-md-2 middle">
            Hey
        </div>
    </div>

    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Label*</span><br>
            <span class="field-description">This is the name which will appear on the EDIT page</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Name*</span><br>
            <span class="field-description">Single word, no spaces, underscore and dashes allowed</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Type*</span>         
        </div>
        <div class="col-md-8 label-form">
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
                    <option value="wysiwyg">Wysiwyg Editor</option>
                    <option value="image">Image</option>
                    <option value="file">File</option>
                </optgroup>
                <optgroup label="Choice">
                    <option value="select">Select</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="radio">Radio Button</option>
                    <option value="true_false">True / False</option>
                </optgroup>
                <optgroup label="Relational">
                    <option value="page_link">Page Link</option>
                    <option value="post_object">Post Object</option>
                    <option value="relationship">Relationship</option>
                    <option value="taxonomy">Taxonomy</option>
                    <option value="user">User</option>
                </optgroup>
                <optgroup label="jQuery">
                    <option value="google_map">Google Map</option>
                    <option value="date_picker">Date Picker</option>
                    <option value="color_picker">Color Picker</option>
                </optgroup>
                <optgroup label="Layout">
                    <option value="message">Message</option>
                    <option value="tab">Tab</option>
                </optgroup>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Instructions</span><br>
            <span class="field-description">Instructions for authors. Shown when submitting data</span> 
        </div>
        <div class="col-md-8 label-form">
            <textarea class="form-control"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Required?</span><br>
          
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Appears when creating a new post</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Placeholder Text</span><br>
            <span class="field-description">Appears within the input</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Prepend</span><br>
            <span class="field-description">Appears before the input</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Append</span><br>
            <span class="field-description">Appears after the input</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Formatting</span><br>
            <span class="field-description">Affects value on front end</span> 
        </div>
        <div class="col-md-8 label-form">
             <select id="type" class="select form-control type"  name="type[]">
               
                <option value="" selected="selected">No Formatting</option>
                <option value="text" selected="selected">Convert HTML into tags</option>
              
           
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Character Limit</span><br>
            <span class="field-description">Leave blank for no limit</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            <span class="field-description">Leave blank for no limit</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
</div>
<style type="text/css">
.field-row{
    border-bottom:1px solid #e8e8e8;
}
.label-form{
    padding-top:10px;
    padding-left: 30px;
    padding-bottom: 10px;
    border-right: 1px solid #e8e8e8;
    height:auto;
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