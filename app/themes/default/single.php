<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/app/static/common.css">
    <link rel="stylesheet" href="/app/themes/default/main.css">
    <link rel="stylesheet" href="/app/themes/default/blog.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/app/static/jquery-1.11.2.min.js"></script>

    <!-- disqus integration -->
    <?php if(disqus_forum()): ?>
    <script type="application/javascript">
      $(document).ready(function() {
          Disqium('.blog-main.single', {
              apiKey: 'E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F',
              forum: '<?= disqus_forum() ?>'
          });
      });
    </script>
    <?php endif ?>

    <link rel="stylesheet" href="/app/static/disqium/disqium.css" />
    <script src="/app/static/disqium/disqium.js"></script>

    <?php include('prismic.php') ?>

    <?php include('theme/blog.php') ?>

</head>

<body>

    <div id="right-panel">

        <?php get_sidebar(); ?>

    </div>

    <div id="main">

        <a id="menu-hamburger" href="#right-panel"></a>


<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header single" style="<?= post_thumbnail_url() ? 'background-image: url(' . post_thumbnail_url() . ')' : '' ?>">

    <div class="wrapper">

       <?php single_post_date(); ?>

       <?php single_post_author(); ?>

       <h1 class="blog-title"><?= single_post_title(); ?></h1>

       <?php single_post_shortlede(); ?>

    </div>
</div>

<div class="blog-main single container" <?= wio_attributes(); ?>>

    <?php the_content(); ?>

</div>

<?php endwhile; ?>

<footer class="blog-footer single">

    <?php single_prev_post_link() ?>

    <a class="menu" href="/blog">Home</a>

    <?php single_next_post_link() ?>

</footer>

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
      if(timeSinceLastTransition > 0.6 || y == 0 || y == window.pageYOffset) {
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
      latestKnownScrollY = window.pageYOffset;
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

</body>

</html>
