(function ()
{
    'use strict';

    angular
        .module('app.survey.addQuestion')
        .controller('AddQuestionSurveyController', AddQuestionSurveyController);

    /** @ngInject */
    function AddQuestionSurveyController($scope, $templateCache, $compile, $state, api, $mdToast,$mdDialog)
    {    
        // $scope.htmlVariable = [
        //                 ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'quote'],
        //                 ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],
        //                 ['justifyLeft','justifyCenter','justifyRight','justifyFull','indent','outdent'],
        //                 ['html', 'insertImage', 'insertLink', 'insertVideo', 'wordcount', 'charcount']
        //             ],
        window.sortedArray = [];
       /* $('body').on('click','.add_media',function(){
            var elm = $(this).parents('.questDiv');
            $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
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
        });*/
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
        
        /*$scope.menuOptions = [
            ['<md-icon md-font-icon="icon-pencil"></md-icon><label>Select</label>', function ($itemScope, $event, modelValue, text, $li) {
                
                //$scope.selected = $itemScope.item.name;
            }],
            null, // Dividier
            ['Remove', function ($itemScope, $event, modelValue, text, $li) {
                $scope.items.splice($itemScope.$index, 1);
            }]
        ];*/
        $scope.uploadFile = function(fileData){
            var formData = new FormData();
            $scope.showLoading = true;
            formData.append('file',fileData.target.files[0]);
            api.postMethod.uploadFile(formData, $scope).then(function(res){
                api.getallFiles.filesList.get({}, function(res){
                    $scope.listFiles = res.response;
                });
                $scope.showLoading = false;
                $scope.completeUpload = true;
                setTimeout(function(){
                    $scope.completeUpload = false;
                },5000)
            });
        }
        $scope.insertSlug = function(slug){

        }
        window.compile = $compile;
        window.scope = $scope;
        $scope.surveyID = $state.params.id;
        window.groupHTML = '';
        window.$state = $state;
        $.get('app/main/survey/addQuestion/html/group.html', function(data) {
            groupHTML = data;
        });
        window.questHTML = '';
        $.get('app/main/survey/addQuestion/html/question.html', function(data){
            questHTML = data;
        });
        window.keyValHTML = '';
        $.get('app/main/survey/addQuestion/html/key_value.html', function(data){
            keyValHTML = data;
        });
        $('.question-div').sortable({
            handle: '.quest-count',
        });
        api.editQuestions.questions.get({'id':$state.params.id}, function(res){
            console.log(res);
            $scope.id = res.response;
            $scope.responceData = res.response;

            window.quesid = 1;
            
                // for group div
                $('.survey_group_error').addClass("none");

                $(document).on('click','.delete-group',function(){
                    if($(this).parents('.group-div').find('.groupFrameDiv').length == 1){
                        $('.survey_group_error').show('2000');
                        // alert('is 1');
                    }
                });
                $(document).on('click','.add-group',function(){
                   $(this).parents('.layout-align-start-start').find('.survey_group_error').hide();
                });

                // for question div
                $('.survey_question_error').addClass("none");
                if(res.response.group.group_questions != "" || res.response.group.group_questions != null || res.response.group.group_questions != undefined){
                    $('.survey_question_error').hide();
                }
                $(document).on('click','.delete-question',function(){
                    if($(this).parents('.question-div').find('.questDiv').length == 1){
                        $(this).parents('.group-frame').find('.survey_question_error').show();
                        // alert("yuhooo is 1");
                    }else{
                        $(this).parents('.group-frame').find('.survey_question_error').hide();
                        // alert("oops not 1");
                    }
                });
                $(document).on('click','.add-question',function(){
                   $(this).parents('.group-frame').find('.survey_question_error').hide();
                });



            $.each(res.response.group, function(key, val){
               

                if(key == 0){
                    var elm = $('.groupFrameDiv');
                    elm.attr('id',val.group_order);
                    elm.attr('group-id',val.group_id);
                    elm.find('.groupName').val(val.group_name).change();
                    elm.find('.group_title').text(val.group_name);
                    elm.find('.groupDescription').val(val.group_description);
                    elm.find('.countNumber').html(key+1);
                    elm.find('.sectionId').val(val.group_id);
                    elm.find('.add_fields').attr('data-id',val.group_id);

                  /*  console.log(val.group_questions);*/
                    if(val.group_questions != "" || val.group_questions != null || val.group_questions != undefined){
                        $('.survey_question_error').hide();
                    }
                    $(document).on('click','.delete-question',function(){
                        if($(this).parents('.question-div').find('.questDiv').length == 1){
                            $(this).parents('.group-frame').find('.survey_question_error').show();
                        }else{
                            $(this).parents('.group-frame').find('.survey_question_error').hide();
                        }
                    });
                    $(document).on('click','.add-question',function(){
                       $(this).parents('.group-frame').find('.survey_question_error').hide();
                    });
                    if(val.group_questions != undefined){
                        $('.fields_count:first').html(val.group_questions.length);
                    }else{
                        $('.fields_count:first').html(0);
                    }
                }else{

                    $('.group-div').append($compile(groupHTML)($scope));
                    var elm = $('.groupFrameDiv:last');
                    elm.attr('id',val.group_order);
                    elm.attr('group-id',val.group_id);
                    var groupLength = $('.groupFrameDiv').length;
                    $('.groupFrameDiv:last').attr('data-number',groupLength);
                    elm.find('.groupName:last').val(val.group_name).change();
                    elm.find('.group_title:last').text(val.group_name);
                    elm.find('.groupDescription:last').val(val.group_description);
                    elm.find('.countNumber').html(key+1);
                    elm.find('.sectionId:last').val(val.group_id);
                    elm.find('.add_fields:last').attr('data-id',val.group_id);
                    if(val.group_questions != undefined){
                        $('.fields_count:last').html(val.group_questions.length);
                    }else{
                        $('.fields_count:last').html(0);
                    }
                }
                $('.question-div').sortable({
                    handle: '.quest-count'
                });
            });
        });
        $(document).on('click','.add_fields', function(){
            var group_id = $(this).attr('data-id');
            var survey_id = $state.params.id;
            $state.go('app.survey_fields',{survey_id: survey_id, group_id: group_id});
        });
        $scope.rand = Math.random();
        /*$scope.saveSurvey = function(survey){
            var index = 1;
            var groupsData = {};
            $('.groupFrameDiv').each(function(){
                var groupName = $(this).find('.groupName').val();
                var groupDesc = $(this).find('.groupDescription').val();
                var questData = {};
                var questId = '';
                $(this).find('.questDiv').each(function(){
                    var questType = $(this).find('.questType').val();
                    questId = $(this).find('.questID').html();
                    var tempQuestData = {};
                    var pattern = $(this).find('.pattern').val();
                    var otherPattern = '';

                    if(pattern == 'others'){
                        otherPattern = $(this).find('.othetPattern').val();
                    }
                    var optionsList = [];
                    if(questType == 'checkbox' || questType == 'radio' || questType == 'dropdown'){
                        $(this).find('.extraOptions').each(function(){
                                var extraOptions = {};
                                extraOptions['options'] = {};
                                extraOptions['options']['label'] = $(this).find('.optionKey').val();
                                extraOptions['options']['value'] = $(this).find('.optionValue').val();
                                extraOptions['options']['condition'] = $(this).find('.Condition').val();
                                optionsList.push(extraOptions);
                        });
                    }
                    tempQuestData['question_id']        = questId;
                    tempQuestData['question_type']      = questType;
                    tempQuestData['question']           = $(this).find('.questTitle').val(); 
                    tempQuestData['question_desc']      = $(this).find('.questDescription').val();
                    tempQuestData['question_key']       = $(this).find('.questKey').val();
                    tempQuestData['next_question']      = $(this).find('.nextQuest').val();
                    tempQuestData['pattern']            = pattern;
                    tempQuestData['otherPattern']       = otherPattern;  
                    tempQuestData['extraOptions']       = optionsList;
                    tempQuestData['required']           = $(this).find('.required').val();
                    questData[questId] = tempQuestData;
                });
                var dataTemp = {}
                dataTemp['group_name'] = groupName;
                dataTemp['group_description'] = groupDesc;
                dataTemp['group_questions'] = questData;
                groupsData['group_'+index] = dataTemp;
                index++;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(groupsData));
            formData.append('survey_id',$state.params.id);
            $scope.isLoading = true;
            api.postMethod.saveSurveyQuest(formData).then(function(result){
                $scope.isLoading = false;
                $mdToast.show(
                    $mdToast.simple()
                    .textContent('Survey saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go('app.survey_list');
            });
        }*/

        $scope.saveSurvey = function(survey){
            var index = 1;
            var groupsData = {};
            $('.groupFrameDiv').each(function(){
                var groupName = $(this).find('.groupName').val();
                var groupDesc = $(this).find('.groupDescription').val();
                var groupId = $(this).find('.sectionId').val();
                var dataTemp = {}
                dataTemp['group_name'] = groupName;
                dataTemp['group_description'] = groupDesc;
                dataTemp['group_id'] = groupId;
                groupsData['group_'+index] = dataTemp;
                index++;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(groupsData));
            formData.append('survey_id',$state.params.id);
            $scope.isLoading = true;
            api.postMethod.saveSection(formData).then(function(result){
                $scope.isLoading = false;
                $mdToast.show(
                    $mdToast.simple()
                    .textContent('Sections saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go($state.current, {}, {reload: true});
            });
        }
        

        $('body').on('click','.delKeyVal', function(){
            $(this).parents('.keyValMinus').remove(); 
        });
        if(!$('.groupFrameDiv').hasClass('expanded')){
            $('.group-div').removeClass('ui-sortable');
            $('.group-div').sortable({
                handle: '.countNumber',
                stop : function(event, ui){
                  var sortArray = $(this).sortable('toArray');
                  window.sortedArray = sortArray.clean("");
                }
            });
        }
        
        Array.prototype.clean = function(deleteValue) {
          for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {         
              this.splice(i, 1);
              i--;
            }
          }
          return this;
        };
        $('body').on('click','.delete-group', function(){
            var groupFrame = $(this).parents('.group-frame');
            groupFrame.animate({
                'margin-left':'40%',
                'opacity':'0.5'
            },200, function(){
                groupFrame.remove();
                $('.countNumber').each(function(i){
                    $(this).html(i+1); 
                });
            });
        });

        $('body').on('click', '.delete-question', function(){
            var reorder = $(this).parent('div').parent('div').parent('.group-frame').parent('.question-div');
            var elem = $(this).parent('div').parent('div').parent('.group-frame'); 
            elem.animate({
                'margin-left':'40%',
                'opacity':'0.5'
            },200, function(){
                elem.remove();
                reorder.find('.quest-count').each(function(i){
                    $(this).html(i+1); 
                });
            });
        });

        //hide show


        $('body').on('change','.questType', function(){
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

        // $scope.other_pattern = false;
        $(document).on('change','.pattern',function(){
            
            if($(this).val() == 'others'){
                $(this).parents('.main-row').find('.patternChange').fadeIn();
            }else{
                $(this).parents('.main-row').find('.patternChange').fadeOut();
            }
        });
    }
})();

$('body').on('click','.add-group', function(){
    var total = $('.countNumber').length;
    $('.group-div').append(compile(groupHTML)(scope));
    $('.add_fields:last').remove();
    var elm = $('.groupFrameDiv:last');
    elm.attr('id',$('.groupFrameDiv').length);

    $('.countNumber:last').html(parseInt(total+1));
    scope.rand = Math.random();
    var groupLength = $('.groupFrameDiv').length;
    quesid++;
    var questLength = quesid;
    $('.groupFrameDiv:last').attr('data-number',groupLength);
    $('.questID:last').html('SID'+$state.params.id+'_GID'+groupLength+'_QID'+questLength);
    $('.question-div').sortable({
        handle: '.quest-count'
    });
});


$('body').on('click','.add-question', function(){
    var elem = $(this).parent('div').parent('.group-frame').find('.quest-count');
    var total = elem.length;
    $(this).parent('div').parent('.group-frame').find('.question-div').append(compile(questHTML)(scope));
    $(this).parent('div').parent('.group-frame').find('.quest-count:last').html(parseInt(total+1));
    quesid++;
    var questLength = quesid;
    var groupNumber = $(this).parent('div').parents('.group-frame').attr('data-number');
    $(this).parent('div').parent('.group-frame').find('.questID:last').html('SID'+$state.params.id+'_GID'+groupNumber+'_QID'+parseInt(questLength));
    $('.question-div').sortable({
        handle: '.quest-count'
    });
    $(this).off('click');
});
$('body').on('click','.addKeys', function(){
    $(this).parents('.keyValue').prepend(window.compile(keyValHTML)(window.scope));
});

$('body').on('click','.accrodian', function(){
    var elem = $(this);
    var groupFrame = $(this).parent('div').parent('div').parent('.group-frame');
    if(groupFrame.hasClass('expanded')){
        groupFrame.animate({
            'height': '50px'
        },300);
        groupFrame.removeClass('expanded');
        elem.css('transform','rotate(0deg)');
    }else{
        groupFrame.addClass('expanded');
        groupFrame.animate({
            'height': $(this).get(0).scrollHeight + 17
        },200, function(){
            $(this).height('auto');
            elem.css('transform','rotate(180deg)');
        });
    }
    
});
$('body').on('click','.upload-button',function(){
   $('.upload-file').click(); 
});
$('body').on('click','.optionKey', function(){
    $('.currentSelection').removeClass('currentSelection');
     $(this).addClass('currentSelection');
});
$('body').on('click','.questDescription', function(){
    $('.currentSelection').removeClass('currentSelection');
    $(this).addClass('currentSelection');
});
$('body').on('click','.icon-arrow img',function(){
    if(!$(this).parents('.group-frame').hasClass('expended')){
        $(this).parents('.group-frame').addClass('expended');
        $(this).parents('.group-frame').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.group-frame').removeClass('expended');
        $(this).parents('.group-frame').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
$('body').on('click','.qstn-div img',function(){
    if(!$(this).parents('.questDiv').hasClass('expended')){
        $(this).parents('.questDiv').addClass('expended');
        $(this).parents('.questDiv').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.questDiv').removeClass('expended');
        $(this).parents('.questDiv').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
 $(document).on('keyup','.questTitle',function(){
    var value = $(this).val();
    $(this).parents('.questDiv').find('.qust_title').html(value);
});

/*$(document).bind("contextmenu", function (event) {
    
    // Avoid the real one
    event.preventDefault();
    
    // Show contextmenu
    $(".custom-menu").finish().toggle(100).
    
    // In the right position (the mouse)
    css({
        top: (event.pageY-60) + "px",
        left: (event.pageX-200) + "px",
        position: 'absolute',
        'overflow-y': 'hidden'
    });
});


// If the document is clicked somewhere
$(document).bind("mousedown", function (e) {
    
    // If the clicked element is not the menu
    if (!$(e.target).parents(".custom-menu").length > 0) {
        
        // Hide it
        $(".custom-menu").hide(100);
    }
});


// If the menu element is clicked
$(".custom-menu li").click(function(){
    
    // This is the triggered action name
    switch($(this).attr("data-action")) {
        
        // A case for each action. Your actions here
        case "first": alert("first"); break;
        case "second": alert("second"); break;
        case "third": alert("third"); break;
    }

    // Hide it AFTER the action was triggered
    $(".custom-menu").hide(100);
});*/
$('.survey_question_error').hide();