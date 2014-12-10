<?php

require_once __DIR__ . '/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

// Author
$app->get('/author/:id/:slug', function($id, $slug) use($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $state->current_document_id = $id;

    if ($state->current_document($prismic) == null) {
        $app->response->setStatus(404);
        $theme->render('404');
    } else {
        $theme->render('author', array(
            'author' => $state->current_document($prismic)
        ));
    }
});

// Search
$app->get('/search', function() use($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $theme->render('search');
});

// Category
$app->get('/category/:id/:slug', function ($id, $slug) use($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $state->current_category_id = $id;
    $theme->render('category');
});

// Index
$app->get('/', function() use ($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $theme->render('index');
});

// RSS Feed
$app->get('/feed', function() use ($app) {
    $prismic = new PrismicHelper($app);
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
            ->url($blogUrl . $prismic->$linkResolver->resolveDocument($post))
            ->pubDate($post->getDate("post.date")->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

// Previews
$app->get('/preview', function() use($app) {
    $prismic = new PrismicHelper($app);
    $token = $app->request()->params('token');
    $url = $prismic->get_api()->previewSession($token, $prismic->$linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) use($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $state->set_current_archive($year, $month, $day);
    $theme->render('archive', array(
        'archive_date' => $state->current_archive_date_formatted()
    ));
});

// Post
$app->get('/:year/:month/:day/:uid', function($year, $month, $day, $uid) use($app) {
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $state->current_document_id = $uid;

    $doc = $state->current_document($prismic);
    if ($doc->document->getUid() != $uid) {
        // The user came from a URL with an older uid
        $app->response->redirect($prismic->linkResolver->resolveDocument($doc->document));
    }
    if ($doc instanceof Post) {
        $theme->render('single', array(
            'post' => $doc
        ));
    } else {
        $app->response->setStatus(404);
        $theme->render('404');
    }
});

// Page
$app->get('/:uid(/:uid2)', function($uid, $uid2 = null) use($app) {
    if ($uid2) $uid = $uid2; // If $uid2 is defined, $uid is the parent
    $prismic = new PrismicHelper($app);
    $state = new State($app, $prismic);
    $theme = new Theme($app, $state, $prismic);
    $state->current_document_id = $uid;

    $page = $state->current_document($prismic);
    if ($page->document->getUid() != $uid) {
        // The user came from a URL with an older uid
        $app->response->redirect($prismic->linkResolver->resolveDocument($page->document));
    }
    if ($page instanceof Page) {
        $theme->render('page', array(
            'page' => $page
        ));
    } else {
        $app->response->setStatus(404);
        $theme->render('404');
    }
});
