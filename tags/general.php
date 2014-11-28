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
    echo SITE_TITLE;
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
    return State::current_query();
}

// Author tags

function get_the_author()
{
    return 'TODO';
}

function the_author()
{
    echo get_the_author();
}

function list_authors()
{

}

// Pages tags

function get_pages()
{
    return PrismicHelper::get_pages();
}

