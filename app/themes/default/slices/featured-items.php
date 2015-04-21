<div class="row-centered-separate featured-items">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration'); ?>

    <div class="col-3 center">

      <?php if ($illustration) { ?>

        <div class="illustration round-image" style="background-image: url(<?= $illustration->getView("icon")->getUrl(); ?>)"></div>

      <?php } ?>

      <?= $item->get('title') ? $item->get('title')->asHtml() : ''; ?>

      <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

      <?php $readMore = $item->get('read-more'); ?>

      <?php if ($readMore): ?>

      <?php $url = $linkResolver->resolve($readMore); ?>

      <a class="button" href="<?= $url ?>">READ MORE</a>

      <?php endif ?>

    </div>

  <?php } ?>

</div>
