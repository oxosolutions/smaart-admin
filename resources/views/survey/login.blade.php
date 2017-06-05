@extends('layouts.survey')
@section('content')
<style type="text/css">
	.survey-wrapper{
		padding:20px;
	}
</style>
<div  class="survey-wrapper">
	<center><h1>Login</h1></center>
	<form method="POST" action="{{route('survey.do_auth')}}">
		<div class="survey-content">
			<div class="wrapper-row">
		
				<input type="hidden" value="{{csrf_token()}}" name="_token" />

				<div id="field_label_SID4_GID1_QID1" class="field-label">
					<label for="input_SID4_GID1_QID1">
						<h4 class="field-title">Your email</h4>
						<p class="field-description">Please enter your Email</p>
					</label>
				</div>
				<div id="field_SID4_GID1_QID1" class="field text field-type-text">
			    	{{Form::text('email','',['placeholder'=>'Username','id'=>'input_SID4_GID1_QID1'])}}
			    	@if($errors->has('email'))
						<span style="font-size: 12px;margin-top: -10%; color: #ff1b1b">
							{{$errors->first('email')}}
						</span>
					@endif
				</div>

				<div id="field_label_SID4_GID1_QID1" class="field-label">
					<label for="input_SID4_GID1_QID1">
						<h4 class="field-title">Your Password</h4>
						<p class="field-description">Please enter your Password</p>
					</label>
				</div>
				<div id="field_SID4_GID1_QID1" class="field text field-type-text">
					{{Form::password('password',['placeholder'=>'Password','id' => 'input_SID4_GID1_QID1'])}}
			        @if($errors->has('password'))
						<span style="font-size: 12px;margin-top: -10%; color: #ff1b1b">
							{{$errors->first('password')}}
						</span>
			        @endif
				</div>
			</div> <!-- wrapper-row -->
		</div> <!-- survey-content -->
		
		<div class="survey-footer">
			<div class="wrapper-row">
			
				<input type="submit" name="submit" value="Login Now" class="survey-submit button submit-button" />
				
			</div> <!-- wrapper-row -->
		</div> <!-- survey-footer -->
	</form>
	
	
</div><!-- survey-wrapper -->

@endsection
