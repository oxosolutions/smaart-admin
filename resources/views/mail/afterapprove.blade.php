@extends('mail.layout.email')
@section('content')

<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;"><b>Hello {{$user}}!</b></p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">New user account is successfully approved by administrator.</p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Following are the details of your account</p>

<ul>
	<li>URL: http://projects.fhts.ac.in/sdgindia/</li>
	
</ul>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">you can now log in to you account.</p>


@endsection