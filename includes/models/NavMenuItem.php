<?php


class NavMenuItem
{
    private $label, $link, $children;

    public function __construct($label, $docLink, array $children)
    {
        $this->label = $label;
        $this->link = $docLink;
        $this->children = $children;
    }

    public function getTitle()
    {
        return $this->label;
    }

    public function getPermalink()
    {
        return $this->link->getUrl(PrismicHelper::$linkResolver);
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == $this->getPermalink();
    }

    public static function home()
    {
        $homeId = PrismicHelper::get_api()->bookmark('home');
        if (!$homeId) return null;
        $doc = PrismicHelper::get_document($homeId);
        if (!$doc || $doc->getType() != "page") return null;

        return new NavMenuItem(
            'Home',
            new \Prismic\Fragment\Link\DocumentLink($doc->getId(), "page", array(), "home", false),
            NavMenuItem::getPageChildren($doc)
        );
    }

    private static function getPageChildren($page)
    {
        $result = array();
        if (!$page) return $result;
        $group = $page->getGroup("page.children");
        if (!$group) return $result;
        foreach ($group->getArray() as $item) {
            if (!isset($item['label']) || !isset($item['link'])) continue;
            $label = $item['label']->asText();
            $link = $item['link'];
            $children = array();
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                $children = NavMenuItem::getPageChildren(PrismicHelper::get_document($link->getId()));
            }
            array_push($result, new NavMenuItem($label, $link, $children));
        }
        return $result;
    }

}