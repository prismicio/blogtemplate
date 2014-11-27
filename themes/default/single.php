<?php get_header() ?>

<h2><?php echo current_document()->getText("post.title") ?></h2>

<div class="post-body">
    <?php echo html_for("post.body") ?>
</div>

<?php get_footer() ?>
