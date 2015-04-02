<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="blog-header">
    <div class="container">
        <h1 class="blog-title"><?= the_title() ?></h1>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12 blog-main">

            <div id="page-content">
                <?php page_content() ?>
            </div>


        </div>
    </div>
</div>
<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>
