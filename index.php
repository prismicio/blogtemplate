<?php
require 'vendor/autoload.php';

if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    include 'includes/templates/firstrun.php';
    exit();
}

require_once 'includes/PrismicHelper.php';
require_once 'includes/theme.php';

require_once 'tags/general.php';
require_once 'tags/posts.php';
require_once 'tags/author.php';

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
        $doc = current_document();
        $app->etag(PrismicHelper::get_ref() . ':' . $doc->getId());
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
        $doc = current_document();
        $app->etag(PrismicHelper::get_ref() . ':' . $doc->getId());
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

