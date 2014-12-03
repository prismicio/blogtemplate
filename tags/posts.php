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

// Loop management

function have_posts()
{
    return Loop::has_more();
}

function the_post()
{
    Loop::increment();
}

function rewind_posts()
{
    Loop::reset();
}

// To be used within the loop

function the_permalink()
{
    echo PrismicHelper::$linkResolver->resolveDocument(Loop::current_post());
}

function the_title()
{
    $doc = Loop::current_post();
    echo $doc ? htmlentities($doc->getText($doc->getType() . ".title")) : "";
}

function the_category($separator = '', $parents = '', $post_id = false)
{
    $doc = $post_id ? PrismicHelper::get_document($post_id) : Loop::current_post();
    if (!$doc) return null;
    $category = $doc->getText("post.category");
    if (!$category) {
        return null;
    }
    $url = '/category/' . $category;
    echo '<a href="' . $url . '">' . $category . '</a>';
}

function the_date_link($format = "F, jS Y")
{
    $date = get_date("post.date", Loop::current_post());
    if (!$date) {
        return null;
    }
    $date = $date->asDateTime();
    $label = date_format($date, $format);
    $url = archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
    echo '<a href="' . $url . '">' . $label . '</a>';
}

function the_content($more_link_text = '(more...')
{
    $doc = Loop::current_post();
    if (!$doc) return null;
    $field = $doc->getType() . '.body';
    if ($doc->get($field)) {
        echo $doc->get($field)->asHtml(PrismicHelper::$linkResolver);
    }
}

// Other tags

function current_document()
{
    return State::current_document();
}

function posts() {
    return State::current_posts()->getResults();
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

