<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header" style="background-image: url(<?= post_thumbnail_url() ?>)">
    <div class="container">
        <h1 class="blog-title"><?= single_post_title() ?></h1>
    </div>
</div>

<div class="container">
    <div class="blog-main" <?= wio_post_attributes() ?>>


<?php
$post_year  = get_the_time('Y');
$post_month = get_the_time('m');
$post_day   = get_the_time('d');
?>

<p class="blog-post-meta">
    <span class="fa fa-calendar" aria-hidden="true"></span> <?= get_day_link($post_year, $post_month, $post_day) ?>,
    <span class="fa fa-calendar" aria-hidden="true"></span> <?= the_author_link() ?></p>

    <?php the_content() ?>

    <?php previous_post_link() ?>

    <?php next_post_link() ?>

<?php endwhile; ?>

        </div>
</div>

<?php get_footer() ?>
