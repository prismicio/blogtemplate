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

<div id="commentable-area" class="blog-main single container commentable-container" <?= single_wio_attributes() ?>>

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
<script src="//www.google-analytics.com/cx/api.js?experiment=<?=current_experiment_id()?>"></script>
<script src="//wroom.xxx/prismic.min.js"></script>
<?php if(current_experiment_id()) { ?>
<script>prismic.startExperiment("<?=current_experiment_id()?>", cxApi);</script>
<?php }?>

<!-- Hamburger menu -->
<script src="/app/static/jquery.panelslider.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#menu-hamburger').panelslider({side: 'right', duration: 200 });

    (function() {
      var SideComments = require('side-comments');
      var author = {
        id: 1,
        avatarUrl: "http://aroc.github.io/side-comments-demo/public/images/user.png",
        name: "You"
      };
      sideComments = new SideComments('#commentable-area', author);
      sideComments.on('commentPosted', function( comment ) {
        comment.id = parseInt(Math.random() * (100000 - 1) + 1);
        sideComments.insertComment(comment);
      });
      sideComments.on('commentDeleted', function( comment ) {
        sideComments.removeComment(comment.sectionId, comment.id);
      });
    })();
  });
</script>
