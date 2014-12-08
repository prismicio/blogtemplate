<?php

function home()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    return $prismic->get_document($prismic->get_api()->bookmark("home"));
}

function page_link($page, $attrs = array())
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    if($app->request->getUrl() == $page['url']) {
        $attrs['class'] = isset($attrs['class']) ? ($attrs['class'] . ' active') : 'active';
    }
    return _make_link($page['url'], $page['label'], $attrs);
}

function has_children($page)
{
    if (!$page) return false;
    return count($page->getGroup("page.children")) > 0;
}

function get_page_children($page)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $result = array();
    if (!$page) return $result;
    $group = $page->getGroup("page.children");
    if (!$group) return $result;
    foreach ($group->getArray() as $item) {
        if (!isset($item['label']) || !isset($item['link'])) continue;
        $label = $item['label']->asText();
        $link = $item['link'];
        $url = $link->getUrl($prismic->linkResolver);
        $children = array();
        if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
            $children = get_page_children($prismic->get_document($link->getId()));
        }
        array_push($result, array(
            'label' => $label,
            'url' => $url,
            'children' => $children
        ));
    }
    return $result;
}

function get_pages()
{
    return get_page_children(home());
}
