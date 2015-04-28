<?php get_header() ?>

  <div class="blog-header contact" style="background-image: url(<?= contact_image_url() ?>)">

    <div class="wrapper">

      <h1><?= contact_title() ?></h1>

    </div>

  </div>

<div class="main">
<?php if (!mailgun_loaded()) { ?>
    <p class="lead">The contact module is not configured (API keys, domain and administrator email).</p>
<?php } else { /* is configure */ ?>

    <p data-success="Your email has been successfuly send" data-failure="An error occured while sending the email" class="info success"><?= contact_description() ?></p>

    <form name="contact-form" action="#">
      <div class="form-group">
        <label for="sender">Your email</label>
        <input type="email" spellcheck="false" name="sender" />
      </div>

      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" spellcheck="false" name="subject" />
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message"></textarea>
      </div>

      <input type="hidden" name="token" value="<?=  mailgun_domain_sha1() ?>" />

      <input type="hidden" name="pubkey" value="<?= mailgun_pubkey() ?>" />

      <div class="actions">
        <button class="send button" disabled="disabled">Send</button>
      </div>
    </form>
<?php } ?>
</div>

<script src="/app/static/mailgun-validator.min.js"></script>
<script src="/app/static/contact.js"></script>

<?php get_footer() ?>
