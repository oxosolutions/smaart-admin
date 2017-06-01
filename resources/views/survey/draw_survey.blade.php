@extends('layouts.survey')
@section('content')
@php
	$Drawer = 'App\Http\Controllers\DrawSurveyController';
@endphp
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

{{-- @foreach ($sdata->group as $key => $survey_group)
	@foreach ($survey_group->question as $field_key => $field) 

	{{dump(json_decode($field->answer,true))}}

	@endforeach
@endforeach
 --}}
{{-- {{dd($design_settings)}} --}}
<div>
	<div class="fade-background" >
	</div>


	<div id="aione_topbar_1" class="sione-topbar" style="top: 0px;z-index:9999;background-color:#fff;position: fixed;width: 100%;border-bottom: 1px solid #a8a8a8">
		{{-- <div style="padding:10px ; background-color: #2D323E;position: fixed;z-index: 999;width: 17%" class="w-3"> --}}
				{{--  --}}
		{{-- </div> --}}
		<div style="width: 5%;float: left;text-align: center;padding: 29px 0px;border-right: 1px solid #e8e8e8">
			<a class="menu-button" href="javascript:;" style="color: white;font-size: 20px"><i class="fa fa-bars"></i></a>
		</div>
		<div style="width: 80%;float: left;padding: 15px 0px;">
			<div style="margin:0px;float: left;width: 50%">
				<div style="width: 35%">
					<div style="width: 30%;float: left;text-align: center">
						<img src="{{asset('images/logo.png')}}" style="width: 40px">
					</div>
					<div style="color: rgb(3,155,229);width: 70%;float: right">
						<span style="font-size: 20px;line-height: 20px">SMAART&trade;</span><br>
						<span style="font-size: 20px">Framework</span>
					</div>
					<div style="clear: both">
						
					</div>
				</div>
				
			</div>
			<div style="float: right;width: 50%;text-align: right;padding: 14px 0px	">
				<span id="sum_filled_ques">0</span>/{{@$progress_bar_question}}
			</div>
			
		</div>
		<div style="width: 15%;float: left;text-align: center;padding: 29px 0px">
			<div>
				Welcome: Abc Xyz
				<img src="">
			</div>
		</div>
		<div style="clear: both">
			
		</div>
		
	</div>
	<div id="aione_sidenav_1" class="aione-sidenav aione-hide">
		<div style="text-align: right;padding:10px ">
			<a href="javascript:;" style="border:1px solid #a8a8a8;padding:4px 8px" class="menu-close"><i class="fa fa-times"></i></a>
		</div>

		@if(count(@$sdata->group)>0)
			<ul style="margin: 0">
				@foreach ($sdata->group as $key => $survey_group)
					<li class="root">
						{{-- SECTION 1 --}}
						<div style="float: left;width: 90%;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;display: block;line-height: 36px;padding:6px;">
							{{$survey_group->title}}
						</div>
						<div style="float:right;width: 10%;text-align: center;padding: 6px 0px; ">	
							<i class="fa fa-chevron-down aione-arrow" style="font-size: 12px;line-height: 34px"> </i>
						</div>
						<div style="clear:both;">
							
						</div>
						<ul>
							@foreach ($survey_group->question as $field_key => $field)
							<?php
								$field_id = 'sid'.$field->survey_id.'_gid'.$field->group_id.'_qid'.$field->id;
								$field_meta = json_decode($field->answer,true);
								// dump($field);
							?>
								<li>
									<a href="#field_{{$field_id}}">
									 	<div>
										 	<div style="width: 100%;float: left;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;display: block;padding: 6px">
												<span>{{$field['question']}}</span>
												<span style="background-color: red;" id="mark_{{json_decode($field->answer)->question_id}}">{{-- <b>Pending</b> --}}</span>
										 	</div> 
										 	<div style="width: 10%;float: right" class="aione-status-icons">
										 		
										 	</div>
										 	<div style="clear: both;">
										 		
										 	</div>
										</div>
									</a>
								</li>
							@endforeach
						</ul>
						
						{{-- <b style="color:blue;">
							({{count($survey_group->question)}}) 
						</b> --}}
					</li>
					
				@endforeach
				
			</ul>
		@endif
		
	</div>
	<div class="aione-content aione-content-mini" id="main-content">
		
			@if(@$err_msg)
				@foreach($err_msg as $key => $error_message)
					<div class="survey-wrapper" style="margin-top: 35px;">
						<div class="survey-header">
							<div class="wrapper-row">
								<h1 class="survey-title">Something went wrong</h1>
								<h3 class="survey-description"></h3>
							</div> <!-- wrapper-row -->
						</div> <!-- survey-header -->
						<div id="survey_content" class="survey-content">
							<div class="wrapper-row">
								<div id="survey_error_messages" class="survey-error-messages">
									<div class="wrapper-row">
										<h3 class="survey-error-message"><?php echo $error_message; ?></h3>
									</div> <!-- wrapper-row -->
								</div> <!-- survey-error-messages -->
							</div> <!-- wrapper-row -->
						</div> <!-- survey-content -->
						<div id="survey_footer" class="survey-footer">
							<div class="wrapper-row">
		            			{!! Form::button('Try Again', ['class' => 'button','onclick'=>'window.location.reload()']) !!}
							</div> <!-- wrapper-row -->
						</div> <!-- survey-footer -->
					</div>
					@break
				@endforeach
			@else
			@if(Auth::check()!=false || $timer['survey_timer_status'])
			<div id="survey_topbar_{{$sdata->id}}" class="survey-topbar">
				<div class="wrapper-row">
					<div class="survey-topbar-left">
						
					</div>
					<div class="survey-topbar-right">
						@if(Auth::check()!=false)
							Welcome {{Auth::user()->name}}, <a href="{{url('out')}}/{{$token}}">Logout</a>
						@endif
					</div>
					<div class="clear"></div>
				</div> <!-- wrapper-row -->
			</div> <!-- survey-topbar -->
			@endif

			<div id="survey_{{$sdata->id}}" class="survey-wrapper">
				{!! Form::open(['route' => ['survey.nxt', $token],'id'=>"survey_form_".$sdata->id, 'class'=>'survey-form','files'=>true]) !!}
				@if($Drawer::getSettings($design_settings,'showProgressbar') == 1)
					{{-- <progress style="width: 100%;height: 10px;" id="progress" value="{{@$viewType['ques_filled_count']}}" max="{{$progress_bar_question}}">
					</progress> --}}
							{{-- <span id='sum_filled_ques'></span> --}}
						
					<div class="aione-progress-bar">
						<div class="aione-progress-bg">

							<div class="aione-progress-inside" >

							</div>
						</div>
					</div>
					
					<style type="text/css">
						.aione-progress-bg {
						    background: rgba(255,0,0,0.1);
						    min-height: 4px;
						}


						.aione-progress-inside {
							width: 0%;
						    height: 5px;
						    background: #22adba;
						    background: rgba(0,128,0,0.9);;
						    background-size: 10% 100%, 100% 100%;
						}

					</style>



				@endif

				{{-- <span id="sum_filled_ques">0</span>/{{$progress_bar_question}} --}}
					<input type="hidden" name="survey_started_on" value="<?php echo date('YmdHis').substr((string)microtime(), 2, 6); ?>" >
					<input type="hidden" name="survey_id" value="{{$sdata->id}}" >
					<input type="hidden" name="code" value="{{$token}}" />
					@if($Drawer::getSettings($design_settings,'surveyTitle') == 1 || $Drawer::getSettings($design_settings,'surveyDescription') == 1)
						<div id="survey_header_{{$sdata->id}}" class="survey-header">
							<div class="wrapper-row">

								<h1 class="survey-title">{!! ($Drawer::getSettings($design_settings,'surveyTitle'))?$sdata->name:''!!} </h1> 
								{{-- <h4>
									Progress Bar:  
									<progress id="progress" value="{{@$viewType['ques_filled_count']}}" max="{{$progress_bar_question}}">
									</progress> 
								</h4> --}}
								<h3 class="survey-description">{!! $Drawer::getSettings($design_settings,'surveyDescription')?$sdata->description:'' !!}</h3>
							</div> <!-- wrapper-row -->
						</div> <!-- survey-header -->
					@endif
					@if ($message = Session::get('successfullSaveSurvey'))
						<div id="survey_content" class="survey-content">
							<div class="wrapper-row">
								<div id="survey_success_messages" class="survey-success-messages">
									<div class="wrapper-row">
										<h1 class="survey-success-title">Success</h1>
										<h3 class="survey-success-description">{{Session::get('successfullSaveSurvey')}}</h3>
									</div> <!-- wrapper-row -->
								</div> <!-- survey-success-messages -->
							</div> <!-- wrapper-row -->
						</div> <!-- survey-content -->
						<div id="survey_footer_{{$sdata->id}}" class="survey-footer">
							<div class="wrapper-row">
							<a href="{{url()->current()}}" class='button'> Fill Survey Again</a>
							</div> <!-- wrapper-row -->
						</div> <!-- survey-footer -->
						
					@else
						<div id="survey_content_{{$sdata->id}}" class="survey-content">

							<div class="wrapper-row">
							<?php 
							
							$group_status ="enable"; ?>
								@if(count($sdata->group)>0)
									@foreach ($sdata->group as $key => $survey_group)
										@if($group_status =="enable")
										<div id="survey_group_{{$survey_group->id}}" class="survey-group"> 
											@if($Drawer::getSettings($design_settings,'showGroupTitle') == 1 || $Drawer::getSettings($design_settings,'groupDescription') == 1)
												<div id="group_header_{{$survey_group->id}}" class="group-header">
													<div class="content-row">
														<h2 class="group-title" id="{{$survey_group->title}}">{{($Drawer::getSettings($design_settings,'showGroupTitle'))?$survey_group->title:''}}</h2>
														<h4 class="group-description">{{($Drawer::getSettings($design_settings,'groupDescription'))?$survey_group->description:''}}</h4>
													</div> <!-- content-row -->
												</div> <!-- group-header -->
											@endif
											<div id="group_content_{{$survey_group->id}}" class="group-content">
												<div class="content-row">
												@php
													$index= 1;
												@endphp
												@foreach ($survey_group->question as $field_key => $field) 
														<?php
															$field_id = 'sid'.$field->survey_id.'_gid'.$field->group_id.'_qid'.$field->id;
															$field_meta = json_decode($field->answer,true);


															$validation_array = [];

														if($field_meta['required']=="yes"){
															@$validation_array[] = 'required';
														
														}

														if($field_meta['pattern']!=null && $field_meta['pattern']!="blank" ){

															if($field_meta['pattern'] =='email')
															{
																@$validation_array[] = 'email';
															}elseif($field_meta['pattern'] =='number'){
																@$validation_array[] = 'number';
															}else{

															@$validation_array[] = 'custom';
															}

															//@$validation_array[] = 'number';

														  	//$validate = "data-validation-regexp=".$field_meta['pattern']; 
														  	@$validate = "data-validation-regexp=".$field_meta['pattern']; 
														}
															
													
														 
															$validations = implode(' ',$validation_array);
														?>
														

														
														<div id="field_{{$field_id}}" class="field-wrapper field-wrapper-{{$field_meta['question_id']}} field-wrapper-type-{{$field_meta['question_type']}}">

															
															<div id="field_label_{{$field_meta['question_id']}}" class="field-label">
																<label for="input_{{$field_meta['question_id']}}">

																	<h4 class="field-title" id="{{$field->question}}"><?php echo $field->question; ?></h4>
																	@php
																		$index++;
																	@endphp
												{{-- by sandeep	--}}		{{--@if($Drawer::getSettings($design_settings,'questionPlacement') == 'above') --}}
																		<p class="field-description">
																			<?php
																				$media = SurveyHelper::get_survey_media($field_meta['question_desc']);
																			 	echo $media['text']; 
																			 ?>
																		 </p>
												{{-- by sandeep	--}}	{{-- 	@endif --}}
																</label>
															</div> <!-- field-label -->
															<div  id="field_{{$field_meta['question_id']}}" class="field {{$field_meta['question_type']}} field-type-{{$field_meta['question_type']}} ">
																	@php
																	 $qid = $field_meta['question_id'];
																	 @$filled_ans = $filled_data->$qid;
																	@endphp
															
																@if($field_meta['question_type'] =="text")
																	
																	
																	<input class="{{$field_meta['question_id']}}"  name="{{$field_meta['question_id']}}" id="input_{{$field_meta['question_id']}}" type="text" placeholder="" data-validation="{{$validations}}" value="{{@$filled_ans}}">
																	@elseif($field_meta['question_type'] =="text_only")
																	<textarea class="{{$field_meta['question_id']}}" data-validation="{{@$validations}}" name="{{$field_meta['question_id']}}" id="textarea_{{$field_meta['question_id']}}">{{@$filled_ans}} </textarea>
																@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="checkbox" )
																		@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																		<div id="field_option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option">
																		@php
																		$m_ans = [];
																		if(@$filled_ans)
																		{
																			$m_ans = json_decode($filled_ans, true);
																		}

																		@endphp
																			@if(in_array($option_value['options']['value'], $m_ans))
																				<input checked="checked" class="{{$field_meta['question_id']}}" data-validation="checkbox_group" data-validation-qty="min1" id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}[]" type="checkbox" value="{{$option_value['options']['value']}}">

																			@else
																			<input class="{{$field_meta['question_id']}}" data-validation="checkbox_group" data-validation-qty="min1" id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}[]" type="checkbox" value="{{$option_value['options']['value']}}">

																			@endif
																				<label for="option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option-label"> <?php echo $option_value['options']['label']; ?></label>
																			</div>
																		@endforeach

																@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="radio" )
																	@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																	

																		<div id="field_option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option">
																		@if(@$filled_ans == $option_value['options']['value'])
																			<input class="{{$field_meta['question_id']}}"  checked="checked" data-validation="{{@$validations}}"  id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}" type="radio" value="{{$option_value['options']['value']}}">
																			@else
																			<input class="{{$field_meta['question_id']}}"  data-validation="{{@$validations}}"  id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}" type="radio" value="{{$option_value['options']['value']}}">
																		@endif
																			<label for="option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option-label"> <?php echo $option_value['options']['label']; ?></label>
																		</div>
																	@endforeach
																@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="dropdown" )
																	<select class="{{$field_meta['question_id']}}" id="{{$field_meta['question_id']}}" data-validation="{{@$validations}}" {{@$validate}} name="{{trim($field_meta['question_id'])}}" >

																	<option value=""> Select Option </option>
																	@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																	@if(@$filled_ans == @$option_value['options']['value'])
																		<option selected="selected" value="{{@$option_value['options']['value']}}"> <?php echo @$option_value['options']['label']; ?> </option>
																		@else
																		<option  value="{{@$option_value['options']['value']}}"> <?php echo @$option_value['options']['label']; ?> </option>
																	@endif

																	@endforeach
																	</select>

																@endif
																@if($Drawer::getSettings($design_settings,'questionPlacement') == 'below')
																	<p class="field-description" style="margin-top: 1%;">
																		<?php
																			$media = SurveyHelper::get_survey_media($field_meta['question_desc']);
																		 	echo $media['text']; 
																		 ?>
																	 </p>
																@endif
															</div> <!-- field -->
															
														</div> <!-- field-wrapper -->
													@endforeach

												</div> <!-- content-row -->
											</div> <!-- group-content -->
											
											<div id="group_footer_{{$survey_group->id}}" class="group-footer">
												<div class="content-row">
												</div> <!-- content-row -->
											</div> <!-- group-footer -->

											
										</div> <!-- survey-group -->
										@endif <!-- SURVEY-GROUP STATUS ENDIF -->
									@endforeach
									
									@else
									<div class="survey-content">
										<div class="wrapper-row ">
											<h1 class="survey-error-messages">NO QUESTION EXIST!</h1>
										</div>
									</div>

								@endif
								
							</div> <!-- wrapper-row -->
						</div> <!-- survey-content -->


					@endif
					
					@if (!Session::get('successfullSaveSurvey') && count($sdata->group)>0 )
						<div id="survey_footer_{{$sdata->id}}" class="survey-footer">
							<div class="wrapper-row">
							@if($viewType['type'] =="survey")
								<input id="viewType" type="hidden" name="type" value="{{$viewType['type']}}">
								<input type="hidden" name="token" value="{{$viewType['token']}}">

								{!! Form::submit('Save', ['class' => 'button']) !!}
		            			{!! Form::button('Cancel', ['class' => 'button','onclick'=>'window.location.reload()']) !!}
		            			{!! Form::close()!!}
		            		@endif
								
								@if($viewType['type'] =="group" ||  $viewType['type'] =="question")
									
										<input type="hidden" name="token" value="{{$viewType['token']}}">
										<input type="hidden" name="type" value="{{$viewType['type']}}">
										
										<input type="hidden" name="group_id" value="{{$survey_group->id}}">
										<input type="hidden" name="group_no" value="{{$viewType['group_no']}}">
										@if(!empty(Session::get('filled_id')))
										
										 <input type="hidden" name="filled_id" value="{{Session::get('filled_id')}}">

										@endif


										@if($viewType['type']=='question')
											<input type="hidden" name="number" value="{{$viewType['number']}}">
											@if($viewType['number']!=0 ) 
												<input type="submit" class='button' name="previous" value="Previous">
											@endif
										@elseif($viewType['type']=='group')
											@if($viewType['group_no']!=0 ) 
												<input type="submit" class='button' name="previous" value="Previous">
											@endif

										@endif
										<input type="submit" class='button' name="next" value="Next">
										
									{!! Form::close()!!}
								@endif
							</div> <!-- wrapper-row -->
						</div> <!-- survey-footer -->
					@endif

			</div><!-- survey-wrapper -->

				<a href="{{route('survey.survey_save_data',['sid'=>$sid])}}"> View Save survey </a>

			<div id="survey_copyright" class="survey-copyright">
				<div class="wrapper-row ">
					&copy; copyright 2017. Survey created with <a href="http://smaartframework.com/" target="_blank">SMAART™ Framework</a> 
				</div> <!-- wrapper-row -->
			</div> <!-- survey-footer -->	
			@endif
		
	</div>
	<div style="clear: both;">
		
	</div>
</div>
@endsection

<style type="text/css">
	#aione_sidenav_1::-webkit-scrollbar-track
	{
	    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	    background-color: transparent;
	}

	#aione_sidenav_1::-webkit-scrollbar
	{
	    width: 5px;
	    background-color: transparent;
	   
	}

	#aione_sidenav_1::-webkit-scrollbar-thumb
	{
	    background-color: #787A7E;
	     border-radius: 6px;
	}
	.theme-minimal .main{
		max-width: 100% !important
	}
	#aione_sidenav_1{
		position: fixed;
	}

	.aione-sidenav{
		width: 17%;
		float: left;
		background-color: #FFF;
		color: grey;
		height: 700px;
		overflow: scroll;
		position: fixed;
		z-index: 9999;
	}
	.aione-content{
		width: 83%;
		float: right;
		margin-top: 78px;
	}
	.aione-content-mini{
		width: 100%;
	    -webkit-transition-property: width; 
	    -webkit-transition-duration: 1s;
	    transition-property: width;
	    transition-duration: 1s;
	}
	#aione_sidenav_1 ul {
	  margin: 0px 0px 0px 0px;
	  list-style: none;
	  line-height: 2em;
	  font-family: Arial;
	  padding: 0;

	  left: 0;

	}
	#aione_sidenav_1 ul li {
	  font-size: 16px;
	  position: relative;
	  
	  border-bottom: 1px solid #e8e8e8;

	}
	
	#aione_sidenav_1 ul li ul li:hover {
	  background-color: #37A1D5 !important;
	  color: #fff !important;
	  cursor: pointer;
	     /* margin-left: -26px;
    padding-left: 26px;
    width: 113%;*/
	}
	#aione_sidenav_1 ul li ul li:hover a{
	  
	  color: #fff !important;
	}
	
	#aione_sidenav_1 ul li:before {
	  position: absolute;
	  left: -7px;
	  top: -6px;
	  content: '';
	  display: block;
	  border-left: 1px solid #ddd;
	  height: 26px;
	  border-bottom: 1px solid #ddd;
	  width: 10px;
	}
	#aione_sidenav_1 ul li:after {
	  position: absolute;
	  left: -7px;
	  bottom: -3px;
	  content: '';
	  display: block;
	  border-left: 1px solid #ddd;
	  height: 100%;
	}
	#aione_sidenav_1 ul li.root {
	  margin: 0px 0px 0px 0px;
	  
	}
	#aione_sidenav_1 ul li.root:hover {
	  background-color: #FFF;
	}
	#aione_sidenav_1 ul li.root:before {
	  display: none;
	}
	.menu-toggle{
		width: 0px;
	    -webkit-transition-property: width; 
	    -webkit-transition-duration: 1s;
	    transition-property: width;
	    transition-duration: 1s;
	}
	#aione_sidenav_1 ul li.root:after {
	  display: none;
	}
	#aione_sidenav_1 ul li:last-child:after {
	  display: none;
	}
	.aione-hide{
		display: none;
	}
	.aione-menu-mini{
		width:6%;
	}
	.m-0{
		margin:0px !important; 
	}
	a{
	 	text-decoration: none !important;
	 	color: grey !important;
	}
	.aione-arrow-left{
		transform: rotate(-90deg);
	}
	.w-3{
	    -webkit-transition-property: width; 
	    -webkit-transition-duration: 1s;
	    transition-property: width;
	    transition-duration: 1s;
		width: 3% !important;
	}
	.fade-background {
	    background-color: black;
	    z-index: 99;
	    opacity: 0.5;
	    width: 100%;
	    height: 100%;
	    position: fixed;
	    top: 0;
	    left: 0;
	    display: none;
	}	
	.aione-block{
		display: block;
	}
	.menu-close:hover{
		border:0px !important;
		background-color: #D43113;
		color: #FFF !important;
		border-color:#D43113;
	}
</style>

