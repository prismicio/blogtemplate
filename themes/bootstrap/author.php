<?php get_header() ?>

<h2><?= author_name() ?></h2>

<?= author_image() ?>

<?php foreach(posts() as $post) { ?>
<div class="blog-post">
    <h2 class="blog-post-title"><?= link_to_post($post) ?></h2>
    <p class="blog-post-meta">
        <?php if (post_date_link($post)) { ?>
        <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= post_date_link($post) ?>
        <?php } ?>
        <?php if (author_link(author($post))) { ?>
        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= author_link(author($post)) ?>
        <?php } ?>
        <?php if (category_link($post)) { ?>
        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?= category_link($post) ?>
        <?php } ?>
    </p>

    <?= get_html("post.body", $post); ?>
</div>
<?php } ?>

<?php get_footer() ?>
