<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

    <h2><?php the_title() ?></h2>
    <div id="page-content">
        <?php the_content() ?>
    </div>

<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>
