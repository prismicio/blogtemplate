<?php

function home()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    return NavMenuItem::home($prismic);
}

function page_link($page, $attrs = array())
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $classes = array();
    if ($page->isActive($app)) array_push($classes, 'active');
    if ($page->isExternal()) array_push($classes, 'external');
    return '<a href="' . $page->getPermalink() . '" class="' . join(' ', $classes) . '">' . $page->getTitle() . '</a>';
}

function get_pages()
{
    return home()->getChildren();
}
