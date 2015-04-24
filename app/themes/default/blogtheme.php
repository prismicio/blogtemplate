
<style>

<?php

  $theme = the_theme();

  $textColor = $theme->getColor('theme.blog-text-color');

  $titleColor = $theme->getColor('theme.blog-title-color');

  $textFont = $theme->getText('theme.blog-text-font');

  $titleFont = $theme->getText('theme.blog-title-font');

?>

body {

  <?= $textFont ? 'font-family:'.$textFont : ''; ?>

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>
}

a {
  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>
}

h1, .h1,
h2, .h2,
h3, .h3,
h4, .h4,
h5, .h5,
h6, .h6 {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>
}

.blog-header {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
  background-color: #201145;
}

.blog-header h1, .blog-header .date, .blog-header .shortlede, .blog-header .author {
  color: #FFF;
}

.blog-footer.single a .label {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
}

.blog-description {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
  color: rgba(255, 255, 255, 0.60);
}

.blog-post-title a, .blog-post-meta a {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>
}

.blog-post-meta a {
  color: #9494AF;
}

.blog-post-title a {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>
}

.author-image {
  background-color: #FFF;
}

.author-bio {
  color: #FFF;
}

.author-sites li a {
  color: #FFF;
}

.blog-footer.single {
  background-color: #FFF;
}

.blog-footer.single a .label {
  color: #B4B4B4;
}

.blog-main.single .image-label {
  color: #9494AF;
}

.blog-main.single .block-citation {
  border-left: solid #B4B4B4 4px;
}

.blog-header {
  background: #201145 none;
  border-bottom: 1px solid #2F3163;
}

.blog-main.single .image-left + .image-label, .blog-main.single .image-full-column + .image-label {
  border-right: solid #9494AF 7px;
}

</style>
