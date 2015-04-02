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
    global $loop;
    return $loop->has_more();
}

function the_post()
{
    global $loop;
    $loop->increment();
}

function rewind_posts()
{
    global $loop;
    $loop->reset();
}

// To be used within the loop

function the_ID()
{
    global $loop;
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
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $id ? $prismic->get_document($id) : $loop->current_post();
    return $post ? $prismic->linkResolver->resolveDocument($post) : null;
}

function current_experiment_id()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $api = $prismic->get_api();
    $currentExperiment = $api->getExperiments()->getCurrent();
    return $currentExperiment ? $currentExperiment->getGoogleId() : NULL;
}

function the_title()
{
    global $loop;
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
    global $loop;
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
    global $loop;
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
    global $loop;
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
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    $body = $doc->getStructuredText($doc->getType() . '.body');
    if ($body) {
        echo $body->asHtml($prismic->linkResolver);
    }
}

function the_post_thumbnail($size = 'main', $attr = array())
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    if ($size == 'full') {
        $size = 'main';
    }
    if (!$doc) return null;
    $image = $doc->getImage($doc->getType() . '.image');
    if ($image) {
        echo $image->getView($size)->asHtml();
    }
}

function post_thumbnail_url($size = 'main')
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    if ($size == 'full') {
        $size = 'main';
    }
    if (!$doc) return null;
    $image = $doc->getImage($doc->getType() . '.image');
    if ($image) {
        return $image->getView($size)->getUrl();
    }
}

function has_post_thumbnail()
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    return ($doc != null && $doc->getImage($doc->getType() . '.image') != null);
}

function has_post_format($format = array(), $post = null)
{
    // TODO
    return false;
}

function get_the_excerpt()
{
    global $WPGLOBAL, $loop;
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
    global $loop;
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
    global $loop;
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

function single_wio_attributes()
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    if(!$doc) return null;
    echo 'data-wio-id="'. $doc->getId() . '"';
}

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

function single_post_shortlede()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) return null;
    if ($doc instanceof Author) return null;
    if ($doc->getStructuredText('post.shortlede')) {
        echo '<p class="shortlede">' . $doc->getStructuredText('post.shortlede')->asText() . '</p>';
    }
}

function single_post_date($format = "F, jS Y")
{
    global $loop;
    $date = get_date("post.date", $loop->current_post());
    if ($date) {
        if ($date instanceof \Prismic\Fragment\Date) {
            $date = $date->asDateTime();
        }
        echo '<p class="date">' . date_format($date, $format) . '</p>';
    }
}

function single_post_author()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $loop->current_post();
    if (!$post) return null;
    $author = $post->getLink($post->getType() . '.author');
    if (!$author) return null;
    echo '<span class="author">' . $author->getText('author.full_name') . '</span>';
}

function single_prev_post_link()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['single_prev_post'])) {
        $post = $WPGLOBAL['single_prev_post'];
        $title = $post->getText($post->getType() . '.title');
        $url = document_url($post);
        echo '<a href="'. $url .'" class="previous">
                <i class="fa fa-arrow-left"></i>
                <span class="label">Previous article</span>
                <p class="title">'. $title .'</p>
              </a>';
    }
}

function single_next_post_link()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['single_next_post'])) {
        $post = $WPGLOBAL['single_next_post'];
        $title = $post->getText($post->getType() . '.title');
        $url = document_url($post);
        echo '<a href="'. $url .'" class="next">
                <span class="label">Next article</span>
                <i class="fa fa-arrow-right"></i>
                <p class="title">'. $title .'</p>
              </a>';
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

