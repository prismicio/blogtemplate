<?php get_header() ?>

<div class="container blog-main">

<?php while ( have_posts() ) : the_post(); ?>

    <h2><?php the_title() ?></h2>
    <div id="page-content">
        <?php the_content() ?>
    </div>

<?php endwhile; // end of the loop. ?>

</div>

<?php get_footer() ?>
