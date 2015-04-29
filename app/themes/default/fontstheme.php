
<?php $theme = the_theme(); ?>

<?php if($theme) : ?>

<?php foreach($theme->getGroup('theme.fonts')->getArray() as $item) { ?>

  <link href='<?= $item->getLink("font")->getUrl() ?>' rel='stylesheet' type='text/css'>

<?php } ?>

<?php endif ?>
