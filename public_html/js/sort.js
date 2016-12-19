$(document).ready(function(){

    $("#sort").change(function() {
        $.cookie('sort', $("#sort").val());
        location.reload();
    });
	
});