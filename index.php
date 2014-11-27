<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/prismic.php';
require_once 'lib/template-helpers.php';

$app = new \Slim\Slim();
$currentDocument = null;

// Page
$app->get('/page/:pid/:slug', function($pid, $slug) {
    include('themes/' . PI_THEME . '/page.php');
});

// Post
$app->get('/:id/:slug', function($id, $slug) {
    global $app, $currentDocument;
    $doc = get_document($id);

    if ($doc) {
        if ($doc->getSlug() == $slug) {
            $currentDocument = $doc;
            include('themes/' . PI_THEME . '/single.php');
        } else {
            $app->response->redirect('/' . $id . '/' . $doc->getSlug(), 301);
        }
    } else {
        $app->response->setStatus(404);
        include('themes/' . PI_THEME . '/404.php');
    }
});

// Previews
$app->get('/preview', function() {
    global $app, $linkResolver;
    $token = $app->request()->params('token');
    $url = get_api()->previewSession($token, $linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// Index
$app->get('/', function() {
    include('themes/' . PI_THEME . '/index.php');
});

$app->run();

