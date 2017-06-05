@foreach(glob("js/*.js") as $file)
	<script type="text/javascript" src="{{asset($file)}}?ref={{rand(8899,9999)}}"></script>
@endforeach
