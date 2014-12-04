<?php

/**
 * Stubs for compatibility with wordpress
 */

function language_attributes() {}

function wp_head() {
    echo '<link rel="stylesheet" href="' . Theme::directory_url() . '/style.css">';
}

function body_class() {}

function post_class() {}

function esc_url($url)
{
    return $url;
}

function esc_attr($input)
{
    return $input;
}

function _e($input)
{
    return $input;
}

function _x($text, $context, $domain = 'default') {
    return $text;
}


function esc_attr_e($input)
{
    return $input;
}

function esc_html($input)
{
    return $input;
}

function wp_nav_menu() {}

function get_header_image() {}

function get_post_format() {}

function post_password_required() {
    return false;
}

function is_attachment() {
    return false;
}

function comments_open() {}

function __() {}

function add_action($name) {}

function add_filter($name) {}

function get_the_tag_list() {}

function edit_post_link() {}

function comments_template() {}