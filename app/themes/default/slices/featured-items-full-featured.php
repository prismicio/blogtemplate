<div data-slicetype="featured-items" class="slice full-featured">

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>
    <div class="group-doc">
    <?php $illustration = $groupDoc->get('illustration')->getView('icon'); ?>
    <?php $readMore = $groupDoc->get('read-more'); ?>
            <div class='illustration' style='background-image: url("<?= $illustration->getUrl() ?>")'></div>
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