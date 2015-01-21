<?php

function single_cat_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $category = $WPGLOBAL['category'];
    $result = null;
    if ($category) {
        $result = $prefix . $category->getText('category.name');
    }
    if ($display) {
        echo $result;
    } else {
        return $result;
    }
}

function single_cat_illustration_url()
{
    global $WPGLOBAL;
    $category = $WPGLOBAL['category'];
    if ($category && $category->getImage('category.illustration')) {
        return $category->getImage('category.illustration')->getView("main")->getUrl();
    }
}

function single_cat_illustration($display = true)
{
    global $WPGLOBAL;
    $category = $WPGLOBAL['category'];
    $prismic = $WPGLOBAL['prismic'];
    $result = null;
    if ($category && $category->getImage('category.illustration')) {
        $result = $category->getImage('category.illustration')->asHtml($prismic->linkResolver);
    }
    if ($display) {
        echo $result;
    } else {
        return $result;
    }
}

function the_category($separator = '',$parents='', $post_id = null)
{
    echo get_the_category_list($separator, $parents, $post_id);
}

function get_the_category_list($separator = '', $parents = '', $post_id = null)
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $post_id ? $prismic->get_document($post_id) : $loop->current_post();
    if (!$doc) return null;
    if ($doc instanceof Author) return null;
    $strings = array();
    $categories = $doc->getGroup('post.categories');
    if (!$categories) return null;
    foreach ($doc->getGroup('post.categories')->getArray() as $item) {
        $category = $item->getLink('link');
        $url = $prismic->linkResolver->resolve($category);
        $label = $category->getText('category.name');
        array_push($strings, '<a href="' . $url . '">' . $label . '</a>');
    }
    return join($separator, $strings);
}

function single_tag_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $tag = isset($WPGLOBAL['tag']) ? $WPGLOBAL['tag'] : null;
    if ($display) {
        echo $tag;
    } else {
        return $prefix . $tag;
    }
}

function category_description($uid = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    if ($uid != null) {
        $category = $prismic->by_uid("category", $uid);
    } else {
        $category = $WPGLOBAL['category'];
    }
    if ($category && $category->getStructuredText('category.description')) {
        return $category->getStructuredText('category.description')->asHtml($prismic->linkResolver);
    }
}

function tag_description($tag_id = null)
{
    // TODO
}
