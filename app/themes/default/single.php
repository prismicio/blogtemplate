<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header" style="background-image: url(<?= post_thumbnail_url() ?>)">
    <h1 class="blog-title"><?= single_post_title() ?></h1>
</div>

<div class="blog-main container" <?= wio_post_attributes() ?>>

<?php
$post_year  = get_the_time('Y');
$post_month = get_the_time('m');
$post_day   = get_the_time('d');
?>

    <?php the_content() ?>

    <?php previous_post_link() ?>

    <?php next_post_link() ?>

</div>

<?php endwhile; ?>

<?php get_footer() ?>
