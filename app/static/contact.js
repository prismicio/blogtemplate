// require('mailgun-validator.min.js')
$(function(){

  var $form = $('form[name=contact-form]'),
      $submit = $form.find('button.send'),
      pubKey = $form.find('[name=pubkey]').val(),
      token = $form.find('[name=token]').val(),
      $sender = $form.find('[name=sender]'),
      $subject = $form.find('[name=subject]'),
      $message = $form.find('[name=message]'),

      notEmpty = function() {
        var input = $(this);
        if ($.trim(input.val()) == "") {
          input.addClass("has-error");
          $submit.attr("disabled", "disabled");
        } else {
          input.removeClass("has-error");
          validate();
        }
      },

      validate = function() {
        if ($(".has-error").length > 0) return;

        if ($.trim($sender.val()) == "" ||
            $.trim($subject.val()) == "" ||
            $.trim($message.val()) == "") { // not changed empty val
          return;
        }
        $submit.removeAttr("disabled");
      };

  $subject.on('change', notEmpty);

  $message.on('change', notEmpty);

  $sender.on('change', function() {
    var email = $sender.val();

    if (email.length < 7) { // quick client validation
      $sender.addClass("has-error");
      $submit.attr("disabled", "disabled");
      return;
    }

    run_validator(email, {
      api_key: pubKey,
      success: function(res){
        if (res.is_valid) {
          $sender.removeClass("has-error");
          validate();
        } else {
          $sender.addClass("has-error");
          $submit.attr("disabled", "disabled");
        }
      }
    });
  });

  $submit.click(function() {

    $submit.attr("disabled", "disabled");

    $submit.text('sending...');

    $.ajax({
      type: "POST",
      url: "/contact",
      dataType: "json",
      data: {
        'token': token,
        'sender': $sender.val(),
        'subject': $subject.val(),
        'message': $message.val()
      }
    }).then(function(res) {
      //$alert.text(res.success).attr("class", "alert-success");
    }).fail(function(res) {
      //$alert.text(res.error).attr("class", "alert-error");
    }).always(function() {
      $subject.val('');
      $message.val('');
      $sender.val('');
      $submit.text('send');
      $submit.removeAttr("disabled");
    });

    return false;
  });
});
