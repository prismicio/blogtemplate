<div class="row-centered-separate featuredItems">
  <?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>
  <?php $illustration = $groupDoc->get('illustration') ?>
  <?php $readMore = $groupDoc->get('read-more'); ?>
  <?php $url = "";/*$linkResolver->resolve($readMore);*/ ?>
  <?php $title = "";/*$readMore->get('title');*/ ?>

  <div class="col-3 center">
    <div class="featuredItems-roundImg" style='background-image: url("<?= $illustration->getView('icon')->getUrl() ?>")'></div>
    <h3><?= $groupDoc->get('title')->asHtml() ?></h3>
    <p><?= $groupDoc->get('summary')->asHtml() ?></p>
    <a href="#" class="button">Learn more</a>
  </div>
  <?php } ?>

</div>
