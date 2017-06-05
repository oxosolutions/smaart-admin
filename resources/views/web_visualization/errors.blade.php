
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div>
	@foreach($errors as $error)
	<div class="aione-errors" >
		<i class="fa fa-exclamation"></i><span>Error</span> {{$error}} 	
	</div>
	@endforeach
</div>
<style type="text/css">
	.aione-errors{
		padding: 20px;border:2px dashed #c8c8c8;color: #c8c8c8;width: 50%;margin: 15px auto;background-color: white;font-size: 28px;text-align: center;
	}
	.aione-errors span{
		font-weight: 700;padding: 0px 20px
	}
</style>
