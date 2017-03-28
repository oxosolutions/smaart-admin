@extends('layouts.survey')
@section('content')
{{@$theme}}
{{@$skip_auth}}
	
	@if(@$err_msg)
			@foreach($err_msg as $key => $error_message)
				<div class="survey-wrapper" style="margin-top: 35px;">
					<div class="survey-header">
						<div class="wrapper-row">
							<h1 class="survey-title">OOPS..!  Something Went Wrong</h1>
							<h3 class="survey-description"></h3>
						</div> <!-- wrapper-row -->
					</div> <!-- survey-header -->
					<div class="survey-content">
						<div class="wrapper-row ">
							<h1 class="survey-error-messages"><?php echo $error_message; ?></h1>
						</div>
					</div>
				</div>
				@break
			@endforeach
	@else
		<div id="survey_{{$sdata->id}}" class="survey-wrapper">
		@if(Auth::check()!=false)
		<div class="top-bar">
			<ul>
				<li>Welcome {{Auth::user()->name}},</li>
				<li><a class'='button' href="{{url('out')}}/{{$token}}">Logout</a></li>
			</ul>
		</div>
		@endif
			{!! Form::open(['route' => 'survey.store','id'=>"survey_form_".$sdata->id, 'class'=>'survey-form','files'=>true]) !!}
				<input type="hidden" name="survey_started_on" value="<?php echo date('YmdHis').substr((string)microtime(), 2, 6); ?>" >
				<input type="hidden" name="survey_id" value="{{$sdata->id}}" >
				<input type="hidden" name="code" value="{{$token}}" />
				<div id="survey_header_{{$sdata->id}}" class="survey-header">
					<div class="wrapper-row">

						<h1 class="survey-title"><?php echo $sdata->name; ?> </h1> 
						<h3 class="survey-description"><?php echo $sdata->description; ?></h3>
					</div> <!-- wrapper-row -->
				</div> <!-- survey-header -->
				@if ($message = Session::get('successfullSaveSurvey'))
					<div id="survey_success_{{$sdata->id}}" class="survey-success-messages">
						<div class="wrapper-row">
							<h1 class="survey-title">Success</h1>
							<h3 class="survey-description">{{Session::get('successfullSaveSurvey')}}</h3>
						</div> <!-- wrapper-row -->
					</div>
				@else
					<div id="survey_content_{{$sdata->id}}" class="survey-content">

						<div class="wrapper-row">
					
							@if(count($sdata->group)>0)
								@foreach ($sdata->group as $key => $survey_group)
									<div id="survey_group_{{$survey_group->id}}" class="survey-group"> 
									
										<div id="group_header_{{$survey_group->id}}" class="group-header">
											<div class="content-row">
												<h2 class="group-title"><?php echo $survey_group->title; ?></h1>
												<h4 class="group-description"><?php echo $survey_group->description; ?></h3>
											</div> <!-- content-row -->
										</div> <!-- group-header -->
										
										<div id="group_content_{{$survey_group->id}}" class="group-content">
											<div class="content-row">
											
											
												@foreach ($survey_group->question as $field_key => $field) 
													<?php
														$field_id = 'sid'.$field->survey_id.'_gid'.$field->group_id.'_qid'.$field->id;
														$field_meta = json_decode($field->answer,true);
														$validation_array = [];

													if($field_meta['required']=="yes"){
														$validation_array[] = 'required';
													
													}

													if($field_meta['pattern']!=null && $field_meta['pattern']!="blank" ){
														$validation_array[] = 'custom';
													  	//$validate = "data-validation-regexp=".$field_meta['pattern']; 
													  	$validate = "data-validation-regexp=".$field_meta['pattern']; 
													}
														
												
													 
														$validations = implode(' ',$validation_array);
													?>
													

													
													<div id="field_{{$field_id}}" class="field-wrapper field-wrapper-{{$field_meta['question_id']}} field-wrapper-type-{{$field_meta['question_type']}}">

														
														<div id="field_label_{{$field_meta['question_id']}}" class="field-label">
															<label for="input_{{$field_meta['question_id']}}">
																<h4 class="field-title"><?php echo $field->question ?></h4>
																<p class="field-description"><?php
																$media = SurveyHelper::get_survey_media($field_meta['question_desc']);

																 echo $media['text']; ?></p>
															</label>
														</div> <!-- field-label -->
													

														
														
														<div  id="field_{{$field_meta['question_id']}}" class="field {{$field_meta['question_type']}} field-type-{{$field_meta['question_type']}} ">
														
															@if($field_meta['question_type'] =="text")

																<input {{$validate}}  name="{{$field_meta['question_id']}}" id="input_{{$field_meta['question_id']}}" type="text" placeholder="" data-validation="{{$validations}}" >
																@elseif($field_meta['question_type'] =="text_only")
																<textarea {{$validate}} name="{{$field_meta['question_id']}}" id="textarea_{{$field_meta['question_id']}}"> </textarea>
															@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="checkbox" )
																	@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																		<div id="field_option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option">
																			<input id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}[]" type="checkbox" value="{{$option_key}}">
																			<label for="option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option-label"> <?php echo $option_value; ?></label>
																		</div>
																	@endforeach

															@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="radio" )
																@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																	<div id="field_option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option">
																		<input id="option_{{$field_meta['question_id']}}_{{$option_key}}" name="{{$field_meta['question_id']}}" type="radio" value="{{$option_key}}">
																		<label for="option_{{$field_meta['question_id']}}_{{$option_key}}" class="field-option-label"> <?php echo $option_value; ?></label>
																	</div>
																@endforeach
															@elseif($field_meta["extraOptions"] && $field_meta['question_type'] =="dropdown" )
																<select {{$validate}} name="{{$field_meta['question_id']}}" >
																@foreach($field_meta["extraOptions"] as $option_key =>  $option_value)
																	<option value="{{$option_key}}"> <?php echo $option_value; ?> </option>
																@endforeach
																</select>

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
							{!! Form::submit('Save', ['class' => 'button']) !!}
                			{!! Form::button('Cancel', ['class' => 'button','onclick'=>'window.location.reload()']) !!}
							
						</div> <!-- wrapper-row -->
					</div> <!-- survey-footer -->
				@endif

		</div><!-- survey-wrapper -->
	@endif
			

@endsection
