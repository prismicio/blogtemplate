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
        foreach($this->prismic->get_api()->bookmarks() as $name => $id) {
            if ($link->getId() == $id && $name == 'home') {
                return '/';
            }
        }
        if ($link->isBroken()) {
            return null;
        }
        if ($link->getType() == "author") {
            return "/author/" . $link->getId() . '/' . $link->getSlug();
        }
        if ($link->getType() == "category") {
            return "/category/" . $link->getUid();
        }
        if ($link->getType() == "post") {
            $date = $link->getDate("post.date");
            $year = $date ? $date->asDateTime()->format('Y') : '0';
            $month = $date ? $date->asDateTime()->format('m') : '0';
            $day = $date ? $date->asDateTime()->format('d') : '0';
            return "/" . $year . '/' . $month . '/' . $day . '/' . urlencode($link->getUid());
        }

        if ($link->getType() == "page") {
            $homeId = $this->prismic->get_api()->bookmark('home');
            if ($link->getId() != $homeId) {
                $pieces = $this->prismic->page_path($link->getUid());
                $pieces_encoded = array_map( function($p)
                {
                  return urlencode($p);
                }, $pieces);
                return '/' . implode('/', $pieces_encoded);
            }
        }
        return "/" . $link->getUid();
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

    public function pageSize()
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

    function form($pageSize = null)
    {
        if (!$pageSize) $pageSize = $this->pageSize();
        return $this->get_api()->forms()->everything
            ->pageSize($pageSize)
            ->ref($this->get_ref());
    }

    function by_uid($type, $uid, $fetch = array())
    {
        $results =
          $this->form()
            ->fetchLinks($fetch)
            ->query(array(
                Predicates::at("my.".$type.".uid", $uid)
            ))
            ->submit()
            ->getResults();

        if (count($results) > 0) {
            return $results[0];
        }
        return null;
    }

    function get_document($id)
    {
        $results = $this->single($id, "document.id")->getResults();
        if (count($results) > 0) {
            return $results[0];
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

    function refresh_path($path)
    {

      $pages = $this->form()
          ->query(Predicates::in("my.page.uid", $path))
          ->submit()
          ->getResults();

      $npath = array_map(function($p)
      {
        return $p->getUid();
      }, $pages);

      if(count($path) == count($npath)){
        return $npath;
      }
      return null;

    }

    function page_path($uid)
    {
      $homeId = $this->get_api()->bookmark('home');

      $pages = $this->form()
          ->query(Predicates::at("document.type", "page"))
          ->submit()
          ->getResults();

      $parents = array();
      foreach ($pages as $p) {
        if ($p->getId() == $homeId) continue;
        $cs = $p->getGroup('page.children');
        if ($cs)
        {
          foreach($cs->getArray() as $child)
          {
            $link = $child->getLink('link');
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                $parent_title = $p->getUid();
                $parents[$link->getUid()]= $parent_title;
            }
          }
        }
      }

      $p = $uid;

      $path = array($uid);
      while(array_key_exists($p, $parents))
      {
        $nextp = $parents[$p];
        array_push($path, $nextp);
        $p = $nextp;
      }
      return array_reverse($path);

    }

    function archives($date, $page = 1)
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
        return $this->form()
            ->query(array(
                Predicates::at("document.type", "post"),
                Predicates::dateAfter("my.post.date", $lowerBound),
                Predicates::dateBefore("my.post.date", $upperBound)
            ))
            ->orderings("[my.post.date desc]")
            ->page($page)
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

    function get_calendar()
    {
        $calendar = array();
        $page = 1;
        do {
            $posts = $this->form(100)
                ->page($page)
                ->query(Predicates::at("document.type", "post"))
                ->orderings("my.post.date desc")
                ->submit();
            foreach ($posts->getResults() as $post) {
                if (!$post->getDate("post.date")) continue;
                $date = $post->getDate("post.date")->asDateTime();
                $key = $date->format("F Y");
                $last = end($calendar);
                if ($key != $last['label']) {
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
            ->pageSize(1)
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
            ->pageSize(1)
            ->orderings("[my.post.date desc]")
            ->submit()
            ->getResults();
        if (count($posts) > 0) {
            return $posts[0];
        }
        return null;
    }

    public function home()
    {
        $homeId = $this->get_api()->bookmark('home');
        if (!$homeId) return null;
        $home = $this->get_document($homeId);
        if (!$home || $home->getType() != "page") return null;

        return array(
            'label' => 'Home',
            'url' => $this->linkResolver->resolveDocument($home),
            'external' => false,
            'children' => $this->getPageChildren($home)
        );
    }

    private function getPageChildren($page)
    {
        $result = array();
        if (!$page) return $result;
        $group = $page->getGroup("page.children");
        if (!$group) return $result;
        foreach ($group->getArray() as $item) {
            if (!isset($item['label']) || !isset($item['link'])) continue;
            $label = $item->getText('label');
            $link = $item->getLink('link');
            $children = array();
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                $doc = $this->get_document($link->getId());
                if (!$label) {
                    $label = "No label";
                }
                $children = $this->getPageChildren($doc);
            }
            array_push($result, array(
                'label' => $label,
                'url' => $link->getUrl($this->linkResolver),
                'external' => $link instanceof \Prismic\Fragment\Link\WebLink,
                'children' => $children
            ));
        }
        return $result;
    }

}
