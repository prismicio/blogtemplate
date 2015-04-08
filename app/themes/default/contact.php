<?php get_header() ?>
    <div class="blog-header contact">
    <div class="container">
        <h1 class="blog-title">Contact</h1>
    </div>
    </div>

<div class="container blog-main">
<?php if (!mailgun_loaded()) { ?>
    <p class="lead">The contact module is not configured (API keys, domain and administrator email).</p>
<?php } else { /* is configure */ ?>
    <p class="lead">You can submit your message using the form bellow.</p>
    
    <form name="contact_form" action="#">
      <div class="form-group">
        <label for="sender" class="control-label">Your email</label>
        <input type="email" class="form-control" id="sender" name="sender" />
      </div>
      
      <div class="form-group">
        <label for="subject" class="control-label">Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" />
      </div>
      
      <div class="form-group">
        <label for="message" class="control-label">Message</label>
        <textarea class="form-control" id="message" name="message"></textarea>
      </div>

      <button id="send" class="btn" disabled="disabled"
        data-token="<?php echo mailgun_domain_sha1() ?>"
        data-pubkey="<?php echo mailgun_pubkey() ?>">Send</button>
    </form>

    <p id="contact-alert" class="hidden"></p>
<?php } ?>
</div>

<!-- Hamburger menu -->
<script src="/app/static/jquery.panelslider.js"></script>
<script type="text/javascript">
    $('#menu-hamburger').panelslider({side: 'right', duration: 200 });
</script>

<script src="/app/static/mailgun-validator.min.js"></script>
<script src="/app/static/contact.js"></script>

</body>
</html>