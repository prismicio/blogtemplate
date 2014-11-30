<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/PrismicHelper.php';
require_once 'lib/State.php';

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

function home_link($label, $attrs = array())
{
    $attrs['href'] = '/';
    if($_SERVER['REQUEST_URI'] == "/") {
        $attrs['class'] = $attrs['class'] ? ($attrs['class'] . ' active') : 'active';
    }
    $result = '<a ';
    foreach($attrs as $k => $v) {
        $result .= ($k . '="' . $v . '" ');
    }
    $result .= ('>' . $label . '</a>');
    return $result;
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

function get_calendar() {
    return PrismicHelper::get_calendar();
}

// Pages tags

function get_pages()
{
    return PrismicHelper::get_pages();
}

function page_link($page, $attrs = array())
{
    $attrs['href'] = document_url($page);
    if($_SERVER['REQUEST_URI'] == $attrs['href']) {
        $attrs['class'] = $attrs['class'] ? ($attrs['class'] . ' active') : 'active';
    }
    $result = '<a ';
    foreach($attrs as $k => $v) {
        $result .= ($k . '="' . $v . '" ');
    }
    $result .= ('>' . htmlentities($page->getText("page.title")) . '</a>');
    return $result;
}
