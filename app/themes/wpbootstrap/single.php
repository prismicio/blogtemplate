<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<h2 class="blog-post-title"><?= single_post_title() ?></h2>
<p class="blog-post-meta">
    <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= post_date_link() ?>,
    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= the_author_link() ?></p>

<?php the_content() ?>

<?php endwhile; ?>

<?php get_footer() ?>
