 <a href="{{route('surrvey.create',$model->id)}}" title="Edit"> Question </a>|<a href="{{route('surrvey.edit',$model->id)}}" title="Edit"><span class="fa fa-edit"></span></a> | 
<a href="javascript:;" class="delete" data-link="{{route('surrvey.del',$model->id)}}" title="Delete"><span class="fa fa-trash"  style="color:red"></span></a>
| 
<a href="{{route('surrvey.setting',$model->id)}}" >Setting</a>
| @if(!empty($model->surrvey_table) || $model->surrvey_table !=null)
<a href="{{route('survey.draw',$model->id)}}" >Fill Survey</a>
@else
Still Not Filled
@endif
