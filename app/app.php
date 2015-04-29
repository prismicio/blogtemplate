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

date_default_timezone_set('UTC');

require_once __DIR__.'/includes.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

use Mailgun\Mailgun;

// Index
$app->get('/', function () use ($app, $prismic) {

    $homeId = $prismic->get_api()->bookmark('home');

    if (!$homeId) {
        not_found($app);

        return;
    }

    $home = $prismic->get_document($homeId);

    if (!$home || $home->getType() != 'page') {
        not_found($app);

        return;
    }

    $theme = $prismic->get_theme();

    render($app, 'page', array('single_post' => $home, 'theme' => $theme));
});

// Author
$app->get('/author/:id/:slug', function ($id, $slug) use ($app,$prismic) {
    $author = $prismic->get_document($id);

    if (!$author) {
        not_found($app);

        return;
    }

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::at('my.post.author', $id))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $theme = $prismic->get_theme();

    render($app, 'author', array('posts' => $posts, 'author' => $author, 'theme' => $theme));
});

// Search results
$app->get('/search', function () use ($app,$prismic) {
    $q = $app->request()->params('q');

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::fulltext('document', $q))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $theme = $prismic->get_theme();

    render($app, 'search', array('posts' => $posts, 'theme' => $theme));
});

// Category
$app->get('/category/:uid', function ($uid) use ($app,$prismic) {
    $cat = $prismic->by_uid('category', $uid);

    if (!$cat) {
        not_found($app);

        return;
    }

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::any('my.post.categories.link', array($cat->getId())))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $theme = $prismic->get_theme();

    render($app, 'category', array('category' => $cat, 'posts' => $posts, 'theme' => $theme));
});

// Tag
$app->get('/tag/:tag', function ($tag) use ($app,$prismic) {
    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::any('document.tags', array($tag)))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $theme = $prismic->get_theme();

    render($app, 'tag', array('posts' => $posts, 'tag' => $tag, 'theme' => $theme));
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) use ($app,$prismic) {
    global $WPGLOBAL;

    $posts = $prismic->archives(array(
        'year' => $year,
        'month' => $month,
        'day' => $day,
    ), current_page($app));

    $date = array('year' => $year, 'month' => $month, 'day' => $day);

    $theme = $prismic->get_theme();

    render($app, 'archive', array('posts' => $posts, 'date' => $date, 'theme' => $theme));
});

// Previews
$app->get('/preview', function () use ($app,$prismic) {
    $token = $app->request()->params('token');
    $url = $prismic->get_api()->previewSession($token, $prismic->linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url ? $url : '/', 301);
});

// RSS Feed,
// using the Suin RSS Writer library
$app->get('/feed', function () use ($app, $prismic) {
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
        $item->title($post->getText('post.title'))
            ->description($post->getHtml('post.body', $prismic->linkResolver))
            ->url($blogUrl.$prismic->linkResolver->resolveDocument($post))
            ->pubDate($post->getDate('post.date')->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

$app->post('/disqus/threads/create', function () use ($app) {
    $title = $_POST['title'];
    $identifier = $_POST['identifier'];
    $httpClient = \Prismic\Api::defaultHttpAdapter();

    if ($app->config('disqus.forum')) { // OVERWRITTEN DISQUS CONFIGURATION

        $data = array(
            'api_key' => $app->config('disqus.apikey'),
            'api_secret' => $app->config('disqus.apisecret'),
            'access_token' => $app->config('disqus.accesstoken'),
            'forum' => $app->config('disqus.forum'),
            'title' => $title,
            'identifier' => $identifier,
        );

        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->post('https://disqus.com/api/3.0/threads/create.json', array(), $data);
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $body = array(
                'code' => $json->code,
                'id' => $json->response->id,
            );
            $app->response->setBody(json_encode($body));
        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $json = json_decode($e->getResponse()->getBody());
            $app->response->setStatus($e->getResponse()->getStatusCode());
            $app->response->setBody(array('code' => $json->code));
        }
    } else { // DEFAULT DISQUS CONFIGURATION

        $data = array(
            'title' => $title,
            'identifier' => $identifier,
        );

        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->post('https://prismic.io/starterkit/disqus/threads/create', array(), $data);
            $app->response->setStatus($response->getStatusCode());
            $json = json_decode($response->getBody());
            $body = array(
                'code' => $json->code,
                'id' => $json->id,
            );
            $app->response->setBody(json_encode($body));
        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $app->response->setStatus($e->getResponse()->getStatusCode());
            $app->response->setBody($e->getResponse()->getBody());
        }
    }
});

// Blog home
$app->get('/blog', function () use ($app, $prismic) {

    $homeblogId = $prismic->get_api()->bookmark('homeblog');

    if (!$homeblogId) {
        not_found($app);

        return;
    }

    $homeblog = $prismic->get_document($homeblogId);

    $posts = $prismic->form()
        ->page(current_page($app))
        ->query(Predicates::at('document.type', 'post'))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->submit();

    $theme = $prismic->get_theme();

    render($app, 'homeblog', array('homeblog' => $homeblog, 'posts' => $posts, 'theme' => $theme));
});

// Post
$app->get('/blog/:year/:month/:day/:uid', function ($year, $month, $day, $uid) use ($app,$prismic) {
    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company',
    );

    $ctx = array();

    $doc = $prismic->by_uid('post', $uid, $fetch);
    if (!$doc) {
        not_found($app);

        return;
    }

    $ctx['single_post'] = $doc;

    $prev_doc = $prismic->get_prev_post($doc->getId());

    $next_doc = $prismic->get_next_post($doc->getId());

    if ($prev_doc) {
        $ctx['single_prev_post'] = $prev_doc;
    }
    if ($next_doc) {
        $ctx['single_next_post'] = $next_doc;
    }

    $permalink = $prismic->linkResolver->resolveDocument($doc);

    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid or date
        $app->response->redirect($permalink);

        return;
    }

    $theme = $prismic->get_theme();
    $ctx['theme'] = $theme;

    render($app, 'single', $ctx);
});

// Contact
$app->get('/contact', function() use ($app, $prismic) {

    $contactId = $prismic->get_api()->bookmark('contact');

    if (!$contactId) {
        not_found($app);

        return;
    }

    $contact = $prismic->get_document($contactId);

    $theme = $prismic->get_theme();

    render($app, 'contact', array('contact' => $contact, 'theme' => $theme));
});

$app->post('/contact', function() use ($app) {
  $resp = $app->response;
  $resp->headers->set('Content-Type', 'application/json');

  $domain = $app->config('mailgun.domain');

  $token = $app->request->post('token');

  if ($token != sha1($domain)) {
      $resp->setBody(json_encode(array("error" => "Unauthorized contact token")));
      return;
  }

  $message = array(
    'from' => $app->request->post('sender'),
    'to' => $app->config('mailgun.email'),
    'subject' => $app->request->post('subject'),
    'text' => $app->request->post('message'));

  $mailgun = new Mailgun($app->config('mailgun.apikey'));

  try {
      $res = $mailgun->sendMessage($domain, $message);
      $data = ($res->http_response_code == 200)
        ? array("success" => $res->http_response_body->message)
        : array("error" => $res->http_response_body->message);

      $resp->setBody(json_encode($data));
  } catch (Exception $e) {
      $resp->setBody(json_encode(array("error" => $e->getMessage())));
  }
});

// Page
$app->get('/:path+', function ($path) use ($app,$prismic) {
    $page_uid = check_page_path($path, $prismic, $app);

    $theme = $prismic->get_theme();

    if ($page_uid) {
        $page = $prismic->by_uid('page', $page_uid);
        if (!$page) {
            not_found($app, $theme);

            return;
        }

        render($app, 'page', array('single_post' => $page, 'theme' => $theme));
    }
});
