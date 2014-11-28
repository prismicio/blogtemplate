<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/PrismicHelper.php';
require_once 'lib/State.php';

/**
 * Most of these functions accept a $document as parameter.
 * For the single page, the document can be omitted.
 *
 * get_* function will return the values, others will output them.
 *
 * The way the tags are written can lead to the same request being done several times,
 * but it's OK because the Prismic kit has a built-in cache (APC).
 */

function current_document()
{
    return State::current_document();
}

function posts() {
    return PrismicHelper::get_posts(State::current_page())->getResults();
}

function get_document_url($document)
{
    $doc = $document ? $document : current_document();
    return PrismicHelper::$linkResolver->resolveDocument($doc);
}

function document_url($document) {
    echo get_document_url($document);
}

function get_post_title($document = null)
{
    $doc = $document ? $document : current_document();
    return $doc ? $doc->getText($doc->getType() . ".title") : "";
}

function post_title($document = null)
{
    echo get_post_title($document);
}

function get_text($field, $document = null)
{
    $doc = $document ? $document : current_document();
    return $doc->get($field)->asText(PrismicHelper::$linkResolver);
}

function get_html($field, $document = null)
{
    $doc = $document ? $document : current_document();
    return $doc->get($field)->asHtml(PrismicHelper::$linkResolver);
}

function get_date($field, $format, $document = null)
{
    $doc = $document ? $document : current_document();
    if (!$doc) return null;
    $date = $doc->getDate($field);
    if ($date != null) {
        return date_format($date->asDateTime(), $format);
    } else {
        return null;
    }
}

function get_author($document = null) {
    $doc = $document ? $document : current_document();
    if ($doc == null) return null;
    $docLink = $doc->getLink($doc->getType() . ".author");
    return PrismicHelper::get_document($docLink->getId());
}

function author_link($author = null) {
    $auth = $author ? $author : get_author();
    if (!$auth) return null;
    $author_name = $auth->getText("author.full_name");
    $author_link = PrismicHelper::$linkResolver->resolveDocument($auth);
    echo '<a href = "' . $author_link . '">' . $author_name . '</a>';
}


