<div class="slides">

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

    <?php $illustration = $groupDoc->get('illustration')->getMain(); ?>
    <?php $readMore = $groupDoc->get('read-more'); ?>
    <div class="slide" style="background-image: url(<?= $illustration->getUrl(); ?>)">
        <?= $groupDoc->get('title')->asHtml() ?>
        <?= $groupDoc->get('summary')->asHtml() ?>
        <?php if ($readMore) {
            $url = $linkResolver->resolve($readMore);
            $title = $readMore->get('title');
            echo '<a href="' . $url . '">' . $title . '</a>';
        } ?>
        <div class="slide-arrows">
            <a href="#" class="arrow-prev">Previous</a>
            <a href="#" class="arrow-next">Next</a>
        </div>
    </div>

<?php } ?>

</div>
