<?php

date_default_timezone_set("UTC");

require_once __DIR__ . '/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

if (!function_exists('check_404')) {
    function check_404($app, $theme, $doc)
    {
        if (!$doc) {
            $app->response->setStatus(404);
            $theme->render('404');
        }
        return $doc == null;
    }
}

if (!function_exists('current_page')) {
    function current_page($app)
    {
        $pageQuery = $app->request()->params('page');
        return $pageQuery == null ? '1' : $pageQuery;
    }
}

// Author
$app->get('/author/:id/:slug', function($id, $slug) use($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);
    $author = $prismic->get_document($id);

    if (!check_404($app, $theme, $author)) {
        $posts = $prismic->byAuthor($id)->getResults();
        $theme->render('author', array(
            'author' => $author,
            'posts' => $posts
        ));
    }
});

// Search
$app->get('/search', function() use($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);
    $q = $app->request()->params('q');
    $response = $prismic->search($q, current_page($app));
    $theme->render('search', array(
        'response' => $response,
        "search_query" => $q
    ));
});

// Category
$app->get('/category/:uid', function ($uid) use($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);
    $cat = $prismic->get_category($uid);
    if (!check_404($app, $theme, $cat)) {
        $response = $prismic->byCategory($cat->getId());
        $theme->render('category', array(
            'category' => $cat,
            'response' => $response
        ));
    }
});

// Tag
$app->get('/tag/:tag', function ($tag) use($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);
    $response = $prismic->byTag($tag);
    $theme->render('tag', array(
        'tag' => $tag,
        'response' => $response
    ));
});

// Index
$app->get('/', function() use ($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);
    $response = $prismic->get_posts(current_page($app));
    $theme->render('index', array(
        'response' => $response
    ));
});

// RSS Feed
$app->get('/feed', function() use ($app) {
    $prismic = new PrismicHelper($app);
    $blogUrl = $app->request()->getUrl();
    $posts = $prismic->get_posts(current_page($app))->getResults();
    $feed = new Feed();
    $channel = new Channel();
    $channel
        ->title(SITE_TITLE)
        ->description(SITE_DESCRIPTION)
        ->url($blogUrl)
        ->appendTo($feed);

    foreach ($posts as $post) {
        $item = new Item();
        $item
            ->title($post->getText("post.title"))
            ->description($post->getHtml("post.body", $prismic->linkResolver))
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
    $theme = new Theme($app, $prismic);
    $posts = $prismic->archives(array(
        'year' => $year,
        'month' => $month,
        'day' => $day
    ), current_page($app));
    if ($day != null) {
        $dt = DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day);
        $archive_date = $dt->format('F jS, Y');
    } elseif ($month != null) {
        $dt = DateTime::createFromFormat('!Y-m', $year . '-' . $month);
        $archive_date = $dt->format('F Y');
    } else {
        $archive_date = $year;
    }
    $theme->render('archive', array(
        'response' => $posts,
        'date' => array('year' => $year, 'month' => $month, 'day' => $day),
        'archive_date' => $archive_date
    ));
});

// Post
$app->get('/:year/:month/:day/:uid', function($year, $month, $day, $uid) use($app) {
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);

    $doc = $prismic->get_post($uid);
    $permalink = $prismic->linkResolver->resolveDocument($doc);
    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid or date
        $app->response->redirect($permalink);
        return;
    }
    if (!check_404($app, $theme, $doc)) {
        $theme->render('single', array(
            'post' => $doc
        ));
    }
});

// Page
$app->get('/:uid(/:uid2)', function($uid, $uid2 = null) use($app) {
    if ($uid2) $uid = $uid2; // If $uid2 is defined, $uid is the parent
    $prismic = new PrismicHelper($app);
    $theme = new Theme($app, $prismic);

    $page = $prismic->get_page($uid);
    $permalink = $prismic->linkResolver->resolveDocument($page);
    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid
        $app->response->redirect($permalink);
    }
    if (!check_404($app, $theme, $page)) {
        $theme->render('page', array(
            'page' => $page
        ));
    }
});
