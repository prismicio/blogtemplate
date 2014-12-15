<?php

require_once 'vendor/autoload.php';

// config.php is not present by default, so we show a message explaining to create one
if (file_exists('config.php')) {
    include 'config.php';
} else {
    include 'app/includes/templates/firstrun.php';
    exit();
}

$composer = json_decode(file_get_contents(__DIR__ . '/composer.json'));
$app = new \Slim\Slim(
    array(
      'version'        => $composer->version,
      'debug'          => DEBUG,
      'mode'           => MODE,
      'prismic.url'    => PRISMIC_URL,
      'theme'          => PI_THEME,
      'site.title'     => SITE_TITLE,
      'site.description' => SITE_DESCRIPTION,
      'page_size'      => PAGE_SIZE,
    )
);



require_once __DIR__ . '/app/app.php';

$app->run();

