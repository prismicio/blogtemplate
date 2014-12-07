<?php

require_once 'vendor/autoload.php';
$composer = json_decode(file_get_contents(__DIR__ . '/composer.json'));
$app = new \Slim\Slim(
    array(
      'version'        => $composer->version,
      'debug'          => false,
      'mode'           => 'production',
      // 'templates.path' => __DIR__ . '/app/themes',
    )
);

require_once __DIR__ . '/app/app.php';

$app->run();

