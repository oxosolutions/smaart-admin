@if($model->approved ==1)
	<a class="text-red" href="{{route('apiuser.unapproved',$model->id)}}">un-approve</a> | 
@else
	<a class="text-green" href="{{route('apiuser.approved',$model->id)}}">approve</a> | 
@endif
@if(App\UserMeta::checkmeta($model->id) ==0)
	<a href="{{route('api.create_users_meta',$model->id)}}">Add User meta</a> |
@else
	<a href="{{route('apiuser.editmeta',$model->id)}}">Edit User meta</a> | 
@endif
<a href="{{route('api.edit_users',$model->id)}}"><span class="fa fa-edit"></span></a> | 
<a href="{{route('api.user_detail',$model->id) }}"><span class="fa fa-eye" style="color: green;"></span></a> |
<a href="javascript:;" class="delete" data-link="{{route('apiuser.delete',$model->id)}}"  style="color:red" title="Delete"><span class="fa fa-trash" style="color: red"></span></a>
