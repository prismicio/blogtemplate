<?php get_header() ?>

<div class="blog-header search">

  <div class="wrapper">

    <h1>Query: <?= get_search_query() ?></h1>

  </div>
</div>

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
