<?php

/*
 * This is the main file of the application, including routing and controllers.
 *
 * $app is a Slim application instance, see the framework documentation for more details:
 * http://docs.slimframework.com/
 *
 * The order of the routes matter, as it will define the priority of routes. For that reason we
 * need to keep the more "generic" routes, such as the pages route, at the end of the file.
 */

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

date_default_timezone_set("UTC");

require_once __DIR__ . '/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

// Index
$app->get('/', function() use ($app, $prismic) {
    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $posts = $prismic->form()
        ->page(current_page($app))
        ->query(Predicates::at("document.type", 'post'))
        ->fetchLinks($fetch)
        ->orderings("my.post.date desc")
        ->submit();

    render($app, 'index', array('posts' => $posts));
});

// Author
$app->get('/author/:id/:slug', function($id, $slug) use($app, $prismic) {
    $author = $prismic->get_document($id);

    if (!$author) {
        not_found($app);
        return;
    }

    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $posts = $prismic->form()
        ->query(
          Predicates::at("document.type", 'post'),
          Predicates::at("my.post.author", $id))
        ->fetchLinks($fetch)
        ->orderings("my.post.date desc")
        ->page(current_page($app))
        ->submit();

    render($app, 'author', array('posts' => $posts, 'author' => $author));
});

// Search results
$app->get('/search', function() use($app, $prismic) {
    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $q = $app->request()->params('q');

    $posts = $prismic->form()
        ->query(
          Predicates::at("document.type", 'post'),
          Predicates::fulltext("document", $q))
        ->fetchLinks($fetch)
        ->orderings("my.post.date desc")
        ->page(current_page($app))
        ->submit();

    render($app, 'search', array('posts' => $posts));
});

// Category
$app->get('/category/:uid', function ($uid) use($app, $prismic) {

    $cat = $prismic->by_uid("category", $uid);

    if (!$cat) {
        not_found($app);
        return;
    }

    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $posts = $prismic->form()
        ->query(
            Predicates::at("document.type", 'post'),
            Predicates::any("my.post.categories.link", array($cat->getId())))
        ->fetchLinks($fetch)
        ->orderings("my.post.date desc")
        ->page(current_page($app))
        ->submit();

    render($app, 'category', array('category' => $cat, 'posts' => $posts));
});

// Tag
$app->get('/tag/:tag', function ($tag) use($app, $prismic) {
    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $posts = $prismic->form()
        ->query(
          Predicates::at("document.type", 'post'),
          Predicates::any("document.tags", array($tag)))
        ->fetchLinks($fetch)
        ->orderings("my.post.date desc")
        ->page(current_page($app))
        ->submit();

    render($app, 'tag', array('posts' => $posts));
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) use($app, $prismic) {
    global $WPGLOBAL;

    $posts = $prismic->archives(array(
        'year' => $year,
        'month' => $month,
        'day' => $day
    ), current_page($app));
    if ($day) {
        $dt = DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day);
        $archive_date = $dt->format('F jS, Y');
    } elseif ($month) {
        $dt = DateTime::createFromFormat('!Y-m', $year . '-' . $month);
        $archive_date = $dt->format('F Y');
    } else {
        $archive_date = $year;
    }
    $WPGLOBAL['date'] = array('year' => $year, 'month' => $month, 'day' => $day);
    render($app, 'archive', array('posts' => $posts));
});

// Previews
$app->get('/preview', function() use($app, $prismic) {
    $token = $app->request()->params('token');
    $url = $prismic->get_api()->previewSession($token, $prismic->linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url, 301);
});

// RSS Feed,
// using the Suin RSS Writer library
$app->get('/feed', function() use ($app, $prismic) {
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
        $item->title($post->getText("post.title"))
            ->description($post->getHtml("post.body", $prismic->linkResolver))
            ->url($blogUrl . $prismic->linkResolver->resolveDocument($post))
            ->pubDate($post->getDate("post.date")->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

// Post
$app->get('/:year/:month/:day/:uid', function($year, $month, $day, $uid) use($app, $prismic) {
    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company'
    );
    $doc = $prismic->by_uid('post', $uid, $fetch);
    if (!$doc) {
        not_found($app);
        return;
    }
    $permalink = $prismic->linkResolver->resolveDocument($doc);
    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid or date
        $app->response->redirect($permalink);
        return;
    }
    render($app, 'single', array('single_post' => $doc));
});

// Page
$app->get('/:path+', function($path) use($app, $prismic) {
    $page_uid = check_page_path($path, $prismic, $app);

    if ($page_uid) {
      $page = $prismic->by_uid('page', $page_uid);

      render($app, 'page', array('single_post' => $page));
    }
});
