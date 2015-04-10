<div class="slice slice-featured <?= $slice->getLabel() ?>">

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>
    <div class="group-doc">
    <?php $illustration = $groupDoc->get('illustration') ?>
    <?php $readMore = $groupDoc->get('read-more'); ?>
        <?php if (!is_null($illustration) && !is_null($illustration->getView('icon'))) { ?>
            <div class='illustration' style='background-image: url("<?= $illustration->getView('icon')->getUrl() ?>")'></div>
        <?php } ?>
        <?= $groupDoc->get('title')->asHtml() ?>
        <?= $groupDoc->get('summary')->asHtml() ?>
        <?php if ($readMore) {
            $url = $linkResolver->resolve($readMore);
            $title = $readMore->get('title');
            echo '<a href="' . $url . '">' . $title . '</a>';
        } ?>
    </div>
<?php } ?>

</div>