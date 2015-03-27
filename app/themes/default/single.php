<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header single" style="background-image: url(<?= post_thumbnail_url() ?>)">
    <div class="wrapper">
       <?php single_post_date() ?>
       <h1 class="blog-title"><?= single_post_title() ?></h1>
       <?php single_post_shortlede() ?>
       <?php single_post_author() ?>
    </div>
</div>

<div class="blog-main single container" <?= wio_post_attributes() ?>>

    <?php the_content() ?>

    <?php previous_post_link() ?>

    <?php next_post_link() ?>

</div>

<?php endwhile; ?>

<footer class="blog-footer single">
    <?php single_prev_post_link() ?>
    <?php single_next_post_link() ?>
</footer>

<!-- Prismic toolbar -->
<script src="//wroom.xxx/prismic.min.js"></script>

<!-- Hamburger menu -->
<script src="/app/static/jquery.panelslider.js"></script>
<script type="text/javascript">
  $('#menu-hamburger').panelslider({side: 'right', duration: 200 });
</script>
