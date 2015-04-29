<?php $theme = the_theme(); ?>

<?php if($theme) : ?>

<?php include('fonts.php') ?>

<style>

<?php

  $textColor = $theme->getColor('theme.errorpage-text-color');

  $titleColor = $theme->getColor('theme.errorpage-title-color');

?>

.error-page h1 {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.error-page p {

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>;
}

</style>

<?php endif ?>
