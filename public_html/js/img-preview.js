$(document).ready(function () {

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#add_img").change(function(){
        readURL(this);
        $("#add_img_reset").show();
        $("#img_preview").show();
    });
    
    $("#add_img_reset").click(function(){
      //  $("#add_img").val('');
        $("#add_img").type('');
        $("#add_img").type('file');
        $("#add_img_reset").hide();
        $("#img_preview").hide();
    });

});