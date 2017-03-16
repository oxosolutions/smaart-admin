<h1>Draw Survey</h1>
<?php
			$survey['survey_id'] 			= 	$sdata->id;
			$survey['survey_name'] 		= 	$sdata->name;
	  		$survey['survey_author_id']	= 	$sdata->created_by;
	  		$survey['survey_author_name']	= 	$sdata->creat_by->name;
	  		$survey['survey_author_role_id']	= 	$sdata->creat_by->role_id;
	  		$survey['survey_author_role_name']	= 	$sdata->creat_by->roles->name;

	  		$survey['survey_description'] 	=	$sdata->description;
	  		$survey['survey_status'] 		= 	$sdata->status;
	  		if(count($sdata->setting) >0)
	  		{
	  			foreach ($sdata->setting as $key => $value) {	  		
		            $survey['survey_settings'][$value->key]= $value->value;
	            }
	        }
	  		if(count($sdata->group)>0){
				foreach ($sdata->group as $key => $grp) {
					$survey['survey_group'][$key]['group_id'] = $grp->id;
					$survey['survey_group'][$key]['survey_id'] = $grp->survey_id;
					$survey['survey_group'][$key]['title'] = $grp->title;
		    		$survey['survey_group'][$key]['description'] =$grp->description;			
					foreach ($grp->question as $qkey => $ques) {
						$answer = json_decode($ques->answer,true);
						foreach ($answer as $anskey => $ansVal) {
							$survey['survey_group'][$key]['question'][$qkey][$anskey] =$ansVal;
						}
						$survey['survey_group'][$key]['question'][$qkey]['survey_id']  = $ques->survey_id;
		        		$survey['survey_group'][$key]['question'][$qkey]['question']  = $ques->question;
		        		$survey['survey_group'][$key]['question'][$qkey]['group_id']  = $ques->group_id;
					}			
				}
			}

				
				?>


  {!! Form::open(['route' => 'survey.store', 'files'=>true]) !!}
<h3> Survey Name : {{$sdata->name}}</h3>
 @if(count($sdata->group)>0)
				@foreach ($sdata->group as $key => $grp) 
					<h3 style="color:green;">Group Title: {{$grp->title}}</h3>
		    		<p style="color:brown;"> Group Description: {{$grp->description}}</p> 			
					@foreach ($grp->question as $qkey => $ques) 

						<h3 style="color:orange">Question:- {{$ques->question}}</h3>
						<?php $answer = json_decode($ques->answer,true);

						 ?>
						
						
						@foreach ($answer as $anskey => $ansVal) 
						
								@if($anskey=="question_type" && $ansVal=="text")
									<input type="text" placeholder="fill Question" >
									@elseif($anskey=="question_type" && $ansVal=="checkbox")
										<?php $type ="checkbox" ?>
									@endif
									@if(@$type=="checkbox")
										@if($anskey=="extraOptions")
											
										@foreach($ansVal as $optKey =>  $optVal)
											{{$optVal}}<input type="checkbox" value="{{$optKey}}">
										@endforeach
										@endif
									@else
							@endif
								
						@endforeach
						 {{dump($answer)}}	
				@endforeach
			@endforeach
@endif

	<div class="box-footer">
	{!! Form::submit('Save Survey', ['class' => 'btn btn-primary']) !!}
	</div>
