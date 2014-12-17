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

function get_pages()
{
    $home = home();
    return $home['children'];
}
