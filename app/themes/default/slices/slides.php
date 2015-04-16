<div class="slides row-separate">

    <a href="#" class="arrow-prev">&nbsp;</a>


<?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration')->getMain(); ?>

    <?php $readMore = $item->get('read-more'); ?>

    <div class="slide" style="background-image: url(<?= $illustration->getUrl(); ?>)">

        <div class="slide-container">

            <h1><?= $item->get('title')->asText(); ?></h1>

            <?= $item->get('summary')->asHtml(); ?>

            <?php if ($readMore): ?>

            <?php $url = $linkResolver->resolve($readMore); ?>

            <a class="button" href="<?= $url ?>">READ MORE</a>

            <?php endif ?>

        </div>

    </div>

<?php } ?>

    <a href="#" class="arrow-next">&nbsp;</a>

</div>
