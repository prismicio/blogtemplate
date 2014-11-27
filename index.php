<?php
require 'vendor/autoload.php';
require_once 'config.php';

$app = new \Slim\Slim();

// Page
$app->get('/page/:pid/:slug', function() {
    echo "Hello, page :pid";
});

// Post
$app->get('/:docid/:slug', function() {
    echo "Hello, doc :docid";
});

// Index
$app->get('/', function() {
    echo "Hello, world";
});

$app->run();
