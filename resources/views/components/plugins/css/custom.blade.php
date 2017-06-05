@foreach(glob("css/*.css") as $file)
	<link rel="stylesheet" href="{{asset($file)}}">
@endforeach