<div class="slides">

    <a href="#" class="arrow-prev">&nbsp;</a>


<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

    <?php $illustration = $groupDoc->get('illustration')->getMain(); ?>

    <?php $readMore = $groupDoc->get('read-more'); ?>

    <div class="slide" style="background-image: url(<?= $illustration->getUrl(); ?>)">

        <div class="slide-container">

            <?= $groupDoc->get('title')->asHtml() ?>

            <?= $groupDoc->get('summary')->asHtml() ?>

            <?php if ($readMore) {

                $url = $linkResolver->resolve($readMore);

                $title = $readMore->get('title');

                echo '<a href="' . $url . '">' . $title . '</a>';
            } ?>

        </div>

    </div>

<?php } ?>

    <a href="#" class="arrow-next">&nbsp;</a>

</div>
