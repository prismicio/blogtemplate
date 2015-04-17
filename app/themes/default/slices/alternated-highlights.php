<div class="row-separate alternated-items">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

<?php $odd = ($index % 2 != 0) ?>

<div class="<?= $odd ? "alternate" : ""; ?>">

<div class="row-centered alternated-highlights">

  <?php $illustrationUrl = $item->get('illustration')->getMain()->getUrl(); ?>

  <?php if(!$odd): ?>

    <div class="col-2 center">

      <img src="<?= $illustrationUrl ?>" />

    </div>

  <?php endif ?>

  <div class="col-2">

     <h2><?= $item->get('title')->asText(); ?></h2>

     <?= $item->get('summary')->asHtml(); ?>

     <?php $readMore = $item->get('read-more'); ?>

     <?php if ($readMore): ?>

     <?php $url = $linkResolver->resolve($readMore); ?>

     <a class="button" href="<?= $url ?>">READ MORE</a>

     <?php endif ?>

  </div>

  <?php if($odd): ?>

    <div class="col-2 center">

      <img src="<?= $illustrationUrl ?>" />

    </div>

  <?php endif ?>

</div>

</div>

<?php $index++; ?>

<?php } ?>

</div>
