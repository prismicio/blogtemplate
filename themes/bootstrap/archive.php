<?php get_header() ?>

<h2>Archives for <?= archive_date() ?></h2>

<?php foreach(posts() as $post) { ?>
    <div class="blog-post">
        <h2 class="blog-post-title"><?= link_to_post($post) ?></h2>
        <p class="blog-post-meta">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= post_date_link($post) ?>,
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= author_link(author($post)) ?></p>

        <?= get_html("post.body", $post); ?>
    </div>
<?php } ?>

<?php get_footer() ?>
