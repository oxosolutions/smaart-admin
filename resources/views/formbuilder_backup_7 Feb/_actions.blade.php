@if($model->status=='enable')
	<a href="{{route('map.disable',$model->id)}}" >Disable</a>
@else
	<a href="{{route('map.enable',$model->id)}}" >Enable</a>
@endif

| <a href="{{route('map.edit',$model->id)}}" title="Edit"><span class="fa fa-edit"></span></a> | 
<a href="javascript:;" class="delete" data-link="{{route('fact.delete',$model->id)}}" title="Delete"><span class="fa fa-trash"  style="color:red"></span></a>