
       
            <div class="box-body no-padding">
              <table class="table table-striped">
               
                <tr>
                  <td style="width:10px;">1.</td>
                  <td> 
                      {!!Form::label('status','Enable Surrvey ',['class'=>'control-label']) !!}
                  </td>
                  <td>
                      {!!Form::radio('status','enable',null, ['class'=>' cmn-toggle cmn-toggle-round']) !!}
                      {!!Form::label('enable','Enable') !!}
                      {!!Form::radio('status','disable',null, ['class'=>'']) !!}
                      {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
                  </td>
                 
                </tr>
                <tr>
                  <td>2.</td>
                  <td>
                        {!!Form::label('status','Authentication Required :',['class'=>' control-label']) !!}
                  </td>
                  <td>
                      {!!Form::radio('authentication_required','enable',null, ['class'=>'auth_req']) !!}
                      {!!Form::label('enable','Enable') !!}
                      {!!Form::radio('authentication_required','disable',true, ['class'=>'auth_req ']) !!}
                      {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
                  </td>
                </tr>
                @if($model->authentication_required =='enable')
                   {{--  <script> $("#auth_req").slidedown(); </script> --}}
                @endif
                <tr id="auth_req">
                  <td>3.</td>
                  <td> 
                    {!!Form::label('status','Authentication Type :',['class'=>'control-label']) !!}
                  </td>
                  <td>
                    {!!Form::radio('authentication_type','role_based', null, ['id'=>'rol','class'=>'auth_type']) !!}
                    {!!Form::label('enable','Role Based') !!}
                    {!!Form::radio('authentication_type','individual_based', true, ['class'=>'auth_type']) !!}
                    {!!Form::label('enable','Individual Based',['class'=>' control-label']) !!}
                  </td>
                </tr>
                <tr id="individual_based">
                  <td>4.</td>
                  <td>
                      {!!Form::label('individual','Individual List') !!}
                  </td>
                  <td>
                       {!!Form::select('individual',App\User::userList(),@$model->authorize, ['class'=>'form-control select2 multi','multiple']) !!}

                  </td>
                </tr>

                <tr id="role_based">
                  <td>4.</td>
                  <td>
                      {!!Form::label('role_list','Role list') !!}
                  </td>
                  <td>
                    @if($model->authentication_type=='role_based' && $model->authorize!=null)
                       <?php $roleData = json_decode($model->authorize,true); ?>
                          @foreach (App\Role::role_list() as $key => $val)
                             @if(in_array($key, $roleData))
                                {!!Form::checkbox('role[]',$key,true, ['class'=>'']) !!}
                                {!!Form::label('role', $val) !!}
                            @else
                              {!!Form::checkbox('role[]',$key,null, ['class'=>'']) !!}
                                {!!Form::label('role', $val) !!}
                             
                          @endif
                           
                          </br>
                        @endforeach
                    @else
                      @foreach (App\Role::role_list() as $key => $val)
                        {!!Form::checkbox('role[]',$key,null, ['class'=>'']) !!}
                        {!!Form::label('role', $val) !!}
                         </br>
                      @endforeach
                    @endif
                  </td>
                </tr>
                
                  <tr>
                  <td >5.</td>
                  <td> 
                    {!!Form::label('scheduling','Enable Survey Scheduling',['class'=>' control-label']) !!}
                  </td>
                  <td>
                    {!!Form::radio('scheduling','enable',null, ['class'=>'scheduling']) !!}
                    {!!Form::label('enable','Enable') !!}
                    {!!Form::radio('scheduling','disable',true, ['class'=>'scheduling']) !!}
                    {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
                  </td>
                 
                </tr>
                <tr class="SCHenable">
                  <td style="width:10px;">6.</td>
                  <td> 
                    {!!Form::label('start_date','Survey Start Date') !!}
                   </td>
                  <td>
                       {!!Form::text('start_date',null, ['class'=>'form-control dates','placeholder'=>'DD-MM-YYYY HH:MM']) !!}       
                  </td>
                 
                </tr>
                <tr class="SCHenable">
                  <td style="width:10px;">7.</td>
                  <td> 
                      {!!Form::label('expire_date','Survey Expire Date') !!}
                   </td>
                  <td>
                       {!!Form::text('expire_date',null, ['class'=>'form-control dates','placeholder'=>'DD-MM-YYYY HH:MM']) !!}
                  </td>
                 
                </tr>
                 <tr>
                  <td style="width:10px;">8.</td>
                  <td> 
                      {!!Form::label('timer_status','Enable Survey Timer',['class'=>' control-label']) !!}
                   </td>
                  <td>
                      {!!Form::radio('timer_status','enable',null, ['class'=>'timer']) !!}
                      {!!Form::label('enable','Enable') !!}
                      {!!Form::radio('timer_status','disable',true, ['class'=>'timer ']) !!}
                      {!!Form::label('enable','Disable',['class'=>'control-label']) !!}                  
                  </td>
                </tr>
                 <tr id="timer_types">
                  <td style="width:10px;">9.</td>
                  <td> 
                    {!!Form::label('timer_type','Survey Timer Type',['class'=>' control-label']) !!}
     
                   </td>
                  <td>
                    {!!Form::radio('timer_type','expire_time',true, ["id"=>"expire_time", "class"=>"time_types"]) !!}
                    {!!Form::label('enable','Survey Expire Time') !!}
                    {!!Form::radio('timer_type','durnation',null, [ "class"=>"time_types"]) !!}
                    {!!Form::label('enable','Survey Duration',['class'=>' control-label']) !!}                
                  </td>
                </tr>
                <tr id="durnation">
                  <td style="width:10px;">10.</td>
                  <td> 
                    {!!Form::label('survey_duration','Survey Duration') !!}
     
                   </td>
                  <td>
                     {!!Form::text('timer_durnation',@$timer_durnation, ['class'=>'form-control','placeholder'=>'HH:MM']) !!}
                  </td>
                 
                </tr>
                 <tr>
                  <td style="width:10px;">11.</td>
                  <td> 
                    {!!Form::label('response_limit','Enable Survey Response Limit',['class'=>' control-label']) !!}
     
                   </td>
                  <td>
                    {!!Form::radio('response_limit_status','enable',null, ['class'=>'res_lmt ']) !!}
                    {!!Form::label('enable','Enable') !!}
                    {!!Form::radio('response_limit_status','disable',true, ['class'=>'res_lmt ']) !!}
                    {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
                  </td>
                 
                </tr>
                  <tr id="res_lmt">
                  <td  style="width:10px;">12.</td>
                  <td> 
                      {!!Form::label('response_limit_val','Survey Response Limit') !!}
                   </td>
                  <td>
                    {!!Form::text('response_limit',@$response_limit, ['class'=>'form-control','placeholder'=>'']) !!}     
                  </td>
                 
                </tr>
                <tr>
                  <td style="width:10px;">13.</td>
                  <td> {!!Form::label('response_limit','response_limit_type',['class'=>'control-label']) !!}
  
                   </td>
                  <td>
                    {!!Form::radio('response_limit_type','per_user',null, ['class'=>'']) !!}
                    {!!Form::label('enable',' Per User') !!}
                    {!!Form::radio('response_limit_type','per_ip_address', null,['class'=>'']) !!}
                    {!!Form::label('enable','Per IP Address',['class'=>' control-label']) !!}                 
                   </td>
                 
                </tr>
                 <tr>
                  <td style="width:10px;">14.</td>
                  <td>
                   {!!Form::label('error_messages','Display Custom error messages',['class'=>' control-label']) !!}

                   </td>
                  <td>
                    {!!Form::radio('error_messages','enable', null, ['class'=>'error_messages']) !!}
                    {!!Form::label('enable','Enable') !!}
                    {!!Form::radio('error_messages','disable',true, ['class'=>'error_messages']) !!}
                    {!!Form::label('enable','Disable',['class'=>' control-label']) !!}               
                   </td>
                 
                </tr>
               <?php $mes_arry = json_decode($model->error_message_value,true); ?>
                <tr class="mess" >
                      
                      <td style="width:60px;">Mno 1</td>
                      <td><label class="form-field-heading">Survey is Disabled </label></td> 
                       <td >       
                        <input type="text" id="survey_messages_disabled" class="form-control" name="mess[survey_messages_disabled]" value="{{@$mes_arry['survey_messages_disabled']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
                  <tr  class="mess" >
                    <td style="width:60px;">Mno 2</td>
                    <td >
                      <label class="form-field-heading">Survey Authorization Required </label>
                      <!-- <h3 class="form-field-subheading">title</h3> -->
                    </td>
                    <td class="aione-input-field-wrapper">       
                      <input type="text" id="survey_messages_authorization_required" class="form-control" name="mess[survey_messages_authorization_required]" value="{{@$mes_arry['survey_messages_authorization_required']}}" placeholder="eg. You need to be logged in to access the survey">
                    </td>
                  </tr>
                <tr class="mess">
                      <td style="width:60px;">Mno 3</td>
                      <td><label class="form-field-heading">Survey Un-authorization Role </label></td> 
                       <td >       
                        <input type="text" id="survey_messages_disabled" class="form-control" name="mess[survey_messages_unauthorized_role]" value="{{@$mes_arry['survey_messages_unauthorized_role']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>

                <tr class="mess">
                      <td style="width:60px;">Mno 4</td>
                      <td><label class="form-field-heading">Survey Un-authorization User </label></td> 
                       <td >       
                        <input type="text" id="survey_messages_disabled" class="form-control" name="mess[survey_messages_unauthorized_user]" value="{{@$mes_arry['survey_messages_unauthorized_user']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
                <tr class="mess">
                      <td style="width:60px;">Mno 5</td>
                      <td><label class="form-field-heading">Invalid Survey Id </label></td> 
                       <td >       
                        <input type="text" id="survey_messages_disabled" class="form-control" name="mess[survey_messages_invalid_id]" value="{{@$mes_arry['survey_messages_invalid_id']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
                <tr class="mess">
                      <td style="width:60px;">Mno 6</td>
                      <td><label class="form-field-heading">Empty Survey Id  </label></td> 
                       <td >       
                        <input type="text" id="survey_messages_disabled" class="form-control" name="mess[survey_messages_empty_id]" value="{{@$mes_arry['survey_messages_empty_id']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
                <tr class="mess">
                      <td style="width:60px;">Mno 7</td>
                      <td><label class="form-field-heading">Survey Not Started </label></td> 
                       <td >       
                        <input type="text" id="" class="form-control" name="mess[survey_messages_not_started]" value="{{@$mes_arry['survey_messages_not_started']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
                <tr class="mess">
                      <td style="width:60px;">Mno 8</td>
                      <td><label class="form-field-heading">Survey is Expired </label></td> 
                       <td >       
                        <input type="text" id="" class="form-control" name="mess[survey_messages_expired]" value="{{@$mes_arry['survey_messages_expired']}}" placeholder="eg. Survey is disabled">
                    </td>
                </tr>
   
              </table>

            </div>
            <!-- /.box-body -->
          </div>
       

       
     

{{-- <div class="box-body">

 
  <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
      {!!Form::label('status','Enable Surrvey ',['class'=>'col-sm-4 control-label']) !!}
      <div class="col-sm-2">
        {!!Form::radio('status','enable', ['class'=>'form-control']) !!}
              {!!Form::label('enable','Enable') !!}

      </div>
      <div class="col-sm-2">
        {!!Form::radio('status','disable', ['class'=>'form-control']) !!}
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
      </div>
      @if($errors->has('name'))
        <span class="help-block">
              {{ $errors->first('name') }}
        </span>
      @endif
  </div></br>

   <div class="form-group {{ $errors->has('authentication_required') ? ' has-error' : '' }}">
      {!!Form::label('status','Authentication Required :',['class'=>'col-sm-4 control-label']) !!}
      <div class="col-sm-2">
        {!!Form::radio('authentication_required','enable', ['class'=>'form-control']) !!}
              {!!Form::label('enable','Enable') !!}

      </div>
      <div class="col-sm-2">
        {!!Form::radio('authentication_required','disable', ['class'=>'form-control']) !!}
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
      </div>
      @if($errors->has('authentication_required'))
        <span class="help-block">
              {{ $errors->first('authentication_required') }}
        </span>
      @endif
  </div></br>

   <div class="form-group {{ $errors->has('authentication_type') ? ' has-error' : '' }}">
      {!!Form::label('status','Authentication Type :',['class'=>'col-sm-4 control-label']) !!}
      <div class="col-sm-2">
        {!!Form::radio('authentication_type','role_based', ['class'=>'form-control']) !!}
              {!!Form::label('enable','Role Based') !!}

      </div>
      <div class="col-sm-2">
        {!!Form::radio('authentication_type','individual_based', ['class'=>'form-control']) !!}
        {!!Form::label('enable','Individual Based',['class'=>' control-label']) !!}
      </div>
      @if($errors->has('authentication_type'))
        <span class="help-block">
              {{ $errors->first('authentication_type') }}
        </span>
      @endif
  </div></br>

    <div class="form-group {{ $errors->has('individual') ? ' has-error' : '' }}">
    {!!Form::label('individual','Individual List') !!}
    {!!Form::select('individual',App\User::userList(),@$individual, ['class'=>'form-control select2 multi','multiple']) !!}
    @if($errors->has('individual'))
      <span class="help-block">
            {{ $errors->first('individual') }}
      </span>
    @endif
  </div>
    <div class="form-group {{ $errors->has('individual') ? ' has-error' : '' }}">
      <h3> Role list </h3>
          @foreach (App\Role::role_list() as $key => $val)

                  {!!Form::checkbox('role[]',$key, ['class'=>'form-control']) !!}
                    {!!Form::label('role', $val) !!}
            </br>
          @endforeach
</div>

  <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
    {!!Form::label('scheduling','Enable Survey Scheduling',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('scheduling','enable', ['class'=>'form-control']) !!}
            {!!Form::label('enable','Enable') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('scheduling','disable', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div></br>

  <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
    {!!Form::label('start_date','Survey Start Date') !!}
    {!!Form::text('start_date',null, ['class'=>'form-control','placeholder'=>'DD-MM-YYYY HH:MM']) !!}
    @if($errors->has('start_date'))
      <span class="help-block">
            {{ $errors->first('start_date') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('expire_date') ? ' has-error' : '' }}">
    {!!Form::label('expire_date','Survey Expire Date') !!}
    {!!Form::text('expire_date',null, ['class'=>'form-control','placeholder'=>'DD-MM-YYYY HH:MM']) !!}
    @if($errors->has('expire_date'))
      <span class="help-block">
            {{ $errors->first('expire_date') }}
      </span>
    @endif
  </div></br>

  <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
    {!!Form::label('timer_status','Enable Survey Timer',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('timer_status','enable', ['class'=>'form-control']) !!}
            {!!Form::label('enable','Enable') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('timer_status','disable', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div></br>

   <div class="form-group {{ $errors->has('timer_type') ? ' has-error' : '' }}">
    {!!Form::label('timer_type','Survey Timer Type',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('timer_type','expire_time', ['class'=>'form-control']) !!}
            {!!Form::label('enable','Survey Expire Time') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('timer_type','durnation', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Survey Duration',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('timer_type'))
      <span class="help-block">
            {{ $errors->first('timer_type') }}
      </span>
    @endif
  </div></br>

  <div class="form-group {{ $errors->has('survey_duration') ? ' has-error' : '' }}">
    {!!Form::label('survey_duration','Survey Duration') !!}
    {!!Form::text('timer_durnation',null, ['class'=>'form-control','placeholder'=>'HH:MM']) !!}
    @if($errors->has('survey_duration'))
      <span class="help-block">
            {{ $errors->first('survey_duration') }}
      </span>
    @endif
  </div></br>

  <div class="form-group {{ $errors->has('response_limit') ? ' has-error' : '' }}">
    {!!Form::label('response_limit','Enable Survey Response Limit',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('response_limit_status','enable', ['class'=>'form-control']) !!}
            {!!Form::label('enable','Survey Expire Time') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('response_limit_status','disable', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('response_limit'))
      <span class="help-block">
            {{ $errors->first('response_limit') }}
      </span>
    @endif
  </div></br>  

  <div class="form-group {{ $errors->has('response_limit_val') ? ' has-error' : '' }}">
    {!!Form::label('response_limit_val','Survey Response Limit') !!}
    {!!Form::text('response_limit',null, ['class'=>'form-control','placeholder'=>'']) !!}
    @if($errors->has('response_limit_val'))
      <span class="help-block">
            {{ $errors->first('response_limit_val') }}
      </span>
    @endif
  </div></br>

  <div class="form-group {{ $errors->has('response_limit') ? ' has-error' : '' }}">
    {!!Form::label('response_limit','response_limit_type',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('response_limit_type','per_user', ['class'=>'form-control']) !!}
            {!!Form::label('enable',' Per User') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('response_limit_type','per_ip_address', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Per IP Address',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('response_limit'))
      <span class="help-block">
            {{ $errors->first('response_limit') }}
      </span>
    @endif
  </div></br>  
  
  <div class="form-group {{ $errors->has('response_limit') ? ' has-error' : '' }}">
    {!!Form::label('error_messages','Display Custom error messages',['class'=>'col-sm-4 control-label']) !!}
    <div class="col-sm-2">
    {!!Form::radio('error_messages','enable', ['class'=>'form-control']) !!}
            {!!Form::label('enable','Enable') !!}
    </div>
    <div class="col-sm-2">
    {!!Form::radio('error_messages','disable', ['class'=>'form-control']) !!}
    
        {!!Form::label('enable','Disable',['class'=>' control-label']) !!}
    </div>
    @if($errors->has('error_messages'))
      <span class="help-block">
            {{ $errors->first('error_messages') }}
      </span>
    @endif
  </div></br>  
 


  


</div>
<!-- /.box-body -->
 --}}