<div class="slides">

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

    <?php $illustration = $groupDoc->get('illustration')->getMain(); ?>
    <?php $readMore = $groupDoc->get('read-more'); ?>
    <div class="slide" style="background-image: url(<?= $illustration->getUrl(); ?>)">
        <a href="#" class="arrow-prev">&nbsp;</a>
        <div class="slide-container">
            <?= $groupDoc->get('title')->asHtml() ?>
            <?= $groupDoc->get('summary')->asHtml() ?>
            <?php if ($readMore) {
                $url = $linkResolver->resolve($readMore);
                $title = $readMore->get('title');
                echo '<a href="' . $url . '">' . $title . '</a>';
            } ?>
        </div>
        <a href="#" class="arrow-next">&nbsp;</a>
    </div>

<?php } ?>

</div>
