<? get_header() ?>

<ul>
<?php foreach(get_pages() as $page) { ?>
    <li><a href="<?php echo get_url_for($page) ?>"><?php echo $page->getText("page.title") ?></a></li>
<?php } ?>
</ul>

<? get_footer() ?>
