<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header single" style="background-image: url(<?= post_thumbnail_url() ?>)">
    <div class="wrapper">
       <?php single_post_date() ?>
       <?php single_post_author() ?>
       <h1 class="blog-title"><?= single_post_title() ?></h1>
       <?php single_post_shortlede() ?>
    </div>
</div>

<div class="blog-main single container" <?= single_wio_attributes() ?>>

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
<script src="//static.cdn.prismic.io/prismic.min.js"></script>
<?php if(current_experiment_id()) { ?>
<script>prismic.startExperiment("<?=current_experiment_id()?>", cxApi);</script>
<?php }?>

<!-- Hamburger menu -->
<script src="/app/static/jquery.panelslider.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#menu-hamburger').panelslider({side: 'right', duration: 200 });
  });
</script>

<!-- Handle footer -->
<script type="text/javascript">
  $(document).ready(function() {
    var viewportHeight =  Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var latestKnownScrollY = 0;
    var previousScrollY = 0;
    var ticking = false;
    var $footer = $('.blog-footer');
    var lastTransition = Date.now();

    function update() {
      var previousY = previousScrollY;
      var y = latestKnownScrollY;
      var scrollDown = y > previousY;
      var maxScrollHeight = document.body.scrollHeight - viewportHeight;
      var percent = (y * 100) / maxScrollHeight;
      var timeSinceLastTransition = (Date.now() - lastTransition) / 1000;

      if(timeSinceLastTransition > 0.6) {
        if((percent >= 80 && scrollDown) || (percent >= 6 && !scrollDown)) {
          $footer.addClass('fade-in');
        } else {
          $footer.removeClass('fade-in');
        }
        lastTransition = Date.now();
      }

      ticking = false;
    }

    function requestTick() {
      if(!ticking) {
        requestAnimationFrame(update);
      }
      ticking = true;
    }

    function onScroll() {
      previousScrollY = latestKnownScrollY;
      latestKnownScrollY = window.scrollY;
      requestTick();
    }

    if(document.body.scrollHeight >= (viewportHeight * 2)) {
      window.addEventListener('scroll', onScroll, false);
      onScroll();
    } else {
      $footer.addClass('fade-in');
    }

  });
</script>
