<?php get_header() ?>

<div class="blog-header category" style="<?php if (single_cat_illustration_url()) { ?>background-image: url(<?= single_cat_illustration_url() ?>)<?php } ?>">

    <div class="wrapper">

        <h1 class="blog-title"><?= single_cat_title() ?></h1>

    </div>

</div>

<div class="blog-main category container">

<?= category_description() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php get_template_part('content'); ?>

<?php endwhile; else : ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

</div>

<?php get_footer() ?>
