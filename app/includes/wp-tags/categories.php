<?php

function single_cat_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $category = $WPGLOBAL['single_post'];
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

function get_the_category_list($separator = '',$parents='', $post_id = null)
{
    return the_category($separator, $parents, $post_id);
}

function the_category($separator = '', $parents = '', $post_id = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $loop = $WPGLOBAL['loop'];
    $doc = $post_id ? $prismic->get_document($post_id) : $loop->current_post();
    if (!$doc) return null;
    if ($doc instanceof Author) return null;
    $strings = array();
    foreach ($prismic->document_categories($doc) as $category) {
        $url = $prismic->linkResolver->resolveDocument($category);
        $label = $category->getText('category.name');
        array_push($strings, '<a href="' . $url . '">' . $label . '</a>');
    }
    echo join($separator, $strings);
}

