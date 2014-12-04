
<div class="blog-post">
    <h2 class="blog-post-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <p class="blog-post-meta">
        <span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= the_date_link() ?>
        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= author_link(author(Loop::current_post())) ?>
        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?php the_category() ?>
    </p>

    <?php the_excerpt() ?>
</div>
