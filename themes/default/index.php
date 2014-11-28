<?php get_header() ?>

<?php foreach(posts() as $post) { ?>
    <h3><a href="<?php echo get_url_for($post) ?>"><?php echo $post->getText("post.title") ?></a></h3>

    <div>
        <?php echo $post->getHtml("post.body"); ?>
    </div>
<?php } ?>

<?php get_footer() ?>
