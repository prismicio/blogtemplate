<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= post_title() ?> <?= site_title() ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/themes/default/main.css">
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
        <?= home_link('Home', array('class' => 'blog-nav-item')) ?>
        <?php foreach(get_pages() as $page) { ?>
            <?= page_link($page, array('class' => 'blog-nav-item')) ?>
        <?php } ?>
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

