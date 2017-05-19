@extends('layouts.survey_save_data')

@section('content')
    <section class="content">
     <div class="row">
        <div class="col-xs-12">
          @php 
            $head = $ansText ="";
            foreach ($sdata[0] as $key => $value) {
              $head .="<th>$key </th>";
              $ansText .="<td> $value </td>";
            }
            unset($sdata[0]);
            @endphp
              <table id="pages" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th><input type="checkbox" class="icheckbox_minimal-blue selectall"></th>
                 {!! $head !!}
                </tr>
                </thead>
                    <tbody>
                      <tr> 
                        <td></td>
                        {!! $ansText !!}
                      </tr>
                      @foreach($sdata as $key => $val)
                      <tr>
                        <td> </td>
                        @foreach($val as $akey => $aval)
                          <td> {{$aval}}</td>
                        @endforeach
                      </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{route('survey.export_survey_data',['sid'=>$sid])}}">EXporttttttttttttttt</a>
      </div>
    </section>
@endsection