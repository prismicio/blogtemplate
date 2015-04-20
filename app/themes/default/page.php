<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/app/static/common.css">
    <link rel="stylesheet" href="/app/themes/default/main.css">
    <link rel="stylesheet" href="/app/themes/default/page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/app/static/jquery-1.11.2.min.js"></script>
    <script src="/app/themes/default/slices/slices.js"></script>

    <?php include('prismic.php') ?>

</head>

<body class="<?= is_front_page() ? 'front-page' : '' ?>">

    <div id="right-panel">
        <?php get_sidebar() ?>
    </div>

    <div id="main">
        <a id="menu-hamburger" href="#right-panel"></a>

        <?php while ( have_posts() ) : the_post(); ?>

        <div id="page-content">
            <?php page_content() ?>
        </div>


<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>
