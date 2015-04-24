<div class="slides row-separate">

<?php if(count($slice->getValue()->getArray()) > 1): ?>

    <a href="#" class="arrow-prev">&nbsp;</a>

<?php endif ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null; ?>

    <?php $readMore = $item->get('read-more'); ?>

    <?php $readMoreLabel = $item->get('read-more-label'); ?>

    <div class="slide" <?= $illustration ? 'style="background-image: url('.$illustration->getUrl().')"' : '' ?> >

        <div class="slide-container">

            <?= $item->get('title') ? $item->get('title')->asHtml() : ''; ?>

            <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

            <?php if ($readMore): ?>

            <?php $url = $linkResolver->resolve($readMore); ?>

            <a class="button <?= is_home() ? 'home' : '';?>" href="<?= $url ?>">

              <?= $readMoreLabel ? $readMoreLabel->asText() : 'learn more' ?>

            </a>

            <?php endif ?>

        </div>

    </div>

<?php } ?>

<?php if(count($slice->getValue()->getArray()) > 1): ?>

    <a href="#" class="arrow-next">&nbsp;</a>

<?php endif ?>

</div>
