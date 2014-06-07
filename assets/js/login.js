$(function () {
    var action = '';
    var form_data = '';
    $('#login').click(function () {
        action = $("#loginform").attr("action");
        form_data = {
            username: $("#username").val(),
            password: $("#password").val(),
            is_ajax: '1'
        };
        $('#login').keypress(function (e) {
            if (e.which == 13) { //Enter key pressed
                $('#login').click();
            }
        });
        $.ajax({
            type: 'POST',
            url: '?base=misc&script=login',
            data: form_data,
            success: function (response) {
                if (response == 'success') {
                    $("#loginform").slideUp('slow', function () {
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