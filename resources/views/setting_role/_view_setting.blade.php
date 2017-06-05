<style type="text/css">
.label{
  font-size: 100%;
  font-weight: 600;
}
</style>

<div class="box-body">

  <table id="example2" class="table table-bordered table-hover">
  <tr>
  <th>Module</th>
  <th>Read</th>
  <th>Write</th>
  <th>Delete</th>
  </tr>
    <tbody>
@foreach($role_permisson as $val)
        <tr>
            <td>{{$val->pname}}</td>
            <td>@if ($val->read==1) YES @else no @endif</td>
            <td>@if ($val->write==1) YES @else no @endif</td>
             <td>@if ($val->delete==1) YES @else no @endif</td>

        </tr>
 @endforeach      
        
        
    </tbody>
  </table>
  <!-- +"rid": 10
      +"rname": "admin"
      +"rdname": "Admin"
      +"read": 1
      +"write": 0
      +"delete": 0
      +"pname": "new permisson"
      +"pdname": "Goal" -->

</div>
