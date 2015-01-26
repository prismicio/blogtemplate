<?php get_header() ?>

<div class="container blog-main">

<h2>Archives for <?= archive_date() ?></h2>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

</div>

<?php get_footer() ?>
