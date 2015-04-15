<div class="row-centered-separate featured-mini">

  <?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

  <div class="col-3">

    <?php $illustration = $groupDoc->get('illustration') ?>

    <div class="illustration featuredItems-roundImg" style="background-image: url('<?= $illustration->getView('icon')->getUrl() ?>')"></div>

    <?= $groupDoc->get('title')->asHtml() ?>

    <?= $groupDoc->get('summary')->asHtml() ?>

  </div>

  <?php } ?>

</div>
