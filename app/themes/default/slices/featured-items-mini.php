<div class="row-centered-separate featuredMini">

  <?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

  <div class="col-3">

    <?php $illustration = $groupDoc->get('illustration') ?>

    <div class="illustration" style="background-image: url('<?= $illustration->getView('icon')->getUrl() ?>')"></div>

    <?= $groupDoc->get('title')->asHtml() ?>

    <?= $groupDoc->get('summary')->asHtml() ?>

    <?php $readMore = $groupDoc->get('read-more'); ?>

    <?php if ($readMore) {

      $url = $linkResolver->resolve($readMore);

      $title = $readMore->get('title');

      echo '<a href="' . $url . '">' . $title . '</a>';

    } ?>

  </div>

  <?php } ?>

</div>
