<?php get_header() ?>

<h2 class="blog-post-title"><?= get_text("post.title") ?></h2>
<p class="blog-post-meta"><?= get_date("post.date", "F, jS Y") ?> by <?= author_link() ?></p>

<?= get_html("post.body") ?>

<?php get_footer() ?>
