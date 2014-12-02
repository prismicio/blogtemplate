<?php
require 'vendor/autoload.php';

/**
 * The way the tags are written can lead to the same request being done several times,
 * but it's OK because the Prismic kit has a built-in cache (APC).
 */

// General tags

function site_title()
{
    return SITE_TITLE;
}

function site_description()
{
    return SITE_DESCRIPTION;
}

function the_feed_link($anchor)
{
    return '<a href="/feed">' . $anchor . '</a>';
}

function home_link($label, $attrs = array())
{
    if($_SERVER['REQUEST_URI'] == "/") {
        $attrs['class'] = $attrs['class'] ? ($attrs['class'] . ' active') : 'active';
    }
    return _make_link('/', $label, $attrs);
}

function sidebar()
{
    Theme::render('sidebar');
}

function get_header()
{
    Theme::render('header');
}

function get_footer()
{
    Theme::render('footer');
}

function get_search_query()
{
    return htmlentities(State::current_query());
}

function get_category()
{
    return htmlentities(State::$current_category);
}

function get_calendar() {
    return PrismicHelper::get_calendar();
}

// Pages tags

function get_pages()
{
    return PrismicHelper::get_bookmarks();
}

function page_link($page, $attrs = array())
{
    $url = document_url($page);
    if($_SERVER['REQUEST_URI'] == $url) {
        $attrs['class'] = $attrs['class'] ? ($attrs['class'] . ' active') : 'active';
    }
    return _make_link($url, htmlentities($page->getText("page.title")), $attrs);
}

// Helpers (shouldn't be used in templates)

function _make_link($url, $label, $attrs)
{
    $attrs['href'] = $url;
    $result = '<a ';
    foreach($attrs as $k => $v) {
        $result .= ($k . '="' . $v . '" ');
    }
    $result .= ('>' . $label . '</a>');
    return $result;
}
