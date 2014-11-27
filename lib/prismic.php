<?php
require '../vendor/autoload.php';

use Prismic\Api;
use Prismic\LinkResolver;

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

