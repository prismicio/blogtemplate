<?php

function author($document = null) {
    $doc = $document ? $document : current_document();
    if ($doc && $doc->getType() == "author") return $doc;
    if ($doc == null) return null;
    $docLink = $doc->getLink($doc->getType() . ".author");
    if ($docLink) {
        return PrismicHelper::get_document($docLink->getId());
    }
    return null;
}

function the_author()
{
    echo get_the_author();
}

function get_the_author() {
    $auth = PrismicHelper::get_document(Loop::current_author_id());
    if (!$auth) return null;
    return htmlentities($auth->getText("author.full_name"));
}

function get_author_posts_url($author_id, $author_nicename = '')
{
    $auth = PrismicHelper::get_document($author_id);
    if (!$auth) return null;
    return PrismicHelper::$linkResolver->resolveDocument($auth);
}

function author_link($author = null) {
    $auth = $author ? $author : author();
    if (!$auth) return null;
    $author_link = PrismicHelper::$linkResolver->resolveDocument($auth);
    return '<a href = "' . $author_link . '">' . author_name($author) . '</a>';
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
    $author = PrismicHelper::get_document($userID ? $userID : Loop::current_author_id());
    switch ($field)
    {
        case 'ID': return $author->getID();
        case 'display_name': return $author->getText('author.full_name');
        default: return null;
    }
}

function the_author_meta($field, $userID = null)
{
    echo get_the_author_meta();
}