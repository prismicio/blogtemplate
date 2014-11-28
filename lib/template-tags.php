<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/PrismicHelper.php';

// General tags

function get_title()
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

// Document tags

function get_url_for($doc)
{
    return PrismicHelper::$linkResolver->resolveDocument($doc);
}

function previous_post_link()
{
    echo 'TODO';
}

function next_post_link()
{
    echo 'TODO';
}

function current_document()
{
    global $currentDocument;
    return $currentDocument;
}

function html_for($field)
{
    global $linkResolver, $currentDocument;
    return $currentDocument->get($field)->asHtml(PrismicHelper::$linkResolver);
}

// Pages tags

function get_pages()
{
    return PrismicHelper::get_all("page")->getResults();
}

