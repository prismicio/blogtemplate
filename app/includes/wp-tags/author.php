<?php

function the_author()
{
    echo get_the_author();
}

function get_the_author() {
    global $WPGLOBAL, $loop;
    $post = $loop->current_post();
    $author = $post->getLink($post->getType() . '.author');
    if (!$author) return null;
    return htmlentities($author->getText('author.full_name'));
}

function get_author_posts_url($author_id, $author_nicename = '')
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    if (!$author_id) return null;
    $auth = $prismic->get_document($author_id);
    if (!$auth) return null;
    return $prismic->linkResolver->resolveDocument($auth);
}

function get_the_author_link() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $loop->current_post();
    if (!$post) return null;
    $auth = null;
    if ($post->getType() == 'author') {
        $auth = $post;
    } else {
        $auth = $post->getLink($post->getType() . '.author');
    }
    if (!$auth) return null;
    $author_link = $prismic->linkResolver->resolve($auth);
    return '<a href = "' . $author_link . '">' . $auth->getText('author.full_name') . '</a>';
}

function the_author_link() {
    echo get_the_author_link();
}

function author_image() {
    echo get_author_image();
}

function get_author_image() {
    global $WPGLOBAL, $loop;
    $post = $loop->current_post();
    $author = $post->getLink($post->getType() . '.author');
    if (!$author) {
        return null;
    }
    $photo = $author->getImage('author.photo');
    if ($photo) {
        return $photo->asHtml();
    } else {
        return null;
    }
}

function get_the_author_meta($field, $userID = null)
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $author = null;
    if ($userID) {
        $author = $prismic->get_document($userID);
    } else {
        $post = $loop->current_post();
        if ($post != null) {
            $author = $post->getLink($post->getType() . '.author');
        }
    }
    if ($author == null) return null;
    switch ($field)
    {
        case 'ID': return $author->getId();
        case 'display_name': return $author->getText('author.full_name')->asText();
        default: return null;
    }
}

function the_author_meta($field, $userID = null)
{
    echo get_the_author_meta($field, $userID = null);
}

