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
    global $WPGLOBAL;
    return $WPGLOBAL['loop']->has_more();
}

function the_post()
{
    global $WPGLOBAL;
    $WPGLOBAL['loop']->increment();
}

function rewind_posts()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $loop->reset();
}

// To be used within the loop

function the_ID()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    echo $loop->current_post()->getId();
}

function is_sticky()
{
    return false;
}

function the_permalink()
{
    echo get_permalink();
}

function get_permalink($id = null, $leavename = false)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $loop = $WPGLOBAL['loop'];
    $post = $id ? $prismic->get_document($id) : $loop->current_post();
    return $post ? $post->getPermalink() : null;
}

function the_title()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    echo $doc ? htmlentities($doc->getTitle()) : "";
}

function the_title_attribute()
{
    return the_title();
}

function the_date_link($format = "F, jS Y")
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $date = get_date("post.date", $loop->current_post());
    if (!$date) {
        return null;
    }
    $label = date_format($date, $format);
    $url = archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
    echo '<a href="' . $url . '">' . $label . '</a>';
}

function get_the_date()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $date = get_date("post.date", $loop->current_post());
    if (!$date) {
        return null;
    }
    $date = $date->asDateTime();
    return date_format($date, 'F, jS Y');
}

function get_the_time()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $date = get_date("post.date", $loop->current_post());
    if (!$date) {
        return null;
    }
    $date = $date->asDateTime();
    return date_format($date, 'g:iA');
}

function the_content($more_link_text = '(more...')
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    echo $doc->getBody();
}

function the_post_thumbnail()
{
    // TODO
}

function has_post_thumbnail()
{
    // TODO
    return false;
}

function has_post_format($format = array(), $post = null)
{
    // TODO
    return false;
}

function get_the_excerpt()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    return $doc->getExcerpt();
}

function get_post_type()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    return $doc->getType();
}

function the_excerpt()
{
    echo get_the_excerpt();
}

// Other tags

function current_document()
{
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    $prismic = $WPGLOBAL['prismic'];
    return $state->current_document($prismic);
}

function posts() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->current_posts()->getResults();
}

function document_url($document)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $document ? $document : current_document();
    return $prismic->linkResolver->resolveDocument($doc);
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

 function single_post_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $doc = new Post(current_document(), $prismic);
    $result = $prefix . $doc->getTitle();
    if ($display) {
        echo htmlentities($result);
    } else {
        return $result;
    }
}

function get_html($field, $document = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $document ? $document : current_document();
    if ($doc->get($field)) {
        return $doc->get($field)->asHtml($prismic->linkResolver);
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

