<?php

/**
 * The way the tags are written can lead to the same request being done several times,
 * but it's OK because the Prismic kit has a built-in cache (APC).
 */

// General tags

function get_bloginfo($show = 'name')
{
    global $app;
    switch ($show) {
        case 'atom_url':
        case 'rdf_url':
        case 'rss_url':
        case 'rss2_url': return '/feed';
        case 'description': return SITE_DESCRIPTION;
        case 'wpurl':
        case 'url': return $app->request()->getUrl();
        case 'admin_email': return ADMIN_EMAIL;
        case 'charset': return 'UTF-8';
        case 'language': return 'en-US';
        case 'name': return SITE_TITLE;
        default: return '';
    }
}

function bloginfo($show = 'name')
{
    echo get_bloginfo($show);
}

function site_title()
{
    return SITE_TITLE;
}

function home_url($path = '', $scheme = null)
{
    global $app;
    return $app->request()->getUrl() . $path;
}

function wp_title()
{
    echo site_title();
}

function get_template_directory_uri()
{
    return Theme::directory_url();
}

function get_template_directory()
{
    return Theme::directory();
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
        $attrs['class'] = isset($attrs['class']) ? ($attrs['class'] . ' active') : 'active';
    }
    return _make_link('/', $label, $attrs);
}

function get_sidebar()
{
    Theme::render('sidebar');
}

function is_active_sidebar()
{
    return true;
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

function get_search_form($echo = true)
{
    $html = '<form method = "get" action = "search" >
                <input type = "text" placeholder = "Search..." name = "q" >
            </form >';
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}

function get_calendar() {
    return PrismicHelper::get_calendar();
}

function get_template_part($slug, $name = null)
{
    if ($name) {
        Theme::render($slug . '-' . $name);
    } else {
        Theme::render($slug);
    }
}

function is_search()
{
    return State::current_query() != null;
}

function is_single()
{
    return State::current_document() && State::current_document()->getType() == "post";
}

function is_singular()
{
    return State::current_document() != null;
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


