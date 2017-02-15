$(function(){

	$("#file-3").fileinput({
	    showUpload: false,
	    showCaption: false,
	    browseClass: "btn btn-primary btn-sm",
	    fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
	});
	$(".select2").select2({
		tags: true,
		maximumSelectionLength: 1
	});

	$(".select2-department").select2();
}());