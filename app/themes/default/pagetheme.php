
<style>

<?php

  $theme = the_theme();

  $textColor = $theme->getColor('theme.page-text-color');

  $backgroundColor = $theme->getColor('theme.page-background-color');

  $titleColor = $theme->getColor('theme.page-title-color');

  $textFont = $theme->getText('theme.page-text-font');

  $titleFont = $theme->getText('theme.page-title-font');

  $themeColor = $theme->getColor('theme.page-main-color');

  $themeAlternateColor = $theme->getColor('theme.page-alternate-color');

  $slideTextColor = $theme->getColor('theme.page-slide-text-color');

?>

body {

  <?= $textFont ? 'font-family:'.$textFont : ''; ?>;

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>;

  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;
}

h1, h2, h3 {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

h2, h3 {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.button {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
  text-transform: uppercase;
}

.button:hover {
  <?= $titleColor ? 'background:'.$titleColor->asText() : ''; ?>;
  <?= $titleColor ? 'border-color:'.$titleColor->asText() : ''; ?>;
  color: #fff;
}

.round-image {
  <?= $themeColor ? 'background-color:'.$themeColor->asText() : ''; ?>;
}

.featured-preview {
  <?= $themeColor ? 'background-color:'.$themeColor->asText() : ''; ?>;
}

.featured-preview li {
  <?= $themeColor ? 'border-top: 1px solid '.$themeColor->asText() : ''; ?>;
}

.featured-preview li:hover {
  background: #FFF;
}

.alternated-items > div {
  <?= $themeColor ? 'background: '.$themeColor->asText() : ''; ?>;
}

.alternated-items .alternate {
  <?= $themeAlternateColor ? 'background-color: '.$themeAlternateColor->asText() : ''; ?>;
}

.featured-items-simple .illustration {
  <?= $themeColor ? 'background-color: '.$themeColor->asText() : ''; ?>;
}

.faq h3 {
  border-bottom: 1px solid #D6D6D6;
}

.slides .slide h2 {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

.slides, .slides h2, .slide-arrows a {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : ''; ?>;
}

.slides .arrow-prev, .slides .arrow-next {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : ''; ?>;
}

.slides .slide {
  background-color: #201145;
}

.slides .button {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : ''; ?>;
}

.slides p {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

.slides .button:not(.home):hover {
  <?= $slideTextColor ? 'background:'.$slideTextColor->asText() : ''; ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.button.home {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : ''; ?>;
  background: #5154AB;
  box-shadow: 0px 3px #412F8D;
}

</style>
