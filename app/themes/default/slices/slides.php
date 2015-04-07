<div class="slides">

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

    <?php $illustration = $groupDoc->get('illustration')->getMain(); ?>
    <div class="slide" style="background-image: url(<?= $illustration->getUrl(); ?>)">
        <?= $groupDoc->get('title')->asHtml() ?>
        <?= $groupDoc->get('summary')->asHtml() ?>
    </div>

<?php } ?>

</div>
