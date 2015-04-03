<?php

function home()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    return $prismic->home();
}

function page_link($page, $attrs = array())
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $classes = array();
    $active = $app->request()->getPath() == $page['url'];
    if ($active) array_push($classes, 'active');
    if ($page['external'] == true) array_push($classes, 'external');
    return '<a href="' . $page['url'] . '" class="' . join(' ', $classes) . '">' . $page['label'] . '</a>';
}

function page_content()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    $body = $doc->getSliceZone($doc->getType() . '.body');
    if ($body) {
        echo $body->asHtml($prismic->linkResolver);
    }
}

function get_pages()
{
    $home = home();
    if (array_key_exists('children', $home)) {
        return $home['children'];
    } else {
        return array();
    }
}

function is_page_template($template = null)
{
    // TODO
    return false;
}