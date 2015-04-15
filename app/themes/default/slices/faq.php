<div class="row-centered-separate faq">

  <?php $index = 0; ?>

  <?php foreach($slice->getValue()->getArray() as $groupDoc) { ?>

    <?php if($index % 3 == 0): ?>
      <?= $index != 0 ? "</div>" : "" ?>
      <div class="col-2">
    <?php endif ?>

    <h3><?= $groupDoc->get('question')->asText(); ?></h3>

    <?= $groupDoc->get('answer')->asHtml(); ?>

    <?php $index++; ?>

  <?php } ?>

</div>
