<?php

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
    if (State::current_query() != null) {
        // Search page
        return PrismicHelper::search(State::current_query(), State::current_page())->getResults();
    } else if (State::current_archive_date() != null) {
        // Archive page
        return PrismicHelper::archives(State::current_archive_date(), State::current_page())->getResults();
    } else if (State::$current_category != null) {
        // Category page
        return PrismicHelper::category(State::$current_category, State::current_page())->getResults();
    }
    // Index page
    return PrismicHelper::get_posts(State::current_page())->getResults();
}

function document_url($document)
{
    $doc = $document ? $document : current_document();
    return PrismicHelper::$linkResolver->resolveDocument($doc);
}

function post_title($document = null)
{
    $doc = $document ? $document : current_document();
    return $doc ? htmlentities($doc->getText($doc->getType() . ".title")) : "";
}

function link_to_post($post)
{
    return '<a href="' . document_url($post) . '">' . post_title($post) . '</a>';
}

function get_text($field, $document = null)
{
    $doc = $document ? $document : current_document();
    return htmlentities($doc->get($field)->asText(PrismicHelper::$linkResolver));
}

function get_html($field, $document = null)
{
    $doc = $document ? $document : current_document();
    if ($doc->get($field)) {
        return $doc->get($field)->asHtml(PrismicHelper::$linkResolver);
    }
    return null;
}

function get_date($field, $document = null)
{
    $doc = $document ? $document : current_document();
    if (!$doc) return null;
    return $doc->getDate($field);
}

function post_date_link($document = null)
{
    $date = get_date("post.date", $document);
    if (!$date) {
        return null;
    }
    $date = $date->asDateTime();
    $label = date_format($date, "F, jS Y");
    $url = archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
    return '<a href="' . $url . '">' . $label . '</a>';
}

function category_link($document = null)
{
    $doc = $document ? $document : current_document();
    if (!$doc) return null;
    $category = $document->getText("post.category");
    if (!$category) {
        return null;
    }
    $url = '/category/' . $category;
    return '<a href="' . $url . '">' . $category . '</a>';
}
