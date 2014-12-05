<?php

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

class BlogLinkResolver extends LinkResolver
{
    public function resolve($link)
    {
        $name = PrismicHelper::get_bookmark_name($link->getId());
        if ($name == 'home') {
            return '/';
        }
        if ($link->isBroken()) {
            return null;
        }
        if ($link->getType() == "author") {
            return "/author/" . $link->getId() . '/' . $link->getSlug();
        }
        if ($link->getType() == "category") {
            return "/category/" . $link->getId() . '/' . $link->getSlug();
        }
        return $this->resolveDocument(PrismicHelper::get_document($link->getId()));
    }

    public function resolveDocument($doc)
    {
        if (!$doc) return null;
        $name = PrismicHelper::get_bookmark_name($doc->getId());
        if ($name == 'home') {
            return '/';
        } else if ($name) {
            return '/' . $name;
        }
        if ($doc->getType() == "author") {
            return "/author/" . $doc->getId() . '/' . $doc->getSlug();
        }
        $date = $doc->getDate("post.date");
        $year = $date ? $date->asDateTime()->format('Y') : '0';
        $month = $date ? $date->asDateTime()->format('m') : '0';
        $day = $date ? $date->asDateTime()->format('d') : '0';
        return "/" . $year . '/' . $month . '/' . $day . '/' . $doc->getId() . '/' . $doc->getSlug();
    }

}

class PrismicHelper
{

    static $linkResolver;

    private static $api = null;

    static function get_api()
    {
        if (PrismicHelper::$api == null) {
            PrismicHelper::$api = Api::get(PRISMIC_URL, ACCESS_TOKEN);
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

    // Array of Category
    static function document_categories($document)
    {
        $result = array();
        if (!$document) return $result;
        $group = $document->getGroup('post.categories');
        if (!$group) return $result;
        foreach ($group->getArray() as $item) {
            array_push($result, Category::fromId($item['link']->getId()));
        }
        return $result;
    }

    static function get_bookmark_name($documentId)
    {
        foreach(PrismicHelper::get_api()->bookmarks() as $name => $id) {
            if ($documentId == $id) {
                return $name;
            }
        }
        return null;
    }

    static function search($q, $page = 1, $pageSize = PAGE_SIZE)
    {
        return PrismicHelper::form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::fulltext("document", $q)))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    static function category($categoryId, $page = 1, $pageSize = PAGE_SIZE)
    {
        return PrismicHelper::form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::any("my.post.categories.link", array($categoryId))))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    static function byAuthor($authorDocId, $page = 1, $pageSize = PAGE_SIZE)
    {
        return PrismicHelper::form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::at("my.post.author", $authorDocId)))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    static function archives($date, $page = 1, $pageSize = PAGE_SIZE)
    {
        if (!$date['month']) {
            $lowerBound = DateTime::createFromFormat('Y-m-d', ($date['year'] - 1) . '-12-31');
            $upperBound = DateTime::createFromFormat('Y-m-d', ($date['year'] + 1) . '-01-01');
        } else if (!$date['day']) {
            $lowerBound = DateTime::createFromFormat('Y-m-d', $date['year'] . '-' . $date['month'] .'-01');
            $upperBound = clone $lowerBound;
            $lowerBound->modify('-1 day');
            $upperBound->modify('+1 month - 1 day');
        } else {
            $lowerBound = DateTime::createFromFormat('Y-m-d', $date['year'] . '-' . $date['month'] .'-' . $date['day']);
            $upperBound = clone $lowerBound;
            $lowerBound->modify('-1 day');
        }
        return PrismicHelper::form()
            ->query(array(
                Predicates::at("document.type", "post"),
                Predicates::dateAfter("my.post.date", $lowerBound),
                Predicates::dateBefore("my.post.date", $upperBound)
            ))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    static function get_bookmarks()
    {
        $bookmarks = PrismicHelper::get_api()->bookmarks();
        $bkIds = array();
        foreach ($bookmarks as $name => $id) {
            array_push($bkIds, $id);
        }
        if (count($bkIds) == 0) {
            return array();
        }
        return PrismicHelper::form()
            ->query(Predicates::any("document.id", $bkIds))
            ->orderings("[my.page.priority desc]")
            ->submit()
            ->getResults();
    }

    static function get_posts($page, $pageSize = PAGE_SIZE)
    {
        return PrismicHelper::form()
            ->page($page)
            ->pageSize($pageSize)
            ->query(Predicates::at("document.type", "post"))
            ->orderings("[my.post.date desc]")
            ->submit();
    }

    static function get_calendar()
    {
        $calendar = array();
        $page = 1;
        do {
            $posts = PrismicHelper::get_posts($page, 100);
            foreach ($posts->getResults() as $post) {
                if (!$post->getDate("post.date")) continue;
                $date = $post->getDate("post.date")->asDateTime();
                $key = $date->format("F Y");
                if ($key != end($calendar)['label']) {
                    array_push($calendar, array(
                        'label' => $key,
                        'link' => archive_link($date->format('Y'), $date->format('m'))
                    ));
                }
                $page++;
            }
        } while ($posts->getNextPage());
        return $calendar;
    }

}

PrismicHelper::$linkResolver = new BlogLinkResolver();
