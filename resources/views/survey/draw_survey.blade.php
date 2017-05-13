@extends('layouts.survey')
@section('content')
@php
	$Drawer = 'App\Http\Controllers\DrawSurveyController';
@endphp


{{-- @foreach ($sdata->group as $key => $survey_group)
	@foreach ($survey_group->question as $field_key => $field) 

	{{dump(json_decode($field->answer,true))}}

	@endforeach
@endforeach
 --}}
{{-- {{dd($design_settings)}} --}}
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
					@if($timer['survey_timer_status'] == 1)
						<?php
						$expire_time = "";
						if($timer['survey_timer_type'] == 'duration'){
							$date = new DateTime(date("Y-m-d H:i:s"));
							$date->add(new DateInterval('PT'.$timer['survey_duration'].'M'));
							$expire_time = $date->format('Y/m/d H:i:s');
						} 
						if($timer['survey_timer_type'] == 'expiry'){
							$expire_time = date("Y/m/d H:i:s", strtotime($timer['survey_expiry_date']));
						}
						?>
						Time left <span id="survey_timer" class="survey-timer" data-expire-time="{{$expire_time}}"></span>
					@endif
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
				<progress style="width: 100%;height: 10px;" id="progress" value="{{@$viewType['ques_filled_count']}}" max="{{$progress_bar_question}}">
				</progress> 
			@endif
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
													<h2 class="group-title">{{($Drawer::getSettings($design_settings,'showGroupTitle'))?$survey_group->title:''}}</h1>
													<h4 class="group-description">{{($Drawer::getSettings($design_settings,'groupDescription'))?$survey_group->description:''}}</h3>
												</div> <!-- content-row -->
											</div> <!-- group-header -->
										@endif
										<div id="group_content_{{$survey_group->id}}" class="group-content">
											<div class="content-row">
											
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
														}else{

														@$validation_array[] = 'custom';
														}
													  	//$validate = "data-validation-regexp=".$field_meta['pattern']; 
													  	@$validate = "data-validation-regexp=".$field_meta['pattern']; 
													}
														
												
													 
														$validations = implode(' ',$validation_array);
													?>
													

													
													<div id="field_{{$field_id}}" class="field-wrapper field-wrapper-{{$field_meta['question_id']}} field-wrapper-type-{{$field_meta['question_type']}}">

														
														<div id="field_label_{{$field_meta['question_id']}}" class="field-label">
															<label for="input_{{$field_meta['question_id']}}">
																<h4 class="field-title"><?php echo $field->question; ?></h4>
																@if($Drawer::getSettings($design_settings,'questionPlacement') == 'above')
																	<p class="field-description">
																		<?php
																			$media = SurveyHelper::get_survey_media($field_meta['question_desc']);
																		 	echo $media['text']; 
																		 ?>
																	 </p>
																@endif
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
																			<input class="{{$field_meta['question_id']}}" data-validation="checkbox_group" data-validation-qty="min1" id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}[]" type="checkbox" value="{{$option_value['options']['value']}}">
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
		<div id="survey_copyright" class="survey-copyright">
			<div class="wrapper-row ">
				&copy; copyright 2017. Survey created with <a href="http://smaartframework.com/" target="_blank">SMAART™ Framework</a> 
			</div> <!-- wrapper-row -->
		</div> <!-- survey-footer -->	
		@endif
	

@endsection
