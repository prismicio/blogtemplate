<?php
require '../vendor/autoload.php';

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

class BlogLinkResolver extends LinkResolver
{
    public function resolve($link)
    {
        if ($link->isBroken()) {
            return null;
        }
        if ($link->getType() == "page") {
            return "/page/" . $link->getId() . '/' . $link->getSlug();
        }
        return "/" . $link->getId() . '/' . $link->getSlug();
    }
}

$linkResolver = new BlogLinkResolver();

$api = null;

function get_api() {
    global $api;
    if ($api == null) {
        $api = Api::get(PRISMIC_URL);
    }
    return $api;
}

function get_ref() {
    global $app;
    $previewCookie = $app->request()->cookies[Prismic\PREVIEW_COOKIE];
    if ($previewCookie != null) {
        return $previewCookie;
    } else {
        return get_api()->master();
    }
}

function get_document($id) {
    $api = get_api();
    $results = $api->forms()
        ->everything
        ->query(Predicates::at("document.id", $id))
        ->ref(get_ref())
        ->submit()
        ->getResults();
    if (count($results) > 0) {
        return $results[0];
    } else {
        return null;
    }
}
