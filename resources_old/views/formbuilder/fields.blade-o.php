<style type="text/css">
    .field-group .label-form{
        min-height: 75px;
        height: auto;
    }
    li{
        list-style: none
    }
</style>
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
    <!--start field type :text -->
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
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!--end field type :text -->

    <!--start field type :textarea -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Appears when creating a new post</span> 
        </div>
        <div class="col-md-8 label-form">
            <textarea class="form-control"></textarea>
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
            <span class="field-title">Character Limit</span><br>
            <span class="field-description">Leave blank for no limit</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Rows</span><br>
            <span class="field-description">Sets the textarea height</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="number" class="form-control" />
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
                <option value="text" selected="selected">Convert new line into <br >  tags</option>
                <option value="text" selected="selected">Convert HTML into tags</option>
          
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!--end field type :textarea -->

    <!-- start field type number -->
     <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Appears when creating a new post</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="number" class="form-control"></input>
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
            <span class="field-title">Minimum Value</span><br>
           
        </div>
        <div class="col-md-8 label-form">
            <input type="number" class="form-control"></input>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Maximum Value</span><br>
           
        </div>
        <div class="col-md-8 label-form">
            <input type="number" class="form-control"></input>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Step Size</span><br>
           
        </div>
        <div class="col-md-8 label-form">
            <input type="number" class="form-control"></input>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!-- end field type number -->

    <!-- start field type email -->
     <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Appears when creating a new post</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control"></input>
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
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!-- end field type email -->

    <!-- start field type password -->
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
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!-- end field type password -->

    <!-- start field type Wysiwyg editor -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Appears when creating a new post</span> 
        </div>
        <div class="col-md-8 label-form">
            <textarea class="form-control"></textarea>
        </div>  
    </div> 
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Toolbar</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="Full"> Full
            <input type="radio" name="" value="Basic"> Basic
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Show Media Upload Buttons?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="yes"> yes
            <input type="radio" name="" value="no"> no
        </div>  
    </div>
    <!-- end field type Wysiwyg editor -->

    <!-- start field type image -->
     <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Return Value</span><br>
            <span class="field-description">Specify the returned value on front end</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="object"> Image Object
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="url"> Image URL
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="id"> Image ID
        </div>  
    </div>
     <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Preview Size</span><br>
            <span class="field-description">Shown when entering data</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="thumbnail"> Thumbnail
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="medium"> Medium
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="large"> Large
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="full"> Full
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="medium_large"> Medium Large
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="blog-large"> Blog Large
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="blog-mediume"> Blog Marge
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="tabs_img"> Tabs Image
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="related_img"> Related Img
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="portfolio_full"> Portfolio Full
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_one"> Portfolio One
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_two"> Portfolio Two
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_three"> Portfolio Three
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_four"> Portfolio Four
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_five"> Portfolio Five
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="psrtfolio_six"> Portfolio Six
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="recent_Posts"> Recent Posts
            <input type="radio" name="fields[field_580ddbcdd27b4][preview_size]" value="recent_works_thumbnail"> Recent Works Thumbnail
        </div> 
        <div class="row field-row" style="margin-left: 0">
            <div class="col-md-3 label-form bg-color" >
                <span class="field-title">Library</span><br>
                <span class="field-description">Limit the media library choice</span><br>
            </div>
            <div class="col-md-8 label-form">
                <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="all">All
                <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="uploadedTo"> Uploaded to post
            </div>  
        </div>
        <div class="row field-row" style="margin-left: 0">
            <div class="col-md-3 label-form bg-color" >
                <span class="field-title">Conditional Logic</span><br>
                
            </div>
            <div class="col-md-8 label-form">
                <input type="radio" name="" value="1"> yes
                <input type="radio" name="" value="0"> No
            </div>  
        </div>
    </div>
    <!-- end field type image -->
    <!-- start field type file -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Return Value</span><br>
            <span class="field-description">Specify the returned value on front end</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="object"> Image Object
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="url"> Image URL
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="id"> Image ID
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Library</span><br>
            <span class="field-description">Limit the media library choice</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="all">All
            <input type="radio" name="fields[field_580ddbcdd27b4][save_format]" value="uploadedTo"> Uploaded to post
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="" value="1"> yes
            <input type="radio" name="" value="0"> No
        </div>  
    </div>
    <!-- end field type file -->
    <!-- start field type choice -->

    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Choices</span><br>
            <span class="field-description">Enter each choice on a new line.<br>For more control, you may specify both a value and label like this:<br>red : Red<br>blue : Blue</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][choices]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Enter each default value on a new line</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][default_value]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>

    <!-- end field type choice -->

    <!-- start field type choice -->

    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Choices</span><br>
            <span class="field-description">Enter each choice on a new line.<br>For more control, you may specify both a value and label like this:<br>red : Red<br>blue : Blue</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][choices]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Enter each default value on a new line</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][default_value]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>

    <!-- end field type choice -->

    <!-- start field type checkbox -->

    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Choices</span><br>
            <span class="field-description">Enter each choice on a new line.<br>For more control, you may specify both a value and label like this:<br>red : Red<br>blue : Blue</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][choices]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Enter each default value on a new line</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][default_value]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Layout</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="vertical"> Vertical
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="horizontal"> Horizontal
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>

    <!-- end field type checkbox -->
    <!-- start field type radio_button -->

    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Choices</span><br>
            <span class="field-description">Enter each choice on a new line.<br>For more control, you may specify both a value and label like this:<br>red : Red<br>blue : Blue</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][choices]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">Enter each default value on a new line</span><br>
        </div>
        <div class="col-md-8 label-form">
            <textarea name="fields[field_580ddbcdd27b4][default_value]"></textarea>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Layout</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="vertical"> Vertical
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="horizontal"> Horizontal
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>

    <!-- end field type Radio_button -->
    <!-- end field type True_False -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Message</span><br>
            <span class="field-description">eg. Show extra content</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="text" class="form-control" name="fields[field_580ddbcdd27b4][message]" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Default Value</span><br>
            <span class="field-description">eg. Show extra content</span> 
        </div>
        <div class="col-md-8 label-form">
            <input type="checkbox" class="form-control" name="fields[field_580ddbcdd27b4][default_value]" />
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field type True_False -->
    <!-- start field type page_link -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Post Type</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_post_type" class="select" name="fields[field_580ddbcdd27b4][post_type][]" multiple="multiple" size="5">
                <option value="all" selected="selected">All</option>
                <option value="post">post</option>
                <option value="page">page</option>
                <option value="attachment">attachment</option>
                <option value="slide">slide</option>
                <option value="aione_portfolio">aione_portfolio</option>
                <option value="aione_faq">aione_faq</option>
                <option value="themeoxo_elastic">themeoxo_elastic</option>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field type page_link -->
    <!-- start field type Post_object -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">post type</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_post_type" class="select" name="fields[field_580ddbcdd27b4][post_type][]" multiple="multiple" size="5">
                <option value="all" selected="selected">all</option>
                <option value="post">post</option>
                <option value="page">page</option>
                <option value="attachment">attachment</option>
                <option value="slide">slide</option>
                <option value="aione_portfolio">aione_portfolio</option>
                <option value="aione_faq">aione_faq</option>
                <option value="themeoxo_elastic">themeoxo_elastic</option>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Filter from Taxonomy</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_taxonomy" class="select" name="fields[field_580ddbcdd27b4][taxonomy][]" multiple="multiple" size="5">
                <option value="all" selected="selected">All</option>
                <optgroup label="Posts: category">
                <option value="category:1">Uncategorized</option>
                </optgroup>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- start field type Post_object -->
    <!-- start field type Relation -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Return Format</span><br>
            <span class="field-description">Specify the returned value on front end</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="object"> Post Object
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="id">Post IDs
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">post type</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_post_type" class="select" name="fields[field_580ddbcdd27b4][post_type][]" multiple="multiple" size="5">
                <option value="all" selected="selected">all</option>
                <option value="post">post</option>
                <option value="page">page</option>
                <option value="attachment">attachment</option>
                <option value="slide">slide</option>
                <option value="aione_portfolio">aione_portfolio</option>
                <option value="aione_faq">aione_faq</option>
                <option value="themeoxo_elastic">themeoxo_elastic</option>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Filter from Taxonomy</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_taxonomy" class="select" name="fields[field_580ddbcdd27b4][taxonomy][]" multiple="multiple" size="5">
                <option value="all" selected="selected">All</option>
                <optgroup label="Posts: category">
                <option value="category:1">Uncategorized</option>
                </optgroup>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">FIlters</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="checkbox" name="" value="search"> Search
            <input type="checkbox" name="" value="post_type"> Post Type Select
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Elements</span><br>
            <span class="field-description">Selected elements will be displayed in each result</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <ul class="acf-checkbox-list checkbox vertical">
                <li>
                    <label>
                        <input id="acf-field-field_580ddbcdd27b4_result_elements" type="checkbox" class="checkbox" name="fields[field_580ddbcdd27b4][result_elements][]" value="featured_image">Featured Image
                    </label>
                    </li>
                <li>
                    <label>
                        <input id="acf-field-field_580ddbcdd27b4_result_elements-post_title" type="checkbox" class="checkbox" name="fields[field_580ddbcdd27b4][result_elements][]" value="post_title" checked="yes" disabled="true">Post Title
                    </label>
                </li>
                <li>
                    <label>
                        <input id="acf-field-field_580ddbcdd27b4_result_elements-post_type" type="checkbox" class="checkbox" name="fields[field_580ddbcdd27b4][result_elements][]" value="post_type" checked="yes">Post Type
                    </label>
                </li>
            </ul>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Select multiple values?</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][multiple]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field type Relation -->

    <!-- start field type taxonomy -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Taxonomy</span><br>            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_taxonomy" class="select" name="fields[field_580ddbcdd27b4][taxonomy]">
                <option value="category" selected="selected">category</option>
                <option value="post_tag">post_tag</option>
                <option value="slide-page">slide-page</option>
                <option value="portfolio_category">portfolio_category</option>
                <option value="portfolio_skills">portfolio_skills</option>
                <option value="portfolio_tags">portfolio_tags</option>
                <option value="faq_category">faq_category</option>
                <option value="themeoxo_es_groups">themeoxo_es_groups</option>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Type</span><br>            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_field_type" class="select" name="fields[field_580ddbcdd27b4][field_type]">
                <optgroup label="Multiple Values">
                    <option value="checkbox" selected="selected">Checkbox</option>
                    <option value="multi_select">Multi Select</option>
                </optgroup>
                <optgroup label="Single Value">
                    <option value="radio">Radio Buttons</option>
                    <option value="select">Select</option>
                </optgroup>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">post type</span><br>
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_post_type" class="select" name="fields[field_580ddbcdd27b4][post_type][]" multiple="multiple" size="5">
                <option value="all" selected="selected">all</option>
                <option value="post">post</option>
                <option value="page">page</option>
                <option value="attachment">attachment</option>
                <option value="slide">slide</option>
                <option value="aione_portfolio">aione_portfolio</option>
                <option value="aione_faq">aione_faq</option>
                <option value="themeoxo_elastic">themeoxo_elastic</option>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Filter from Taxonomy</span><br>
            
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_taxonomy" class="select" name="fields[field_580ddbcdd27b4][taxonomy][]" multiple="multiple" size="5">
                <option value="all" selected="selected">All</option>
                <optgroup label="Posts: category">
                <option value="category:1">Uncategorized</option>
                </optgroup>
            </select>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Load & Save Terms to Post</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input id="acf-field-field_580ddbcdd27b4_load_save_terms-1" type="checkbox" name="fields[field_580ddbcdd27b4][load_save_terms]" value="1">Load value based on the post's terms and update the post's terms on save
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> Yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field type taxonomy -->
    <!-- start field type user -->
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Filter by role</span><br>
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_role" class="select" name="fields[field_580ddbcdd27b4][role][]" multiple="multiple" size="5">
                <option value="all" selected="selected">All</option>
                <option value="administrator">Administrator</option>
                <option value="editor">Editor</option>
                <option value="author">Author</option>
                <option value="contributor">Contributor</option>
                <option value="subscriber">Subscriber</option>
            </select>
        </div>
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Field Type</span><br>
        </div>
        <div class="col-md-8 label-form">
            <select id="acf-field-field_580ddbcdd27b4_field_type" class="select" name="fields[field_580ddbcdd27b4][field_type]">
                <optgroup label="Multiple Values">
                    <option value="multi_select">Multi Select</option>
                </optgroup>
                <optgroup label="Single Value">
                    <option value="select" selected="selected">Select</option>
                </optgroup>
            </select>
        </div>
    </div>
        <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="1"> yes
            <input type="radio" name="fields[field_580ddbcdd27b4][allow_null]" value="0"> No
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> Yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field user -->
    <!-- start field google map -->
    
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
        </div>
        <div class="col-md-8 label-form">
            <ul class="hl clearfix">
                <li style="width:48%;">
                    <div class="acf-input-prepend">lat</div>
                    <div class="acf-input-wrap">
                        <input type="text" id="acf-field-field_580ddbcdd27b4_center_lat" class="text acf-is-prepended" name="fields[field_580ddbcdd27b4][center_lat]" value="" placeholder="-37.81411">
                    </div>
                </li>
                <li style="width:48%; margin-left:4%;">
                    <div class="acf-input-prepend">lng</div>
                    <div class="acf-input-wrap">
                        <input type="text" id="acf-field-field_580ddbcdd27b4_center_lng" class="text acf-is-prepended" name="fields[field_580ddbcdd27b4][center_lng]" value="" placeholder="144.96328">
                    </div>
                </li>
            </ul>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Allow Null</span><br>
        </div>
        <div class="col-md-8 label-form">
            <ul class="hl clearfix">
                <li style="width:48%;">
                    <div class="acf-input-prepend">lat</div>
                    <div class="acf-input-wrap">
                        <input type="text" id="acf-field-field_580ddbcdd27b4_center_lat" class="text acf-is-prepended" name="fields[field_580ddbcdd27b4][center_lat]" value="" placeholder="-37.81411">
                    </div>
                </li>
                <li style="width:48%; margin-left:4%;">
                    <div class="acf-input-prepend">lng</div>
                    <div class="acf-input-wrap">
                        <input type="text" id="acf-field-field_580ddbcdd27b4_center_lng" class="text acf-is-prepended" name="fields[field_580ddbcdd27b4][center_lng]" value="" placeholder="144.96328">
                    </div>
                </li>
            </ul>
        </div>  
    </div>
    <div class="row field-row" style="margin-left: 0">
        <div class="col-md-3 label-form bg-color" >
            <span class="field-title">Conditional Logic</span><br>
        </div>
        <div class="col-md-8 label-form">
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="1"> Yes
            <input type="radio" name="fields[field_580ddbcdd27b4][conditional_logic][status]" value="0"> No
        </div>  
    </div>
    <!-- end field user -->

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