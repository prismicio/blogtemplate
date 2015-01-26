<?php get_header() ?>

<div class="blog-header">
    <div class="container">
        <h1 class="blog-title">tag: <?= single_tag_title() ?></h1>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12 blog-main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
    <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

        </div>
    </div>
</div>

<?php get_footer() ?>
