<?php get_header() ?>

<h2><?= author_name() ?></h2>

<?= author_image() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
<?php endif; ?>

<?php get_footer() ?>
