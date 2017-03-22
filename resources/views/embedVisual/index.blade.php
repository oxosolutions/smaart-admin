@extends('layouts.visual')
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
			    padding: 0px
		}
		.main{
			max-width: 100%
		}
	</style>
		<div class="survey-wrapper" style="margin-top: 35px;">
			<div class="survey-header">
				<div class="wrapper-row">
					<h1 class="survey-title">MY MAP</h1>
					<h3 class="survey-description">This is some description if any</h3>
				</div> <!-- wrapper-row -->
			</div> <!-- survey-header -->
			<div class="survey-content">
				<div class="wrapper-row">
					<?php
						echo "<pre>";
							print_r($data);
						?>
				</div>
			</div>
		</div>
		<div id="" class="survey-wrapper">
		
				@if ($message = Session::get('successfullSaveSurvey'))
					<div id="survey_saved_{{$sdata->id}}" class="survey-header">
						<div class="wrapper-row">
							<h1 class="survey-title">Success</h1>
							<h3 class="survey-description">{{Session::get('successfullSaveSurvey')}}</h3>
						</div> <!-- wrapper-row -->
					</div>
				@else
					{{-- <div id="survey_content" class="survey-content">
						<div class="wrapper-row">
					
							
							
						</div> 
					</div>  --}}
				@endif
				
		</div><!-- survey-wrapper -->

@endsection