<?php

date_default_timezone_set("UTC");

require_once __DIR__ . '/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

// Author
$app->get('/author/:id/:slug', function($id, $slug) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $author = $prismic->get_document($id);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    if ($author == null) {
        not_found($app);
        return;
    }
    $loop->setResponse($prismic->byAuthor($id));

    render($app, 'author');
});

// Search
$app->get('/search', function() use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $q = $app->request()->params('q');
    $loop->setResponse($prismic->search($q, current_page($app)));
    render($app, 'search');
});

// Category
$app->get('/category/:uid', function ($uid) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $cat = $prismic->get_category($uid);
    $WPGLOBAL['single_post'] = $cat;
    if ($cat == null) {
        not_found($app);
        return;
    }
    $loop->setResponse($prismic->byCategory($cat->getId()));
    render($app, 'category');
});

// Tag
$app->get('/tag/:tag', function ($tag) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $loop->setResponse($prismic->byTag($tag));
    render($app, 'tag');
});

// Index
$app->get('/', function() use ($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $loop->setResponse($prismic->get_posts(current_page($app)));
    render($app, 'index');
});

// RSS Feed
$app->get('/feed', function() use ($app) {
    $prismic = new PrismicHelper($app);
    $blogUrl = $app->request()->getUrl();
    $posts = $prismic->get_posts(current_page($app))->getResults();
    $feed = new Feed();
    $channel = new Channel();
    $channel
        ->title($app->config('site.title'))
        ->description($app->config('site.description'))
        ->url($blogUrl)
        ->appendTo($feed);

    foreach ($posts as $post) {
        $item = new Item();
        $item
            ->title($post->getText("post.title"))
            ->description($post->getHtml("post.body", $prismic->linkResolver))
            ->url($blogUrl . $prismic->linkResolver->resolveDocument($post))
            ->pubDate($post->getDate("post.date")->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

// Previews
$app->get('/preview', function() use($app) {
    $prismic = new PrismicHelper($app);
    $token = $app->request()->params('token');
    $url = $prismic->get_api()->previewSession($token, $prismic->linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $loop->setResponse($prismic->archives(array(
        'year' => $year,
        'month' => $month,
        'day' => $day
    ), current_page($app)));
    if ($day != null) {
        $dt = DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day);
        $archive_date = $dt->format('F jS, Y');
    } elseif ($month != null) {
        $dt = DateTime::createFromFormat('!Y-m', $year . '-' . $month);
        $archive_date = $dt->format('F Y');
    } else {
        $archive_date = $year;
    }
    $WPGLOBAL['date'] = array('year' => $year, 'month' => $month, 'day' => $day);
    render($app, 'archive');
});

// Post
$app->get('/:year/:month/:day/:uid', function($year, $month, $day, $uid) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $doc = $prismic->get_post($uid);
    if ($doc == null) {
        not_found($app);
        return;
    }
    $permalink = $prismic->linkResolver->resolveDocument($doc);
    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid or date
        $app->response->redirect($permalink);
        return;
    }
    $loop->setPosts(array($doc));
    $WPGLOBAL['single_post'] = $doc;
    render($app, 'single');
});

// Page with subs
$app->get('/:path+', function($path) use($app) {
    global $WPGLOBAL;
    $prismic = new PrismicHelper($app);
    $loop = new Loop($prismic);
    $WPGLOBAL = array(
        'app' => $app,
        'prismic' => $prismic,
        'loop' => $loop
    );

    $page_uid = check_page_path($path, $prismic, $app);

    if($page_uid != null)
    {
      $page = $prismic->get_page($page_uid);
      $loop->setPosts(array($page));
      render($app, 'page');
    }
});
