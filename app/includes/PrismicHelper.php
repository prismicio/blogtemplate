<?php

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

class BlogLinkResolver extends LinkResolver
{
    private $prismic;

    public function __construct($prismic)
    {
        $this->prismic = $prismic;
    }

    public function resolve($link)
    {
        $name = $this->prismic->get_bookmark_name($link->getId());
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
        return $this->resolveDocument($this->prismic->get_document($link->getId()));
    }

    public function resolveDocument($doc)
    {
        if (!$doc) return null;
        $name = $this->prismic->get_bookmark_name($doc->getId());
        if ($name == 'home') {
            return '/';
        } else if ($name) {
            return '/' . $name;
        }
        if ($doc->getType() == "category") {
            return "/category/" . $doc->getUid();
        }
        if ($doc->getType() == "author") {
            return "/author/" . $doc->getId() . '/' . $doc->getSlug();
        }
        if ($doc->getType() == "post") {
            $date = $doc->getDate("post.date");
            $year = $date ? $date->asDateTime()->format('Y') : '0';
            $month = $date ? $date->asDateTime()->format('m') : '0';
            $day = $date ? $date->asDateTime()->format('d') : '0';
            return "/" . $year . '/' . $month . '/' . $day . '/' . urlencode($doc->getUid());
        }
        if ($doc->getType() == "page") {
            $homeId = $this->prismic->get_api()->bookmark('home');
            $parent = $this->prismic->get_parent($doc->getId());
            if ($parent && $parent->getId() != $homeId) {
                return "/" . $parent->getUid() . "/" . urlencode($doc->getUid());
            }
        }
        return "/" . $doc->getUid();
    }

}

class PrismicHelper
{

    private $app;
    public $linkResolver;

    public function __construct($app) {
        $this->app = $app;
        $this->linkResolver = new BlogLinkResolver($this);
    }

    private $api = null;

    private function pageSize()
    {
        return $this->app->config('page_size');
    }

    function get_api()
    {
        $url = $this->app->config('prismic.url');
        $access_token = $this->app->config('prismic.access_token');
        if ($this->api == null) {
            $this->api = Api::get($url, $access_token);
        }
        return $this->api;
    }

    function get_ref()
    {
        $previewCookie = $this->app->request()->cookies[Prismic\PREVIEW_COOKIE];
        if ($previewCookie != null) {
            return $previewCookie;
        } else {
            return $this->get_api()->master();
        }
    }

    function form()
    {
        return $this->get_api()->forms()->everything->ref(PrismicHelper::get_ref());
    }

    function get_authors() {
        return $this->form()
            ->query(Predicates::at("document.type", "author"))
            ->submit();
    }

    // Try with uid, fallback to id
    function get_document($id)
    {
        $results = $this->single($id, "document.uid")->getResults();
        if (count($results) > 0) {
            return $results[0];
        }
        $results2 = $this->single($id, "document.id")->getResults();
        if (count($results2) > 0) {
            return $results2[0];
        }
        return null;
    }

    // Array of Category
    function document_categories($document)
    {
        if (!$document) return array();
        $group = $document->getGroup('post.categories');
        if (!$group) return array();
        $ids = array();
        foreach ($group->getArray() as $item) {
            array_push($ids, $item->getLink('link')->getId());
        }
        return $this->from_ids($ids)->getResults();
    }

    function get_bookmark_name($documentId)
    {
        foreach($this->get_api()->bookmarks() as $name => $id) {
            if ($documentId == $id) {
                return $name;
            }
        }
        return null;
    }

    function from_ids(array $documentIds)
    {
        return $this->form()
            ->query(array(Predicates::any("document.id", $documentIds)))
            ->submit();
    }

    function single($documentId, $field = "document.id")
    {
        return $this->form()
            ->query(array(Predicates::at($field, $documentId)))
            ->submit();
    }

    function search($q, $page = 1, $pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
        return $this->form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::fulltext("document", $q)))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    function category($categoryId, $page = 1, $pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
        return $this->form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::any("my.post.categories.link", array($categoryId))))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    function byAuthor($authorDocId, $page = 1, $pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
        return $this->form()
            ->query(array(Predicates::at("document.type", "post"), Predicates::at("my.post.author", $authorDocId)))
            ->orderings("[my.post.date desc]")
            ->page($page)
            ->pageSize($pageSize)
            ->submit();
    }

    function archives($date, $page = 1, $pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
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
        return $this->form()
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

    function get_bookmarks()
    {
        $bookmarks = $this->get_api()->bookmarks();
        $bkIds = array();
        foreach ($bookmarks as $name => $id) {
            array_push($bkIds, $id);
        }
        if (count($bkIds) == 0) {
            return array();
        }
        return $this->form()
            ->query(Predicates::any("document.id", $bkIds))
            ->orderings("[my.page.priority desc]")
            ->submit()
            ->getResults();
    }

    function get_posts($page, $pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
        return $this->form()
            ->page($page)
            ->pageSize($pageSize)
            ->query(Predicates::at("document.type", "post"))
            ->orderings("[my.post.date desc]")
            ->submit();
    }

    function archive_link($year, $month = null, $day = null)
    {
        $url = '/archive/' . $year;
        if ($month) {
            $url .= '/' . $month;
        }
        if ($month && $day) {
            $url .= '/' . $day;
        }
        return $url;
    }

    function get_parent($pageId)
    {
        $results = $this->form()
            ->query(Predicates::any("my.page.children.link", array($pageId)))
            ->submit()->getResults();
        if (count($results) > 0) {
            return $results[0];
        }
        return null;
    }

    function get_calendar()
    {
        $calendar = array();
        $page = 1;
        do {
            $posts = $this->get_posts($page, 100);
            foreach ($posts->getResults() as $post) {
                if (!$post->getDate("post.date")) continue;
                $date = $post->getDate("post.date")->asDateTime();
                $key = $date->format("F Y");
                if ($key != end($calendar)['label']) {
                    array_push($calendar, array(
                        'label' => $key,
                        'link' => $this->archive_link($date->format('Y'), $date->format('m'))
                    ));
                }
                $page++;
            }
        } while ($posts->getNextPage());
        return $calendar;
    }

    function previous($document) {
        $posts = $this->form()
            ->query(Predicates::at("document.type", "post"))
            ->set("after", $document->getId())
            ->orderings("[my.post.date]")
            ->submit()
            ->getResults();
        if (count($posts) > 0) {
            return $posts[0];
        }
        return null;
    }

    function next($document) {
        $posts = $this->form()
            ->query(Predicates::at("document.type", "post"))
            ->set("after", $document->getId())
            ->orderings("[my.post.date desc]")
            ->submit()
            ->getResults();
        if (count($posts) > 0) {
            return $posts[0];
        }
        return null;
    }

}

