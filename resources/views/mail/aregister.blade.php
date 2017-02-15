@extends('mail.layout.email')
@section('content')

<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;"><b>Hello Admin</b></p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">New User is registered at SDG India.</p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Following are the details of the user</p>
<ul>
	<li>Name: {{$userName}}</li>
	<li>Email: {{$userEmail}}</li>
	<li>Phone: {{$userPhone}}</li>
	<li>Department: {{$department}}</li>
	<li>Ministry: {{$ministry}}</li>
	<li>Designation: {{$designation}}</li>
</ul>

<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;">
  <tbody>
	<tr>
	  <td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">
		<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;">
		  <tbody>
			<tr>
			  <td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;">
<a href="http://projects.fhts.ac.in/sdgindia/admin/public/approve/email/{{$api_token}}" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">Approve User</a>
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
<p style="font-family:sans-serif;font-size:14px;font-weight:900;margin:0;Margin-bottom:15px;"><b>http://projects.fhts.ac.in/sdgindia/admin/public/approve/email/{{$api_token}}</b></p>
<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Alternative you can login to admin panel to manage users. </p>



@endsection