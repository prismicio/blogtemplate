<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/prismic.php';
require_once 'lib/template-helpers.php';

$app = new \Slim\Slim();

// Page
$app->get('/page/:pid/:slug', function($pid, $slug) {
    include('themes/' . PI_THEME . '/page.php');
});

// Post
$app->get('/:docid/:slug', function($docid, $slug) {
    include('themes/' . PI_THEME . '/single.php');
});

// Index
$app->get('/', function() {
    include('themes/' . PI_THEME . '/index.php');
});

$app->run();

