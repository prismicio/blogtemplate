<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="icon" type="image/png" href="/app/static/favicon.png">
    <link rel="stylesheet" href="/app/static/common.css">
    <link rel="stylesheet" href="/app/themes/default/main.css">
    <link rel="stylesheet" href="/app/themes/default/contact.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/app/static/jquery-1.11.2.min.js"></script>

    <?php include('prismic.php') ?>

    <?php include('theme/blog.php') ?>

</head>

<body class="contact-page">

  <div class="contact-header" style="<?= contact_image_url() ? 'background-image: url('.contact_image_url().')' : ''?>">

    <div class="wrapper">

      <h1><?= contact_title() ?></h1>

    </div>

  </div>

  <div id="right-panel">
      <?php get_sidebar() ?>
  </div>

  <div class="main">

  <a id="menu-hamburger" href="#right-panel"></a>

  <?php if (!is_mailgun_loaded()) { ?>

    <p class="lead">The contact module is not configured (API keys, domain and administrator email).</p>

  <?php } else { /* is configure */ ?>

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

      <button class="send button" disabled="disabled">Send</button>

      <span data-success="<?= contact_feedback_success() ?>" data-error="<?= contact_feedback_error() ?>" class="feedback"></span>

    </form>

  <?php } ?>

</div>

<script src="/app/static/mailgun-validator.min.js"></script>
<script src="/app/static/contact.js"></script>

<?php get_footer() ?>
