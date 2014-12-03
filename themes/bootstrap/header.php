<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/themes/bootstrap/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="blog-masthead">
    <div class="container">
        <nav class="blog-nav">
            <form class="navbar-form navbar-right" method="get" action="search">
                <input type="text" placeholder="Search..." name="q">
            </form>
            <ul>
                <li class="blog-nav-item"><?= home_link('Home') ?></li>
                <?php foreach(get_pages() as $page) { ?>
                    <?php if(count($page['children']) > 0) { ?>
                        <li class="blog-nav-item dropdown">
                            <a href="<?= $page['url'] ?>"><?= $page['label'] ?></a>
                            <ul class="dropdown-menu">
                                <?php foreach($page['children'] as $subpage) { ?>
                                    <a href="<?= $subpage['url'] ?>"><?= $subpage['label'] ?></a>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="blog-nav-item">
                            <a href="<?= $page['url'] ?>"><?= $page['label'] ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>

<div class="container">

    <div class="blog-header">
        <h1 class="blog-title"><?= site_title() ?></h1>
        <p class="lead blog-description"><?= site_description() ?></p>
    </div>

    <div class="row">

        <div class="col-sm-8 blog-main">

