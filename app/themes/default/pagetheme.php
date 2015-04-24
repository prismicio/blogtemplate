
<style>

<?php

  $theme = the_theme();

  $textColor = $theme->getColor('theme.page-text-color');

  $titleColor = $theme->getColor('theme.page-title-color');

  $textFont = $theme->getText('theme.page-text-font');

  $titleFont = $theme->getText('theme.page-title-font');

?>

body {

  <?= $textFont ? 'font-family:'.$textFont : ''; ?>

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>
}

h1, h2, h3 {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
}

h2, h3 {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>
}

.button {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>
  text-transform: uppercase;
}

.button:hover {
  <?= $titleColor ? 'background:'.$titleColor->asText() : ''; ?>
  <?= $titleColor ? 'border-color:'.$titleColor->asText() : ''; ?>
  color: #fff;
}

.round-image {
  background-color: #F3F3F9;
}

.featured-preview {
  background: #F3F3F9;
}

.featured-preview li {
  border-top: 1px solid #F3F3F9;
}

.featured-preview li:hover {
  background: #FFF;
}

.alternated-items > div {
  background: #F3F3F9;
}

.alternated-items .alternate {
  background: #E9E9F5;
}

.featured-items-simple .illustration {
  background-color: #F3F3F9;
}

.faq h3 {
  border-bottom: 1px solid #D6D6D6;
}

.slides .slide h2 {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
}

.slides, .slides h2, .slide-arrows a {
  color: #FFF;
}

.slides .slide {
  background-color: #201145;
}

.slides .button {
  color: #FFF;
}

.slides p {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
}

.slides .button:not(.home):hover {
  background: #FFF;
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>
}

.button.home {
  background: #5154AB;
  color: #FFF;
  box-shadow: 0px 3px #412F8D;
}

</style>
