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
        if ($link->getType() == "author") {
            return "/author/" . $link->getId() . '/' . $link->getSlug();
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

    static function form()
    {
        return PrismicHelper::get_api()->forms()->everything->ref(PrismicHelper::get_ref());
    }

    static function get_authors() {
        return PrismicHelper::form()
            ->query(Predicates::at("document.type", "author"))
            ->submit();
    }

    static function get_document($id)
    {
        $results = PrismicHelper::form()
            ->query(Predicates::at("document.id", $id))
            ->submit()
            ->getResults();
        if (count($results) > 0) {
            return $results[0];
        } else {
            return null;
        }
    }

    static function get_pages()
    {
        return PrismicHelper::form()
            ->query(Predicates::at("document.type", "page"))
            ->submit()
            ->getResults();
    }

    static function get_posts($page)
    {
        return PrismicHelper::form()
            ->query(Predicates::at("document.type", "post"))
            ->page($page)
            ->submit();
    }

}

PrismicHelper::$linkResolver = new BlogLinkResolver();
