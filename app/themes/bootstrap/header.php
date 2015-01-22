<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <!-- Theme: WP Bootstrap -->

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Blog Template -->
    <link rel="stylesheet" href="/app/static/common.css">

    <!-- Current theme -->
    <link rel="stylesheet" href="/app/themes/bootstrap/main.css">

    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="<?= is_front_page() ? 'front-page' : '' ?>">

    <nav class="blog-nav">
        <li class="blog-nav-item"><?= home_link('Home') ?></li>
        <?php foreach(get_pages() as $page) { ?>
            <?php if(count($page['children']) > 0) { ?>
                <li class="blog-nav-item dropdown">
                    <?= page_link($page) ?>
                    <ul class="dropdown-menu">
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
    </nav>

    <div class="navbar-form searchbar">
        <?php get_search_form() ?>
    </div>


