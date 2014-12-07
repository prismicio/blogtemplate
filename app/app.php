<?php

require_once __DIR__ . '/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

// Author
$app->get('/author/:id/:slug', function($id, $slug) {
    global $app;
    State::$current_document_id = $id;

    if (State::current_document() == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else {
        Theme::render('author', array(
            'author' => State::current_document()
        ));
    }
});

// Search
$app->get('/search', function() {
    Theme::render('search');
});

// Category
$app->get('/category/:id/:slug', function ($id, $slug) {
    State::$current_category_id = $id;
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

// Post or page
$app->get('/:year/:month/:day/:id/:slug', function($year, $month, $day, $id, $slug) {
    global $app;
    State::$current_document_id = $id;

    $doc = State::current_document();
    if ($doc == null) {
        $app->response->setStatus(404);
        Theme::render('404');
    } else if ($doc->getType() == 'page') {
        Theme::render('page', array(
            'page' => new Page($doc)
        ));
    } else {
        Theme::render('single', array(
            'post' => new Post($doc)
        ));
    }
});

// Archive
$app->get('/:year/:month(/:day)', function ($year, $month, $day = null) {
    State::set_current_archive($year, $month, $day);
    Theme::render('archive');
});


