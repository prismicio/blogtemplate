<?php

// Some themes check for WP version, let's not bother them
$GLOBALS['wp_version'] = '10.0';

/**
 * Stubs for compatibility with wordpress
 */

function language_attributes() {}

function wp_head() {
    global $WPGLOBAL;
    $theme = $WPGLOBAL['theme'];
    echo '<link rel="stylesheet" href="' . $theme->directory_url() . '/style.css">';
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

function get_header_image() {}

function get_post_format() {}

function post_password_required() {
    return false;
}

function is_attachment() {
    return false;
}

function comments_open() {}

function get_comments_number($post_id = null) {
    return 0;
}

function __() {}

function add_action($name) {}

function add_filter($name) {}

function get_the_tag_list() {}

function edit_post_link() {}

function comments_template() {}

function get_post_format_strings() {
    return array();
}

function get_post_format_string() {
    return null;
}

function dynamic_sidebar() {
}

function do_action() {}

function wp_footer() {}

function get_object_taxonomies($object, $output = 'names') {
    return array();
}

function has_nav_menu($location) {
    return false;
}
