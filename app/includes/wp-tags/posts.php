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
    return $post ? $prismic->linkResolver->resolveDocument($post) : null;
}

function the_title()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if ($doc) {
        echo htmlentities($doc->getText($doc->getType() . '.title'));
    }
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
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }
    $label = date_format($date, $format);
    $url = archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
    echo '<a href="' . $url . '">' . $label . '</a>';
}

function get_the_date($format = 'F, jS Y')
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $date = get_date("post.date", $loop->current_post());
    if (!$date) {
        return null;
    }
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }
    return date_format($date, $format);
}

function get_the_time($format = 'g:iA')
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $date = get_date("post.date", $loop->current_post());
    if (!$date) {
        return null;
    }
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }
    return date_format($date, $format);
}

function the_content($more_link_text = '(more...')
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    $body = $doc->getStructuredText($doc->getType() . '.body');
    if ($body) {
        echo $body->asHtml($prismic->linkResolver);
    }
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
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    if ($doc instanceof Author) return null;
    if ($doc->getStructuredText('post.shortlede')) {
        return $doc->getStructuredText('post.shortlede')->asHtml($prismic->linkResolver);
    }
    // Plain text to avoid open tag at the end
    $body = $doc->getStructuredText($doc->getType() . '.body');
    if (!$body) {
        return "";
    }
    if (strlen($body->asText()) > 300) {
        return substr($body->asText(), 0, 300) . "...";
    } else {
        return $body->asText();
    }
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

function get_the_tags()
{
    global $WPGLOBAL;
    $loop = $WPGLOBAL['loop'];
    $doc = $loop->current_post();
    if (!$doc) return array();
    return $doc->getTags();
}

function the_tags($before = '', $sep = '', $after = '')
{
    echo get_the_tag_list($before, $sep, $after);
}

function get_the_tag_list($before = '', $sep = '', $after = '') {
    $tags = get_the_tags();
    if (count($tags) == 0) return;
    $result = $before;
    $result .= join($sep, array_map(function ($tag) use ($sep) {
        return '<a href="/tag/' . $tag . '">' . $tag . '</a>';
    }, $tags));
    $result .= $after;
    return $result;
}

// Other tags

function single_post()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['single_post'])) {
        return $WPGLOBAL['single_post'];
    }
    return null;
}

function document_url($document)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    return $prismic->linkResolver->resolveDocument($document);
}

function link_to_post($post)
{
    return '<a href="' . document_url($post) . '">' . post_title($post) . '</a>';
}

function single_post_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    if (!single_post()) return null;
    $result = $prefix . single_post()->getText('post.title');
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

function get_date($field, $doc)
{
    if (!$doc) return null;
    if ($doc instanceof Author) return null;
    return $doc->getDate($field);
}

