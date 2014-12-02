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

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

$app = new \Slim\Slim();

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

// Category
$app->get('/category/:category', function ($category) {
    State::$current_category = $category;
    Theme::render('category');
});

// Index
$app->get('/', function() {
    Theme::render('index');
});

// RSS Feed
$app->get('/feed', function() {
    global $app;
    $blogUrl = $app->request()->getUrl();
    $feed = new Feed();
    $channel = new Channel();
    $channel
        ->title(SITE_TITLE)
        ->description(SITE_DESCRIPTION)
        ->url($blogUrl)
        ->appendTo($feed);

    foreach (posts() as $post) {
        echo 'Add item: ' . post_title($post);
        $item = new Item();
        $item
            ->title(post_title($post))
            ->description(get_html("post.body", $post))
            ->url($blogUrl . PrismicHelper::$linkResolver->resolveDocument($post))
            ->pubDate($post->getDate("post.date")->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

// Previews
$app->get('/preview', function() {
    global $app;
    $token = $app->request()->params('token');
    $url = PrismicHelper::get_api()->previewSession($token, PrismicHelper::$linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// Post
$app->get('/:year/:month/:day/:id/:slug', function($year, $month, $day, $id, $slug) {
    global $app;
    State::$current_document_id = $id;

    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        Theme::render('single');
    }
});

// Archive
$app->get('/:year/:month(/:day)', function ($year, $month, $day = null) {
    State::set_current_archive($year, $month, $day);
    Theme::render('archive');
});

// Page (bookmarks)
$app->get('/:name', function($name) {
    global $app;

    State::$current_document_id = $bookmarks = PrismicHelper::get_api()->bookmark($name);
    if (current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        $doc = current_document();
        $app->etag(PrismicHelper::get_ref() . ':' . $doc->getId());
        Theme::render('page');
    }
});

$app->run();

