(function ()
{
    'use strict';

    angular
        .module('app.survey.fields')
        .controller('FieldsController', FieldsController);

    /** @ngInject */
    function FieldsController($scope, $templateCache, $compile, $state, api, $mdToast,$mdDialog){
        window.fieldHTML = '';
        window.fieldid = 1;
        $.get('app/main/survey/fields/_field.html', function(data){
            fieldHTML = data;
        });
        window.keyValHTML = '';
        $.get('app/main/survey/fields/key_value.html', function(data){
            keyValHTML = data;
        });

    	api.editFields.fields.get({'survey_id':$state.params.survey_id,'group_id':$state.params.group_id}, function(res){
    		if(res.data.group == undefined){
                $scope.survey_name = res.survey_name;
                $scope.group_name = res.group_name;
                return false;
            }
            $scope.survey_name = res.survey_name;
            $scope.group_name = res.group_name;
            $.each(res.data.group[1].group_questions, function(ikey, ival){
                console.log(ival);
                var stringArray = (ival.question_id).split('_');
                var questNumber = stringArray[2].replace ( /[^\d.]/g, '' );
                questNumber = parseInt(questNumber, 10);
                
                /*if(questNumber > quesid){
                    quesid = questNumber;
                }*/
                if(ikey == 1){
                    var questElm = $('.field-div');
                    questElm.find('.fieldID').html(ival.question_id);
                    questElm.find('.fieldType').val(ival.question_type);
                    questElm.find('.fieldTitle').val(ival.question);
                    questElm.find('.field_title').html(ival.question);
                    questElm.find('.fieldDescription').val(ival.question_desc);
                    questElm.find('.required').val(ival.required);
                    questElm.find('.nextQuest').val(ival.next_question);
                    questElm.find('.pattern').val(ival.pattern);
                    questElm.find('.fieldKey').val(ival.question_key);
                    questElm.find('.field-count').html(parseInt(ikey));
                    if(ival.pattern == 'others'){
                        questElm.find('.patternChange').show();
                        questElm.find('.othetPattern').val(ival.otherPattern);
                    }
                    if(ival.question_type == 'checkbox' || ival.question_type == 'radio' || ival.question_type == 'dropdown'){
                        questElm.find('.addQuestion_right_section').find('#div-one').show();
                        $('.keyValue').sortable();
                        var keyIndex = 0;
                        console.log(ival.extraOptions);
                        $.each(ival.extraOptions, function(optionKeys, optionVal){
                            if(keyIndex == 0){
                                questElm.find('.keyValue').find('.optionKey').val(optionVal['options']['label']);
                                questElm.find('.keyValue').find('.optionValue').val(optionVal['options']['value']);
                                questElm.find('.keyValue').find('.Condition').val(optionVal['options']['condition']);
                            }else{
                                questElm.find('.keyValue').append($compile(keyValHTML)($scope));
                                questElm.find('.keyValue').find('.optionKey:last').val(optionVal['options']['label']);
                                questElm.find('.keyValue').find('.optionValue:last').val(optionVal['options']['value']);
                                questElm.find('.keyValue').find('.Condition:last').val(optionVal['options']['condition']);
                            }
                            keyIndex++;
                        });
                    }
                }else{
                    $('.field-div').append($compile(fieldHTML)($scope));
                    $('.fieldDiv:last').find('.fieldID').html(ival.question_id);
                    $('.fieldDiv:last').find('.fieldType').val(ival.question_type);
                    $('.fieldDiv:last').find('.fieldTitle').val(ival.question);
                    $('.fieldDiv:last').find('.field_title').html(ival.question);
                    $('.fieldDiv:last').find('.fieldDescription').val(ival.question_desc);
                    $('.fieldDiv:last').find('.required').val(ival.required);
                    $('.fieldDiv:last').find('.nextQuest').val(ival.next_question);
                    $('.fieldDiv:last').find('.pattern').val(ival.pattern);
                    $('.fieldDiv:last').find('.fieldKey').val(ival.question_key);
                    $('.fieldDiv:last').find('.field-count').html(ikey);
                    if(ival.pattern == 'others'){
                        $('.fieldDiv:last').find('.patternChange').show();
                        $('.fieldDiv:last').find('.othetPattern').val(ival.otherPattern);
                    }
                    if(ival.question_type == 'checkbox' || ival.question_type == 'radio' || ival.question_type == 'dropdown'){
                        $('.fieldDiv:last').find('.addQuestion_right_section').find('#div-one').show();
                        $('.keyValue').sortable();
                        var keyIndex = 0;
                        $.each(ival.extraOptions, function(optionKeys, optionVal){
                            if(keyIndex == 0){
                                $('.keyValue:last').find('.optionKey').val(optionVal['options']['label']);
                                $('.keyValue:last').find('.optionValue').val(optionVal['options']['value']);
                                $('.keyValue:last').find('.Condition').val(optionVal['options']['condition']);
                            }else{
                                $('.keyValue:last').append($compile(keyValHTML)($scope));
                                $('.keyValue:last').find('.optionKey:last').val(optionVal['options']['label']);
                                $('.keyValue:last').find('.optionValue:last').val(optionVal['options']['value']);
                                $('.keyValue:last').find('.Condition:last').val(optionVal['options']['condition']);

                            }
                            keyIndex++;
                        });
                    }
                }
                $('.field-div').sortable({
                    handle: '.field-count'
                });
            });
    	});
        
        $scope.saveFields = function(){
            var fieldData = {};
            console.log($('.fieldDiv').length);
            $('.fieldDiv').each(function(){
                var fieldType = $(this).find('.fieldType').val();
                var fieldId = $(this).find('.fieldID').html();
                var tempQuestData = {};
                var pattern = $(this).find('.pattern').val();
                var otherPattern = '';

                if(pattern == 'others'){
                    otherPattern = $(this).find('.othetPattern').val();
                }
                var optionsList = [];
                if(fieldType == 'checkbox' || fieldType == 'radio' || fieldType == 'dropdown'){
                    $(this).find('.extraOptions').each(function(){
                            var extraOptions = {};
                            extraOptions['options'] = {};
                            extraOptions['options']['label'] = $(this).find('.optionKey').val();
                            extraOptions['options']['value'] = $(this).find('.optionValue').val();
                            extraOptions['options']['condition'] = $(this).find('.Condition').val();
                            optionsList.push(extraOptions);
                    });
                }
                tempQuestData['question_id']        = fieldId;
                tempQuestData['question_type']      = fieldType;
                tempQuestData['question']           = $(this).find('.fieldTitle').val(); 
                tempQuestData['question_desc']      = $(this).find('.fieldDescription').val();
                tempQuestData['question_key']       = $(this).find('.fieldKey').val();
                tempQuestData['next_question']      = $(this).find('.nextQuest').val();
                tempQuestData['pattern']            = pattern;
                tempQuestData['otherPattern']       = otherPattern;  
                tempQuestData['extraOptions']       = optionsList;
                tempQuestData['required']           = $(this).find('.required').val();
                fieldData[fieldId] = tempQuestData;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(fieldData));
            formData.append('survey_id',$state.params.survey_id);
            formData.append('group_id',$state.params.group_id);
            api.postMethod.saveFields(formData).then(function(res){
                $state.go($state.current, {}, {reload: true});
            });
        }
        window.compile = $compile;
        window.scope = $scope;
        window.$state = $state;
        window.$mdDialog = $mdDialog;
        $('.field-div').find('.fieldID:last').html('SID'+$state.params.survey_id+'_GID'+$state.params.group_id+'_QID1');
        $scope.getIcon = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
               
                return 'icon-file-image';
            }
            if(type == 'mp3'){
                
                return 'icon-file-music';
            }
            if(type == 'wav'){
               
                return 'icon-music-note-eighth';
            }
            if(type == 'png'){
                
                return 'icon-image-area';
            }
           
            return 'icon-image-broken';
        }

        $scope.getClass = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
                
                return 'red-fg';
            }
            if(type == 'mp3'){
               
                return 'green-fg';
            }
            if(type == 'wav'){
               
                return '';
            }
            if(type == 'png'){
                
                return 'cyan-fg';
            }
            return 'red-fg';
        }
    }
        
})();
$('.keyValue').sortable({
    handle: '.move-key'
});
$('body').on('click','.add-field', function(){
    var elem = $('.field-div').find('.fieldDiv');
    var total = elem.length;
    var tempQid = 0;
    $('.field-div').find('.fieldID').each(function(){
        var splitedData = $(this).html().split('_');
        var newQuestId = splitedData[2].replace ( /[^\d.]/g, '' );
        if(parseInt(newQuestId) > parseInt(tempQid)){
            tempQid = parseInt(newQuestId);
        }
    });
    // var lastQID = $('.field-div').find('.fieldID:last').html();
    
    $('.field-div').append(compile(fieldHTML)(scope));
    $('.field-div').find('.field-count:last').html(parseInt(total+1));
    var questLength = parseInt(tempQid)+1;
    var groupNumber = $state.params.group_id;

    $('.field-div').find('.fieldID:last').html('SID'+$state.params.survey_id+'_GID'+groupNumber+'_QID'+parseInt(questLength));
    $('.field-div').sortable({
        handle: '.field-count'
    });
    $(this).off('click');
});
$('body').on('click','.field-div img',function(){
    if(!$(this).parents('.fieldDiv').hasClass('expended')){
        $(this).parents('.fieldDiv').addClass('expended');
        $(this).parents('.fieldDiv').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.fieldDiv').removeClass('expended');
        $(this).parents('.fieldDiv').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
/*$('body').on('click','.delete-field',function(){
    
    if($(this).parents('.field-div').find('.fieldDiv').length == 1){
        $(this).parents('.field-frame').find('.survey_question_error').show();
        // alert("yuhooo is 1");
    }else{
        $(this).parents('.field-frame').find('.survey_question_error').hide();
        // alert("oops not 1");
    }
});*/
$('body').on('click', '.delete-field', function(){
    var reorder = $(this).parent('div').parent('div').parent('.field-frame').parent('.field-div');
    var elem = $(this).parent('div').parent('div').parent('.field-frame'); 
    elem.animate({
        'margin-left':'40%',
        'opacity':'0.5'
    },200, function(){
        elem.remove();
        reorder.find('.field-count').each(function(i){
            $(this).html(i+1); 
        });
    });
});
$(document).on('keyup','.fieldTitle',function(){
    var value = $(this).val();
    $(this).parents('.fieldDiv').find('.field_title').html(value);
});
$('body').on('change','.fieldType', function(){
    if($(this).val() == "text"){
        $('.addQuestion_right_section > div > div , .div-two').hide();
    }
    if($(this).val() == "checkbox" || $(this).val() == "radio" || $(this).val() == "dropdown"){
        $(this).parent().parent().parent().find('.addQuestion_right_section').find('#div-one').show();
        $('.div-two').hide();
    }
   
    if($(this).val() == "text_only"){
        $('.addQuestion_right_section > div > div , .div-two').hide();
    }
    if($(this).val() == "text_with_image"){
    $('.addQuestion_right_section > div > div').hide();
        $('.div-two').show();
    }
});
$('body').on('click','.addOpts', function(){
    $(this).parents('.keyValue').prepend(window.compile(keyValHTML)(window.scope));
});
$('body').on('click','.add_media',function(){
    var elm = $(this).parents('.fieldDiv');
    $mdDialog.show({
        clickOutsideToClose: true,
        scope: scope,        
        preserveScope: true,           
        templateUrl: 'app/main/survey/addQuestion/dialog/filesDialog.html',
        controller: function DialogController($scope, $mdDialog, api) {
            api.getallFiles.filesList.get({}, function(res){
                $scope.listFiles = res.response;
            });
            $scope.insertSlug = function(slug){
                var text = $('.currentSelection').val();
                $('.currentSelection').val(text+' ['+slug+']');
                $mdDialog.hide();
            }
            $scope.closeDialog = function() {
                $mdDialog.hide();
            }
        }
    });
});
$('body').on('click','.optionKey', function(){
    $('.currentSelection').removeClass('currentSelection');
     $(this).addClass('currentSelection');
});
$('body').on('click','.fieldDescription', function(){
    $('.currentSelection').removeClass('currentSelection');
    $(this).addClass('currentSelection');
});
$('body').on('click','.delKeyVal', function(){
    $(this).parents('.keyValMinus').remove(); 
});