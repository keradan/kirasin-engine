$(document).ready(function(){
    $("#show_preview_box").click(function(){
        check_ready_form();
        if (!is_ready_name || !is_ready_email || !is_ready_text) return false;
        
        var date = new Date();
        
        $(".add-review .preview-box").show();
        $(".add-review form").hide();
        
        $("#preview_box_name p").html($("#name").val());
        $("#preview_box_email p").html($("#email").val());
        $("#preview_box_content p").html($("#text").val());
        $("#preview_box_date p").html(date.getHours() + ':' + date.getMinutes() + ' ' + date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear());
        
        if($("#add_img").val()) {
            $("#preview_box_content img").attr('src', $('#img_preview').attr('src'));
            $("#preview_box_content img").show();
        } else $("#preview_box_content img").hide();
        $("#preview_box").show();
    });
});