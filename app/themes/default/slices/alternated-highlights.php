<div class="row-separate">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

<?php $odd = ($index % 2 != 0) ?>

<div class="<?= $odd ? "alternate" : "" ?>">

<div class="row-centered alternated-highlights">

  <?php $illustrationUrl = $groupDoc->get('illustration')->getMain()->getUrl(); ?>

  <?php if(!$odd): ?>
    <div class="col-2 center">

      <img src="<?= $illustrationUrl ?>" />

    </div>
  <?php endif ?>

  <div class="col-2">

     <h2><?= $groupDoc->get('title')->asText(); ?></h2>

     <?= $groupDoc->get('summary')->asHtml(); ?>

     <?php $readMore = $groupDoc->get('read-more'); ?>

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
