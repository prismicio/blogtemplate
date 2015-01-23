<?php get_header() ?>
    <?php if (is_front_page()) { ?>
    <div class="blog-header home">
    <div class="container">
        <h1 class="blog-title"><?= site_title() ?></h1>
        <p class="lead blog-description"><?= site_description() ?></p>
    </div>
    </div>
    <?php } ?>


<div class="container blog-main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

</div>

<?php get_footer() ?>
