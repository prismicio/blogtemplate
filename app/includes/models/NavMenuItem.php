<?php


class NavMenuItem
{
    private $prismic, $label, $link, $children;

    public function __construct($prismic, $label, $docLink, array $children)
    {
        $this->label = $label;
        $this->link = $docLink;
        $this->children = $children;
        $this->prismic = $prismic;
    }

    public function getTitle()
    {
        return $this->label;
    }

    public function getPermalink()
    {
        return $this->link->getUrl($this->prismic->linkResolver);
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function isActive($app)
    {
        return $app->request()->getPath() == $this->getPermalink();
    }

    public function isExternal()
    {
        return $this->link instanceof Prismic\Fragment\Link\WebLink;
    }

    public static function home($prismic)
    {
        $homeId = $prismic->get_api()->bookmark('home');
        if (!$homeId) return null;
        $doc = $prismic->get_document($homeId);
        if (!$doc || $doc->getType() != "page") return null;

        return new NavMenuItem(
            $prismic,
            'Home',
            new \Prismic\Fragment\Link\DocumentLink($doc->getId(), "page", array(), "home", false),
            NavMenuItem::getPageChildren($doc, $prismic)
        );
    }

    private static function getPageChildren($page, $prismic)
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
                $doc = $prismic->get_document($link->getId());
                if (!$label) {
                    $label = "No label";
                }
                $children = NavMenuItem::getPageChildren($doc, $prismic);
            }
            array_push($result, new NavMenuItem($prismic, $label, $link, $children));
        }
        return $result;
    }

}