<div class="row-separate alternated-items">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

<?php $odd = ($index % 2 != 0) ?>

<?php $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null; ?>

<div class="<?= $odd ? "alternate" : ""; ?>">

<div class="row-centered alternated-highlights">

  <?php if(!$odd): ?>

    <div class="col-2">

      <?php if ($illustration) { ?>
        <div class="illustration" style="background-image: url(<?= $illustration->getUrl(); ?>)"></div>
      <?php } ?>

    </div>

  <?php endif ?>

  <div class="col-2">

     <div class="text-wrapper">

       <h2><?= $item->get('title') ? $item->get('title')->asText() : 'No title'; ?></h2>

       <?php if($item->get('summary')) { ?>
         <?= $item->get('summary')->asHtml(); ?>
       <?php } ?>

       <?php $readMore = $item->get('read-more'); ?>

       <?php if ($readMore): ?>

       <?php $url = $linkResolver->resolve($readMore); ?>

       <a class="button" href="<?= $url ?>">READ MORE</a>

       <?php endif ?>

     </div>

  </div>

  <?php if($odd): ?>

    <div class="col-2">

      <div class="illustration" style="background-image: url(<?= $illustration->getUrl(); ?>)"></div>

    </div>

  <?php endif ?>

</div>

</div>

<?php $index++; ?>

<?php } ?>

</div>
