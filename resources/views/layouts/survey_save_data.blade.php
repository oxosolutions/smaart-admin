<!DOCTYPE html>
<html>
@include('components.head')

<body class="hold-transition skin-blue fixed sidebar-mini">



  <!-- Left side column. contains the logo and sidebar -->
  

       
  @yield('content')
  

  {{--@include('components.aside')--}}


<!-- ./wrapper -->

@include('components.script')

</body>
</html>