<div class="row-centered-separate featured-items-simple">

    <ul>

    <?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

      <?php $illustration = $groupDoc->get('illustration') ?>

      <li class="col-2" data-illustration="<?= $illustration->getMain()->getUrl() ?>">

      <?php if (!is_null($illustration) && !is_null($illustration->getView('icon'))) { ?>

        <div class="illustration" style="background-image: url('<?= $illustration->getView('icon')->getUrl() ?>')"></div>

      <?php } ?>

      <?= $groupDoc->get('title')->asHtml() ?>

      <?= $groupDoc->get('summary')->asHtml() ?>

    </li>

    <?php } ?>

    </ul>

</div>
