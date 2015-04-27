
<style>

<?php

  $theme = the_theme();

  $textColor = $theme->getColor('theme.blog-text-color');

  $backgroundColor = $theme->getColor('theme.blog-background-color');

  $footerBackgroundColor = $theme->getColor('theme.blog-footer-background-color');

  $footerTextColor = $theme->getColor('theme.blog-footer-text-color');

  $titleColor = $theme->getColor('theme.blog-title-color');

  $textFont = $theme->getText('theme.blog-text-font');

  $titleFont = $theme->getText('theme.blog-title-font');

  $metaTextColor = $theme->getColor('theme.blog-meta-text-color');

  $homeTextColor = $theme->getColor('theme.blog-home-text-color');

  $imageLabelTextColor = $theme->getColor('theme.blog-imagelabel-text-color');

?>

body {

  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;

  <?= $textFont ? 'font-family:'.$textFont : ''; ?>;

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>;
}

a {
  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>;
}

h1, .h1,
h2, .h2,
h3, .h3,
h4, .h4,
h5, .h5,
h6, .h6 {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.blog-header {
  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
  background-color: #201145;
}

.blog-header h1, .blog-header .date, .blog-header .shortlede, .blog-header .author {
  <?= $homeTextColor ? 'color:'.$homeTextColor->asText() : ''; ?>;
}

.blog-footer.single {

  <?= $footerBackgroundColor ? 'background-color:'.$footerBackgroundColor->asText() : ''; ?>;
 }

.blog-footer.single a .label {

  <?= $footerTextColor ? 'color:'.$footerTextColor->asText() : ''; ?>;

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

.blog-footer.single .menu::before {
  <?= $footerTextColor ? 'color:'.$footerTextColor->asText() : ''; ?>;
}


.blog-description {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
  color: rgba(255, 255, 255, 0.60);
}

.blog-post-title a, .blog-post-meta a {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

.blog-post-meta a {

  <?= $metaTextColor ? 'color:'.$metaTextColor->asText() : ''; ?>;
}

.blog-post-title a {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.blog-post-meta > *, .blog-post-meta .tags::before, .blog-post-meta .categories::before, .blog-post-meta .author::before {

  <?= $metaTextColor ? 'color:'.$metaTextColor->asText() : ''; ?>;
}

.blog-main.single .image-label {

  <?= $imageLabelTextColor ? 'color:'.$imageLabelTextColor->asText() : ''; ?>;
}

.blog-header {
  background: #201145 none;
  border-bottom: 1px solid #2F3163;
}

.blog-main.single .image-left + .image-label, .blog-main.single .image-full-column + .image-label {

  <?= $imageLabelTextColor ? 'border-right-color:'.$imageLabelTextColor->asText() : ''; ?>;
}

</style>
