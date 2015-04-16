<div class="row-separate">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

<?php $odd = ($index % 2 != 0) ?>

<div class="<?= $odd ? "alternate" : "" ?>">

<div class="row alternated-highlights-full">

  <?php $illustrationUrl = $groupDoc->get('illustration')->getMain()->getUrl(); ?>

  <?php if(!$odd): ?>
    <div class="col-illustration">

      <img src="<?= $illustrationUrl ?>" />

    </div>
  <?php endif ?>

  <div class="col-text">
     <h2><?= $groupDoc->get('title')->asText(); ?></h2>

     <?= $groupDoc->get('summary')->asHtml(); ?>
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
