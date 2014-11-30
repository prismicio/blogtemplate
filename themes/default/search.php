<?php get_header() ?>

Query: <?php echo get_search_query() ?>

<?php foreach(posts() as $post) { ?>
    <div class="blog-post">
        <h2 class="blog-post-title"><a href="<?php document_url($post) ?>"><?php echo $post->getText("post.title") ?></a></h2>
        <p class="blog-post-meta"><?php echo get_date("post.date", "F, jS Y", $post) ?> by <?php author_link(get_author($post)) ?></p>

        <?php get_html("post.body", $post); ?>
    </div>
<?php } ?>

<?php get_footer() ?>
