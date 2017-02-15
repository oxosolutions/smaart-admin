<!DOCTYPE html>
<html>
@include('components.head')

<body class="hold-transition skin-blue fixed sidebar-mini">

<div class="wrapper">

  @include('components.header')
  <!-- Left side column. contains the logo and sidebar -->
  
  @include('components.sidebar')

       
  @yield('content')
  

  @include('components.footer')
  {{--@include('components.aside')--}}

</div>
<!-- ./wrapper -->

@include('components.script')

</body>
</html>