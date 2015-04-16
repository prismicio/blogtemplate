<div class="row-centered-separate featured-items">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration'); ?>

    <div class="col-3 center">

      <div class="illustration round-image" style="background-image: url(<?= $illustration->getView("icon")->getUrl(); ?>)"></div>

      <h3><?= $item->get('title')->asText(); ?></h3>

      <p><?= $item->get('summary')->asHtml(); ?></p>

      <?php $readMore = $item->get('read-more'); ?>

      <?php if ($readMore): ?>

      <?php $url = $linkResolver->resolve($readMore); ?>

      <a class="button" href="<?= $url ?>">READ MORE</a>

      <?php endif ?>

    </div>

  <?php } ?>

</div>
