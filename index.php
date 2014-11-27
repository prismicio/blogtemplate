<?php
require 'vendor/autoload.php';
require_once 'config.php';

use Prismic\Api;
use Prismic\Predicates;

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
    $api = Api::get(PRISMIC_URL);
    $pages = $api->forms()
        ->everything
        ->query(Predicates::at("document.type", "page"))
        ->ref($api->master())
        ->submit();
    echo "Hello, world";
    echo "<ul>";
    foreach($pages->getResults() as $page) {
        echo "<li>" . $page->getText("page.title") . "</li>";
    }
});

$app->run();

