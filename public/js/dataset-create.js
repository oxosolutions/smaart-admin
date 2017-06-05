$(function(){

	/*$("#file-3").fileinput({
	    showUpload: false,
	    showCaption: false,
	    browseClass: "btn btn-primary btn-sm",
	    fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
	});*/
	$('.dataset-operation').change(function(){

		if($(this).val() == 'replace' || $(this).val() == 'append'){

			$('.dataset-view-hide').show(300);
		}else{

			$('.dataset-view-hide').hide(300);
		}
	});
}());