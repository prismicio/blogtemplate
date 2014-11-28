<?php
require 'vendor/autoload.php';

require_once 'config.php';
require_once 'lib/PrismicHelper.php';
require_once 'lib/theme.php';

require_once 'tags/general.php';
require_once 'tags/posts.php';

$app = new \Slim\Slim();

// Page
$app->get('/page/:pid/:slug', function($pid, $slug) {
    global $app;
    State::$current_document_id = $pid;
    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
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

// Search
$app->get('/search', function() {
    Theme::render('search');
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

$app->run();

