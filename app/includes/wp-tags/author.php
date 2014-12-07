<?php

function author($document = null) {
    if (!$document && current_document()) {
        $document = new Post(current_document());
    }
    if (!$document) return null;
    return $document->getAuthor();
}

function the_author()
{
    echo get_the_author();
}

function get_the_author() {
    $auth = Loop::current_author();
    if (!$auth) return null;
    return htmlentities($auth->getName());
}

function get_author_posts_url($author_id, $author_nicename = '')
{
    if (!$author_id) return null;
    $auth = PrismicHelper::get_document($author_id);
    if (!$auth) return null;
    return PrismicHelper::$linkResolver->resolveDocument($auth);
}

function author_link($author = null) {
    $auth = $author ? $author : author();
    if (!$auth) return null;
    $author_link = $auth->getPermalink();
    return '<a href = "' . $author_link . '">' . $author->getName() . '</a>';
}

function author_image($author = null) {
    $auth = $author ? $author : author();
    if (!$auth) return null;
    $photo = $auth->getImage('author.photo');
    if ($photo) {
        return $photo->asHtml();
    } else {
        return null;
    }
}

function get_the_author_meta($field, $userID = null)
{
    $author_id = $userID ? $userID : Loop::current_author_id();
    if (!$author_id) return null;
    $author = PrismicHelper::get_document($author_id);
    switch ($field)
    {
        case 'ID': return $author->getID();
        case 'display_name': return $author->getText('author.full_name')->asText();
        default: return null;
    }
}

function the_author_meta($field, $userID = null)
{
    echo get_the_author_meta($field, $userID = null);
}