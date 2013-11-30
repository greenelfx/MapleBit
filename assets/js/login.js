$(function() {
    var action = '';
    var form_data = '';
    $('#login').click(function () {
         action = $("#loginform").attr("action");
         form_data = {
         username: $("#username").val(),
         password: $("#password").val(),
         is_ajax: '1'};  

        $.ajax({
            type: 'POST',
            url: action,
            data: form_data, 
            success: function(response) {
                 if(response == 'success') {
                   $("#loginform").slideUp('slow', function() {
                     $("#message").html('<script>location.reload();</script><div class=\"alert alert-success\">Logged in. Reloading...</div>');
                   });
                 } else { 
                    $('#message').hide().html("<br/><div class=\"alert alert-danger\">Wrong username or password</div>").fadeIn('fast');
                 }
				 console.log(response);
            }
         }); 
        return false;      
    });  
});