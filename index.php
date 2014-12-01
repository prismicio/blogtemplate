<?php
require 'vendor/autoload.php';

if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    include 'includes/templates/firstrun.php';
    exit();
}

require 'includes/PrismicHelper.php';
require 'includes/State.php';
require 'includes/theme.php';

require 'tags/general.php';
require 'tags/posts.php';
require 'tags/author.php';
require 'tags/archive.php';

$app = new \Slim\Slim();

// Page
$app->get('/page/:pid/:slug', function($pid, $slug) {
    global $app;
    State::$current_document_id = $pid;
    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        $doc = current_document();
        $app->etag(PrismicHelper::get_ref() . ':' . $doc->getId());
        Theme::render('page');
    }
});

// Author
$app->get('/author/:id/:slug', function($id, $slug) {
    global $app;
    State::$current_document_id = $id;

    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        Theme::render('author');
    }
});

// Search
$app->get('/search', function() {
    Theme::render('search');
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) {
    State::set_current_archive($year, $month, $day);
    Theme::render('archive');
});

// Index
$app->get('/', function() {
    Theme::render('index');
});

// Previews
$app->get('/preview', function() {
    global $app, $linkResolver;
    $token = $app->request()->params('token');
    $url = PrismicHelper::get_api()->previewSession($token, $linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// Post
$app->get('/:id/:slug', function($id, $slug) {
    global $app;
    State::$current_document_id = $id;

    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        Theme::render('single');
    }
});

$app->run();

