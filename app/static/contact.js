// require('mailgun-validator.min.js')
$(function(){

  var $form = $('form[name=contact-form]'),
      $submit = $form.find('button.send'),
      pubKey = $form.find('[name=pubkey]').val(),
      token = $form.find('[name=token]').val(),
      $sender = $form.find('[name=sender]'),
      $subject = $form.find('[name=subject]'),
      $message = $form.find('[name=message]'),
      $feedback = $form.find('.feedback'),

      notEmpty = function($input) {
        if ($.trim($input.val()) == "") {
          $input.addClass("has-error");
          $submit.attr("disabled", "disabled");
        } else {
          $input.removeClass("has-error");
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
      },

      onChange = function() {

        $feedback.text('');

        var $input = $(this);
        notEmpty($input);
      };

  $subject.on('change keyup', onChange);

  $message.on('change keyup', onChange);

  $sender.on('change keyup', function() {

    $feedback.text('');

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
      $feedback.addClass('success');
      $feedback.text($feedback.data('success'));
    }).fail(function(res) {
      $feedback.addClass('error');
      $feedback.text($feedback.data('error'));
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
