<?php
require 'vendor/autoload.php';

require_once 'config.php';
require_once 'lib/PrismicHelper.php';
require_once 'lib/theme.php';
require_once 'lib/template-tags.php';

$app = new \Slim\Slim();
$currentDocument = null;

// Page
$app->get('/page/:pid/:slug', function($pid, $slug) {
    Theme::render('page');
});

// Post
$app->get('/:id/:slug', function($id, $slug) {
    global $app, $currentDocument;
    $doc = PrismicHelper::get_document($id);

    if ($doc) {
        if ($doc->getSlug() == $slug) {
            $currentDocument = $doc;
            Theme::render('single');
        } else {
            $app->response->redirect('/' . $id . '/' . $doc->getSlug(), 301);
        }
    } else {
        $app->response->setStatus(404);
        Theme::render('404');
    }
});

// Search
$app->get('/search', function() {
    global $app;
    $q = $app->request()->params('q');
    $documents = PrismicHelper::search($q);
    Theme::render('search');
});

// Index
$app->get('/', function() {
    global $app;
    $page = $app->request()->params('page') || 0;
    $documents = PrismicHelper::get_posts($page);
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

