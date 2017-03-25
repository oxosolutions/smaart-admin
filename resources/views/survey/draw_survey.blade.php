@extends('layouts.survey')
@section('content')
	<style type="text/css">
		.error_main_div{
			padding: 10px;
			border: 2px dashed #ededed;
			text-align: center;
		}
		.error_main_div h1{
			font-size: 100px;
			padding: 0px;
		}
		.erroe_main_div h2{
			font-size: 80px
		}
		.wrapper{
			min-height: 648px;
		}
		.survey-error-messages{
			color: red;
			text-align: center;
			border: 2px dashed red;
			padding: 65px;
			margin: 50px;
			font-size: 25px
		}
		.survey-success-messages{
			color: #666666;
		}

	</style>
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
			{{Auth::user()->name}}
		@endif
			{!! Form::open(['route' => 'survey.store','id'=>"survey_form_".$sdata->id, 'class'=>'survey-form','files'=>true]) !!}
				<input type="hidden" name="started_on" value="<?php echo date('YmdHisu'); ?>" >
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
											<?php //dd($survey_group->question); ?>
											
												@foreach ($survey_group->question as $field_key => $field) 
													<?php 
													$field_id = 'sid'.$field->survey_id.'_gid'.$field->group_id.'_qid'.$field->id;
													$field_meta = json_decode($field->answer,true);
													
													// 1. Required Field Star
													// 2. required Field Class
													?>
													
													<div id="field_{{$field_id}}" class="field-wrapper field-wrapper-{{$field_meta['question_id']}} field-wrapper-type-{{$field_meta['question_type']}}">

														
														<div id="field_label_{{$field_meta['question_id']}}" class="field-label">
															<label for="input_{{$field_meta['question_id']}}">
																<h4 class="field-title"><?php echo $field->question ?></h4>
																<p class="field-description"><?php echo $field_meta['question_desc']; ?></p>
															</label>
														</div> <!-- field-label -->
														
														<div  id="field_{{$field_meta['question_id']}}" class="field {{$field_meta['question_type']}} field-type-{{$field_meta['question_type']}}">
														
															@if($field_meta['question_type'] =="text")
																<input name="{{$field_meta['question_id']}}" id="input_{{$field_meta['question_id']}}" type="text" placeholder="" >
																@elseif($field_meta['question_type'] =="text_only")
																<textarea  name="{{$field_meta['question_id']}}" id="textarea_{{$field_meta['question_id']}}"> </textarea>
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
																<select name="{{$field_meta['question_id']}}" >
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