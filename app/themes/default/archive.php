<?php get_header() ?>
    <div class="blog-header home">
    <div class="container">
        <h1 class="blog-title">Archives for <?= archive_date() ?></h1>
    </div>
    </div>

<div class="container blog-main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

</div>

<?php get_footer() ?>
