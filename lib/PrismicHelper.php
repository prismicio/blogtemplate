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

class PrismicHelper
{

    static $linkResolver;

    private static $api = null;

    static function get_api()
    {
        if (PrismicHelper::$api == null) {
            PrismicHelper::$api = Api::get(PRISMIC_URL);
        }
        return PrismicHelper::$api;
    }

    static function get_ref()
    {
        global $app;
        $previewCookie = $app->request()->cookies[Prismic\PREVIEW_COOKIE];
        if ($previewCookie != null) {
            return $previewCookie;
        } else {
            return PrismicHelper::get_api()->master();
        }
    }

    static function get_document($id)
    {
        $api = PrismicHelper::get_api();
        $results = $api->forms()
            ->everything
            ->query(Predicates::at("document.id", $id))
            ->ref(PrismicHelper::get_ref())
            ->submit()
            ->getResults();
        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }

    static function get_all($type, $page = 0)
    {
        $api = PrismicHelper::get_api();
        return $api->forms()
            ->everything
            ->query(Predicates::at("document.type", $type))
            ->ref(PrismicHelper::get_ref())
            ->page($page)
            ->submit();
    }

    static function get_posts($page)
    {
        return PrismicHelper::get_all("post", $page)->getResults();
    }

}

PrismicHelper::$linkResolver = new BlogLinkResolver();
