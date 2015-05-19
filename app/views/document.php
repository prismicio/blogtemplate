<?php /*
       * This file is a generic template file for user-created document masks.
       * It is recommended to create a new specific file for each mask you create
       * in addition to the default ones.
       */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="icon" type="image/png" href="/app/static/favicon.png">
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/app/static/common.css">
    <link rel="stylesheet" href="/app/views/main.css">
    <link rel="stylesheet" href="/app/views/page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/app/static/jquery-1.11.2.min.js"></script>
    <script src="/app/views/slices/slices.js"></script>

    <?php include('prismic.php') ?>

    <?php include('theme/page.php') ?>

</head>

<body class="">

    <div id="right-panel">

        <?php get_sidebar() ?>

    </div>

    <div id="main" <?= the_wio_attributes(); ?>>

        <a id="menu-hamburger" href="#right-panel"></a>

        <div id="page-content">

            <?= single_post()->asHtml() ?>

        </div>

<?php get_footer() ?>
