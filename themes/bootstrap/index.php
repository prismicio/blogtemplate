<?php get_header() ?>

<?php foreach(posts() as $post) { ?>
<div class="blog-post">
    <h2 class="blog-post-title"><?= link_to_post($post) ?></h2>
    <p class="blog-post-meta"><?= get_date("post.date", "F, jS Y", $post) ?> by <?= author_link(author($post)) ?></p>

    <?= get_html("post.body", $post); ?>
</div>
<?php } ?>

<?php get_footer() ?>
