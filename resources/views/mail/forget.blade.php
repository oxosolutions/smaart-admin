@extends('mail.layout.email')
@section('content')

<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;"><b>Hello {{$user}}!</b></p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">You have request to reset your password.</p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Please click on the following link to change your password.</p>
<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;">
  <tbody>
	<tr>
	  <td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">
		<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;">
		  <tbody>
			<tr>
			  <td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;">
<a href="http://projects.fhts.ac.in/sdgindia/newPassword/{{$token}}" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">Change Password</a>
</td>
			</tr>
		  </tbody>
		</table>
	  </td>
	</tr>
  </tbody>
</table>

<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">or copy and paste the following link to your browser's address bar.
</p>
<p style="font-family:sans-serif;font-size:14px;font-weight:900;margin:0;Margin-bottom:15px;"><b>http://projects.fhts.ac.in/sdgindia/newPassword/{{$token}}</b></p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">If you do not understand this email. Do not do anything. You will be able to log in to your account with your existing password.</p>
<p style="font-family:sans-serif;font-size:14px;font-weight:900;margin:0;Margin-bottom:15px;">Thank you!</p>
@endsection