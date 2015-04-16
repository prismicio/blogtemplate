<div class="row-centered-separate featured-preview">

  <div class="col-2 list-pane">

    <ul>

    <?php foreach($slice->getValue()->getArray() as $item) { ?>

      <?php $illustration = $item->get('illustration'); ?>

      <li data-illustration="<?= $illustration->getMain()->getUrl(); ?>">

      <?php if (!is_null($illustration) && !is_null($illustration->getView('icon'))): ?>

        <div class="illustration" style="background-image: url('<?= $illustration->getView('icon')->getUrl() ?>')"></div>

      <?php endif ?>

      <h3><?= $item->get('title')->asText(); ?></h3>

      <?= $item->get('summary')->asHtml(); ?>

    </li>

    <?php } ?>

    </ul>

  </div>

  <div class="col-2 preview-pane">

    <img src="" width="100%">

  </div>

</div>
