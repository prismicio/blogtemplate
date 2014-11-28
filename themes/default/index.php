<?php get_header() ?>

<?php foreach(posts() as $post) { ?>
    <h3><a href="<?php document_url($post) ?>"><?php echo $post->getText("post.title") ?></a></h3>
    <h4>By <?php author_link(get_author($post)) ?> on <?php echo get_date("post.date", "F, jS Y", $post) ?></h4>

    <div>
        <?php get_html("post.body", $post); ?>
    </div>
<?php } ?>

<?php get_footer() ?>
