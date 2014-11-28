<html>

<head>
    <script src="//static.cdn.prismic.io/prismic.min.js"></script>
</head>

<h1><a href="/"><?php get_title() ?></a></h1>

<div class="search-box">
    <form method="get" action="search">
        <input type="text" name="q">
        <input type="submit">
    </form>
</div>

<ul>
<?php foreach(get_pages() as $page) { ?>
    <li><a href="<?php echo get_url_for($page) ?>"><?php echo $page->getText("page.title") ?></a></li>
<?php } ?>
</ul>

<body>

