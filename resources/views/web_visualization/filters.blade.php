@if(!empty($filters))
@if(isset($meta['show_filters']) && $meta['show_filters'] == 1)

<!--==============================-->
<div id="aione_sidebar_{{$visualization_id}}" class="aione-box aione-sidebar aione-sidebar-position-{{$meta['filters_position']}}" style="margin-top: 15px;margin-right: 15px">
	<div class="wrapper-row" >

		<div class="chart-filters col l4" >
			
			<div class="filter-title" >
				<center> 
					<h6 style="margin: 0px;font-size: 18px;font-weight: 600;color: grey">Filters <span><a href="javascript:;"><img src="{{asset('arrow-down1.png')}}" alt=""></a></span></h6>
				</center>
			</div>
			
			<div class="survey-chart-filters hideDiv">
				<form method="POST" action="">
					{!! Form::token() !!}
					@php
						$multidrop = 0;
						$singledrop = 0;
						$range = 0;
					@endphp
					@foreach($filters as $key => $value)
						@if($value['column_type'] == 'mdropdown')
							<div class="row" style="margin-top: 5%;">
								<div class=" col-md-12">
									<label>{{ucfirst($value['column_name'])}}</label>
									<select name='mdropdown[{{$multidrop}}][{{$key}}][]' multiple>
										@foreach($value['column_data'] as $option)
											<option value="{{$option}}"
											@if(isset($value['selected_value']) && in_array($option, $value['selected_value']))
												selected="selected"
											@endif
											>{{$option}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@php
								$multidrop++;
							@endphp
						@endif
						@if($value['column_type'] == 'dropdown')
							<div class="row" style="margin-top: 5%;">
								<div class="col-md-12">
									<label>{{ucfirst($value['column_name'])}}</label>
									<select name='dropdown[{{$singledrop}}][{{$key}}][]'>
										<option value="">All</option>
										@foreach($value['column_data'] as $option)
											<option value="{{$option}}" 
											@if(isset($value['selected_value']) && in_array($option, $value['selected_value']))
												selected="selected"
											@endif
											>{{$option}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@php
								$singledrop++;
							@endphp
						@endif
						@if($value['column_type'] == 'range')
							<div class="row" style="margin-top: 5%;">
								<div class="col-md-12">
									<label style="width: 100%">{{ucfirst($value['column_name'])}}</label>
									<b>{{$value['column_min']}}<b/><input type="range[]"  name="range[{{$range}}][{{$key}}]" data-slider-min="{{$value['column_min']}}" data-slider-max="{{$value['column_max']}}" data-slider-step="1" data-slider-value="[{{$value['column_min']}},{{$value['column_max']}}]" class="slider" /><b>{{$value['column_max']}}<b/>
								</div>
							</div>
							@php
								$range++;
							@endphp
						@endif
					@endforeach
					<div class="chats-filter-button">
						<button name="downloadData" type="submit" value="downloadData" class="aione-btn waves-effect waves-light btn" style="">Download Data</button>
						<input type="submit" name="applyFilter" style="float: right" class="aione-btn btn btn-default pull-right" value="Apply Filters" />
					</div>
					
				</form>
			</div>
		</div>


	</div> <!-- wrapper-row -->
</div> <!-- aione_sidebar -->
@endif
@endif
<style type="text/css">
	.aione-btn{
		margin-left: 5px;background-color: #4584F0;border: none;padding: 10px 20px;color: white;margin: 15px 0px;font-size: 14px;font-weight: 700;
	}
</style>