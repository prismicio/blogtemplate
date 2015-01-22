<?php get_header() ?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 blog-main">

<?php while ( have_posts() ) : the_post(); ?>

<?php
$post_year  = get_the_time('Y');
$post_month = get_the_time('m');
$post_day   = get_the_time('d');
?>

<h2 class="blog-post-title"><?= single_post_title() ?></h2>
<p class="blog-post-meta">
    <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= get_day_link($post_year, $post_month, $post_day) ?>,
    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= the_author_link() ?></p>

    <?php the_content() ?>

    <?php previous_post_link() ?>

    <?php next_post_link() ?>

<?php endwhile; ?>

        </div>
    </div>
</div>

<?php get_footer() ?>
