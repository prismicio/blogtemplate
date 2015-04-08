// require('mailgun-validator.min.js')
$(document).ready(function(){
    var submit = $('#send'),
        pubKey = submit.data('pubkey'),
        token = submit.data('token'),
        sender = $('#sender'),
        subject = $('#subject'),
        message = $('#message'),
        notEmpty = function(){
            var input = $(this);
            
            if ($.trim(input.val()) == "") {
                input.addClass("has-error");
                submit.attr("disabled", "disabled")
            } else {
                input.removeClass("has-error");
                validate()
            }
        },
    validate = function(){
        if ($(".has-error").length > 0) return;

        if ($.trim(sender.val()) == "" ||
            $.trim(subject.val()) == "" ||
            $.trim(message.val()) == "") { // not changed empty val
            return
        }
        
        submit.removeAttr("disabled")
    },
    alert = $("#contact-alert");

    subject.on('change keyup', notEmpty);
    message.on('change keyup', notEmpty);

    sender.on('change keyup', function(){
        var email = sender.val();
        
        if (email.length < 7) { // quick client validation
            sender.addClass("has-error");
            submit.attr("disabled", "disabled");
            return
        }

        run_validator(email, {
            api_key: pubKey,
            success: function(res){
                if (res.is_valid) {
                    sender.removeClass("has-error");
                    validate()
                } else {
                    sender.addClass("has-error");
                    submit.attr("disabled", "disabled")
                }
            }
        })
    });

    submit.click(function(){
        submit.attr("disabled", "disabled");
        
        $.ajax({
            type: "POST",
            url: "/contact",
            dataType: "json",
            data: {
                'token': token,
                'sender': sender.val(),
                'subject': subject.val(),
                'message': message.val()
            },
            success: function(res){
                if (res['success']) {
                    alert.text(res.success).attr("class", "alert-success")
                } else if (res['error']) {
                    alert.text(res.error).attr("class", "alert-error")
                }
                    
                submit.removeAttr("disabled")
            }
        });
        
        return false
    });
});