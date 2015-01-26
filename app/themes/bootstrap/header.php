<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/app/static/common.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/app/themes/bootstrap/main.css">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/app/static/modernizr.js"></script>
    <script src="/app/static/jquery-1.11.2.min.js"></script>
</head>

<body class="<?= is_front_page() ? 'front-page' : '' ?>">

    <div id="right-panel">
        <ul>
            <li>
                <?php get_search_form() ?>
            </li>
            <li class="blog-nav-item"><?= home_link('Home') ?></li>
            <?php foreach(get_pages() as $page) { ?>
                <?php if(count($page['children']) > 0) { ?>
                    <li class="blog-nav-item">
                        <?= page_link($page) ?>
                        <ul>
                            <?php foreach($page['children'] as $subpage) { ?>
                                <?= page_link($subpage) ?>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="blog-nav-item">
                        <?= page_link($page) ?>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>

<div id="main">
    <a id="menu-hamburger" href="#right-panel">
        <i class="fa fa-bars"></i>
    </a>

