var is_ready_name = false;
var is_ready_email = false;
var is_ready_text = false;

function check_ready_form() {
    $("#name").trigger("change");
    $("#email").trigger("change");
    $("#text").trigger("change");
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

$(document).ready(function(){
    
    $("#name").change(function(){
        if($("#name").val()) {
            is_ready_name = true;
            $("#name_help_block").html('');
        } else {
            is_ready_name = false;
            $("#name_help_block").html('Это поле обязательно');
        }
    });
    
    $("#email").change(function(){
        if(!$("#email").val()) {
            is_ready_email = false;
            $("#email_help_block").html('Это поле обязательно');
        } else if (!validateEmail($("#email").val())){
            is_ready_email = false;
            $("#email_help_block").html('Email адресс не корректный');
         
        } else {
            is_ready_email = true;
            $("#email_help_block").html('');
        }
    });
    
    $("#text").change(function(){
        if($("#text").val()) {
            is_ready_text = true;
            $("#text_help_block").html('');
        } else {
            is_ready_text = false;
            $("#text_help_block").html('Это поле обязательно');
        }
    });
    
    $("#submit_preview_box").click(function(){
        $("#add_review_form").submit();
    });
    
});