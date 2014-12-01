<?php get_header() ?>

Category: <?= get_category() ?>

<?php foreach(posts() as $post) { ?>
    <div class="blog-post">
        <h2 class="blog-post-title"><?= link_to_post($post) ?></h2>
        <p class="blog-post-meta">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= post_date_link($post) ?>
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= author_link(author($post)) ?>
            <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?= category_link($post) ?>
        </p>

        <?= get_html("post.body", $post); ?>
    </div>
<?php } ?>

<?php get_footer() ?>
