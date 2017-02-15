<script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{asset('/bower_components/admin-lte/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script type="text/javascript">
	function route(){
		return '{{url("/")}}';
	}
</script>

@if(@$js)
	@foreach(@$js as $key => $file)
    @if(is_array($file))
      @foreach(@$file as $iKey => $iVal)
        <script type="text/javascript" src="{{asset('js/'.$iVal.'.js')}}?ref={{rand(8899,9999)}}"></script>
      @endforeach
    @else
		  @include('components.plugins.js.'.$file)
    @endif
	@endforeach
@endif
  

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

<script src="{{asset('/bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('/bower_components/admin-lte/plugins/fastclick/fastclick.js')}}"></script>
<script src="{{asset('/bower_components/admin-lte/dist/js/app.min.js')}}"></script>

<script src="{{asset('/bower_components/admin-lte/dist/js/demo.js')}}"></script>
<script src="{{asset('/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js')}}"></script>

<script src="{{asset('/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
 <script type="text/javascript">
  	$(document).ready(function(){

      $('#datepickerFrom').datepicker({
          dateFormat: 'yy-mm-dd',
          autoclose: true,
    });
       $('#datepickerTo').datepicker({
      autoclose: true
    });


      var sVal =  $("#source").val();
      source(sVal);
            
      $("#source").on('change',function(){
          var sVal =  $("#source").val();
          source(sVal);
      });

function chk()
{
  alert('chk')
}
      function source(sVal)
      {
        if(sVal== '')
            {
                $("#file, #urlTxt, #file_serverTxt").slideUp();
            }
              else if(sVal== 'file')           
            {         
                $("#urlTxt ,#file_serverTxt").slideUp();
                $("#file").slideDown();
            }
            else if(sVal =="url")
            {
                $("#urlTxt").slideDown();
                $("#file ,#file_serverTxt").slideUp();
            }else if(sVal =="file_server")
            {
                $("#file, #urlTxt").slideUp();
                $("#file_serverTxt").slideDown();
            }
      }

    var max_fields      = 5; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var add_create_form      = $(".add_field_create_form"); //Add button ID


    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
            $("#append").append($(".append-data").html());

           // $("#append").append('<a href="#" class="remove_field">Remove</a>');
            // $(wrapper).append("{!!Form::label('route','Route') !!}{!!Form::select('route[]',App\Permisson::getRouteListArray(),null, ['class'=>'form-control','placeholder'=>'url ']) !!}"); //add input box
    });

    $(add_create_form).click(function(e){ //on add input button click
        e.preventDefault();
            $("#append").append('<div>'+$(".input_fields_wrap").html()+'<a href="#" class="remove_field btn btn-danger"><i class="fa fa-minus"></i></a></div>');

           // $("#append").append('<a href="#" class="remove_field">Remove</a>');
            // $(wrapper).append("{!!Form::label('route','Route') !!}{!!Form::select('route[]',App\Permisson::getRouteListArray(),null, ['class'=>'form-control','placeholder'=>'url ']) !!}"); //add input box
    });
    $(document).on('click','.remove_field',function(){
      $(this).parent('div').remove();
    });

      var rand = function() {
          return Math.random().toString(36).substr(2); // remove `0.`
      };

      var token = function() {
          return rand() + rand() // to make it longer
      };

      $('.generate-token').click(function(){

      		$('#token').val(token());
      });
      $('#token').val(token());

      $("#filter").keyup(function () {
            var filter = jQuery(this).val();
            jQuery(".filtered > li").each(function () {
                if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                    jQuery(this).hide();
                } else {
                    jQuery(this).show()
                }
            });
      });

      $('#search-btn').click(function(e){
          e.preventDefault();
      });
  });
 </script>
