<div class="row-separate">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

<?php $odd = ($index % 2 != 0) ?>

<div class="<?= $odd ? "alternate" : ""; ?>">

<div class="row alternated-highlights-full">

  <?php $illustrationUrl = $item->get('illustration')->getMain()->getUrl(); ?>

  <?php if(!$odd): ?>

    <div class="col-illustration">

      <img src="<?= $illustrationUrl ?>" />

    </div>

  <?php endif ?>

  <div class="col-text">
     <h2><?= $item->get('title')->asText(); ?></h2>

     <?= $item->get('summary')->asHtml(); ?>
  </div>

  <?php if($odd): ?>

    <div class="col-illustration">

      <img src="<?= $illustrationUrl ?>" />

    </div>

  <?php endif ?>

</div>

</div>

<?php $index++; ?>

<?php } ?>

</div>
