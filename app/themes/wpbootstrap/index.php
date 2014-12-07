<?php get_header() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

<?php get_footer() ?>
